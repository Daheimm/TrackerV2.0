<?php


namespace App\Controllers;


class Logout extends \Core\Controller
{
    public function indexAction()
    {

        session_destroy();
        header("Location: /");

    }

}