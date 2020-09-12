<?php


namespace App\Controllers;
use Core\View;

class Clients extends \Core\Controller
{
    public function indexAction()
    {


        View::renderTemplate('Home/clients.html');

    }
}