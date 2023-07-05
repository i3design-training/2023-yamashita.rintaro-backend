<?php

namespace App\Action\User;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;

class UserCreateAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // リクエストからデータを収集
        try {
            $requestBody = $request->getBody()->getContents();
            $decodedRequestBody = json_decode($requestBody);

            Log::info(sprintf('username: %s', $requestBody));
            $useCaseRequest = User::create(
                [
                    'username' => $decodedRequestBody->username,
                    'password' => $decodedRequestBody->password,
                    'email' => $decodedRequestBody->email
                ]
            );

            $response->getBody()->write("New user registered successfully");
            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write("Error: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
