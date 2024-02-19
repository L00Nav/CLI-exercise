<?php
namespace App\Source\Entity;

class Donation
{
    private int $id;

    private string $donorName;

    private int $amount;

    private int $charityId;

    private string $dateTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDonorName(): string
    {
        return $this->donorName;
    }

    public function setDonorName(string $donorName): void
    {
        $this->donorName = $donorName;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCharityId(): int
    {
        return $this->charityId;
    }

    public function setCharityId(int $charityId): void
    {
        $this->charityId = $charityId;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setDateTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}