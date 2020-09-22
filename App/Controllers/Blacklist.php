<?php


namespace App\Controllers;
use Core\View;

class Blacklist extends \Core\Controller
{
    public function indexAction()
    {
        $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com'? true:false;
        View::renderTemplate('Home/blacklist.html',["posts" => $clients]);
    }

}