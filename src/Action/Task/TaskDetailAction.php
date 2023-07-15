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

			// with(): LaravelのEloquent ORMにおけるEager Loading
			// Eager LoadingでN+1問題解消
			$task = Task::with(['category', 'taskstatus'])->find($taskId);

			if (!$task) {
				throw new Exception('指定されたIDのタスクは存在しません');
			}

			// タスクのデータにカテゴリ名とタスクステータス名を追加
			$taskDetails = $task->toArray();
			$taskDetails['category_name'] = $task->category->name;
			$taskDetails['taskstatus_name'] = $task->taskstatus->name;

			$response->getBody()->write(json_encode($taskDetails));

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
