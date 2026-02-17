<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $login;
    public $email;
    public $password;
    public $role;
    public $medical_exam_date;
    public $smoke_chamber_date;
    
    // NOWE WŁAŚCIWOŚCI DLA ZARZĄDU
    public $funkcja_zarzad;
    public $data_powolania_zarzad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT u.*, bm.funkcja 
                  FROM " . $this->table_name . " u
                  LEFT JOIN board_members bm ON u.id = bm.user_id 
                  WHERE u.role != 'superadmin' 
                  ORDER BY u.last_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        // Zaktualizowane zapytanie - pobiera też dane z zarządu!
        $query = "SELECT u.*, bm.funkcja, bm.data_powolania 
                  FROM " . $this->table_name . " u
                  LEFT JOIN board_members bm ON u.id = bm.user_id 
                  WHERE u.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->login = $row['login'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->medical_exam_date = $row['medical_exam_date'];
            $this->smoke_chamber_date = $row['smoke_chamber_date'];
            
            // Przypisanie danych zarządu
            $this->funkcja_zarzad = $row['funkcja'] ?? null;
            $this->data_powolania_zarzad = $row['data_powolania'] ?? null;
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (first_name, last_name, login, email, password, role, medical_exam_date, smoke_chamber_date) 
                  VALUES (:imie, :nazwisko, :login, :email, :haslo, :rola, :badania, :komora)";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':imie' => $this->first_name,
            ':nazwisko' => $this->last_name,
            ':login' => $this->login,
            ':email' => $this->email,
            ':haslo' => $this->password,
            ':rola' => $this->role,
            ':badania' => $this->medical_exam_date,
            ':komora' => $this->smoke_chamber_date
        ])) {
            // NOWOŚĆ: Jeśli wybrano funkcję w zarządzie, dodaj do tabeli board_members
            $this->id = $this->conn->lastInsertId(); // Pobieramy ID nowo dodanego druha
            if (!empty($this->funkcja_zarzad)) {
                $ins = $this->conn->prepare("INSERT INTO board_members (user_id, funkcja, data_powolania) VALUES (?, ?, ?)");
                $ins->execute([$this->id, $this->funkcja_zarzad, $this->data_powolania_zarzad]);
            }
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET first_name = :imie, last_name = :nazwisko, login = :login,
                      email = :email, role = :rola, medical_exam_date = :badania, smoke_chamber_date = :komora 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':imie' => $this->first_name, ':nazwisko' => $this->last_name, ':login' => $this->login,
            ':email' => $this->email, ':rola' => $this->role, ':badania' => $this->medical_exam_date,
            ':komora' => $this->smoke_chamber_date, ':id' => $this->id
        ])) {
            // NOWOŚĆ: Aktualizacja Zarządu
            if (!empty($this->funkcja_zarzad)) {
                // Sprawdzamy, czy druh już był w zarządzie
                $check = $this->conn->prepare("SELECT id FROM board_members WHERE user_id = ?");
                $check->execute([$this->id]);
                if ($check->rowCount() > 0) {
                    $upd = $this->conn->prepare("UPDATE board_members SET funkcja = ?, data_powolania = ? WHERE user_id = ?");
                    $upd->execute([$this->funkcja_zarzad, $this->data_powolania_zarzad, $this->id]);
                } else {
                    $ins = $this->conn->prepare("INSERT INTO board_members (user_id, funkcja, data_powolania) VALUES (?, ?, ?)");
                    $ins->execute([$this->id, $this->funkcja_zarzad, $this->data_powolania_zarzad]);
                }
            } else {
                // Jeśli wybrano "Brak funkcji", usuwamy go z zarządu
                $del = $this->conn->prepare("DELETE FROM board_members WHERE user_id = ?");
                $del->execute([$this->id]);
            }
            return true;
        }
        return false;
    }

    public function delete() {
        // Usuwa z users, a baza sama usunie go z board_members dzięki ON DELETE CASCADE
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }

    public function findByLogin($login) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE login = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBoardMembers() {
        // Zmodyfikowana kolejność na podstawie Twojego screena
        $query = "SELECT bm.funkcja, bm.data_powolania, u.first_name, u.last_name 
                  FROM board_members bm 
                  JOIN " . $this->table_name . " u ON bm.user_id = u.id 
                  ORDER BY FIELD(bm.funkcja, 'PREZES', 'WICEPREZES', 'NACZELNIK', 'ZASTĘPCA NACZELNIKA', 'SKARBNIK', 'SEKRETARZ', 'GOSPODARZ', 'CZŁONEK ZARZĄDU'), bm.data_powolania ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    // --- METODA 8: Zmiana e-maila i/lub hasła (dla profilu i resetu) ---
    public function updateCredentials($id, $email, $password_hash = null) {
        if ($password_hash) {
            // Zmieniamy maila i hasło
            $query = "UPDATE " . $this->table_name . " SET email = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$email, $password_hash, $id]);
        } else {
            // Zmieniamy tylko maila (hasło zostaje stare)
            $query = "UPDATE " . $this->table_name . " SET email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$email, $id]);
        }
    }
}
?>