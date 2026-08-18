<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function getUsers(array $filters = []): array;
    public function getUserById(int $id): array;
    public function createUser(array $data): array;
    public function updateUser(int $id, array $data): array;
    public function toggleUserStatus(int $id): array;
}
