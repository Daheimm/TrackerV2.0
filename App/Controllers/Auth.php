<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Auth as MAuth;

class Auth extends \Core\Controller
{
    private $model;

    public function indexAction()
    {


        View::renderTemplate('Home/login.html');

    }

    public function show()
    {

        if (array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {

            if (isset($_POST["email"]) && isset($_POST["password"])) {

                $this->model = new MAuth($_POST["email"], $_POST["password"]);

                if ($this->model->isAnswer) {
                    $_SESSION["user"] = $_POST["email"];
                    header('Location: /');
                    exit;
                } else {
                    echo $this->model->answer;
                    header('Location: /');
                }
            }
        }

    }
}