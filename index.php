<?php
require_once __DIR__ . '/app/Controllers/PaymentsController.php';

$paymentsPath = __DIR__ . '/data/payments.json';
$servicesPath = __DIR__ . '/data/services.json';
$controller = new PaymentsController($paymentsPath, $servicesPath);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create_payment') {
        $controller->store($_POST);
        header('Location: /index.php?view=payments');
        exit;
    }

    if ($action === 'add_installment') {
        $controller->addInstallment($_POST['payment_id'], $_POST);
        header('Location: /index.php?view=installments&id=' . urlencode($_POST['payment_id']));
        exit;
    }

    if ($action === 'create_service') {
        $controller->storeService($_POST);
        header('Location: /index.php?view=services');
        exit;
    }
}

$view = $_GET['view'] ?? 'payments';

switch ($view) {
    case 'create':
        extract($controller->create());
        include __DIR__ . '/views/payments/create.php';
        break;
    case 'installments':
        $payment = $controller->show($_GET['id']);
        $methods = $controller->paymentMethods();
        if ($payment) {
            include __DIR__ . '/views/payments/installments.php';
        } else {
            echo 'Pago no encontrado';
        }
        break;
    case 'services':
        extract($controller->services());
        include __DIR__ . '/views/payments/services.php';
        break;
    case 'reports':
        extract($controller->reports());
        include __DIR__ . '/views/payments/reports.php';
        break;
    case 'dashboard':
        extract($controller->dashboard());
        include __DIR__ . '/views/payments/dashboard.php';
        break;
    default:
        extract($controller->list());
        include __DIR__ . '/views/payments/index.php';
        break;
}
