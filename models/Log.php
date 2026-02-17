<?php
class Log {
    private $conn;
    private $table_name = "system_logs";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Zapisywanie nowego logu do bazy
    public function create($user_id, $akcja) {
        // Pobieramy IP użytkownika
        $adres_ip = $_SERVER['REMOTE_ADDR'];
        if ($adres_ip === '::1') $adres_ip = '127.0.0.1'; // Poprawka dla localhosta

        $query = "INSERT INTO " . $this->table_name . " (user_id, akcja, adres_ip) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $akcja, $adres_ip]);
    }

    // Pobieranie 500 najnowszych logów z dołączonymi danymi kto to zrobił
    public function readAll() {
        $query = "SELECT l.*, u.first_name, u.last_name, u.login 
                  FROM " . $this->table_name . " l
                  LEFT JOIN users u ON l.user_id = u.id 
                  ORDER BY l.data_zdarzenia DESC LIMIT 500";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    // --- Pobieranie logów do eksportu (z opcjonalnym zakresem dat) ---
    public function getLogsForExport($date_from = null, $date_to = null) {
        $query = "SELECT l.data_zdarzenia, u.first_name, u.last_name, u.login, l.akcja, l.adres_ip 
                  FROM " . $this->table_name . " l
                  LEFT JOIN users u ON l.user_id = u.id ";
        
        $conditions = [];
        $params = [];

        // Jeśli podano datę początkową
        if (!empty($date_from)) {
            $conditions[] = "DATE(l.data_zdarzenia) >= ?";
            $params[] = $date_from;
        }
        // Jeśli podano datę końcową
        if (!empty($date_to)) {
            $conditions[] = "DATE(l.data_zdarzenia) <= ?";
            $params[] = $date_to;
        }

        // Doklejamy warunki do zapytania, jeśli jakieś istnieją
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY l.data_zdarzenia DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt;
    }
}
?>