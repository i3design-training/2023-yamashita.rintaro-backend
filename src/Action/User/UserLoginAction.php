<?php

namespace App\Action\User;

use App\Models\EmailVerification;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;

class UserLoginAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			// getBody(): ストリームとしてリクエストボディを取得
			// getContents(): ストリームの現在の位置から残りのすべてのデータを文字列として返す
			// ストリーム
			// 		データを連続的に、かつ一度に一部ずつ処理する仕組み
			// 		大きなファイルの操作、ネットワーク通信、パイプライン処理などに有効
			$requestBody = $request->getBody()->getContents();

			// json_decode: JSON文字列をオブジェクトに変換
			// オブジェクトに変換しないと、リクエストボディのデータはただの文字列として扱われてデータにアクセスできない
			// JSON文字列例）"{\"email\":\"example@example.com\",\"password\":\"examplepassword\"}"
			$decodedRequestBody = json_decode($requestBody);

			// 入力値の検証
			if ($decodedRequestBody === null || !isset($decodedRequestBody->email) || !isset($decodedRequestBody->password)) {
				throw new \InvalidArgumentException('無効な入力データ');
			}

			Log::debug(sprintf('ログイン処理開始'));

			// リクエストのemailをもとに、ユーザーを取得する
			$user = User::where('email', $decodedRequestBody->email)->first();

			// ユーザーのpasswordとリクエストのpasswordが一致するかチェック
			if (!$user || !$user->verifyPassword($decodedRequestBody->password)) {
				throw new \InvalidArgumentException('認証に失敗しました');
			}

			// 認証token作成
			// ユーザIDと秘密鍵より、Tokenを生成する

			// ユーザにTokenを返す

			$response->getBody()->write(json_encode('認証成功'));

			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		} catch (\InvalidArgumentException $e) {
			Log::error($e->getMessage());

			$response->getBody()->write(json_encode(['error' => $e->getMessage()]));

			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(400);
		} catch (\Exception $e) {
			Log::error($e->getMessage());

			$response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));

			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(500);
		}
	}
}
