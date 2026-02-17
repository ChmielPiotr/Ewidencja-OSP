<?php
require_once 'models/Vehicle.php';
require_once 'models/Equipment.php';
require_once 'models/Log.php';

class VehicleController {
    private $db;
    private $vehicleModel;
    private $equipmentModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->vehicleModel = new Vehicle($this->db);
        $this->equipmentModel = new Equipment($this->db);
    }

    // GŁÓWNA STRONA GARAŻU (POKAZUJE WOZY I SPRZĘT)
    public function index() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit; }
        
        $pojazdy = $this->vehicleModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $sprzety = $this->equipmentModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/vehicles_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->vehicleModel->rodzaj = trim($_POST['rodzaj']);
            $this->vehicleModel->marka_model = trim($_POST['marka_model']);
            $this->vehicleModel->numer_operacyjny = trim($_POST['numer_operacyjny']);
            $this->vehicleModel->nr_rejestracyjny = trim($_POST['nr_rejestracyjny']);
            $this->vehicleModel->przeglad_data = $_POST['przeglad_data'];
            $this->vehicleModel->ubezpieczenie_data = $_POST['ubezpieczenie_data'];
            $this->vehicleModel->ubezpieczenie_ac_data = !empty($_POST['ubezpieczenie_ac_data']) ? $_POST['ubezpieczenie_ac_data'] : null;
            $this->vehicleModel->uwagi = !empty($_POST['uwagi']) ? trim($_POST['uwagi']) : null;

            if ($this->vehicleModel->create()) {
                // LOGOWANIE
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Dodano nowy wóz bojowy: " . $this->vehicleModel->numer_operacyjny . " (" . $this->vehicleModel->rodzaj . ")");
                
                header("Location: index.php?action=vehicles");
                exit;
            } else {
                $blad = "Nie udało się dodać pojazdu.";
            }
        }
        require_once 'views/add_vehicle.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=vehicles"); exit; }
        
        $this->vehicleModel->id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->vehicleModel->rodzaj = trim($_POST['rodzaj']);
            $this->vehicleModel->marka_model = trim($_POST['marka_model']);
            $this->vehicleModel->numer_operacyjny = trim($_POST['numer_operacyjny']);
            $this->vehicleModel->nr_rejestracyjny = trim($_POST['nr_rejestracyjny']);
            $this->vehicleModel->przeglad_data = $_POST['przeglad_data'];
            $this->vehicleModel->ubezpieczenie_data = $_POST['ubezpieczenie_data'];
            $this->vehicleModel->ubezpieczenie_ac_data = !empty($_POST['ubezpieczenie_ac_data']) ? $_POST['ubezpieczenie_ac_data'] : null;
            $this->vehicleModel->uwagi = !empty($_POST['uwagi']) ? trim($_POST['uwagi']) : null;

            if ($this->vehicleModel->update()) {
                // LOGOWANIE
                $logger = new Log($this->db);
                $logger->create($_SESSION['user_id'], "Zaktualizowano dane wozu: " . $this->vehicleModel->numer_operacyjny);

                header("Location: index.php?action=vehicles");
                exit;
            } else {
                $blad = "Nie udało się zaktualizować danych pojazdu.";
            }
        } else {
            if (!$this->vehicleModel->readOne($this->vehicleModel->id)) {
                die("Nie znaleziono pojazdu.");
            }
        }

        $pojazd = [
            'id' => $this->vehicleModel->id,
            'rodzaj' => $this->vehicleModel->rodzaj,
            'marka_model' => $this->vehicleModel->marka_model,
            'numer_operacyjny' => $this->vehicleModel->numer_operacyjny,
            'nr_rejestracyjny' => $this->vehicleModel->nr_rejestracyjny,
            'przeglad_data' => $this->vehicleModel->przeglad_data,
            'ubezpieczenie_data' => $this->vehicleModel->ubezpieczenie_data,
            'ubezpieczenie_ac_data' => $this->vehicleModel->ubezpieczenie_ac_data,
            'uwagi' => $this->vehicleModel->uwagi
        ];

        require_once 'views/edit_vehicle.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->vehicleModel->id = $_GET['id'];
            if ($this->vehicleModel->readOne($_GET['id'])) {
                $info_o_wozie = $this->vehicleModel->numer_operacyjny . " (" . $this->vehicleModel->rodzaj . ")";
                if ($this->vehicleModel->delete()) {
                    // LOGOWANIE
                    $logger = new Log($this->db);
                    $logger->create($_SESSION['user_id'], "Usunięto wóz z podziału bojowego: " . $info_o_wozie);
                }
            }
        }
        header("Location: index.php?action=vehicles");
        exit;
    }
    

    public function addEquipment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->equipmentModel->nazwa = trim($_POST['nazwa']);
            $this->equipmentModel->ilosc = $_POST['ilosc'];
            $this->equipmentModel->stan = $_POST['stan'];
            $this->equipmentModel->vehicle_id = !empty($_POST['vehicle_id']) ? $_POST['vehicle_id'] : null;
            $this->equipmentModel->uwagi = !empty($_POST['uwagi']) ? trim($_POST['uwagi']) : null;

            if ($this->equipmentModel->create()) {
                $logger = new Log($this->db); $logger->create($_SESSION['user_id'], "Dodano sprzęt: " . $this->equipmentModel->nazwa);
                header("Location: index.php?action=vehicles"); exit;
            } else { $blad = "Błąd przy dodawaniu sprzętu."; }
        }
        $pojazdy_lista = $this->vehicleModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/equipment_form.php';
    }

    public function editEquipment() {
        if (!isset($_GET['id'])) { header("Location: index.php?action=vehicles"); exit; }
        $this->equipmentModel->id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->equipmentModel->nazwa = trim($_POST['nazwa']);
            $this->equipmentModel->ilosc = $_POST['ilosc'];
            $this->equipmentModel->stan = $_POST['stan'];
            $this->equipmentModel->vehicle_id = !empty($_POST['vehicle_id']) ? $_POST['vehicle_id'] : null;
            $this->equipmentModel->uwagi = !empty($_POST['uwagi']) ? trim($_POST['uwagi']) : null;

            if ($this->equipmentModel->update()) {
                $logger = new Log($this->db); $logger->create($_SESSION['user_id'], "Edytowano sprzęt: " . $this->equipmentModel->nazwa);
                header("Location: index.php?action=vehicles"); exit;
            } else { $blad = "Błąd przy edycji sprzętu."; }
        } else {
            $this->equipmentModel->readOne($this->equipmentModel->id);
        }

        $sprzet = ['id' => $this->equipmentModel->id, 'nazwa' => $this->equipmentModel->nazwa, 'ilosc' => $this->equipmentModel->ilosc, 'stan' => $this->equipmentModel->stan, 'vehicle_id' => $this->equipmentModel->vehicle_id, 'uwagi' => $this->equipmentModel->uwagi];
        $pojazdy_lista = $this->vehicleModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/equipment_form.php';
    }

    public function deleteEquipment() {
        if (isset($_GET['id'])) {
            $this->equipmentModel->id = $_GET['id'];
            if ($this->equipmentModel->readOne($_GET['id'])) {
                $nazwa = $this->equipmentModel->nazwa;
                if ($this->equipmentModel->delete()) {
                    $logger = new Log($this->db); $logger->create($_SESSION['user_id'], "Usunięto sprzęt: " . $nazwa);
                }
            }
        }
        header("Location: index.php?action=vehicles"); exit;
    }
}
?>