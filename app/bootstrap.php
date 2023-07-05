<?php

// 関数呼び出しや戻り値での型の不一致がある場合にエラーを出す
declare(strict_types=1);

// Composerを使ってインストールしたパッケージやクラスの自動ローディングを有効に
require __DIR__ . '/../vendor/autoload.php';

// vlucas/phpdotenvパッケージを使って環境変数をロード
use Dotenv\Dotenv;

// Eloquentを使用するためのクラス
use Illuminate\Database\Capsule\Manager;
use Slim\Factory\AppFactory;

// logを使うためのライブラリ
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use DI\ContainerBuilder;
use App\Domain\User\Service\UserCreator;
use App\Domain\User\Repository\UserCreatorRepository;

date_default_timezone_set("Asia/Tokyo");

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Slimフレームワークのアプリケーションインスタンスを作成
// 必要な依存関係（ルートコレクター、ミドルウェアディスパッチャーなど）を自動的にセットアップしてくれる
$app = AppFactory::create();

// 作成したインスタンスに対してルーティングの設定を行う
$routes = require __DIR__ . '/routes.php';
$routes($app);

// Eloquent ORMをセットアップ
$manager = new Manager();
$manager->addConnection([
    'driver' => 'pgsql',
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: 5432,
    'database' => getenv('DB_DATABASE') ?: 'todo',
    'username' => getenv('DB_USERNAME') ?: 'todo',
    'password' => getenv('DB_PASSWORD') ?: 'todo',
    'prefix' => '',
]);
$manager->bootEloquent();

// Monologのセットアップ
$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

// ロガーをコンテナに追加する
$container = $app->getContainer();
$container['logger'] = function ($c) use ($log) {
    return $log;
};

// 初期化と設定が完了したアプリケーションインスタンスを返す
return $app;
