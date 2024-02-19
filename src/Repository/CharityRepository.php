<?php
namespace App\Source\Repository;

include __DIR__ . '/../Entity/Charity.php';
include_once __DIR__ . '/../Abstractions/AbstractRepository.php';

use App\Source\Abstractions\AbstractRepository;
use App\Source\Entity\Charity;

class CharityRepository extends AbstractRepository
{
    public function __construct()
    {
        $size = filesize(__DIR__.'/../Databases/Charities.json'); //check file size

        if ($size) //if a file exists and isn't empty
        {
            $this->file = fopen(__DIR__.'/../Databases/Charities.json', 'r'); //open
            $this->database = (array)json_decode(fread($this->file, $size)); //read
            fclose($this->file); //close
        }
        else
        {
            $this->database = []; //make a new dataset
        }
    }

    public function add(array $charity): void
    {
        $this->database[] = [
            'id' => $this->generateNewId(),
            'name' => $charity['name'],
            'email' => $charity['representativeEmail'],
        ];
    }

    public function findAll()
    {
        $charities = [];
        foreach($this->database as $charityData)
        {
            $charityData = (array)$charityData;
            $newCharity = new Charity();
            $newCharity->setId($charityData['id']);
            $newCharity->setName($charityData['name']);
            $newCharity->setRepresentativeEmail($charityData['email']);
            $charities[$charityData['id']] = $newCharity;
        }
        return $charities;
    }

    public function find($id)
    {
        $charity = new Charity();
        $charityData = $this->database[$id];
        $charityData = (array)$charityData;
        $charity->setId($charityData['id']);
        $charity->setName($charityData['name']);
        $charity->setRepresentativeEmail($charityData['email']);

        return $charity;
    }

    public function overwrite(int $id, Charity $charity): void
    {
        $this->database[$id] = [
            'id' => $id,
            'name' => $charity->getName(),
            'email' => $charity->getRepresentativeEmail(),
        ];
    }
    
    public function save()
    {
        //making sure the key of each entry matches with entity key ended up being crucial
        $rearrangedDatabase = [];
        foreach ($this->database as $data)
        {
            $data = (array)$data;
            $rearrangedDatabase[$data['id']] = $data;
        }

        $this->file = fopen(__DIR__.'/../Databases/Charities.json', 'w+');
        fwrite($this->file, json_encode($rearrangedDatabase));
        fclose($this->file);
    }
}