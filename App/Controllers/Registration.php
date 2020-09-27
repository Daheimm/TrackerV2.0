<?php


namespace App\Controllers;

use \Core\View;
use \App\Models\Registration as MRegistration;

class Registration extends \Core\Controller
{

    public function indexAction()
    {

        View::renderTemplate("Home/registration.html");
    }

    public function check()
    {

        if (array_key_exists("email", $_POST) && array_key_exists("password", $_POST) && array_key_exists("promo", $_POST)) {
            if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["promo"])) {
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                    $model = new MRegistration($_POST["email"], $_POST["password"], $_POST["promo"]);

                    if ($model->isAnswer){
                        echo '<script type="text/javascript">alert("Ваша запись активируется  после проверки в течение 1-8 часов."); document.location.href="?/registration";</script>';
                    }
                    else {
                        echo '<script type="text/javascript">alert("'.$model->answer.'"); document.location.href="?/registration";</script>';

                    }
                }else{
                    echo '<script type="text/javascript">alert("Не правильно введеный email!"); document.location.href="?/registration";</script>';

                }
            } else {

                echo '<script type="text/javascript">alert("Не все заполненны поля!"); document.location.href="?/registration";</script>';

            }
        }else{

            header('Location: ?/registration');
        }
    }
}