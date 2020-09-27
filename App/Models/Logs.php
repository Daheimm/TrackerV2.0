<?php


namespace App\Models;

use PDO;
use App\ConfigClient;
use DateTime;

class Logs extends \Core\Model
{
    protected $db;
    protected $setting;
    protected $conClient = [];
    protected $dbClient;
    public $successful;
    protected $login;
    protected $startDate;
    protected $endDate;
    protected $Where;


    public function __construct($login)
    {
        $this->db = static::getDB();
        $this->login = $login;
        $this->conClient = new ConfigClient();
        $this->getDataClient();
        $this->dbClient = static::getDBClient($this->conClient);
    }

    public function getAllUser()
    {
        return $this->db->query("Select log from LogAndPass")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCompany($login)
    {
        $id = (int)$this->db->query("Select id From LogAndPass Where log = '$login'")->fetch(PDO::FETCH_ASSOC)["id"];
        return $this->db->query("Select nameCompany from NameCompany Where USER_ID = '$id'")->fetchAll(PDO::FETCH_ASSOC);
    }


    private function getDataClient()
    {

        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);
        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->user_id = $this->setting["id"];

    }

    public function getAllDataFromClient($company, $start, $end)
    {
        $buffer = [];
        $this->time($start, $end);
        $this->successful = $this->dbClient->query("Select * From $company $this->Where")->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($this->successful)) {

            foreach ($this->successful as $value) {

                $buffer[] = array_values($value);
            }
        } else {
            $buffer = 'not';
        }

        $this->successful = array("data" => $buffer);
    }

    public function time($start, $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;

        if (!empty($start) && !empty($end)) {

            $this->startDate = DateTime::createFromFormat('m/d/Y', $start)->format('Y-m-d');

            $this->endDate = DateTime::createFromFormat('m/d/Y', $end)->format('Y-m-d');

            $this->Where = "Where Date(date) >= Date('$this->startDate') and Date(date) <= date('$this->endDate')";

        } else {
            $this->currentHandler = false;
            $this->Where = "Where DATE(date) = CURRENT_DATE()";
        }

    }

    public function headerTable($company)
    {
        $buffer = [];
        $this->successful = $this->dbClient->query("SELECT COLUMN_NAME
                                          FROM INFORMATION_SCHEMA.COLUMNS
                                          WHERE TABLE_NAME='$company'")->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($this->successful)) {

            foreach ($this->successful as $value) {

                $buffer[] = array_values($value);
            }
        } else {
            $buffer = 'not';
        }

        $this->successful = $buffer;
    }
}