<?php
class Incident {
    private $conn;
    private $table_name = "incidents";

    public $id;
    public $incident_date;
    public $time_departure;
    public $time_return;
    public $incident_type;
    public $location;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Pobieranie wszystkich akcji na główną listę
    public function readAll() {
        $query = "SELECT id, incident_date, time_departure, time_return, incident_type, location, notes 
                  FROM " . $this->table_name . " 
                  ORDER BY incident_date DESC, time_departure DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    // Pobieranie akcji tylko z konkretnego roku
    public function readByYear($year) {
        $query = "SELECT id, incident_date, time_departure, time_return, incident_type, location, notes 
                  FROM " . $this->table_name . " 
                  WHERE YEAR(incident_date) = :year 
                  ORDER BY incident_date DESC, time_departure DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Pobieranie dostępnych lat do filtru (żeby w menu były tylko te lata, w których był jakiś wyjazd)
    public function getAvailableYears() {
        $query = "SELECT DISTINCT YEAR(incident_date) as year FROM " . $this->table_name . " ORDER BY year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Jeśli baza jest pusta, domyślnie zwracamy chociaż obecny rok
        if (empty($years)) {
            $years[] = date('Y');
        }
        return $years;
    }

    // 2. Dodawanie nowej akcji ratowniczej (Zapis do DWÓCH tabel jednocześnie)
    public function create($participants = []) {
        try {
            // Rozpoczynamy transakcję chroniącą spójność danych
            $this->conn->beginTransaction();

            // Krok A: Zapisujemy ogólne dane o wyjeździe
            $query = "INSERT INTO " . $this->table_name . " 
                      (incident_date, time_departure, time_return, incident_type, location, notes) 
                      VALUES (:incident_date, :time_departure, :time_return, :incident_type, :location, :notes)";
            
            $stmt = $this->conn->prepare($query);
            
            // Filtrowanie wpisywanych tekstów
            $this->incident_type = htmlspecialchars(strip_tags($this->incident_type));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->notes = !empty($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

            $stmt->bindParam(':incident_date', $this->incident_date);
            $stmt->bindParam(':time_departure', $this->time_departure);
            $stmt->bindParam(':time_return', $this->time_return);
            $stmt->bindParam(':incident_type', $this->incident_type);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':notes', $this->notes);
            
            $stmt->execute();
            
            // Pobieramy ID wyjazdu, który przed sekundą stworzyliśmy
            $incident_id = $this->conn->lastInsertId();

            // Krok B: Przypisujemy zaznaczonych strażaków do tego wyjazdu
            if (!empty($participants)) {
                $query_part = "INSERT INTO incident_participants (incident_id, user_id) VALUES (:incident_id, :user_id)";
                $stmt_part = $this->conn->prepare($query_part);
                
                foreach ($participants as $user_id) {
                    $stmt_part->bindParam(':incident_id', $incident_id);
                    $stmt_part->bindParam(':user_id', $user_id);
                    $stmt_part->execute();
                }
            }

            // Zatwierdzamy zapis w obu tabelach
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack(); // Jeśli wystąpił błąd, cofamy wszystko
            die("Błąd bazy danych w module Akcji: " . $e->getMessage());
        }
    }
    
    // 3. Pobieranie listy strażaków biorących udział w konkretnej akcji (przydatne do widoku szczegółów)
    public function getParticipants($incident_id) {
        $query = "SELECT u.id, u.first_name, u.last_name 
                  FROM incident_participants ip
                  JOIN users u ON ip.user_id = u.id
                  WHERE ip.incident_id = :incident_id
                  ORDER BY u.last_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':incident_id', $incident_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>