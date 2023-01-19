<?php

use AttributesRouter\Router;
use Controller\BookController;
use Controller\CoreController;
use Controller\MainController;
use Controller\PostController;
use Controller\ReviewController;
use Controller\SessionController;
use Dotenv\Dotenv;
use Util\AccountUtils;
use Util\Session;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/autoloader.php';

$router = null;
try {
    $router = new Router([
        MainController::class,
        SessionController::class,
        PostController::class,
        ReviewController::class,
        BookController::class,
    ]);
    // Quand tu créer un nouveau controller, il faut l'ajouter ici, sinon les nouvelles routes ne fonctionnerons pas :)
} catch (ReflectionException $e) {
    dump($e->getMessage());
}
$match = $router->match();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($match) {
    $params['params'] = $match['params'];

    $params['router'] = $router;
    $params['session'] = new Session();
    $params['account'] = new AccountUtils($params['session']);

    $controller = new $match['class']();
    $controller->{$match['method']}($params);
} else {
    $params['router'] = $router;

    $controller = new CoreController();
    $controller->page404($params);
}
