<?php


namespace App\Models;

use PDO;
use App\ConfigClient;
use DateTime;

class Statistics extends \Core\Model
{
    protected $login;
    protected $page;
    protected $dateTO;
    protected $dataFrom;
    protected $nameCompany;
    protected $link;
    protected $linkClient;
    protected $conClient;
    protected $setting;
    protected $con;
    protected $count;
    protected $sql;
    public $res;
    protected $array;
    protected $UIquantityTrue;
    protected $GeneralquantityTrue;
    protected $UIquantityFalse;
    protected $GeneralquantityFalse;
    protected $Where;
    protected $startDate;
    protected $endDate;
    protected $clickBot;
    protected $clickTarget;
    protected $currentHandler;
    protected $diffDate;
    protected $countDate;
    protected $datatable;
    protected $row;


    public function __construct($company = 'empty', $login)
    {

        $this->login = $login;
        $this->conClient = new ConfigClient();
        $this->link = static::getDB();
        $this->getDataClient();
        $this->linkClient = static::getDBClient($this->conClient);
        $this->con = 0;
        $this->count = 0;

        if ($company != false) {
            $this->nameCompany = $company;
        }


    }

    private function row_Column()
    {
        $this->row = (int)$this->linkClient->query("Select count(*) From $this->nameCompany")->fetch(PDO::FETCH_ASSOC)["count(*)"];
    }


