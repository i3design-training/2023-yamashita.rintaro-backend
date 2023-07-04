<?php

namespace App\Domain\User\Data;

final class UserCreateData
{
		/**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * UserCreateData constructor.
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function __construct(int $id, string $username, string $email, string $password)
    {
				$this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}