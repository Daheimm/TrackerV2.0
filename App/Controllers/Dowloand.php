<?php


namespace App\Controllers;

use App\Models\Dowloand as DowloandZip;


class Dowloand extends \Core\Controller
{

    private $dowZip;

    public function indexAction()
    {

        if (array_key_exists('company', $_POST)) {

            $this->dowZip = new DowloandZip($_POST["company"], $_SESSION["user"]);
            echo $this->dowZip->getData();

        }
    }
}