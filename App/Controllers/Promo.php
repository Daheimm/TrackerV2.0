<?php


namespace App\Controllers;


use Core\View;

class Promo extends \Core\Controller
{
    public function indexAction()
    {


        View::renderTemplate('Home/promo.html');

    }
}