<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserCreateData;
use App\Domain\User\Repository\UserCreatorRepository;

final class UserCreator
{
    private $repository;

    public function __construct(UserCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): UserCreateData
    {
        // ユーザーの作成
        $userId = $this->repository->insertUser($data);

        // データオブジェクトの作成
        $user = new UserCreateData($userId, $data['username'], $data['email'], $data['password']);

        return $user;
    }
}