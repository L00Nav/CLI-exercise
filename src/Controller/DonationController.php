<?php

namespace App\Source\Controller;
include __DIR__ ."/../Repository/DonationRepository.php";
use App\Source\Repository\DonationRepository;
use App\Source\Entity\Donation;

class DonationController //extends AbstractController
{
    private DonationRepository $repository;

    public function __construct()
    {
        $this->repository = new DonationRepository();
    }

    public function index(int $charityId): array
    {
        return $this->repository->findAllByCharity($charityId);
    }

    public function new(array $donation, int $charityId): void
    {
        $newDonation = new Donation();
        $newDonation->setDonorName( $donation['name'] );
        $newDonation->setAmount( $donation['amount'] );
        $newDonation->setCharityId( $charityId );
        date_default_timezone_set('Europe/Vilnius');
        $newDonation->setDateTime( date("Y-m-d H:i") );

        $this->repository->add( $newDonation );
        $this->repository->save();
    }

    public function view(): void
    {

    }
}