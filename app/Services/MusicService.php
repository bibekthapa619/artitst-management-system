<?php

namespace App\Services;

class MusicService
{
    protected $dbService;
    protected $hidden = [];

    public function __construct(DatabaseService $dbService)
    {
        $dbService->setTable('musics');
        $this->dbService = $dbService;
    }

    public function createMusic(array $data)
    {
        return $this->dbService->insert($data);
    }

    public function findMusic($columns = ['*'], $condition, $bindings = [], $hidden = [])
    {
        return $this->dbService->find($columns, $condition, $bindings, $hidden);
    }

    public function getMusicById($id, $columns = ['*'])
    {
        return $this->dbService->selectOne($id, $columns, $this->hidden);
    }

    public function getAllMusic(array $columns = ['*'], $condition = '', array $bindings = [], $orderBy = 'id ASC', $pageSize = null, $currentPage = 1)
    {
        return $this->dbService->select($columns, $condition, $bindings, $orderBy ,$this->hidden, $pageSize, $currentPage);
    }

    public function updateMusic($id, array $data)
    {
        return $this->dbService->update($id, $data);
    }

    public function deleteMusic($id)
    {
        return $this->dbService->delete($id);
    }
}
