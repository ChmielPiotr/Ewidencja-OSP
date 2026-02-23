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
    case 'vehicles':
        $vehicleController->index();
        break;

    // --- AKCJE TYLKO DLA SUPERADMINA 
    case 'logs':
        $logController->index();
        break;
    case 'export_logs':
        $logController->exportCsv();
        break;

    // --- AKCJE DLA ADMINA I SUPERADMINA 
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
        $vehicleController->addEquipment(); break;
    case 'edit_equipment':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->editEquipment(); break;
    case 'delete_equipment':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $vehicleController->deleteEquipment(); break;
    case 'export_users_pdf':
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') { header("Location: index.php?action=dashboard"); exit; }
        $userController->exportPdf();
        break;
    // --- MODUŁ PRAC GOSPODARCZYCH ---
    case 'works':
        $workController->index();
        break;
    case 'addWork':
        $workController->add();
        break;
    case 'viewWork':
        $workController->view();
        break;
    case 'editWork':
        $workController->edit();
        break;
    case 'deleteWork':
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
        $drillController->index();
        break;
    case 'addDrill':
        $drillController->add();
        break;
    case 'editDrill':
        $drillController->edit();
        break;
    case 'deleteDrill':
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

    
    // ---------------
    case 'exams':
        $userController->examsList();
        break;
    case 'userExams':
        $userController->userExams();
        break;
    case 'addExam':
        $userController->addExam();
        break;
    
    
    case 'incidents':
        $incidentController->index();
        break;
    case 'addIncident':
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