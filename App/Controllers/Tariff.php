<?php


namespace App\Controllers;

use Core\View;
use \App\Models\Tariff as ModelTariff;

class Tariff extends \Core\Controller
{

    public function paySave()
    {
       $modelTariff = new ModelTariff($_SESSION["user"],$_POST["tariff"]);
       $modelTariff->paySave();
       echo  "Тарифф активируется после оплаты.После оплаты написать на телегу. Такие типы оплат не отслеживаются.";
    }

    public function indexAction()
    {
        $clients[] = $_SESSION['user'] == 'dimaakimov528@gmail.com' ? array("clients" => true) : array("clients" => false);
        View::renderTemplate('Home/tariff.html', ["posts" => $clients[0]]);
    }
}