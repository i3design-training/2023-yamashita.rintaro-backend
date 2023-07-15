<?php

namespace App\Action\Task;

use App\Models\Task;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskUpdateAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			Log::info('タスクの更新開始');
			$taskId = $request->getAttribute('id');

			Log::info($taskId);

			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody, true);

			Log::info('Request Body: ' . print_r($decodedRequestBody));

			if (!$taskId) {
				throw new \InvalidArgumentException('タスクIDが必要です');
			}

			$task = Task::find($taskId);

			if (!$task) {
				Log::error('指定されたIDのタスクは存在しません');
				throw new Exception('指定されたIDのタスクは存在しません');
			}

			// $fillable プロパティに指定された属性だけをマスアサインメント
			$task->fill($decodedRequestBody);
			$task->save();
			Log::info('タスクの更新後: ' . print_r($task->toArray(), true));

			$taskWithName = Task::with(['category', 'taskstatus'])->find($taskId);
			// タスクのデータにカテゴリ名とタスクステータス名を追加
			$taskDetails = $taskWithName->toArray();
			$taskDetails['category_name'] = $taskWithName->category->name;
			$taskDetails['taskstatus_name'] = $taskWithName->taskstatus->name;

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
