<?php

declare(strict_types=1);

// PHPの自動ロード機能によって、実際のファイルパス src/Action にマッピングされている
// composer.jsonのautoload セクションで定義されている
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

return function (App $app) {
    $app->get('/', \App\Action\HomeAction::class)->setName('home');
    $app->get('/hello/{name}', \App\Action\HelloAction::class)->setName('hello');

    $app->group('/api', function (Group $group) {
        $group->post('/users/provisionalRegister', \App\Action\User\UserProvisionalRegistrationAction::class);
        $group->post('/users/fullRegistration', \App\Action\User\UserFullRegistrationAction::class);
        $group->post('/users/login', \App\Action\User\UserLoginAction::class);
        $group->put('/users/{id}', \App\Action\UserUpdateAction::class);
        $group->delete('/users/{id}', \App\Action\UserDeleteAction::class);
    });
};
