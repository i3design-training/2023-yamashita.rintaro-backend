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
        $group->get('/users/fullRegistration', \App\Action\User\UserFullRegistrationAction::class);
        $group->post('/users/login', \App\Action\User\UserLoginAction::class);
        $group->put('/users/{id}', \App\Action\UserUpdateAction::class);
        $group->delete('/users/{id}', \App\Action\UserDeleteAction::class);

        $group->group('/tasks', function (Group $group) {
            $group->get('', \App\Action\Task\TaskListAction::class);
            $group->post('/create', \App\Action\Task\TaskCreateAction::class);
            $group->get('/{id}', \App\Action\Task\TaskReadAction::class);
            // $group->put('/{id}/edit', \App\Action\Task\TaskUpdateAction::class);
            // $group->delete('/delete', \App\Action\Task\TaskDeleteAction::class);
        });

        $group->group('/categories', function (Group $group) {
            $group->get('', \App\Action\Category\CategoryListAction::class);
            $group->post('/create', \App\Action\Category\CategoryCreateAction::class);
        });

        $group->group('/taskstatus', function (Group $group) {
            $group->get('', \App\Action\TaskStatus\TaskStatusListAction::class);
            $group->post('/create', \App\Action\TaskStatus\TaskStatusCreateAction::class);
        });
    });
};
