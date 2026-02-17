<?php
class Equipment {
    private $conn;
    private $table_name = "equipment";

    public $id, $nazwa, $ilosc, $stan, $vehicle_id, $uwagi;

    public function __construct($db) { $this->conn = $db; }

    // Pobierz cały sprzęt (wraz z numerem operacyjnym przypisanego wozu)
    public function readAll() {
        $query = "SELECT e.*, v.numer_operacyjny 
                  FROM " . $this->table_name . " e
                  LEFT JOIN vehicles v ON e.vehicle_id = v.id
                  ORDER BY e.nazwa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id']; $this->nazwa = $row['nazwa']; $this->ilosc = $row['ilosc'];
            $this->stan = $row['stan']; $this->vehicle_id = $row['vehicle_id']; $this->uwagi = $row['uwagi'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nazwa, ilosc, stan, vehicle_id, uwagi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->nazwa, $this->ilosc, $this->stan, $this->vehicle_id, $this->uwagi]);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nazwa = ?, ilosc = ?, stan = ?, vehicle_id = ?, uwagi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->nazwa, $this->ilosc, $this->stan, $this->vehicle_id, $this->uwagi, $this->id]);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
}
?>