<?php

use function Stringy\create as s;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$users = App\Generator::generate(100);

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

// BEGIN (write your solution here)
$app->get('/users', function ($request, $response) use ($users) {
    $term = $request->getQueryParam('term');

    $searchAnswer = [];
    foreach ($users as $user) {
        if (!empty($term)) {
            if (strpos(mb_strtolower($user['firstName']), mb_strtolower($term)) !== false) {
                $searchAnswer [] = $user;
            }
        }
    }

    if (empty($searchAnswer)) {
        $searchAnswer = $users;
    }

    $params = [
        'term' => $term,
        //'users' => $users,
        'searchAnswer' => $searchAnswer
    ];

    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});
// END

$app->run();


//<?php
//
//use Slim\Factory\AppFactory;
//use DI\Container;
//
//require __DIR__ . '/../vendor/autoload.php';
//
////$users = App\Generator::generate(100);
//$users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];
//
//$container = new Container();
//$container->set('renderer', function () {
//    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
//});
//
//AppFactory::setContainer($container);
//$app = AppFactory::create();
//$app->addErrorMiddleware(true, true, true);
//
//$app->get('/', function ($request, $response) {
//    return $this->get('renderer')->render($response, 'index.phtml');
//});
//
//$app->get('/users', function ($request, $response) use ($users) {
//    $searchName = $request->getQueryParam('searchName');
//    $searchAnswer = [];
//    foreach ($users as $user) {
//        if (strpos($user, $searchName) !== false) {
//            $searchAnswer [] = $user;
//        }
//    }
//
//    if (empty($searchAnswer)) {
//        $searchAnswer [] = 'Not Found!';
//    }
//
//    $params = [
//        'users' => $users,
//        'searchAnswer' => $searchAnswer,
//        'searchName' => $searchName
//    ];
//
//    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
//});
//
//$app->get('/users/{id}', function ($request, $response, $args) use ($users) {
//    $id = (int) $args['id'];
//    $user = collect($users)->firstWhere('id', $id);
//    $params = ['user' => $user];
//
//    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
//});
//
//$app->run();
