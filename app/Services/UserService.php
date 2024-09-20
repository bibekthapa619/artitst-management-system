<?php

namespace App\Services;

class UserService
{
    protected $dbService;
    protected $hidden = ['password'];

    public function __construct(DatabaseService $dbService)
    {
        $dbService->setTable('users');
        $this->dbService = $dbService;
    }

    public function createUser(array $data)
    {
        return $this->dbService->insert($data);
    }

    public function getUserById($id, $columns = ['*'])
    {
        return $this->dbService->selectOne($id, $columns, $this->hidden);
    }

    public function getAllUsers(array $columns = ['*'], $condition = '', array $bindings = [])
    {
        return $this->dbService->select($columns, $condition, $bindings, $this->hidden);
    }

    public function updateUser($id, array $data)
    {
        return $this->dbService->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->dbService->delete($id);
    }
}
