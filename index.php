<?php
session_start();

require_once 'config/database.php';
require_once 'config/helpers.php';

// Ładujemy wszystkie kontrolery
require_once 'controllers/UserController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/BoardController.php';
require_once 'controllers/LogController.php';
require_once 'controllers/VehicleController.php';
require_once 'controllers/IncidentController.php';
require_once 'controllers/WorkController.php';
require_once 'controllers/DrillController.php';


$database = new Database();
$db = $database->getConnection();

$userController = new UserController($db);
$authController = new AuthController($db);
$dashboardController = new DashboardController($db);
$boardController = new BoardController($db);
$logController = new LogController($db);
$vehicleController = new VehicleController($db);
$incidentController = new IncidentController($db);
$workController = new WorkController($db);
$drillController = new DrillController($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// --- GLOBALNE ZABEZPIECZENIE ---
// Jeśli nie jesteś zalogowany, a próbujesz wejść gdzieś indziej niż logowanie lub odzyskiwanie hasła
if (!isset($_SESSION['user_id']) && $action !== 'login' && $action !== 'forgot_password') {
    header("Location: index.php?action=login");
    exit;
}

switch ($action) {
    // --- AKCJE PUBLICZNE / DLA KAŻDEGO ZALOGOWANEGO 
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'forgot_password':
        $authController->forgotPassword();
        break;
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'generate_pdf':
        $dashboardController->generatePdf();
        break;
    case 'board':
        $boardController->index();
        break;
    

    // --- AKCJE TYLKO DLA SUPERADMINA 
    case 'logs':
        $logController->index();
        break;
    case 'export_logs':
        $logController->exportCsv();
        break;

    // --- AKCJE DLA ADMINA I SUPERADMINA (Zarządzanie kontami i wozami)
    case 'vehicles':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->index();
        break;
    case 'index':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->index();
        break;
    case 'add':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->add();
        break;
    case 'edit':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->edit();
        break;
    case 'delete':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->delete();
        break;
    case 'reset_password':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->resetPassword();
        break;
    case 'add_vehicle':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->add();
        break;
    case 'edit_vehicle':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->edit();
        break;
    case 'delete_vehicle':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->delete();
        break;
    case 'add_equipment':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->addEquipment(); 
        break;
    case 'edit_equipment':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->editEquipment(); 
        break;
    case 'delete_equipment':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->deleteEquipment(); 
        break;
    case 'export_users_pdf':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->exportPdf();
        break;

    // --- MODUŁ PRAC GOSPODARCZYCH ---
    case 'works':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $workController->index();
        break;
    case 'addWork':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $workController->add();
        break;
    case 'viewWork':
        $workController->view();
        break;
    case 'editWork':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $workController->edit();
        break;
    case 'deleteWork':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $workController->delete();
        break;
    case 'exportWorksPdf':
        $workController->exportPdf();
        break;
    case 'exportSingleWorkPdf':
        $workController->exportSinglePdf();
        break;

    // --- MODUŁ ĆWICZEŃ ---
    case 'drills':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $drillController->index();
        break;
    case 'addDrill':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $drillController->add();
        break;
    case 'editDrill':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $drillController->edit();
        break;
    case 'deleteDrill':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $drillController->delete();
        break;
    case 'viewDrill':
        $drillController->view();
        break;
    case 'exportDrillsPdf':
        $drillController->exportPdf();
        break;
    case 'exportSingleDrillPdf':
        $drillController->exportSinglePdf();
        break;

    // --- MODUŁ BADAŃ LEKARSKICH (Całkowicie zablokowany dla zwykłych użytkowników) ---
    case 'exams':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->examsList();
        break;
    case 'userExams':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->userExams();
        break;
    case 'addExam':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->addExam();
        break;
    
    // --- MODUŁ AKCJI RATOWNICZYCH ---
    case 'incidents':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $incidentController->index();
        break;
    case 'addIncident':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $incidentController->add();
        break;
    case 'viewIncident':
        $incidentController->view();
        break;
    case 'exportIncidentsPdf':
        $incidentController->exportAllPdf();
        break;
    case 'exportSingleIncidentPdf':
        $incidentController->exportSinglePdf();
        break;

    // --- DOMYŚLNIE (Jeśli wpiszesz bzdury w URL) ---
    default:
        header("Location: index.php?action=dashboard");
        break;
}
?>