<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\UserRemoveAction;
use App\Application\Actions\User\UserUpdateAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello TrueConf Admin!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->delete('/remove/{id}', UserRemoveAction::class);
        $group->put('/create', UserCreateAction::class);
        $group->post('/update/{id}', UserUpdateAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
