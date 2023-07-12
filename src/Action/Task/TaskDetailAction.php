<?php

namespace App\Action\Task;

use App\Models\Task;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskDetailAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			// ルートパラメータを取得
			// "/tasks/123"の場合、getAttribute('id')は"123"を返す
			$taskId = $request->getAttribute('id');

			if (!$taskId) {
				throw new \InvalidArgumentException('タスクIDが必要です');
			}

			$task = Task::find($taskId);

			if (!$task) {
				throw new Exception('指定されたIDのタスクは存在しません');
			}

			Log::info('タスク詳細取得: ' . $task->id);

			$response->getBody()->write(json_encode($task));

			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		} catch (\InvalidArgumentException $e) {
			Log::error('無効な入力データ: ' . $e->getMessage());
			$response->getBody()->write(json_encode(['error' => $e->getMessage()]));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(400);
		} catch (Exception $e) {
			Log::error('エラー: ' . $e->getMessage());
			$response->getBody()->write(json_encode(['error' => $e->getMessage()]));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(500);
		}
	}
}
