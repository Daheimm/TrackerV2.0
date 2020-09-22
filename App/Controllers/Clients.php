<?php


namespace App\Controllers;

use Core\View;
use App\Models\Clients as modelClients;

class Clients extends \Core\Controller
{
    protected $modelClients;

    public function __construct()
    {
        $this->modelClients = new modelClients();
    }

    public function indexAction()
    {

        View::renderTemplate('Home/clients.html', ["posts" => $this->modelClients->res]);

    }
}