<?php


namespace App\Models;

use PDO;

class ClientProfil extends \Core\Model
{
    public $res;
    private $db;
    private $login;

    public function __construct($login)
    {
        $this->login = $login;
        $this->db = static::getDB();
        $this->getDataProfil();
    }

    private function getDataProfil()
    {

        $this->res = $this->db->query("Select log,pas, p.name,dates,countDay,click,company
                                        From LogAndPass l inner JOIN Paket p on l.IdPacket = p.id 
                                        Where l.log = '$this->login'")->fetch(PDO::FETCH_ASSOC);

    }
}