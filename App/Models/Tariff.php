<?php


namespace App\Models;

use PDO;

class Tariff extends \Core\Model
{
    protected $login;
    protected $db;
    protected $tariff;

    public function __construct($login,$tariff)
    {
        $this->tariff = $tariff;
        $this->login = $login;
        $this->db = static::getDB();
    }

    public function paySave()
    {
        $nameTariff = $this->db->query("Select name From Paket Where id = $this->tariff")->fetch(PDO::FETCH_ASSOC)["name"];

        $today = date("y-m-d");
        $this->db->query("Update LogAndPass set pay = '$nameTariff',payDate = '$today',IdPacket = $this->tariff Where log = '$this->login'");
    }
}