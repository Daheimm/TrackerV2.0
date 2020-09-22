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
        $this->res = $this->link->query("Select * From LogAndPass")->fetchAll(PDO::FETCH_ASSOC);

    }


}