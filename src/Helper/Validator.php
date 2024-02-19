<?php
namespace App\Source\Helper;

class Validator
{
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function isValidPersonName(string $name): bool
    {
        if( strlen($name) < 3)
            return false;

        return preg_match("/^[a-zA-Z-'! ]*$/", $name);
    }

    public function isValidTitle(string $title): bool
    {
        if( strlen($title) < 3)
            return false;

        return preg_match("/^[a-zA-Z0-9-' ]*$/", $title);
    }

    public function isValidSum(string $sum): bool
    {
        return preg_match("/^[0-9., ]*$/", $sum);
    }
}