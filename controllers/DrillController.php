<?php
require_once 'models/Drill.php';
require_once 'models/User.php';
require_once 'models/Log.php';

class DrillController {
    private $db;
    private $drillModel;
    private $userModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->drillModel = new Drill($this->db);
        $this->userModel = new User($this->db);
    }

    public function index() {
        $dostepne_lata = $this->drillModel->getAvailableYears();
        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        $stmt = $this->drillModel->readByYear($wybrany_rok);
        $cwiczenia = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/drills_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->drillModel->drill_date = $_POST['drill_date'];
            $this->drillModel->topic = trim($_POST['topic']);
            $this->drillModel->duration = str_replace(',', '.', $_POST['duration']);
            $this->drillModel->conductor = trim($_POST['conductor']);
            $this->drillModel->notes = trim($_POST['notes']);

            $uczestnicy = isset($_POST['participants']) ? $_POST['participants'] : [];

            if ($this->drillModel->create($uczestnicy)) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Zarejestrowano ćwiczenia: " . mb_substr($this->drillModel->topic, 0, 30));
                header("Location: index.php?action=drills&success=drill_added");
                exit;
            } else {
                $blad = "Błąd zapisu ćwiczeń.";
            }
        }
        $stmt_users = $this->userModel->readAll();
        $strazacy = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/add_drill.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=drills"); exit; }
        $this->drillModel->id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->drillModel->drill_date = $_POST['drill_date'];
            $this->drillModel->topic = trim($_POST['topic']);
            $this->drillModel->duration = str_replace(',', '.', $_POST['duration']);
            $this->drillModel->conductor = trim($_POST['conductor']);
            $this->drillModel->notes = trim($_POST['notes']);

            $uczestnicy = isset($_POST['participants']) ? $_POST['participants'] : [];

            if ($this->drillModel->update($uczestnicy)) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Zaktualizowano ćwiczenia ID: " . $this->drillModel->id);
                header("Location: index.php?action=drills&success=drill_edited");
                exit;
            }
        }

        $stmt = $this->db->prepare("SELECT * FROM drills WHERE id = ?");
        $stmt->execute([$this->drillModel->id]);
        $cwiczenie = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$cwiczenie) { die("Nie znaleziono wpisu."); }

        $uczestnicy_cwiczen = $this->drillModel->getParticipants($this->drillModel->id);
        $zaznaczeni_id = array_column($uczestnicy_cwiczen, 'id');

        $stmt_users = $this->userModel->readAll();
        $strazacy = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/edit_drill.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->drillModel->id = $_GET['id'];
            if ($this->drillModel->delete()) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Usunięto wpis o ćwiczeniach ID: " . $this->drillModel->id);
                header("Location: index.php?action=drills&success=drill_deleted");
            }
        }
        exit;
    }

    public function view() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=drills"); exit; }
        $drill_id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM drills WHERE id = ?");
        $stmt->execute([$drill_id]);
        $cwiczenie = $stmt->fetch(PDO::FETCH_ASSOC);
        $uczestnicy = $this->drillModel->getParticipants($drill_id);
        require_once 'views/view_drill.php';
    }

    public function exportPdf() {
        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();
        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $stmt = $this->drillModel->readByYear($wybrany_rok);
        $cwiczenia = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #0dcaf0; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { margin: 0; color: #0dcaf0; text-transform: uppercase; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #999; padding: 6px; text-align: left; }
            th { background-color: #f8f9fa; }
            .text-center { text-align: center; }
            .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #777; }
        </style></head><body>';
        
        $html .= '<div class="header"><h2>Ewidencja Ćwiczeń i Szkoleń OSP - Rok ' . $wybrany_rok . '</h2></div>';
        $html .= '<table><thead><tr>
                    <th class="text-center" style="width: 5%;">Lp.</th>
                    <th class="text-center" style="width: 15%;">Data</th>
                    <th style="width: 45%;">Temat ćwiczeń</th>
                    <th class="text-center" style="width: 10%;">Czas (h)</th>
                    <th style="width: 25%;">Prowadzący</th>
                  </tr></thead><tbody>';

        $lp = 1;
        if (!empty($cwiczenia)) {
            foreach ($cwiczenia as $cw) {
                $html .= '<tr>
                            <td class="text-center">' . $lp++ . '</td>
                            <td class="text-center"><strong>' . date('d.m.Y', strtotime($cw['drill_date'])) . '</strong></td>
                            <td>' . htmlspecialchars($cw['topic']) . '</td>
                            <td class="text-center">' . $cw['duration'] . '</td>
                            <td>' . htmlspecialchars($cw['conductor'] ?? '-') . '</td>
                          </tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center">Brak ćwiczeń w wybranym roku.</td></tr>';
        }
        $html .= '</tbody></table><div class="footer">Wygenerowano z Systemu Ewidencji OSP</div></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Ewidencja_Cwiczen_" . $wybrany_rok . ".pdf", ["Attachment" => true]);
    }

    public function exportSinglePdf() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=drills"); exit; }
        $drill_id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM drills WHERE id = ?");
        $stmt->execute([$drill_id]);
        $cwiczenie = $stmt->fetch(PDO::FETCH_ASSOC);
        $uczestnicy = $this->drillModel->getParticipants($drill_id);

        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 14px; line-height: 1.6; color: #222; }
            .header { text-align: center; border-bottom: 2px solid #0dcaf0; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { color: #0dcaf0; margin: 0; text-transform: uppercase; }
            .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
            .details-table td { padding: 8px; border-bottom: 1px dashed #ccc; }
            .label { font-weight: bold; width: 35%; color: #555; }
            h3 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; }
            .participants-list { list-style-type: none; padding: 0; margin: 0; }
            .participants-list li { padding: 6px 0; border-bottom: 1px solid #eee; }
        </style></head><body>';

        $html .= '<div class="header"><h2>Karta Ćwiczeń OSP</h2></div>';
        $html .= '<table class="details-table">
                    <tr><td class="label">Data ćwiczeń:</td><td><strong>' . date('d.m.Y', strtotime($cwiczenie['drill_date'])) . '</strong></td></tr>
                    <tr><td class="label">Temat zajęć:</td><td>' . htmlspecialchars($cwiczenie['topic']) . '</td></tr>
                    <tr><td class="label">Czas trwania:</td><td>' . $cwiczenie['duration'] . ' godz.</td></tr>
                    <tr><td class="label">Prowadzący:</td><td>' . htmlspecialchars($cwiczenie['conductor'] ?? '-') . '</td></tr>';
        if (!empty($cwiczenie['notes'])) {
            $html .= '<tr><td class="label">Uwagi:</td><td style="font-style: italic;">' . nl2br(htmlspecialchars($cwiczenie['notes'])) . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h3>Lista obecności (uczestnicy)</h3>';
        if (!empty($uczestnicy)) {
            $html .= '<ul class="participants-list">';
            $lp = 1;
            foreach ($uczestnicy as $u) {
                $html .= '<li><strong>' . $lp++ . '.</strong> ' . htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) . '</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= '<p style="color: #999;">Brak wprowadzonych uczestników.</p>';
        }

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Karta_Cwiczen_" . date('Y-m-d', strtotime($cwiczenie['drill_date'])) . ".pdf", ["Attachment" => true]);
    }
}
?>