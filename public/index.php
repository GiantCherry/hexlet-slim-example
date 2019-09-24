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
