<?php
class Drill {
    private $conn;
    private $table_name = "drills";

    public $id;
    public $drill_date;
    public $topic;
    public $duration;
    public $conductor;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Pobieranie ćwiczeń z konkretnego roku
    public function readByYear($year) {
        $query = "SELECT id, drill_date, topic, duration, conductor, notes 
                  FROM " . $this->table_name . " 
                  WHERE YEAR(drill_date) = :year 
                  ORDER BY drill_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Dostępne lata do filtru
    public function getAvailableYears() {
        $query = "SELECT DISTINCT YEAR(drill_date) as year FROM " . $this->table_name . " ORDER BY year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($years)) { $years[] = date('Y'); }
        return $years;
    }

    // Dodawanie
    public function create($participants = []) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                      (drill_date, topic, duration, conductor, notes) 
                      VALUES (:drill_date, :topic, :duration, :conductor, :notes)";
            
            $stmt = $this->conn->prepare($query);
            
            $this->topic = htmlspecialchars(strip_tags($this->topic));
            $this->conductor = htmlspecialchars(strip_tags($this->conductor));
            $this->notes = !empty($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

            $stmt->bindParam(':drill_date', $this->drill_date);
            $stmt->bindParam(':topic', $this->topic);
            $stmt->bindParam(':duration', $this->duration);
            $stmt->bindParam(':conductor', $this->conductor);
            $stmt->bindParam(':notes', $this->notes);
            
            $stmt->execute();
            $drill_id = $this->conn->lastInsertId();

            if (!empty($participants)) {
                $query_part = "INSERT INTO drill_participants (drill_id, user_id) VALUES (:drill_id, :user_id)";
                $stmt_part = $this->conn->prepare($query_part);
                foreach ($participants as $user_id) {
                    $stmt_part->bindParam(':drill_id', $drill_id);
                    $stmt_part->bindParam(':user_id', $user_id);
                    $stmt_part->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Błąd bazy danych (Ćwiczenia - create): " . $e->getMessage());
        }
    }

    // Edycja
    public function update($participants = []) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                      SET drill_date = :drill_date, topic = :topic, duration = :duration, conductor = :conductor, notes = :notes 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->topic = htmlspecialchars(strip_tags($this->topic));
            $this->conductor = htmlspecialchars(strip_tags($this->conductor));
            $this->notes = !empty($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

            $stmt->bindParam(':drill_date', $this->drill_date);
            $stmt->bindParam(':topic', $this->topic);
            $stmt->bindParam(':duration', $this->duration);
            $stmt->bindParam(':conductor', $this->conductor);
            $stmt->bindParam(':notes', $this->notes);
            $stmt->bindParam(':id', $this->id);
            
            $stmt->execute();

            $del_stmt = $this->conn->prepare("DELETE FROM drill_participants WHERE drill_id = ?");
            $del_stmt->execute([$this->id]);

            if (!empty($participants)) {
                $query_part = "INSERT INTO drill_participants (drill_id, user_id) VALUES (:drill_id, :user_id)";
                $stmt_part = $this->conn->prepare($query_part);
                foreach ($participants as $user_id) {
                    $stmt_part->bindParam(':drill_id', $this->id);
                    $stmt_part->bindParam(':user_id', $user_id);
                    $stmt_part->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Błąd bazy danych (Ćwiczenia - update): " . $e->getMessage());
        }
    }

    // Usuwanie
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
    
    // Pobieranie uczestników
    public function getParticipants($drill_id) {
        $query = "SELECT u.id, u.first_name, u.last_name 
                  FROM drill_participants dp
                  JOIN users u ON dp.user_id = u.id
                  WHERE dp.drill_id = :drill_id
                  ORDER BY u.last_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':drill_id', $drill_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>