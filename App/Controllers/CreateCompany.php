<?php


namespace App\Controllers;

use App\Controllers\CreateCompany as ModelCreateCompany;
use Core\View;

class CreateCompany extends \Core\Controller
{
    private $model;

    public function indexAction()
    {
        if (array_key_exists("nameCompany", $_POST)) {
            $nameCompany = $_POST["nameCompany"];
            if (isset($nameCompany) && !empty($nameCompany)) {
                if (preg_match("/^[a-zA-Z]+$/", $nameCompany)) {
                    $this->model = new \App\Models\CreateCompany($_SESSION["user"], $nameCompany);
                    if ($this->model->getData()) {
                        header("Location: /");
                    }
                } else {
                    echo '<script type="text/javascript">alert("Неверно имя компании, используйте только латинские буквы"); document.location.href="/";</script>';

                }
            } else {

                echo '<script type="text/javascript">alert("Введите имя компании использую только Латинские буквы"); document.location.href="/";</script>';

            }
        } else {
            var_dump("sdas");
        }
    }
}