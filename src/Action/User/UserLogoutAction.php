<?php

namespace App\Action\User;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserLogoutAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody);

			Log::debug(sprintf('ログアウト処理開始'));

			$user = User::where('id', $decodedRequestBody->userId)->first();

			$user->deleteToken();

			$response->getBody()->write(
				json_encode([
					'user_id' => $user->id,
					'username' => $user->username
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


	private function verifyToken(string $token): bool
	{
		try {
			// 正しい形式で、署名が有効で、かつ有効期限が切れていなければ
			JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
			return true;
		} catch (\Exception $th) {
			return false;
		}
	}
}
