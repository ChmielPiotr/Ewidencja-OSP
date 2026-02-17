<?php
class Vehicle {
    private $conn;
    private $table_name = "vehicles";

    public $id;
    public $rodzaj;
    public $marka_model;
    public $numer_operacyjny;
    public $nr_rejestracyjny;
    public $przeglad_data;
    public $ubezpieczenie_data;     // Ubezpieczenie OC
    public $ubezpieczenie_ac_data;  // Ubezpieczenie AC
    public $uwagi;                  // Informacje o usterkach/naprawach

    public function __construct($db) {
        $this->conn = $db;
    }

    // Pobierz wszystkie pojazdy
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY rodzaj ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Pobierz jeden pojazd do edycji
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->rodzaj = $row['rodzaj'];
            $this->marka_model = $row['marka_model'];
            $this->numer_operacyjny = $row['numer_operacyjny'];
            $this->nr_rejestracyjny = $row['nr_rejestracyjny'];
            $this->przeglad_data = $row['przeglad_data'];
            $this->ubezpieczenie_data = $row['ubezpieczenie_data'];
            $this->ubezpieczenie_ac_data = $row['ubezpieczenie_ac_data'];
            $this->uwagi = $row['uwagi'];
            return true;
        }
        return false;
    }

    // Dodaj nowy pojazd
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (rodzaj, marka_model, numer_operacyjny, nr_rejestracyjny, przeglad_data, ubezpieczenie_data, ubezpieczenie_ac_data, uwagi) 
                  VALUES (:rodzaj, :marka_model, :numer_operacyjny, :nr_rejestracyjny, :przeglad_data, :ubezpieczenie_data, :ubezpieczenie_ac_data, :uwagi)";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':rodzaj' => $this->rodzaj,
            ':marka_model' => $this->marka_model,
            ':numer_operacyjny' => $this->numer_operacyjny,
            ':nr_rejestracyjny' => $this->nr_rejestracyjny,
            ':przeglad_data' => $this->przeglad_data,
            ':ubezpieczenie_data' => $this->ubezpieczenie_data,
            ':ubezpieczenie_ac_data' => $this->ubezpieczenie_ac_data,
            ':uwagi' => $this->uwagi
        ])) {
            return true;
        }
        return false;
    }

    // Aktualizuj pojazd
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET rodzaj = :rodzaj, marka_model = :marka_model, numer_operacyjny = :numer_operacyjny, 
                      nr_rejestracyjny = :nr_rejestracyjny, przeglad_data = :przeglad_data, 
                      ubezpieczenie_data = :ubezpieczenie_data, ubezpieczenie_ac_data = :ubezpieczenie_ac_data, uwagi = :uwagi 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':rodzaj' => $this->rodzaj,
            ':marka_model' => $this->marka_model,
            ':numer_operacyjny' => $this->numer_operacyjny,
            ':nr_rejestracyjny' => $this->nr_rejestracyjny,
            ':przeglad_data' => $this->przeglad_data,
            ':ubezpieczenie_data' => $this->ubezpieczenie_data,
            ':ubezpieczenie_ac_data' => $this->ubezpieczenie_ac_data,
            ':uwagi' => $this->uwagi,
            ':id' => $this->id
        ])) {
            return true;
        }
        return false;
    }

    // Usuń pojazd
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>