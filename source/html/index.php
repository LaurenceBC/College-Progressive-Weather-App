<?php

//DEBUG
//ini_set('display_errors', 1);
//  ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once '../autoloader.php';

//Start the session and setup cookie.
session_start();

$cookieLifetime = 12 * 24 * 60;
setcookie(session_name(), session_id(), time() + $cookieLifetime);



//Get the URI add to route data
$uri = parse_url(strip_tags($_SERVER['REQUEST_URI']));
$uri['path'] = trim($uri['path'], '/');
$tmp = explode('/', $uri['path']);
$controller = $tmp[0];
$action = $tmp[1] ?? NULL;
$params = array_slice($tmp, 2);

$RouteData = array('controller' => $controller, 'action' => $action, 'params' => $params);



//Include the header
require_once 'header.php';

$ControllerInstance = null;

switch ($RouteData['controller']) {
    case 'Home':
        $ControllerInstance = new \HomeController();
        break;
    case 'Login':
        $ControllerInstance = new \LoginController();
        break;
    case 'Weather':
        $ControllerInstance = new \WeatherController();
        break;
    case 'myWeather':
        $ControllerInstance = new \myWeatherController();
        break;
    default:
        $ControllerInstance = new \HomeController();
        break;
}

//
empty($RouteData['action']) ?
    $ControllerInstance->defaultAction() : $ControllerInstance->{$RouteData['action']}(...$RouteData['params']);



require_once 'footer.php';
