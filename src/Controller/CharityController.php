<?php

namespace App\Source\Controller;
include __DIR__ ."/../Repository/CharityRepository.php";
use App\Source\Repository\CharityRepository;
use App\Source\Entity\Charity;


class CharityController //extends AbstractController
{
    private CharityRepository $repository;

    public function __construct()
    {
        $this->repository = new CharityRepository();
    }

    public function index(): array
    {
        $charities = $this->repository->findAll();

        return $charities;
    }

    public function new(array $input): void
    {
        $this->repository->add($input);
        $this->repository->save();
    }

    public function view(int $id)
    {
        return $this->repository->find($id);
    }

    public function edit(int $id, array $input): void
    {
        $charity = new Charity();
        $charity->setName($input['name']);
        $charity->setRepresentativeEmail($input['email']);
        $this->repository->overwrite($id, $charity);
        $this->repository->save();
    }

    public function delete(int $id): void
    {
        $this->repository->remove($id);
        $this->repository->save();
    }

    /* CSV import
    //Out of time to get this done, so I'm just writing down my best guess for the general flow of things
    public function importFromCSV()
    {
        //check to see how many columns there are as a validation check

        //read the first line of the file
            //look for 'name' 'email' etc. See if there's a header
            //if so, adapt the input format
            //if not, default to something. name/email

        //loop through table data
            //validation check
            //map into array
            //$this->new();
    }
    */
}