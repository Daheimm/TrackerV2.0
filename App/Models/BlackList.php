<?php


namespace App\Models;

use PDO;
use App\ConfigClient;

class BlackList extends \Core\Model
{
    protected $db;
    protected $setting;
    protected $conClient = [];
    protected $dbClient;
    public $successful;
    protected $blackIP = [];
    protected $login;

    public function __construct($ip,$login)
    {
        $this->db = static::getDB();
        $this->login = $login;
        $this->conClient = new ConfigClient();
        $this->getDataClient();
        $this->dbClient = static::getDBClient($this->conClient);
        $this->blackIP = $ip;
        $this->writeLocalDBIP();
    }

    private function writeLocalDBIP()
    {

        foreach ($this->blackIP as $ip) {
            $res = (int)$this->dbClient->query("Select count(*) From BlackList Where ip = '$ip'")->fetch(PDO::FETCH_ASSOC)["count(*)"];

            if ($res == 0)
                $this->dbClient->query("Insert Into BlackList(ip) values('$ip')");
        }
    }

    private function getDataClient()
    {

        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);
        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->user_id = $this->setting["id"];

    }
}