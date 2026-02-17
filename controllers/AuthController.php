<?php
require_once 'models/User.php';
require_once 'models/Log.php'; 

class AuthController {
    private $db; // NOWOŚĆ: Zmienna do trzymania połączenia z bazą
    private $userModel;

    public function __construct($db) {
        $this->db = $db; // Zapisujemy połączenie dla siebie
        $this->userModel = new User($db);
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=" . (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin') ? 'index' : 'dashboard'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login']); 
            $password = $_POST['password'];

            // Szukamy druha w bazie po loginie!
            $user = $this->userModel->findByLogin($login);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                
                // --- NOWOŚĆ: ZAPISUJEMY LOG LOGOWANIA ---
                // Teraz używamy własnego połączenia $this->db zamiast wyciągać je z userModel!
                $logger = new Log($this->db); 
                $logger->create($user['id'], 'Pomyślne zalogowanie do systemu');
                // ----------------------------------------
                
                header("Location: index.php?action=" . ($user['role'] === 'admin' || $_SESSION['role'] === 'superadmin' ? 'index' : 'dashboard'));
                exit;
            } else {
                $blad = "Nieprawidłowy login lub hasło!";
                // Opcjonalnie: logowanie nieudanej próby
                if ($user) {
                    $logger = new Log($this->db);
                    $logger->create($user['id'], 'Błędne hasło podczas logowania');
                }
            }
        }
        
        require_once 'views/login.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    // Nowa metoda do resetu hasła
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login']);
            $user = $this->userModel->findByLogin($login);

            if ($user && !empty($user['email'])) {
                $komunikat = "Instrukcje resetowania hasła zostały wysłane na adres e-mail przypisany do konta.";
                // TUTAJ PÓŹNIEJ DODAMY WYSYŁANIE MAILA PRZEZ PHPMAILER!
            } else {
                $blad = "Nie znaleziono użytkownika lub użytkownik nie ma podanego adresu e-mail.";
            }
        }
        require_once 'views/forgot_password.php';
    }
}
?>