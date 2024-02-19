<?php
namespace App\Source\Abstractions;

include __DIR__ . "/../Interfaces/RepositoryInterface.php";

use App\Source\Interfaces\RepositoryInterface as RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $file;
    protected array $database;

    public function  __construct()
    {
        // ...
    }

    protected function generateNewId(): int
    {
        $lastKey = array_key_last($this->database);
        if($lastKey === null)
            return 1;

        $lastElement = (array)$this->database[$lastKey];
        $lastId = $lastElement['id'];

        $lastId++;
        return $lastId;
    }

    // public function save()
    // {
    //     fwrite($this->file, json_encode($this->database));
    // }

    public function find (int $id)
    {
        return $this->database[$id];
    }

    public function findAll()
    {
        return $this->database;
    }

    public function remove(int $id): void
    {
        unset($this->database[$id]);
    }
}