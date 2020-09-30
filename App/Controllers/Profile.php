<?php


namespace App\Controllers;


use Core\View;

class Profile extends  \Core\Controller
{
    public function indexAction()
    {

        $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com' ? true:false;
        View::renderTemplate('Home/profile.html', ["clients" => $clients]);

    }
}