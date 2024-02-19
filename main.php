<?php
namespace App;

// include __DIR__ ."/src/Repository/CharityRepository.php";
include __DIR__ ."/src/UserInterface.php";

// use App\Source\Repository\CharityRepository;
// use App\Source\Entity\Charity;
use App\Source\UserInterface as UI;

// $obj = new Charity();
// $obj->setName('dickbutt');
// $obj->setId(69);
// $obj->setRepresentativeEmail('arbitrary value');

// // $json = json_encode($obj);

// $repo = new CharityRepository();
// $repo->add($obj);
// $repo->save();


// while(1)
// {
//     $line = readline("Please ender the seggs number: ");
//     readline_add_history($line);

//     if($line == 69)
//     {
//         echo("Correct\n");
//         echo(readline_info());
//         break;
//     }
//     else
//     {
//         echo("WRONG!\n");
//     }
// }

$ui = new UI();

$ui->start();