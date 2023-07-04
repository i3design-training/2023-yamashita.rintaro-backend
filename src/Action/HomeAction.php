<?php

declare(strict_types=1);

// Action-Domain-Responder（ADR）パターン ＝ MVCパターンをWebに特化した形に進化させたもの
//  Action: HTTPリクエストを受け取り、ドメイン（ビジネスロジック）に必要な操作を行うよう指示する。また、ドメインからの結果をResponderに渡す。
//  Domain: ビジネスロジックを定義する。データの取得や更新、バリデーション、エラーチェックなどを行う。
//  Responder: ドメインから得られた結果に基づいてHTTPレスポンスを生成する。
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction
{
    // __という２つのアンダースコアを先頭にもつメソッド：マジックメソッド
    // マジックメソッドは基本的に直接呼び出すメソッドではなく、ある状況において実行されるメソッド
    // __invoke()：オブジェクトを関数のように実行した場合に呼ばれるマジックメソッド
    public function __invoke(Request $request, Response $response)
    {
        $response->getBody()->write('Hello world!');

        return $response;
    }
}
