<?php

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/system.php');
$container = $containerBuilder->build();

//TODO Route in a cleaner way
//TODO Spec-check params on that route, removing any junk we don't want
$route = $_REQUEST['url'];

$data = '';
switch ($route):
    case 'user':
        //TODO Handle success!
        $data = $container->get('user.handler')->signup($_POST);
        break;
    default:
        http_response_code(404);
        //TODO Handle error (need helper fn)!
        $data = array(
            '$_REQUEST' => $_REQUEST,
            '$_POST' => $_POST,
        );
endswitch;

//TODO Should allow allow HTTP status
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($data);
