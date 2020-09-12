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
            View::renderTemplate('Home/index.html', ["posts" => $this->model->parseListCompany]);
        } else {

            // header("Location: /");
        }

        // var_dump($this->model->listCompany);
    }
}
