<?php

namespace App\Services;

class ArtistService
{
    protected $dbService;
    protected $hidden = ['password'];

    public function __construct(DatabaseService $dbService)
    {
        $dbService->setTable('artists');
        $this->dbService = $dbService;
    }

    public function createArtist(array $data)
    {
        return $this->dbService->insert($data);
    }

    public function updateArtist($id, array $data)
    {
        return $this->dbService->update($id, $data);
    }

    public function getArtistByUserId($userId)
    {
        return $this->dbService->find(['*'], 
                        'user_id = ?', 
                        $bindings = [$userId], 
                        $hidden = []);   
    }

    public function getAllDetailsByUserId($userId)
    {
        $sql = "SELECT users.*, artists.*,users.id as id, artists.id as artist_id 
                FROM users 
                JOIN artists ON users.id = artists.user_id 
                WHERE users.id = ?";
    
        $stmt = $this->dbService->getPDO()->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateArtistByUserId($userId, array $data)
    {
        return $this->dbService->updateWithCondtion('user_id = ?', [$userId] ,$data);
    }

    public function getAllArtists(array $columns = ['*'], $condition = '', array $bindings = [], $orderBy = 'id ASC', $pageSize = null, $currentPage = 1)
    {
        $join = "users ON users.id = artists.user_id";
        return $this->dbService->select($columns, $condition, $bindings, $orderBy ,$this->hidden, $pageSize, $currentPage, $join);
    }

}
