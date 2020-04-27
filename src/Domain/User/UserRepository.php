<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;


    /**
     * @param int $id
     */
    public function findUserOfIdAndRemove(int $id) : User;


    /**
     * @param User $user
     * @return int
     */
    public function addNewUser(User $user): int;


    /**
     * @param int $id
     * @param User $user
     * @return User
     */
    public function updateUserById(int $id, User $user): User;


    /**
     * @return mixed
     */
    public function store();

}
