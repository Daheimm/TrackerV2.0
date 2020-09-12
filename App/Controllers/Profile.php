<?php


namespace App\Controllers;


use Core\View;

class Profile extends  \Core\Controller
{
    public function indexAction()
    {


        View::renderTemplate('Home/profile.html');

    }
}