    public function diagramTraffic()
    {


        $desctop = (int)$this->linkClient->query("select sum(w) from
    (SELECT count(platform) as w from $this->nameCompany Where platform = 'Windows' $this->Where
    union
    select count(platform)  as l from $this->nameCompany Where platform = 'Linux' $this->Where
    union
    select count(platform)  as l from $this->nameCompany Where platform = 'Macintosh' $this->Where) as v")->fetch(PDO::FETCH_ASSOC)["sum(w)"];

        $mobile = (int)$this->linkClient->query("select sum(w) from
      (SELECT count(platform) as w from $this->nameCompany Where platform = 'Android' $this->Where
        union
        select count(platform)  as l from $this->nameCompany  Where platform = 'Iphone' $this->Where
        ) as v")->fetch(PDO::FETCH_ASSOC)["sum(w)"];

        $this->res = array("mobile" => $mobile,
            "desctop" => $desctop);

    }

    public function time($start, $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;

        if (!empty($start) && !empty($end)) {
            $this->startDate = DateTime::createFromFormat('m.d.Y', $start)->format('Y-m-d');

            $this->endDate = DateTime::createFromFormat('m.d.Y', $end)->format('Y-m-d');

            $this->Where = "and Date(date) >= Date('$this->startDate') and Date(date) <= date('$this->endDate')";

        } else {
            $this->currentHandler = false;
            $this->Where = "and  DATE(date) = CURRENT_DATE()";

        }

    }

    public function botAndTarget()
    {

        $this->startDate = new DateTime($this->startDate);
        $this->endDate = new DateTime($this->endDate);
        $diffDate = $this->startDate->diff($this->endDate)->days;
        $date = $this->startDate->format('Y-m-d');
        if ($diffDate != false) {
            for ($i = 0; $i <= $diffDate; $i++) {

                if ($this->startDate <= $this->endDate) {

                    $this->clickTarget[] = (int)$this->linkClient->query("select count(*) from $this->nameCompany Where Date(date) = Date('$date') and ViewBlock = 'true'")->fetch(PDO::FETCH_ASSOC)["count(*)"];

                    $this->clickBot[] = (int)$this->linkClient->query("select count(*) from $this->nameCompany Where Date(date) = Date('$date') and ViewBlock = 'false'")->fetch(PDO::FETCH_ASSOC)["count(*)"];
                    $this->countDate[] = $this->linkClient->query("select date from $this->nameCompany Where Date(date) = Date('$date')")->fetch(PDO::FETCH_ASSOC)["date"];
                    $this->res = array("target" => $this->clickTarget, "bot" => $this->clickBot, "date" => $this->countDate);
                }
                $date = $this->startDate->modify('+1 day')->format('Y-m-d');
            }
        } else {
            $sqlDate = null;
            $time = null;
            if ($this->startDate->format("Y-m-d") == date("Y-m-d")) {
                $sqlDate = 'CURRENT_DATE()';
            } else {
                $date = $this->startDate->format('Y-m-d');
                $sqlDate = "Date('$date')";
            }

            for ($i = 0; $i <= 23; $i++) {
                $this->clickTarget[] = (int)$this->linkClient->query("select Count(*) from $this->nameCompany Where Date(date) = $sqlDate and ViewBlock = 'true' and Hour(time) = $i")->fetch(PDO::FETCH_ASSOC)["Count(*)"];
                $this->clickBot[] = (int)$this->linkClient->query("select Count(*) from $this->nameCompany Where Date(date) = $sqlDate and ViewBlock = 'false' and Hour(time) = $i")->fetch(PDO::FETCH_ASSOC)["Count(*)"];
                $time[] = $this->linkClient->query("select Hour(time) from $this->nameCompany Where Date(date) = $sqlDate  and ViewBlock = 'false' and Hour(time) = $i")->fetch(PDO::FETCH_ASSOC)["Hour(time)"];

            }
            $this->res = array("target" => $this->clickTarget, "bot" => $this->clickBot, "time" => $time);


        }


    }


    private
    function getDataClient()
    {

        $this->setting = ($this->link->query("Select USER_ID,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch();

        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->conClient->setHost("localhost");
        $this->user_id = $this->setting["USER_ID"];

    }

    public function currentDay()
    {

        $this->getDataClient();
        $this->linkClient = static::getDBClient($this->conClient);
        $this->res = $this->linkClient->query("Select * From $this->company Where DATE(date) = CURRENT_DATE()")->fetchAll(PDO::FETCH_ASSOC);


    }


    public
    function listCompany()
    {
        $listCompany = $this->link->query("Select nameCompany From NameCompany Where USER_ID = (Select id From LogAndPass Where log = '$this->login')")->fetchAll(PDO::FETCH_ASSOC);
        return $listCompany;
    }

    private function str_replace_once($search, $replace, $text)
    {
        $pos = strpos($text, $search);
        return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
    }

    public function datatable()
    {
        $this->row_Column();

        if ($this->row >= 1) {
            if ($this->login == 'dimaakimov528@gmail.com') {
                $this->Where = $this->str_replace_once('and', 'Where', $this->Where);

                $this->datatable = $this->linkClient->query("Select date,time,ViewBlock,ip,geoCity,geoContry,geoContinent,geoLocation,lng,
                                                               geoAsn,browser,platform,version,usageType,isp,reason,zip,domain,generalAnswer 
                                                   from $this->nameCompany 
                                                    $this->Where Order BY date desc,time desc")->fetchAll(PDO::FETCH_ASSOC);

                $buffer = [];
                foreach ($this->datatable as $value) {
                    $buffer[] = array_values($value);
                }
                $this->res = array("data" => $buffer);
            } else {
                $this->Where = $this->str_replace_once('and', 'Where', $this->Where);

                $this->datatable = $this->linkClient->query("Select date,time,ViewBlock,ip,geoCity,geoContry,geoContinent,geoLocation,lng,
                                                               geoAsn,browser,platform,version,usageType,isp,domain,getKeys
                                                   from $this->nameCompany 
                                                    $this->Where Order BY date desc,time desc")->fetchAll(PDO::FETCH_ASSOC);

                $buffer = [];
                foreach ($this->datatable as $value) {
                    $buffer[] = array_values($value);
                }
                $this->res = array("data" => $buffer);
            }
        } else {
            $this->res = array("data" => "");
        }

    }

    public function ClickTargetandBot()
    {
        $withhoutAnd = $this->str_replace_once('and', '', $this->Where);

        $this->res = $this->linkClient->query("
        Select count(*) as general From $this->nameCompany Where $withhoutAnd
            union all 
        Select count(distinct ip) as general From $this->nameCompany Where $withhoutAnd
            union all
        Select count(*) as target  From $this->nameCompany Where ViewBlock='true' $this->Where
            union all
        Select count(distinct ip) as block  From $this->nameCompany Where ViewBlock='true' $this->Where
            union all
        Select count(*) as targetClick  From $this->nameCompany Where ViewBlock='false' $this->Where
            union all
        Select count(distinct ip) as blockClick  From $this->nameCompany Where ViewBlock='false' $this->Where
        ")->fetchAll(PDO::FETCH_ASSOC);

    }
}