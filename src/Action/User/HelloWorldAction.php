<?php

namespace App\Action\User;

use App\Helper\Email;
use App\Helper\CreateToken;
use App\Models\User;
use App\Models\EmailVerification;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloWorldAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	) {
		$response->getBody()->write('Hello world!');

		return $response;
	}
}
