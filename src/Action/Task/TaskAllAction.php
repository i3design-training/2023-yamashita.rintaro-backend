<?php

namespace App\Action\User;

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
			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody);

			$user = User::where('id', $decodedRequestBody->userId)->first();

			if (!$user) {
				throw new \InvalidArgumentException('User not found');
			}

			$tasks = $user->tasks;

			$response->getBody()->write(json_encode(['tasks' => $tasks], JSON_UNESCAPED_UNICODE));

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
