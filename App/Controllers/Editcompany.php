<?php


namespace App\Controllers;


use Core\View;

class Editcompany extends \Core\Controller
{
    public function indexAction()
    {


        View::renderTemplate('Home/company-edit.html');

    }
}