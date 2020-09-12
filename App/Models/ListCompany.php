<?php


namespace App\Models;

use App\ConfigClient;
use PDO;


class ListCompany extends \Core\Model
{

    protected $login;
    public $listCompany;
    private $db;
    protected $setting;
    protected $dbClient;
    protected $conClient;
    private $target;
    private $bot;
    public $parseListCompany;
    protected $path;


    public function __construct($login)
    {
        $this->login = $login;
        $this->db = static::getDB();
        $this->conClient = new ConfigClient();
        $this->getDataClient();
        $this->dbClient = static::getDBClient($this->conClient);



    }

    public function getListCompany()
    {
        $this->getCompany();
        $this->parseRequestSQLCompany();
    }

    private function getCompany()
    {
        $this->listCompany = $this->db->query("Select * From NameCompany Where User_ID = (Select id From LogAndPass Where log = '$this->login')");

    }

    private function parseRequestSQLCompany()
    {

        foreach ($this->listCompany as $row) {
            //var_dump("show columns from " . $row["nameCompany"] . " like 'HTTP_ORIGIN'");
            $company = $row["nameCompany"];
            if (!empty($this->dbClient->query("show columns from " . $row["nameCompany"] . " like 'HTTP_ORIGIN'")->fetch(PDO::FETCH_ASSOC))) {


                $this->domain[0] = ($this->dbClient->query("Select HTTP_ORIGIN From " . $row["nameCompany"] . " ORDER BY time desc LIMIT 1"))->fetch()["HTTP_ORIGIN"];

            } else {
                $this->domain[0] = "not domain";
            }
            $this->target = $this->dbClient->query("Select Count(*) from $company where ViewBlock = 'true' and DATE(date) = CURRENT_DATE()")->fetch(PDO::ATTR_AUTOCOMMIT)["Count(*)"];
            $this->bot = $this->dbClient->query("Select Count(*) from $company where ViewBlock = 'false' and DATE(date) = CURRENT_DATE()")->fetch(PDO::FETCH_ASSOC)["Count(*)"];
            $this->path = $this->dbClient->query("Select Count(*) from $company where ViewBlock = 'false' and DATE(date) = CURRENT_DATE()")->fetch(PDO::FETCH_ASSOC)["Count(*)"];
            $this->path = $this->dbClient->query("Select pathBlack,pathTarget from Setting where nameCompany = '$company'")->fetch(PDO::ATTR_AUTOCOMMIT);


            $targetCountry = $this->dbClient->query("Select targetCountry From Setting Where nameCompany = '$company'")->fetch()["targetCountry"];

            $this->parseListCompany[] = array(
                "id" => $row["id"],
                "nameCompany" => $row["nameCompany"],
                "domain" => $this->domain[0],
                "targetCountry" => $targetCountry,
                "target" => $this->target,
                "bot" => $this->bot,
                "pathBlack" => $this->path["pathBlack"],
                "pathTarget" => $this->path["pathTarget"]);
       }
    }

    private function getDataClient()
    {
        $this->setting = $this->db->query("Select `dataBase`,user,password From LogAndPass Where log = '$this->login'")->fetch(PDO::FETCH_ASSOC);

        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);

    }

}