<?php
class Work {
    private $conn;
    private $table_name = "works";

    public $id;
    public $work_date;
    public $description;
    public $estimated_value;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Pobieranie prac z konkretnego roku
    public function readByYear($year) {
        $query = "SELECT id, work_date, description, estimated_value, notes 
                  FROM " . $this->table_name . " 
                  WHERE YEAR(work_date) = :year 
                  ORDER BY work_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Pobieranie dostępnych lat do filtru
    public function getAvailableYears() {
        $query = "SELECT DISTINCT YEAR(work_date) as year FROM " . $this->table_name . " ORDER BY year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($years)) {
            $years[] = date('Y');
        }
        return $years;
    }

    // Dodawanie nowej pracy
    public function create($participants = []) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                      (work_date, description, estimated_value, notes) 
                      VALUES (:work_date, :description, :estimated_value, :notes)";
            
            $stmt = $this->conn->prepare($query);
            
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->notes = !empty($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

            $stmt->bindParam(':work_date', $this->work_date);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':estimated_value', $this->estimated_value);
            $stmt->bindParam(':notes', $this->notes);
            
            $stmt->execute();
            $work_id = $this->conn->lastInsertId();

            if (!empty($participants)) {
                $query_part = "INSERT INTO work_participants (work_id, user_id) VALUES (:work_id, :user_id)";
                $stmt_part = $this->conn->prepare($query_part);
                foreach ($participants as $user_id) {
                    $stmt_part->bindParam(':work_id', $work_id);
                    $stmt_part->bindParam(':user_id', $user_id);
                    $stmt_part->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Błąd bazy danych w module Prac (create): " . $e->getMessage());
        }
    }

    // Edycja istniejącej pracy
    public function update($participants = []) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                      SET work_date = :work_date, description = :description, estimated_value = :estimated_value, notes = :notes 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->notes = !empty($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

            $stmt->bindParam(':work_date', $this->work_date);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':estimated_value', $this->estimated_value);
            $stmt->bindParam(':notes', $this->notes);
            $stmt->bindParam(':id', $this->id);
            
            $stmt->execute();

            // Aktualizacja uczestników: najpierw usuwamy starych, potem dodajemy nowych
            $del_stmt = $this->conn->prepare("DELETE FROM work_participants WHERE work_id = ?");
            $del_stmt->execute([$this->id]);

            if (!empty($participants)) {
                $query_part = "INSERT INTO work_participants (work_id, user_id) VALUES (:work_id, :user_id)";
                $stmt_part = $this->conn->prepare($query_part);
                foreach ($participants as $user_id) {
                    $stmt_part->bindParam(':work_id', $this->id);
                    $stmt_part->bindParam(':user_id', $user_id);
                    $stmt_part->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Błąd bazy danych w module Prac (update): " . $e->getMessage());
        }
    }

    // Usuwanie pracy
    public function delete() {
        // Dzięki ON DELETE CASCADE w bazie, uczestnicy usuną się automatycznie
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
    
    // Pobieranie uczestników
    public function getParticipants($work_id) {
        $query = "SELECT u.id, u.first_name, u.last_name 
                  FROM work_participants wp
                  JOIN users u ON wp.user_id = u.id
                  WHERE wp.work_id = :work_id
                  ORDER BY u.last_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':work_id', $work_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>