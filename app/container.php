<?php

use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\User\UserRepository;
use App\Domain\Email\EmailSenderInterface;
use App\Infrastructure\Email\EmailSender;
use App\UseCase\User\Create\UserCreateUseCaseInterface;
use App\UseCase\User\Create\UserCreateUseCaseInteractor;

return [
    UserRepositoryInterface::class => \DI\create(UserRepository::class),
    EmailSenderInterface::class => \DI\create(EmailSender::class),
    UserCreateUseCaseInterface::class => \DI\create(UserCreateUseCaseInteractor::class)
        ->constructor(
            \DI\get(UserRepositoryInterface::class),
            \DI\get(EmailSenderInterface::class)
        ),
];