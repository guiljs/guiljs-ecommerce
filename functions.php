<?php

use \Hcode\Model\User;

function formatPrice(float $value)
{
    return number_format($value, 2, ",", ".");
}

function checkLogin($inadmin = true)
{
    return User::checkLogin($inadmin);
}

function getUserName()
{
    $user = User::getFromSession();

    return $user->getdesperson();
}
