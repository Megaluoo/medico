<?php
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

Session::start();

$router = new Router();

$router->get('/', function () {
    (new DashboardController())->index();
});

$router->get('/dashboard', function () {
    (new DashboardController())->index();
});

$router->get('/pacientes', function () {
    (new PatientsController())->index();
});

$router->get('/citas', function () {
    (new AppointmentsController())->index();
});

$router->get('/pagos', function () {
    (new PaymentsController())->index();
});

$router->get('/historias', function () {
    (new RecordsController())->index();
});

$router->get('/recetas', function () {
    (new PrescriptionsController())->index();
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
