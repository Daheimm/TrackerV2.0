<?php


namespace App\Controllers;

use App\Models\ActivationAccount as model;

class ActivationAccount extends \Core\Controller
{
    protected $modelActivationAccount;

    public function __construct()
    {
        $this->modelActivationAccount = new model($_POST["login"]);
    }

    public function activation()
    {
        $this->modelActivationAccount->bootstrap();
    }

    public function activationPay()
    {
        $this->modelActivationAccount->activationPay();
        echo $this->modelActivationAccount->msg;
    }
}