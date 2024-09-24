<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseService
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    protected function getColumnsToSelect($columns, $hidden)
    {
        if ($columns === ['*']) {
            if(!empty($hidden)){
                $sql = "SHOW COLUMNS FROM {$this->table}";
                $stmt = $this->pdo->query($sql);
                $allColumns = $stmt->fetchAll(\PDO::FETCH_COLUMN);

                $columns = array_diff($allColumns, $hidden);
            }
            
        } else {
            $columns = array_diff($columns, $hidden);
        }

        return $columns;
    }

    public function insert(array $data)
    {
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array_values($data));

        $id1 = $this->pdo->lastInsertId();

        return $id1;
    }

    public function selectOne($id, $columns = ['*'],$hidden = [])
    {
        $columns = $this->getColumnsToSelect($columns,$hidden);

        $columnsList = implode(',', $columns);

        $sql = "SELECT {$columnsList} FROM {$this->table} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function find($columns = ['*'], $condition, $bindings = [], $hidden = [])
    {
        $columns = $this->getColumnsToSelect($columns,$hidden);

        $columnsList = implode(',', $columns);

        $sql = "SELECT {$columnsList} FROM {$this->table} WHERE {$condition}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function select(array $columns = ['*'], $condition = '', array $bindings = [], $orderBy='id ASC', array $hidden = [], $pageSize = null, $currentPage = 1, $join = '')
    {
        $columns = $this->getColumnsToSelect($columns, $hidden);
        $columnsList = implode(',', $columns);

        $from = null;
        $to = null;
        $lastPage = null;
        $totalRecords = 0;

        if ($pageSize !== null) {
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
            if($join){
                $countSql .= " JOIN {$join}";
            }

            if ($condition) {
                $countSql .= " WHERE {$condition}";
            }
            $countStmt = $this->pdo->prepare($countSql);
            $countStmt->execute($bindings);
            $totalRecords = $countStmt->fetchColumn();

            $offset = ($currentPage - 1) * $pageSize;
            $from = $offset + 1;
            $to = min($offset + $pageSize, $totalRecords);
            $lastPage = (int)ceil($totalRecords / $pageSize);
        }

        $sql = "SELECT {$columnsList} FROM {$this->table}";
        if($join){
            $sql .= " JOIN {$join}";
        }
        if ($condition) {
            $sql .= " WHERE {$condition}";
        }
        $sql .= " ORDER BY {$orderBy}";

        if ($pageSize !== null) {
            $sql .= " LIMIT {$pageSize} OFFSET {$offset}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if($pageSize !== null){
            return [
                'data' => $results,
                'meta' => [
                    'total' => $totalRecords,
                    'page_size' => $pageSize,
                    'current_page' => $currentPage,
                    'last_page' => $lastPage,
                    'from' => $from,
                    'to' => $to,
                ],
            ];
        }

        return $results;
    }

    public function update($id, array $data)
    {
        $data['updated_at'] = now();
        
        $columns = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE {$this->table} SET {$columns} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array_merge(array_values($data), [$id]));
    }

    public function updateWithCondtion($condition, $bindings, array $data)
    {
        $data['updated_at'] = now();
        
        $columns = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE {$this->table} SET {$columns} WHERE {$condition}";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array_merge(array_values($data), $bindings));
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollback()
    {
        $this->pdo->rollBack();
    }
}
