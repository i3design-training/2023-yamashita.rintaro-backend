<?php

namespace App\Action\User;

use App\Models\Token;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;
use Firebase\JWT\JWT;

class UserLoginAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody);

			if ($decodedRequestBody === null || !isset($decodedRequestBody->email) || !isset($decodedRequestBody->password)) {
				throw new \InvalidArgumentException('無効な入力データ');
			}

			Log::debug(sprintf('ログイン処理開始'));

			$user = User::where('email', $decodedRequestBody->email)->first();

			if (!$user || !$user->verifyPassword($decodedRequestBody->password)) {
				throw new \InvalidArgumentException('認証に失敗しました');
			}

			$jwt = $this->generateJWT($user->id);

			// 新しいTokenモデルを作成し、生成したJWTを保存
			Token::updateOrCreate(
				['user_id' => $user->id],
				[
					'token' => $jwt,
					'expiry_date' => date('Y-m-d H:i:s', strtotime('+1 day'))
				]
			);

			$response->getBody()->write(
				json_encode([
					'token' => $jwt,
					'user_id' => $user->id
				])
			);

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


	private function generateJWT($userId)
	{
		$now = time();

		// Payload: 実際に送信するデータ
		$payload = array(
			"iat" => $now, // 発行時間
			"nbf" => $now, // 有効開始時間
			"exp" => $now + (60 * 60 * 24), // 有効終了時間
			"data" => ["userId" => $userId]
		);

		/**
		 * JWT（Json Web Token）を生成。以下の3つの引数を取る。
		 * @param array|object $payload トークンに含めるデータ（クレーム）
		 * @param string $_ENV['JWT_SECRET_KEY'] トークンの署名に使用される秘密鍵
		 * @param string 'HS256' トークンの署名に使用するハッシュアルゴリズムを指定
		 */
		return JWT::encode($payload, $_ENV['JWT_SECRET_KEY'], 'HS256');
	}
}
