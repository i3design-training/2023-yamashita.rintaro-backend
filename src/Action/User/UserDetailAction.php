<?php

namespace App\Action\User;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;

class UserDetailAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$username = $request->getAttribute('username');

			if (!$username) {
				throw new \InvalidArgumentException('ユーザーが存在しません', 400);
			}

			Log::debug($username);

			$user = User::where('username', $username)->first();
			if ($user === null) {
				throw new \InvalidArgumentException('ユーザーが存在しません', 404);
			}

			$response->getBody()->write(
				json_encode([
					'username' => $user->username,
					'email' => $user->email
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
}
