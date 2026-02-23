<?php
require_once 'models/Work.php';
require_once 'models/User.php';
require_once 'models/Log.php';

class WorkController {
    private $db;
    private $workModel;
    private $userModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->workModel = new Work($this->db);
        $this->userModel = new User($this->db);
    }

    // 1. Lista z filtrowaniem po roku
    public function index() {
        $dostepne_lata = $this->workModel->getAvailableYears();
        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        $stmt = $this->workModel->readByYear($wybrany_rok);
        $prace = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/works_list.php';
    }

    // 2. Dodawanie
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->workModel->work_date = $_POST['work_date'];
            $this->workModel->description = trim($_POST['description']);
            $wartosc = str_replace(',', '.', $_POST['estimated_value']);
            $this->workModel->estimated_value = !empty($wartosc) ? $wartosc : 0.00;
            $this->workModel->notes = trim($_POST['notes']);

            $uczestnicy = isset($_POST['participants']) ? $_POST['participants'] : [];

            if ($this->workModel->create($uczestnicy)) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Dodano wpis o pracach: " . mb_substr($this->workModel->description, 0, 30));
                header("Location: index.php?action=works&success=work_added");
                exit;
            } else {
                $blad = "Błąd zapisu.";
            }
        }
        $stmt_users = $this->userModel->readAll();
        $strazacy = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/add_work.php';
    }

    // 3. Edycja
    public function edit() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=works"); exit; }
        
        $this->workModel->id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->workModel->work_date = $_POST['work_date'];
            $this->workModel->description = trim($_POST['description']);
            $wartosc = str_replace(',', '.', $_POST['estimated_value']);
            $this->workModel->estimated_value = !empty($wartosc) ? $wartosc : 0.00;
            $this->workModel->notes = trim($_POST['notes']);

            $uczestnicy = isset($_POST['participants']) ? $_POST['participants'] : [];

            if ($this->workModel->update($uczestnicy)) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Zaktualizowano prace ID: " . $this->workModel->id);
                header("Location: index.php?action=works&success=work_edited");
                exit;
            } else {
                $blad = "Błąd aktualizacji.";
            }
        }

        // Pobieramy dane do formularza
        $stmt = $this->db->prepare("SELECT * FROM works WHERE id = ?");
        $stmt->execute([$this->workModel->id]);
        $praca = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$praca) { die("Nie znaleziono wpisu."); }

        $uczestnicy_pracy = $this->workModel->getParticipants($this->workModel->id);
        $zaznaczeni_id = array_column($uczestnicy_pracy, 'id'); // Wyciągamy same ID żeby łatwo zaznaczyć w HTML

        $stmt_users = $this->userModel->readAll();
        $strazacy = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/edit_work.php'; // Nowy widok!
    }

    // 4. Usuwanie
    public function delete() {
        if (isset($_GET['id'])) {
            $this->workModel->id = $_GET['id'];
            if ($this->workModel->delete()) {
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Usunięto wpis o pracach gosp. ID: " . $this->workModel->id);
                header("Location: index.php?action=works&success=work_deleted");
            }
        }
        exit;
    }

    // 5. Szczegóły
    public function view() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=works"); exit; }
        $work_id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM works WHERE id = ?");
        $stmt->execute([$work_id]);
        $praca = $stmt->fetch(PDO::FETCH_ASSOC);
        $uczestnicy = $this->workModel->getParticipants($work_id);
        require_once 'views/view_work.php';
    }

    // 6. Generowanie PDF z wybranego roku
    public function exportPdf() {
        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        $wybrany_rok = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $stmt = $this->workModel->readByYear($wybrany_rok);
        $prace = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #198754; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { margin: 0; color: #198754; text-transform: uppercase; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #999; padding: 6px; text-align: left; }
            th { background-color: #f8f9fa; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #777; }
        </style></head><body>';
        
        $html .= '<div class="header"><h2>Ewidencja Prac Gospodarczych - Rok ' . $wybrany_rok . '</h2><p>Stan na dzień: ' . date('d.m.Y') . '</p></div>';
        $html .= '<table><thead><tr>
                    <th class="text-center" style="width: 5%;">Lp.</th>
                    <th class="text-center" style="width: 15%;">Data</th>
                    <th style="width: 45%;">Zakres wykonywanych prac</th>
                    <th class="text-right" style="width: 15%;">Wartość (zł)</th>
                  </tr></thead><tbody>';

        $lp = 1;
        $suma = 0;
        if (!empty($prace)) {
            foreach ($prace as $praca) {
                $html .= '<tr>
                            <td class="text-center">' . $lp++ . '</td>
                            <td class="text-center"><strong>' . date('d.m.Y', strtotime($praca['work_date'])) . '</strong></td>
                            <td>' . htmlspecialchars($praca['description']) . '</td>
                            <td class="text-right">' . ($praca['estimated_value'] > 0 ? number_format($praca['estimated_value'], 2, ',', ' ') : '-') . '</td>
                          </tr>';
                $suma += $praca['estimated_value'];
            }
            $html .= '<tr><td colspan="3" class="text-right"><strong>Łączna szacunkowa wartość:</strong></td><td class="text-right"><strong>' . number_format($suma, 2, ',', ' ') . '</strong></td></tr>';
        } else {
            $html .= '<tr><td colspan="4" class="text-center">Brak zarejestrowanych prac w wybranym roku.</td></tr>';
        }

        $html .= '</tbody></table><div class="footer">Wygenerowano z Systemu Ewidencji OSP</div></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Ewidencja_Prac_" . $wybrany_rok . ".pdf", ["Attachment" => true]);
    }
    // 7. Eksport szczegółowej karty pojedynczej pracy do PDF
    public function exportSinglePdf() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=works"); exit; }
        
        $work_id = $_GET['id'];
        
        // Pobieramy dane pracy
        $stmt = $this->db->prepare("SELECT * FROM works WHERE id = ?");
        $stmt->execute([$work_id]);
        $praca = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$praca) { die("Nie znaleziono wpisu."); }

        // Pobieramy druhów
        $uczestnicy = $this->workModel->getParticipants($work_id);

        require_once 'libs/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        $html = '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 14px; line-height: 1.6; color: #222; }
            .header { text-align: center; border-bottom: 2px solid #198754; padding-bottom: 10px; margin-bottom: 20px; }
            h2 { color: #198754; margin: 0; text-transform: uppercase; }
            .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
            .details-table td { padding: 8px; border-bottom: 1px dashed #ccc; }
            .label { font-weight: bold; width: 35%; color: #555; }
            h3 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; }
            .participants-list { list-style-type: none; padding: 0; margin: 0; }
            .participants-list li { padding: 6px 0; border-bottom: 1px solid #eee; }
            .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        </style></head><body>';

        $html .= '<div class="header"><h2>Karta Prac Gospodarczych</h2><p>Raport z wykonanych prac na rzecz OSP</p></div>';

        $html .= '<table class="details-table">
                    <tr><td class="label">Data wykonania:</td><td><strong>' . date('d.m.Y', strtotime($praca['work_date'])) . '</strong></td></tr>
                    <tr><td class="label">Zakres prac:</td><td>' . nl2br(htmlspecialchars($praca['description'])) . '</td></tr>
                    <tr><td class="label">Szacowana wartość:</td><td>' . ($praca['estimated_value'] > 0 ? number_format($praca['estimated_value'], 2, ',', ' ') . ' zł' : 'Nie wyceniono') . '</td></tr>';
        
        if (!empty($praca['notes'])) {
            $html .= '<tr><td class="label">Uwagi:</td><td style="font-style: italic;">' . nl2br(htmlspecialchars($praca['notes'])) . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h3>Osoby zaangażowane w prace</h3>';
        if (!empty($uczestnicy)) {
            $html .= '<ul class="participants-list">';
            $lp = 1;
            foreach ($uczestnicy as $u) {
                $html .= '<li><strong>' . $lp++ . '.</strong> ' . htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) . '</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= '<p style="color: #999;">Brak wprowadzonych uczestników do tych prac.</p>';
        }

        $html .= '<div class="footer">Karta prac nr ' . $praca['id'] . ' wygenerowana z Systemu Ewidencji OSP</div></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Karta_Prac_" . date('Y-m-d', strtotime($praca['work_date'])) . "_ID" . $praca['id'] . ".pdf", ["Attachment" => true]);
    }
}
?>