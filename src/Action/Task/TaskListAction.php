<?php

namespace App\Action\Task;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;

class TaskListAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$userId = $request->getQueryParams()['userId'];
			$user = User::where('id', $userId)->first();

			if (!$user) {
				throw new \InvalidArgumentException('User not found');
			}

			$tasks = $user->tasks;

			$response->getBody()->write(json_encode($tasks));

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
