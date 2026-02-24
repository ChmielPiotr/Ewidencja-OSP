<?php
require_once 'models/User.php';
require_once 'models/Log.php'; // Ładujemy model logów

class UserController {
    private $db;
    private $userModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection; // Zapisujemy połączenie dla logów
        $this->userModel = new User($this->db);
    }

    public function index() {
        $stmt = $this->userModel->readAll();
        $strazacy = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/users_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->first_name = trim($_POST['first_name']);
            $this->userModel->last_name = trim($_POST['last_name']);
            $this->userModel->login = trim($_POST['login']);
            $this->userModel->email = !empty($_POST['email']) ? trim($_POST['email']) : null;
            $this->userModel->password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
            
            $rola = $_POST['role'];
            $this->userModel->role = ($rola === 'admin' || $rola === 'user') ? $rola : 'user';
            
            $this->userModel->funkcja_zarzad = !empty($_POST['funkcja_zarzad']) ? $_POST['funkcja_zarzad'] : null;
            $this->userModel->data_powolania_zarzad = !empty($_POST['data_powolania_zarzad']) ? $_POST['data_powolania_zarzad'] : null;
            
            // Sprawdzamy, czy wybrano funkcję, ale zapomniano o dacie
            if (!empty($_POST['funkcja_zarzad']) && empty($_POST['data_powolania_zarzad'])) {
                $blad = "Błąd: Jeśli druh pełni funkcję w zarządzie, musisz podać datę powołania!";
            } else {
                if ($this->userModel->create()) {
                // LOGOWANIE ZDARZENIA
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Dodano nowego druha: " . $this->userModel->first_name . " " . $this->userModel->last_name);
                
                header("Location: index.php?action=index");
                exit;
                } else {
                    $blad = "Nie udało się dodać strażaka. (Sprawdź czy login nie jest już zajęty)";
                }
            }


            
        }
        require_once 'views/add_user.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?action=index");
            exit;
        }

        $this->userModel->id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->first_name = trim($_POST['first_name']);
            $this->userModel->last_name = trim($_POST['last_name']);
            $this->userModel->login = trim($_POST['login']);
            $this->userModel->email = !empty($_POST['email']) ? trim($_POST['email']) : null;
            
            $rola = $_POST['role'];
            $this->userModel->role = ($rola === 'admin' || $rola === 'user') ? $rola : 'user';
            
            $this->userModel->funkcja_zarzad = !empty($_POST['funkcja_zarzad']) ? $_POST['funkcja_zarzad'] : null;
            $this->userModel->data_powolania_zarzad = !empty($_POST['data_powolania_zarzad']) ? $_POST['data_powolania_zarzad'] : null;

            // Dodajemy zabezpieczenie również do edycji!
            if (!empty($_POST['funkcja_zarzad']) && empty($_POST['data_powolania_zarzad'])) {
                $blad = "Błąd: Jeśli druh pełni funkcję w zarządzie, musisz podać datę powołania!";
            } else {
                if ($this->userModel->update()) {
                    // LOGOWANIE ZDARZENIA
                    $logger = new Log($this->db);
                    $logger->create($_SESSION['user_id'], "Zaktualizowano dane druha: " . $this->userModel->first_name . " " . $this->userModel->last_name);

                    header("Location: index.php?action=index");
                    exit;
                } else {
                    $blad = "Nie udało się zaktualizować danych.";
                }
            }
        } else {
            if (!$this->userModel->readOne($this->userModel->id)) {
                die("Nie znaleziono strażaka w bazie.");
            }
        }

        $druh = [
            'id' => $this->userModel->id,
            'first_name' => $this->userModel->first_name,
            'last_name' => $this->userModel->last_name,
            'login' => $this->userModel->login,
            'email' => $this->userModel->email,
            'role' => $this->userModel->role,
            'medical_exam_date' => $this->userModel->medical_exam_date,
            'smoke_chamber_date' => $this->userModel->smoke_chamber_date,
            'funkcja_zarzad' => $this->userModel->funkcja_zarzad,
            'data_powolania_zarzad' => $this->userModel->data_powolania_zarzad
        ];
        $historia_badan = $this->userModel->getMedicalHistory($this->userModel->id);
        $historia_komory = $this->userModel->getSmokeChamberHistory($this->userModel->id);
        require_once 'views/edit_user.php';
    }
    // ==========================================
    // MODUŁ BADAŃ LEKARSKICH I KOMORY
    // ==========================================
    
    // 1. Wyświetla zbiorczą listę wszystkich druhów i ich status
    public function examsList() {
        $stmt = $this->userModel->readAll();
        $strazacy = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/exams_list.php';
    }

    // 2. Wyświetla historię konkretnego druha
    public function userExams() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=exams"); exit; }
        
        $this->userModel->readOne($_GET['id']);
        $druh = [
            'id' => $this->userModel->id,
            'first_name' => $this->userModel->first_name,
            'last_name' => $this->userModel->last_name
        ];
        
        $historia_badan = $this->userModel->getMedicalHistory($_GET['id']);
        $historia_komory = $this->userModel->getSmokeChamberHistory($_GET['id']);
        
        require_once 'views/user_exams.php';
    }

    // 3. Dodaje nowy wpis do historii
    public function addExam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $typ = $_POST['exam_type'];
            $date_from = $_POST['date_from'];
            $date_to = $_POST['date_to'];
            $notes = !empty($_POST['notes']) ? trim($_POST['notes']) : null;

            if ($typ === 'medical') {
                $this->userModel->addMedicalExam($user_id, $date_from, $date_to, $notes);
                $log_msg = "Dodano badanie lekarskie";
            } else {
                $this->userModel->addSmokeChamberTest($user_id, $date_from, $date_to, $notes);
                $log_msg = "Dodano test w komorze dymowej";
            }
            
            $logger = new Log($this->db);
            $logger->create($_SESSION['user_id'], $log_msg . " dla druha ID: " . $user_id);

            // Zmienione przekierowanie! Teraz wraca do profilu badań, a nie do edycji usera!
            header("Location: index.php?action=userExams&id=" . $user_id . "&success=exam_added");
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->userModel->id = $_GET['id'];
            
            // Pobieramy dane druha zanim go usuniemy, żeby zapisać w logu kogo usunięto
            if ($this->userModel->readOne($_GET['id'])) {
                $imie_nazwisko = $this->userModel->first_name . " " . $this->userModel->last_name;
                
                if ($this->userModel->delete()) {
                    // LOGOWANIE ZDARZENIA
                    $logger = new Log($this->db);
                    $logger->create($_SESSION['user_id'], "Usunięto druha z systemu: " . $imie_nazwisko);
                }
            }
        }
        header("Location: index.php?action=index");
        exit;
    }

    public function resetPassword() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=index"); exit; }
        
        $this->userModel->readOne($_GET['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nowe_haslo = $_POST['new_password'];
            $hash = password_hash($nowe_haslo, PASSWORD_DEFAULT);
            
            if ($this->userModel->updateCredentials($_GET['id'], $this->userModel->email, $hash)) {
                // LOGOWANIE ZDARZENIA
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Wykonano twardy reset hasła dla: " . $this->userModel->first_name . " " . $this->userModel->last_name);

                // Zamiast przekierowania, ustawiamy komunikat o sukcesie!
                $sukces = "Hasło zostało pomyślnie zmienione! Możesz przekazać je druhowi.";
            } else {
                $blad = "Wystąpił błąd podczas resetowania hasła.";
            }
        }
        
        require_once 'views/reset_password.php';
    }
    // --- Eksport całej Ewidencji do pliku PDF ---
    public function exportPdf() {
        // Zabezpieczenie - tylko Admin i SuperAdmin mogą pobierać pełną ewidencję
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        // Pobieramy wszystkich strażaków (tak samo jak do widoku listy)
        $stmt = $this->userModel->readAll();
        $strazacy = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // LOGOWANIE ZDARZENIA
        $logger = new Log($this->db);
        $logger->create($_SESSION['user_id'], "Wyeksportowano pełną Ewidencję Druhów do pliku PDF");

        // Ładujemy bibliotekę Dompdf
        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        // kod HTML dokumentu (tabela ze wszystkimi druhami)
        $html = '
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #333; }
                .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
                h1 { margin: 0; font-size: 20px; color: #dc3545; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; color: #333; }
                .text-center { text-align: center; }
                .footer { margin-top: 30px; font-size: 10px; color: #777; text-align: center; border-top: 1px solid #ddd; padding-top: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>EWIDENCJA DRUHÓW OSP</h1>
                <p>Stan na dzień: <strong>' . date('d.m.Y') . '</strong></p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">Lp.</th>
                        <th style="width: 30%;">Imię i Nazwisko</th>
                        <th style="width: 25%;">Funkcja / Uprawnienia</th>
                        <th style="width: 20%;" class="text-center">Badania lekarskie</th>
                        <th style="width: 20%;" class="text-center">Komora dymowa</th>
                    </tr>
                </thead>
                <tbody>';

        $lp = 1;
        foreach ($strazacy as $druh) {
            $badania = $druh['medical_exam_date'] ? date('d.m.Y', strtotime($druh['medical_exam_date'])) : '-';
            $komora = $druh['smoke_chamber_date'] ? date('d.m.Y', strtotime($druh['smoke_chamber_date'])) : '-';
            
            // Określamy co wpisać w kolumnie "Funkcja" (priorytet ma Zarząd)
            $funkcja = !empty($druh['funkcja']) ? $druh['funkcja'] : ($druh['role'] === 'admin' ? 'Admin' : 'Strażak');

            $html .= '<tr>
                        <td class="text-center">' . $lp++ . '</td>
                        <td><strong>' . htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) . '</strong></td>
                        <td>' . htmlspecialchars($funkcja) . '</td>
                        <td class="text-center">' . $badania . '</td>
                        <td class="text-center">' . $komora . '</td>
                      </tr>';
        }

        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                Wygenerowano automatycznie z Systemu Ewidencji OSP dnia ' . date('d.m.Y \o \g\o\d\z. H:i') . '
            </div>
        </body>
        </html>';

        // Konwertujemy na PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Zwracamy plik do pobrania
        $nazwa_pliku = "Ewidencja_OSP_" . date('Y-m-d') . ".pdf";
        $dompdf->stream($nazwa_pliku, ["Attachment" => true]);
    }

}
?>