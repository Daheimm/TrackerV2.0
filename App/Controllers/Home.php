<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\ListCompany;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    private $model;

    public function indexAction()
    {
        if (array_key_exists("user", $_SESSION)) {

            $this->model = (new ListCompany($_SESSION["user"]));
            $this->model->getListCompany();
            $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com'? true:false;
            //$this->model->parseListCompany[] = array('clients' =>$clients);
            $res = array("company" => $this->model->parseListCompany,"clients" =>$clients);
            View::renderTemplate('Home/index.html', ["posts" => $res]);
        } else {

            // header("Location: /");
        }

        // var_dump($this->model->listCompany);
    }
}
