<?php
namespace App\Source;

include __DIR__ ."/Controller/CharityController.php";
include __DIR__ ."/Controller/DonationController.php";
include __DIR__ ."/Helper/Validator.php";

use App\Source\Controller\CharityController;
use App\Source\Controller\DonationController;
use App\Source\Helper\Validator;

class UserInterface
{
    /////////////////////////////////////////////////////////////////////////////////
    /// Core
    /////////////////////////////////////////////////////////////////////////////////

    private string $state = "Main menu";
    private bool $start = true;
    private bool $quit = false;

    private CharityController $chariCon;
    private DonationController $donCon;
    private Validator $validator;

    private int $charityId;

    public function __construct()
    {
        $this->chariCon = new CharityController();
        $this->donCon = new DonationController();
        $this->validator = new Validator();
        $this->charityId = 0;
    }

    public function start(): void
    {
        while(!$this->quit)
        {
            $this->route();
        }
    }

    private function displayMenu(string $text, array $options, bool $local = false): string
    {
        $input = '';

        //refining from convenient-to-input to convenient-to-use
        //input format: [ [key, label, state], ...]
        $keys = [];
        $labels = [];
        $states = [];
        foreach ($options as $option)
        {
            $keys[] = $option[0];
            $labels[$option[0]] = $option[1]; //key => label
            $states[$option[0]] = $option[2]; //key => state
        }

        foreach ($keys as $key) //list out the menu options
        {
            echo($key . ' - ' . $labels[$key] . "\n");
        }

        while(1) //make sure the input's valid
        {
            $input = strtolower( readline($text) );

            if(in_array($input, $keys))
                break;

            echo("Sorry, I don't recognise that.\n");
        }

        if(!$local)
            $this->state = $states[$input]; //tell the router what to do next

        return $input;
    }

    private function route(): void
    {
        match($this->state)
        {
            'Main menu' => $this->mainMenu(),
            'Quit' => $this->quit(),
            'Charity-index' => $this->charityIndex(),
            'Charity-add' => $this->charityAdd(),
            'Charity-donate' => $this->donate($this->charityId),
            'Donation-index' => $this->donationIndex(),
            'Charity-view' => $this->charityView($this->charityId),
            'Charity-edit' => $this->charityEdit($this->charityId),
            'Charity-delete' => $this->charityDelete($this->charityId),
            default => $this->confused(),
        };
    }


    /////////////////////////////////////////////////////////////////////////////////
    /// Options
    /////////////////////////////////////////////////////////////////////////////////

    private function mainMenu(): void
    {
        if($this->start)
        {
            echo ("\nWelcome to charity and donation manager. How may I help you?\n");
            $this->start = false;
        }
        else
            echo ("\nAnything else I can help you with?\n");
            

        $this->displayMenu("I'd like to: ", [
            ['1', 'View available charities', 'Charity-index'],
            ['2', 'Register a new charity', 'Charity-add'],
            ['x', 'Quit', 'Quit'],
        ]);
    }

    //Charity list
    private function charityIndex(): void
    {
        $charities = $this->chariCon->index();
        $menuItems = [];

        if($charities)
        {
            echo ("\nHere you go. All the charities on our records. Would any of them be of interest?\n");

            foreach($charities as $charity)
            {
                $menuItems[] = [$charity->getId(), $charity->getName(), 'Charity-view'];
            }

            $menuItems[] = ['b', 'Back', 'Main menu'];
            $menuItems[] = ['x', 'Quit', 'Quit'];

            $input = $this->displayMenu('', $menuItems);
            
            if(strtolower(gettype($input)) !== strtoupper(gettype($input)))
                $this->charityId = (integer)$input;
        }
        else
        {
            echo ("\nApologies. We don't seem to have any charities registered at the moment.\n");
            $this->state = "Main menu";
        }
    }

