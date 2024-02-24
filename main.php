<?php
namespace App;

spl_autoload_register(function (string $classname) {
    $classname = '/src' . substr($classname, 10); //cut off the 'App/Source' part, add '/src'
    include __DIR__ . $classname . '.php';
});

// include __DIR__ ."/src/UserInterface.php";

use App\Source\UserInterface as UI;

$ui = new UI();

$ui->start();