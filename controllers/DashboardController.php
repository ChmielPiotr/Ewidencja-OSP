<?php
require_once 'models/User.php';
require_once 'models/Log.php';

class DashboardController {
    private $db;
    private $userModel;

    public function __construct($db) {
        $this->db = $db; 
        $this->userModel = new User($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // --- OBSŁUGA FORMULARZA ZMIANY DANYCH ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $nowy_email = !empty($_POST['email']) ? trim($_POST['email']) : null;
            $nowe_haslo = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
            
            $hash = $nowe_haslo ? password_hash($nowe_haslo, PASSWORD_DEFAULT) : null;
            
            if ($this->userModel->updateCredentials($_SESSION['user_id'], $nowy_email, $hash)) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Użytkownik samodzielnie zaktualizował swoje dane (e-mail/hasło)");
                $komunikat = "Twoje dane zostały pomyślnie zaktualizowane!";
            } else {
                $blad = "Wystąpił błąd podczas aktualizacji danych.";
            }
        }

        // ---Pobieramy podstawowe dane druha---
        $this->userModel->readOne($_SESSION['user_id']);
        
        // ---Pobieramy statystyki druha---
        $statystyki = $this->userModel->getUserStats($_SESSION['user_id']);
        
        $druh = [
            'first_name' => $this->userModel->first_name,
            'email' => $this->userModel->email,
            'medical_exam_date' => $this->userModel->medical_exam_date,
            'smoke_chamber_date' => $this->userModel->smoke_chamber_date,
            'stats' => $statystyki // Przekazujemy statystyki do widoku
        ];

        require_once 'views/dashboard.php';
    }

    public function generatePdf() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $this->userModel->readOne($_SESSION['user_id']);
        $statystyki = $this->userModel->getUserStats($_SESSION['user_id']); // Pobieramy do PDF
        
        $imie = $this->userModel->first_name;
        $nazwisko = $this->userModel->last_name;
        $badania = $this->userModel->medical_exam_date ? date('d.m.Y', strtotime($this->userModel->medical_exam_date)) : 'Brak danych';
        $komora = $this->userModel->smoke_chamber_date ? date('d.m.Y', strtotime($this->userModel->smoke_chamber_date)) : 'Brak danych';

        $logger = new Log($this->db);
        $logger->create($_SESSION['user_id'], "Wygenerowano Kartę Strażaka PDF");

        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        $html = '
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: "DejaVu Sans", sans-serif; color: #333; }
                .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 30px; }
                h1 { color: #dc3545; margin: 0; font-size: 24px; }
                h2 { margin: 5px 0 0 0; font-size: 18px; color: #555; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 30px; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                th { background-color: #f8f9fa; color: #333; }
                .stats-box { border: 1px solid #ccc; padding: 15px; background: #fdfdfd; margin-top: 20px;}
                .stats-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;}
                .footer { margin-top: 50px; font-size: 12px; color: #777; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>KARTA EWIDENCYJNA STRAŻAKA</h1>
                <h2>Ochotnicza Straż Pożarna</h2>
            </div>
            
            <p><strong>Druh/Druhno:</strong> ' . htmlspecialchars($imie . ' ' . $nazwisko) . '</p>
            
            <table>
                <tr>
                    <th style="width: 50%;">Rodzaj uprawnienia / badania</th>
                    <th style="width: 50%;">Termin ważności</th>
                </tr>
                <tr>
                    <td>Badania lekarskie (praca w aparacie)</td>
                    <td><strong>' . $badania . '</strong></td>
                </tr>
                <tr>
                    <td>Komora dymowa</td>
                    <td><strong>' . $komora . '</strong></td>
                </tr>
            </table>

            <div class="stats-box">
                <div class="stats-title">Historia aktywności (Udział w życiu OSP)</div>
                <ul>
                    <li><strong>Akcje ratownicze:</strong> Brał udział w ' . $statystyki['incidents'] . ' wyjazdach.</li>
                    <li><strong>Prace gospodarcze:</strong> Zaangażowany w prace ' . $statystyki['works'] . ' razy.</li>
                    <li><strong>Ćwiczenia:</strong> Odbył ' . $statystyki['drills'] . ' szkoleń/zbiórek.</li>
                </ul>
            </div>

            <div class="footer">
                Wygenerowano z Systemu Ewidencji OSP dnia ' . date('d.m.Y H:i') . '
            </div>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nazwa_pliku = "Karta_Strazaka_" . $imie . "_" . $nazwisko . ".pdf";
        $dompdf->stream($nazwa_pliku, ["Attachment" => true]);
    }
}
?>