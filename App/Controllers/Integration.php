<?php


namespace App\Controllers;

use \Core\View;

class Integration extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Home/integration.html');
    }

}