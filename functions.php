<?php

use \Hcode\Model\User;

function formatPrice($value)
{
    if (!$value > 0) {

    }
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
