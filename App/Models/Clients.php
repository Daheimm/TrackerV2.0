<?php


namespace App\Models;

use PDO;


class Clients extends \Core\Model
{
    protected $data;
    protected $array = [];
    protected $link;
    public $res;

    public function __construct()
    {

        $this->link = static::getDB();
        $this->GetAllAlogin();

    }


    public function GetAllAlogin()
    {

        $this->res = $this->link->query("Select log,pas, p.name,dates,IdPromo,countDay,click,company,payDate,activation
                                        From LogAndPass l inner JOIN Paket p on l.IdPacket = p.id ")->fetchAll(PDO::FETCH_ASSOC);
    }


}