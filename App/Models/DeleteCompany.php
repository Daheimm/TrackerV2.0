<?php


namespace App\Models;

use App\ConfigClient;
use PDO;

class DeleteCompany extends \Core\Model
{
    protected $db;
    protected $dbClient;
    protected $idCompany;
    private $login;
    private $nameCompany;
    protected $setting;
    protected $user_id;
    protected $conClient;
    public $isResult;
    public $result;

    public function __construct($company, $login)
    {


        $this->login = $login;
        $this->nameCompany = $company;
        $this->db = static::getDB();
        $this->conClient = new ConfigClient();
        $this->getDataClient();
        $this->dbClient = static::getDBClient($this->conClient);
        $this->work();

    }


    private function work()
    {
        $this->handlerRequest();
    }


    private function handlerRequest()
    {
        try {

            $this->idCompany = $this->db->query("Select id From LogAndPass Where log = '$this->login'")->fetch(PDO::FETCH_ASSOC)["id"];

            $this->db->query("delete from NameCompany where nameCompany = '$this->nameCompany' && USER_ID = '$this->idCompany'");
            $this->dbClient->query("DROP TABLE $this->nameCompany");

            $countCompany = $this->db->query("Select company From LogAndPass Where log = '$this->login'")->fetch()["company"];
            $countCompany -= 1;
            $this->db->query("Update LogAndPass set company = $countCompany Where log = '$this->login'");

            $this->isResult = "Сompany deleted successfully";

        } catch (exception $e) {
            $this->isResult = "system error contact administrator";
        }
    }

    private function getDataClient()
    {
        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch();
        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->conClient->setHost("localhost");
        $this->user_id = $this->setting["id"];
    }
}