<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function getUsers(array $filters = []): array;
    public function getUserById(int $id): array;
    public function createUser(array $data): array;
    public function updateUser(int $id, array $data): array;
    public function enableUser(int $id): array;
    public function disableUser(int $id): array;
    public function deleteUser(int $id): array;
}
