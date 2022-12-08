<?php
// lapa, kura var - logins/registracija, redzet 10 user izveletus stockus, cenas izmaina

use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\MainPageController;
use App\Controllers\RegisterController;
use App\Redirect;
use App\Template;
use App\ViewVariables\ViewErrorVariables;
use App\ViewVariables\ViewUserVariables;
use App\ViewVariables\ViewVariables;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable('/home/ricards/PhpstormProjects/untitled/');
$dotenv->load();

$loader = new FilesystemLoader('../views');
$twig = new Environment($loader);

$viewVariables = [
    ViewUserVariables::class,
    ViewErrorVariables::class,
];

foreach ($viewVariables as $variable) {
    /** @var ViewVariables $variable */
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [MainPageController::class, 'index']);

    $r->addRoute('GET', '/sign-in', [LoginController::class, 'showForm']);
    $r->addRoute('POST', '/sign-in', [LoginController::class, 'execute']);

    $r->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
    $r->addRoute('POST', '/register', [RegisterController::class, 'execute']);

    $r->addRoute('GET', '/logout', [LogoutController::class, 'execute']);


});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars

        [$controller, $method] = $handler;
        $response = (new $controller)->{$method}($vars);


        if ($response instanceof Template) {
            echo $twig->render($response->getPath(), $response->getParams());

            unset($_SESSION['errors']);
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }


        break;
}