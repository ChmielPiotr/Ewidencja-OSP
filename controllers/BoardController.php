<?php
require_once 'models/User.php';

class BoardController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function index() {
        // Musisz być zalogowany, by widzieć zarząd
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $stmt = $this->userModel->getBoardMembers();
        $zarzad = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ładujemy widok
        require_once 'views/board_list.php';
    }
}
?>