<?php


namespace App\Controllers;

use Core\View;
use \App\Models\ClientProfil as model;

class ClientProfil extends \Core\Controller
{
    protected $modelClient;

    public function __construct()
    {
        $this->modelClient = new model($_SESSION["user"]);
    }

    public function indexAction()
    {

        $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com' ? true:false;




        View::renderTemplate('Home/client-profile.html', ["posts" => $this->modelClient->res,"clients" => $clients]);
    }
}