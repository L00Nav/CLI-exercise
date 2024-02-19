<?php
namespace App\Source\Repository;

include __DIR__ . '/../Entity/Donation.php';
include_once __DIR__ . '/../Abstractions/AbstractRepository.php';

use App\Source\Abstractions\AbstractRepository;
use App\Source\Entity\Donation;

class DonationRepository extends AbstractRepository
{
    public function __construct()
    {
        $size = filesize(__DIR__.'/../Databases/Donations.json'); //check file size

        if ($size) //if a file exists and isn't empty
        {
            $this->file = fopen(__DIR__.'/../Databases/Donations.json', 'r'); //open
            $this->database = (array)json_decode(fread($this->file, $size)); //read
            fclose($this->file); //close
        }
        else
        {
            $this->database = []; //make a new dataset
        }
    }


    public function add(Donation $donation): void
    {
        $this->database[] = [
            'id' => $this->generateNewId(),
            'donorName' => $donation->getDonorName(),
            'amount' => $donation->getAmount(),
            'charityId' => $donation->getCharityId(),
            'dateTime' => $donation->getDateTime(),
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

        $this->file = fopen(__DIR__.'/../Databases/Donations.json', 'w+');
        fwrite($this->file, json_encode($rearrangedDatabase));
        fclose($this->file);
    }


    public function findAllByCharity(int $charityId): array
    {
        $matchingDonations = [];
        foreach ($this->database as $donation)
        {
            $donation = (array)$donation;
            if($donation['charityId'] == $charityId)
            {
                $donationObj = new Donation();
                $donationObj->setId($donation['id']);
                $donationObj->setDonorName($donation['donorName']);
                $donationObj->setAmount($donation['amount']);
                $donationObj->setCharityId($donation['charityId']);
                $donationObj->setDateTime($donation['dateTime']);
                $matchingDonations[] = $donationObj;
            }
        }
        return $matchingDonations;
    }
}