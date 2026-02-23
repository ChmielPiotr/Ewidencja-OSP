<?php
require_once 'models/Incident.php';
require_once 'models/User.php'; // Potrzebujemy modelu usera, żeby pobrać listę druhów do zaznaczenia
require_once 'models/Log.php';

class IncidentController {
    private $db;
    private $incidentModel;
    private $userModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->incidentModel = new Incident($this->db);
        $this->userModel = new User($this->db);
    }

    // 1. Wyświetlanie listy wyjazdów (Teraz z filtrowaniem rocznikami!)
    public function index() {
        // Pobieramy lata do listy rozwijanej
        $dostepne_lata = $this->incidentModel->getAvailableYears();
        
        // Domyślnie bierzemy rok z paska adresu (jeśli kliknięto), a jeśli nie - bieżący rok
        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        // Pobieramy akcje TYLKO z wybranego roku
        $stmt = $this->incidentModel->readByYear($wybrany_rok);
        $akcje = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/incidents_list.php';
    }

    // 2. Dodawanie nowego wyjazdu
    public function add() {
        // Jeśli formularz został wysłany
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->incidentModel->incident_date = $_POST['incident_date'];
            $this->incidentModel->time_departure = $_POST['time_departure'];
            $this->incidentModel->time_return = $_POST['time_return'];
            $this->incidentModel->incident_type = $_POST['incident_type'];
            $this->incidentModel->location = trim($_POST['location']);
            $this->incidentModel->notes = trim($_POST['notes']);

            // Pobieramy tablicę z zaznaczonymi strażakami (jeśli nikogo nie zaznaczono, dajemy pustą tablicę)
            $uczestnicy = isset($_POST['participants']) ? $_POST['participants'] : [];

            // Zapisujemy wyjazd
            if ($this->incidentModel->create($uczestnicy)) {
                // Dodajemy wpis do logów
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Zarejestrowano nowy wyjazd: " . $this->incidentModel->incident_type . " - " . $this->incidentModel->location);

                header("Location: index.php?action=incidents&success=incident_added");
                exit;
            } else {
                $blad = "Wystąpił błąd podczas zapisywania akcji w bazie.";
            }
        }

        // Pobieramy wszystkich aktywnych strażaków (bez SuperAdmina), żeby wyświetlić ich jako Checkboxy w formularzu
        $stmt_users = $this->userModel->readAll();
        $strazacy = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/add_incident.php';
    }

    // 3. Widok szczegółów konkretnej akcji (kto jechał itp.)
    public function view() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=incidents"); exit; }
        
        $incident_id = $_GET['id'];
        
        // Pobieramy podstawowe dane o akcji
        $stmt = $this->db->prepare("SELECT * FROM incidents WHERE id = ?");
        $stmt->execute([$incident_id]);
        $akcja = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$akcja) { die("Nie znaleziono takiej akcji."); }

        // Pobieramy listę druhów, którzy brali udział w tym wyjeździe
        $uczestnicy = $this->incidentModel->getParticipants($incident_id);

        require_once 'views/view_incident.php';
    }

    // Eksport ogólnej listy wyjazdów z konkretnego ROKU
    public function exportAllPdf() {
        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        // Łapiemy rok przekazany w przycisku (lub domyślnie bieżący)
        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        $stmt = $this->incidentModel->readByYear($wybrany_rok);
        $akcje = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // -- CAŁA RESZTA GENEROWANIA PDF (ze zaktualizowanym nagłówkiem) --
        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { margin: 0; color: #dc3545; text-transform: uppercase; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #999; padding: 6px; text-align: left; }
            th { background-color: #f8f9fa; }
            .text-center { text-align: center; }
            .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #777; }
        </style></head><body>';
        
        // Dodaliśmy rok do tytułu dokumentu PDF!
        $html .= '<div class="header"><h2>Rejestr Wyjazdów Ratowniczych - Rok ' . $wybrany_rok . '</h2><p>Stan na dzień: ' . date('d.m.Y') . '</p></div>';
        $html .= '<table><thead><tr>
                    <th class="text-center" style="width: 5%;">Lp.</th>
                    <th class="text-center" style="width: 15%;">Data</th>
                    <th class="text-center" style="width: 15%;">Czas trwania</th>
                    <th style="width: 25%;">Rodzaj zdarzenia</th>
                    <th style="width: 40%;">Miejsce</th>
                  </tr></thead><tbody>';

        $lp = 1;
        if (!empty($akcje)) {
            foreach ($akcje as $akcja) {
                $czas_trwania = date('H:i', strtotime($akcja['time_departure'])) . ' - ' . date('H:i', strtotime($akcja['time_return']));
                $html .= '<tr>
                            <td class="text-center">' . $lp++ . '</td>
                            <td class="text-center"><strong>' . date('d.m.Y', strtotime($akcja['incident_date'])) . '</strong></td>
                            <td class="text-center">' . $czas_trwania . '</td>
                            <td>' . htmlspecialchars($akcja['incident_type']) . '</td>
                            <td>' . htmlspecialchars($akcja['location']) . '</td>
                          </tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center">Brak wyjazdów w wybranym roku.</td></tr>';
        }

        $html .= '</tbody></table><div class="footer">Wygenerowano automatycznie z Systemu Ewidencji OSP</div></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Nazwa pliku zawiera teraz wybrany rok!
        $dompdf->stream("Rejestr_Wyjazdow_" . $wybrany_rok . ".pdf", ["Attachment" => true]);
    }

    // 2. Eksport szczegółowej karty pojedynczego zdarzenia
    public function exportSinglePdf() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=incidents"); exit; }
        
        $incident_id = $_GET['id'];
        
        $stmt = $this->db->prepare("SELECT * FROM incidents WHERE id = ?");
        $stmt->execute([$incident_id]);
        $akcja = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$akcja) { die("Nie znaleziono akcji."); }

        $uczestnicy = $this->incidentModel->getParticipants($incident_id);

        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 14px; line-height: 1.6; color: #222; }
            .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { color: #dc3545; margin: 0; text-transform: uppercase; }
            .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
            .details-table td { padding: 8px; border-bottom: 1px dashed #ccc; }
            .label { font-weight: bold; width: 30%; color: #555; }
            h3 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; }
            .participants-list { list-style-type: none; padding: 0; margin: 0; }
            .participants-list li { padding: 6px 0; border-bottom: 1px solid #eee; }
            .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        </style></head><body>';

        $html .= '<div class="header"><h2>Karta Zdarzenia Ratowniczego</h2><p>Raport z wyjazdu jednostki OSP</p></div>';

        $html .= '<table class="details-table">
                    <tr><td class="label">Data zdarzenia:</td><td><strong>' . date('d.m.Y', strtotime($akcja['incident_date'])) . '</strong></td></tr>
                    <tr><td class="label">Czas wyjazdu alarmowego:</td><td>' . date('H:i', strtotime($akcja['time_departure'])) . '</td></tr>
                    <tr><td class="label">Czas powrotu do remizy:</td><td>' . date('H:i', strtotime($akcja['time_return'])) . '</td></tr>
                    <tr><td class="label">Rodzaj zdarzenia:</td><td><strong>' . htmlspecialchars($akcja['incident_type']) . '</strong></td></tr>
                    <tr><td class="label">Miejsce zdarzenia:</td><td>' . htmlspecialchars($akcja['location']) . '</td></tr>';
        
        if (!empty($akcja['notes'])) {
            $html .= '<tr><td class="label">Opis / Uwagi z akcji:</td><td style="font-style: italic;">' . nl2br(htmlspecialchars($akcja['notes'])) . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h3>Skład zastępu (uczestnicy akcji)</h3>';
        if (!empty($uczestnicy)) {
            $html .= '<ul class="participants-list">';
            $lp = 1;
            foreach ($uczestnicy as $u) {
                $html .= '<li><strong>' . $lp++ . '.</strong> ' . htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) . '</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= '<p style="color: #999;">Brak wprowadzonych uczestników do tego wyjazdu.</p>';
        }

        $html .= '<div class="footer">Karta zdarzenia nr ' . $akcja['id'] . ' wygenerowana z Systemu Ewidencji OSP</div></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Karta_Zdarzenia_" . date('Y-m-d', strtotime($akcja['incident_date'])) . "_ID" . $akcja['id'] . ".pdf", ["Attachment" => true]);
    }
}
?>