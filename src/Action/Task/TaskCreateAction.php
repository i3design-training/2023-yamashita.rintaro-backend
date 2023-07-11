<?php

namespace App\Action\Task;

use App\Models\Task;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskCreateAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody);

			Log::info('タスク作成開始');

			$newTask = Task::create(
				[
					'title' => $decodedRequestBody->title,
					'description' => $decodedRequestBody->description,
					'due_date' => $decodedRequestBody->due_date,
					'status_id' => $decodedRequestBody->status_id,
					'category_id' => $decodedRequestBody->category_id,
					'user_id' => $decodedRequestBody->user_id
				]
			);

			$response->getBody()->write($newTask);
			return $response->withStatus(201);
		} catch (\InvalidArgumentException $e) {
			Log::error('無効な入力データ: ' . $e->getMessage());
			$response->getBody()->write("無効な入力データ");
			return $response->withStatus(400);
		} catch (Exception $e) {
			Log::error('エラー: ' . $e->getMessage());
			$response->getBody()->write("エラーが発生しました");
			return $response->withStatus(500);
		}
	}
}
