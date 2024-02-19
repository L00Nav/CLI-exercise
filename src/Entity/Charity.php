<?php
namespace App\Source\Entity;

class Charity
{
    private int $id;

    private string $name;

    private string $representativeEmail;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRepresentativeEmail(): string
    {
        return $this->representativeEmail;
    }

    public function setRepresentativeEmail(string $representativeEmail): void
    {
        $this->representativeEmail = $representativeEmail;
    }
}