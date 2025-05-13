<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\View;
use App\Models\CarModel;
use App\Models\SaletableModel;
use App\Models\MessagesModel;
use App\Models\UserModel;
use App\Models\CarSalesModel;
use App\Models\AppointmentModel;
use App\Controllers\HomeController;
use App\Controllers\Autoinfocontroller;
use App\Controllers\SaletableController;
use App\Controllers\MessagesController;
use App\Controllers\FunctionsController;
use App\Controllers\ReportController;
// use App\Controllers\RegistrationController;
// use App\Controllers\EntranceController;
use App\Controllers\AuthController;
use Symfony\Component\HttpFoundation\Request;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Инициализация базы данных
$pdo = new PDO(
    sprintf("pgsql:host=%s;dbname=%s", $_ENV['DB_HOST'], $_ENV['DB_NAME']),
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Инициализация компонентов
$request = Request::createFromGlobals();
$view = new View();
$carModel = new CarModel($pdo);
$saletableModel = new SaletableModel($pdo);
$messagesModel = new MessagesModel($pdo);
$userModel = new UserModel($pdo);
$carSalesModel = new CarSalesModel($pdo);
$appointmentModel = new AppointmentModel($pdo);
$homeController = new HomeController($view, $carModel);
$autoinfoController = new Autoinfocontroller($view);
$saletableController = new SaletableController($view, $saletableModel);
$messagesController = new MessagesController($view, $messagesModel);
// $registrationController = new RegistrationController($view);
// $entranceController = new EntranceController($view);
$authController = new AuthController($userModel, $view, $request);
$functionsController = new FunctionsController($view, $carSalesModel);
$reportController = new ReportController($appointmentModel);


// Роутинг
$router = new App\Core\Router();
$router->addRoute('GET', '/', function() use ($homeController) {
    return new \Symfony\Component\HttpFoundation\Response($homeController->index());
});
$router->addRoute('GET', '/autoinfo', function() use ($autoinfoController) {
    return new \Symfony\Component\HttpFoundation\Response($autoinfoController->index());
});
$router->addRoute('GET', '/table', function() use ($saletableController) {
    return new \Symfony\Component\HttpFoundation\Response($saletableController->index());
});
$router->addRoute('POST', '/messages', function() use ($messagesController, $request) {
    return new \Symfony\Component\HttpFoundation\Response($messagesController->handleForm($request));
});
$router->addRoute('GET', '/registration', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->register());
});
$router->addRoute('POST', '/registration', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->register());
});
$router->addRoute('GET', '/entrance', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->login());
});
$router->addRoute('POST', '/entrance', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->login());
});
$router->addRoute('GET', '/profile', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->profile());
});
$router->addRoute('GET', '/logout', function() use ($authController) {
    return new \Symfony\Component\HttpFoundation\Response($authController->logout());
});
$router->addRoute('GET', '/my-orders', function() use ($functionsController) {
    return new \Symfony\Component\HttpFoundation\Response($functionsController->myOrders());
});
$router->addRoute('GET', '/download-report/pdf', function() use ($reportController) {
    return $reportController->generatePdf();
});
$router->addRoute('GET', '/download-report/excel', function() use ($reportController) {
    return $reportController->generateExcel();
});
$router->addRoute('GET', '/download-report/csv', function() use ($reportController) {
    return $reportController->generateCsv();
});


// Обработка запроса
$response = $router->handle($request);
$response->send();