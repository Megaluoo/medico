<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Controllers/PatientsController.php';

$controller = new PatientsController();
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        }
        break;
    case 'show':
        $controller->show();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        }
        break;
    case 'destroy':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->destroy();
        }
        break;
    default:
        $controller->index();
        break;
require_once __DIR__ . '/../bootstrap/session.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$authController = new AuthController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

switch ($uri) {
    case '/login':
        if (currentUser() && !isPost()) {
            redirect('/');
        }
        $authController->login();
        break;

    case '/logout':
        if (!isPost()) {
            redirect('/');
        }
        $authController->logout();
        break;

    case '/admin':
        requireRole(['admin']);
        view('pages/admin.php', ['title' => 'Panel de Administración']);
        break;

    case '/':
        requireAuth();
        view('pages/home.php', ['title' => 'Panel principal']);
        break;

    default:
        http_response_code(404);
        echo 'Página no encontrada';
}
