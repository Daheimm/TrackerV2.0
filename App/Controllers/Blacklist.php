<?php


namespace App\Controllers;
use Core\View;

class Blacklist extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Home/blacklist.html');
    }

}