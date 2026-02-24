<?php
class Database {
    //XAMPP to localhost root bez hasła, nazwa bazy osp_system
    private $host = "localhost";
    private $db_name = "osp_system";
    private $username = "root";
    private $password = "";
    public $conn;


    public function getConnection() {
        $this->conn = null; 

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Błąd połączenia z bazą: " . $exception->getMessage();
        }

        return $this->conn; 
    }
}
?>