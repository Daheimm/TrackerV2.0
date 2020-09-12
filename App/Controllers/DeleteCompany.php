<?php


namespace App\Controllers;

use App\Models\DeleteCompany as modelDel;
use Core\View;

class DeleteCompany extends \Core\Controller
{
    public function indexAction()
    {

        if (array_key_exists("delCompany", $_POST)) {

            if (isset($_POST["delCompany"])) {

               $compnay = $_POST["delCompany"];
                $login = $_SESSION["user"];
                $model = new modelDel($compnay,$login);
                echo $model->isResult;
               header("Location: /");
            }
        }


    }
}