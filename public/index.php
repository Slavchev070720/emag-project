<?php

use App\Exception\NotFoundException;
use App\Controller\HomeController;
use App\Helper\SessionHelper;
use App\Helper\RequestHelper;
use App\Router\Router;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = new Dotenv(true);
$dotenv->load(getenv('PWD') . '/.env');

try {
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . ".php";
        $class = str_replace('App', 'src', $class);
        if(substr($class, 0, 3) !== 'src'){
            $class = 'src/' . $class;
        }
        $classPath = __DIR__ . DIRECTORY_SEPARATOR . "../" . $class;

        if (!file_exists($classPath)) {
            throw new NotFoundException();
        }
        require_once $classPath;
    });

    SessionHelper::sessionStart();
    $requestHelper = RequestHelper::requestInstance();

    $router = Router::routerInstance($requestHelper);
    require_once __DIR__ . "/routes.php";

    $router->startAction();
} catch (Exception $e) {
    if($e instanceof NotFoundException){
        $homeController = new HomeController();
        $homeController->notFound();
    } else {
        $homeController = new HomeController();
        $homeController->serverError();
    }
}
