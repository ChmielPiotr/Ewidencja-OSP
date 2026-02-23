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
    
    // Zmienne z połączonej tabeli board_members
    public $funkcja_zarzad;
    public $data_powolania_zarzad;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Pobieranie wszystkich użytkowników z dołączoną ewentualną funkcją
    public function readAll() {
        $query = "SELECT 
                    u.id, 
                    u.first_name, 
                    u.last_name, 
                    u.role, 
                    u.login, 
                    b.funkcja,
                    (SELECT m.date_to FROM medical_exams m WHERE m.user_id = u.id ORDER BY m.date_to DESC LIMIT 1) as medical_exam_date,
                    (SELECT s.date_to FROM smoke_chamber_tests s WHERE s.user_id = u.id ORDER BY s.date_to DESC LIMIT 1) as smoke_chamber_date
                  FROM " . $this->table_name . " u 
                  LEFT JOIN board_members b ON u.id = b.user_id 
                  WHERE u.role != 'superadmin' 
                  ORDER BY u.last_name ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Pobieranie członków zarządu (Przywrócona funkcja dla BoardController!)
    public function getBoardMembers() {
        $query = "SELECT u.id, u.first_name, u.last_name, b.funkcja, b.data_powolania 
                  FROM board_members b 
                  JOIN users u ON b.user_id = u.id 
                  ORDER BY b.data_powolania ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Pobieranie jednego użytkownika do edycji
    public function readOne($id) {
    $query = "SELECT 
                u.id, u.first_name, u.last_name, u.login, u.email, u.role,
                (SELECT m.date_to FROM medical_exams m WHERE m.user_id = u.id ORDER BY m.date_to DESC LIMIT 1) as medical_exam_date,
                (SELECT s.date_to FROM smoke_chamber_tests s WHERE s.user_id = u.id ORDER BY s.date_to DESC LIMIT 1) as smoke_chamber_date,
                b.funkcja as funkcja_zarzad, 
                b.data_powolania as data_powolania_zarzad
              FROM " . $this->table_name . " u
              LEFT JOIN board_members b ON u.id = b.user_id
              WHERE u.id = :id 
              LIMIT 0,1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $this->id = $row['id'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->login = $row['login'];
        $this->email = $row['email'];
        $this->role = $row['role'];
        $this->medical_exam_date = $row['medical_exam_date'];
        $this->smoke_chamber_date = $row['smoke_chamber_date'];
        $this->funkcja_zarzad = $row['funkcja_zarzad'];
        $this->data_powolania_zarzad = $row['data_powolania_zarzad'];
        return true;
    }
    return false;
    }

    // Tworzenie nowego użytkownika
    public function create() {
        try {
            // Rozpoczynamy transakcję (jeśli padnie tabela zarządu, strażak też się nie utworzy - brak "pustych" kont)
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                      (first_name, last_name, login, email, password, role) 
                      VALUES 
                      (:first_name, :last_name, :login, :email, :password, :role)";

            $stmt = $this->conn->prepare($query);

            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->login = htmlspecialchars(strip_tags($this->login));
            $this->email = !empty($this->email) ? htmlspecialchars(strip_tags($this->email)) : null;
            $this->role = htmlspecialchars(strip_tags($this->role));

            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':role', $this->role);
            

            $stmt->execute();
            
            // Pobieramy ID nowo utworzonego strażaka
            $user_id = $this->conn->lastInsertId();

            // Jeśli wybrano mu funkcję w zarządzie, dodajemy wpis do drugiej tabeli
            if (!empty($this->funkcja_zarzad)) {
                $query_board = "INSERT INTO board_members (user_id, funkcja, data_powolania) VALUES (:user_id, :funkcja, :data_powolania)";
                $stmt_board = $this->conn->prepare($query_board);
                $stmt_board->bindParam(':user_id', $user_id);
                $stmt_board->bindParam(':funkcja', $this->funkcja_zarzad);
                $stmt_board->bindParam(':data_powolania', $this->data_powolania_zarzad);
                $stmt_board->execute();
            }

            // Zatwierdzamy zmiany w obu tabelach
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack(); // Cofamy wszystko
            
            // Jeśli wystąpi błąd z zajętym loginem (kod 23000)
            if ($e->getCode() == 23000) {
                return false; 
            }
            
            // Jeśli to inny błąd bazy (np. brak kolumny), pokaż go żebyśmy go od razu widzieli!
            die("Błąd bazy danych w module zapisu: " . $e->getMessage());
        }
    }

    // Aktualizacja użytkownika (edycja)
    public function update() {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                      SET first_name = :first_name, 
                          last_name = :last_name, 
                          login = :login, 
                          email = :email, 
                          role = :role
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->login = htmlspecialchars(strip_tags($this->login));
            $this->email = !empty($this->email) ? htmlspecialchars(strip_tags($this->email)) : null;
            $this->role = htmlspecialchars(strip_tags($this->role));

            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            // Zarządzanie drugą tabelą (board_members)
            if (!empty($this->funkcja_zarzad)) {
                $check = $this->conn->prepare("SELECT id FROM board_members WHERE user_id = ?");
                $check->execute([$this->id]);
                if ($check->rowCount() > 0) {
                    $update_board = "UPDATE board_members SET funkcja = :funkcja, data_powolania = :data_powolania WHERE user_id = :user_id";
                    $stmt_board = $this->conn->prepare($update_board);
                } else {
                    $update_board = "INSERT INTO board_members (user_id, funkcja, data_powolania) VALUES (:user_id, :funkcja, :data_powolania)";
                    $stmt_board = $this->conn->prepare($update_board);
                }
                $stmt_board->bindParam(':user_id', $this->id);
                $stmt_board->bindParam(':funkcja', $this->funkcja_zarzad);
                $stmt_board->bindParam(':data_powolania', $this->data_powolania_zarzad);
                $stmt_board->execute();
            } else {
                // Jeśli usunięto mu funkcję z formularza, wywalamy go z tabeli zarządu
                $del_board = $this->conn->prepare("DELETE FROM board_members WHERE user_id = ?");
                $del_board->execute([$this->id]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->getCode() == 23000) {
                return false;
            }
            die("Błąd bazy danych w module edycji: " . $e->getMessage());
        }
    }

    // Usuwanie użytkownika
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Najpierw usuwamy druha z zarządu (żeby klucze się nie pogubiły)
            $del_board = $this->conn->prepare("DELETE FROM board_members WHERE user_id = ?");
            $del_board->execute([$this->id]);

            // Następnie usuwamy samo konto
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Zmiana hasła
    public function updateCredentials($id, $email, $password) {
        $query = "UPDATE " . $this->table_name . " SET email = :email, password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $email = !empty($email) ? htmlspecialchars(strip_tags($email)) : null;        
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Autoryzacja
    public function findByLogin($login) {
        $query = "SELECT id, first_name, last_name, login, password, role FROM " . $this->table_name . " WHERE login = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getMedicalHistory($userId) {
        
        $query = "SELECT id, date_from, date_to, notes, created_at 
                  FROM medical_exams 
                  WHERE user_id = :user_id 
                  ORDER BY date_to DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getSmokeChamberHistory($userId) {
        $query = "SELECT id, date_from, date_to, notes, created_at 
                  FROM smoke_chamber_tests 
                  WHERE user_id = :user_id 
                  ORDER BY date_to DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- FUNKCJE DO ZAPISU BADAŃ I KOMORY ---
    public function addMedicalExam($user_id, $date_from, $date_to, $notes = null) {
        $query = "INSERT INTO medical_exams (user_id, date_from, date_to, notes) 
                  VALUES (:user_id, :date_from, :date_to, :notes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':date_from', $date_from);
        $stmt->bindParam(':date_to', $date_to);
        $stmt->bindParam(':notes', $notes);
        return $stmt->execute();
    }

    public function addSmokeChamberTest($user_id, $date_from, $date_to, $notes = null) {
        $query = "INSERT INTO smoke_chamber_tests (user_id, date_from, date_to, notes) 
                  VALUES (:user_id, :date_from, :date_to, :notes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':date_from', $date_from);
        $stmt->bindParam(':date_to', $date_to);
        $stmt->bindParam(':notes', $notes);
        return $stmt->execute();
    }



}
?>