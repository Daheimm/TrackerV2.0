<?php


namespace App\Controllers;

use \Core\View;

class Integration extends \Core\Controller
{
    public function indexAction()
    {
        $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com'? true:false;
        View::renderTemplate('Home/integration.html',["posts" => $clients]);
    }

}