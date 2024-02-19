<?php
namespace App\Source\Interfaces;

interface RepositoryInterface
{
    public function  __construct();
    public function find (int $id);
    public function findAll();
}