<?php
require_once 'models/Log.php';

class LogController {
    private $logModel;

    public function __construct($db) {
        $this->logModel = new Log($db);
    }

    public function index() {
        // POTĘŻNE ZABEZPIECZENIE: Tylko SuperAdmin ma tu wstęp!
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $stmt = $this->logModel->readAll();
        $logi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/logs_list.php';
    }
    // --- Generowanie i pobieranie pliku CSV ---
    public function exportCsv() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: index.php?action=dashboard");
            exit;
        }

        // Pobieramy daty z formularza (jeśli są)
        $date_from = !empty($_GET['date_from']) ? $_GET['date_from'] : null;
        $date_to = !empty($_GET['date_to']) ? $_GET['date_to'] : null;

        $stmt = $this->logModel->getLogsForExport($date_from, $date_to);
        $logi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ustawiamy nazwę pliku z dzisiejszą datą
        $filename = "Logi_OSP_" . date('Y-m-d_H-i-s') . ".csv";

        // Mówimy przeglądarce, że pobieramy plik CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Otwieramy strumień wyjściowy
        $output = fopen('php://output', 'w');
        
        // Trik: Dodajemy BOM (Byte Order Mark) żeby polski Excel poprawnie czytał polskie znaki!
        fputs($output, "\xEF\xBB\xBF");
        
        // Zapisujemy nagłówki kolumn (jako separator używamy średnika ';')
        fputcsv($output, ['Data i godzina', 'Imię i Nazwisko', 'Login', 'Zdarzenie', 'Adres IP'], ';');

        // Wrzucamy dane wiersz po wierszu
        foreach ($logi as $log) {
            $user = $log['first_name'] ? $log['first_name'] . ' ' . $log['last_name'] : 'System / Usunięty';
            $login = $log['login'] ? $log['login'] : '-';

            fputcsv($output, [
                $log['data_zdarzenia'],
                $user,
                $login,
                $log['akcja'],
                $log['adres_ip']
            ], ';');
        }

        fclose($output);
        exit;
    }
}
?>