    //Add a charity
    private function charityAdd(): void
    {
        $charity = [];

        echo ("\nBrilliant! What's the name of this charity then?\n");
        while(1)
        {
            $charity['name'] = readline('Charity name: ');

            if( $this->validator->isValidTitle($charity['name']) )
                break;

            echo ("\nSorry, but a name has to be at least 3 characters and none of them too funky. Letters, numbers, spaces, - and ' are allowed.\n");
        }

        echo ("\nI also need an email address of a representative, please.\n");
        while(1)
        {
            $charity['representativeEmail'] = readline('Email: ');

            if( $this->validator->isValidEmail($charity['representativeEmail']) )
                break;

            echo ("\nSorry, but that doesn't seem to be a valid email address.\n");
        }

        echo ("\nThat'll do nicely. I'll add it to the records straight away.\n");
        $this->chariCon->new($charity);

        $this->state = "Main menu";
    }

    //Donate
    private function donate(int $id): void
    {
        $donation = [];

        echo ("\nFeeling generous, are we? Very well. May I have your name, please?\n");
        while(1)
        {
            $donation['name'] = readline('Name: ');

            if( $this->validator->isValidPersonName($donation['name']) )
                break;

            echo ("\nNot to be rude, but your name might have too many special characters. Please try again.\n");
        }

        echo ("\nAnd how much would you like to donate?\n");
        while(1)
        {
            $donation['amount'] = readline('Amount: ');

            if( $this->validator->isValidSum($donation['amount']) )
                break;

            echo ("\nI'm afraid that's not a valid number. Please don't include any currency symbols.\n");
        }

        echo ("\nThank you very much.\n");
        $this->donCon->new($donation, $this->charityId);

        $this->state = "Main menu";
    }

    //View a charity
    private function charityView(): void
    {
        $charity = $this->chariCon->view($this->charityId);
        echo ("Name: ". $charity->getName() ."\n");
        echo ("Representative email: ". $charity->getRepresentativeEmail() ."\n");
        echo ("\nAh, this one. How can I help you with it?\n");
        $this->displayMenu('', [
            $menuItems[] = ['a', 'Donate', 'Charity-donate'],
            $menuItems[] = ['v', 'View donations', 'Donation-index'],
            $menuItems[] = ['e', 'Edit', 'Charity-edit'],
            $menuItems[] = ['d', 'Delete', 'Charity-delete'],
            $menuItems[] = ['b', 'Back', 'Charity-index'],
            $menuItems[] = ['x', 'Quit', 'Quit'],
        ]);
    }

    //View donations
    private function donationIndex(): void
    {
        $donations = $this->donCon->index($this->charityId);

        foreach ($donations as $donation)
        {
            echo("\nDonor: " . $donation->getDonorName() . "\n");
            echo("Amount: " . $donation->getAmount() . "\n");
            echo("Date and time: " . $donation->getDateTime() . "\n");
        }

        echo ("\nHere you go. Charity's donation records.\n");
        $this->displayMenu('', [
            $menuItems[] = ['b', 'Back', 'Charity-view'],
            $menuItems[] = ['x', 'Quit', 'Quit'],
        ]);
    }

    //Edit a charity
    private function charityEdit(): void
    {
        $charity = [];

        echo ("\nAlright. What shall we rename it to? You can leave this blank to keep the old name.\n");
        while(1)
        {
            $charity['name'] = readline('Charity name: ');

            if( $this->validator->isValidTitle($charity['name']) )
                break;

            echo ("\nSorry, but a name has to be at least 3 characters and none of them too funky. Letters, numbers, spaces, - and ' are allowed.\n");
        }

        echo ("\nHas the representative email changed? Leave blank if not.\n");
        while(1)
        {
            $charity['email'] = readline('Email: ');

            if( $this->validator->isValidEmail($charity['email']) )
                break;

            echo ("\nSorry, but that doesn't seem to be a valid email address.\n");
        }

        echo ("\nThat'll do nicely. Let me just update the records.\n");
        $this->chariCon->edit($this->charityId, $charity);

        $this->state = "Main menu";
    }

    //Delete a charity
    private function charityDelete(): void
    {
        echo ("\nOkay. Let me clear that out for you.\n");
        $this->chariCon->delete($this->charityId);
        $this->charityId = 0;

        $this->state = 'Main menu';
    }

    //Error handling
    private function confused(): void
    {
        echo ("Oh dear. Something went wrong.\nI'm going to have to route you back to the start.\n");
        $this->state = 'Main menu';
    }

    //Quit
    private function quit(): void
    {
        $this->quit = true;
    }
}