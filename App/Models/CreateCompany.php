<?php


namespace App\Models;
use App\ConfigClient;
use PDO;

define("_StackPathForChange_", "/var/www/html/poolForZipFile/stack/");
define("_StackPathForDowloand_", "/var/www/html/poolJS/");
define("_StackPathForContent_", $_SERVER["DOCUMENT_ROOT"] . "/poolForZipFile/file/");
define("_domain_", "https://analitics.fun/");

define("_TRIAL_", 1);  //Неограниченное число компаний и кликов на 14 дней
define("_BEGINER_Company_", 2); // 5 компаний неграничено колличество кликов
define("_PREMIUM_Company_", 3); // 10 компаний неграничено колличество кликов
define("_ENTERPRISE_Company_", 4); // 25 компаний неграничено колличество кликов
define("_ULTIMATE_Company_", 5); // 40 компаний неграничено колличество кликов
class CreateCompany extends \Core\Model
{
    protected $db;
    protected $login;
    protected $nameCompany;
    protected $id;
    protected $user_id;
    protected $sqlClient;
    protected $sqlCore;
    private $identifier;
    private $path;
    private $nameJSCompany;
    private $onlyHeaderJS;
    private $domain;
    private $setting;
    protected $dbClient;
    protected $facebook;
    protected $adwords;
    protected $countCompany;
    protected $sqlCoreUpdate;
    protected $answer;
    protected $conClient;


    public function __construct($login, $company)
    {
        $this->nameCompany = $company;
        $this->login = $login;


        $this->db = static::getDB();
        if ($this->numberOfCompanies()) {
            $this->conClient = new ConfigClient();
            $this->getDataClient();

            $this->dbClient = static::getDBClient($this->conClient);

            $this->init();
            $this->bootstrap();
        } else $this->answer = "Exceeding the limit of your company formation plan";

    }

    public function numberOfCompanies()
    {
        $buffer = $this->db->query("Select company,IdPacket From LogAndPass Where log = '$this->login'")->fetch(PDO::FETCH_ASSOC);

        $buffer["company"] += 1;
        $this->countCompany = $buffer["company"];


        switch ($buffer["IdPacket"]) {
            case _TRIAL_:
                return true;
            case _BEGINER_Company_:
                return intval($buffer["company"]) <= 5 ? true : false;

            case _PREMIUM_Company_:
                return intval($buffer["company"]) <= 10 ? true : false;

            case _ENTERPRISE_Company_:
                return intval($buffer["company"]) <= 25 ? true : false;

            case _ULTIMATE_Company_:
                return intval($buffer["company"]) <= 40 ? true : false;
            default:
                return true;

        }
    }

    private function bootstrap()
    {
        $count = $this->db->query("Select count(*) From NameCompany Where nameCompany = '$this->nameCompany' and USER_ID = '$this->user_id'")->fetch(PDO::FETCH_ASSOC);


        if ((int)$count["count(*)"] == 0) {
            $this->createFolderForClient();
            $this->createFIle();
            $this->sqlRequest();
            $this->makeRequest($this->sqlClient, true);
            $this->makeRequest($this->sqlCore, false);
            $this->makeRequest($this->sqlCoreUpdate, false);
            $this->answer = "Сompany established";
        } else {
            $this->link->insert("Insert Into LoggingProcess (logging) values('the function did not define the username or password')");
            $this->answer = "A company with the same name already exists";
        }
    }

    private function init()
    {
        $this->identifier = $this->db->query("Select identifier From LogAndPass Where id = '$this->user_id'")->fetch(PDO::FETCH_ASSOC)["identifier"];

        $this->path = $this->valueGenerator(10);
        $this->nameJSCompany = $this->valueGenerator(5);
        $this->onlyHeaderJS = $this->valueGenerator(5);
        $this->facebook = $this->valueGenerator(5);
        $this->adwords = $this->valueGenerator(5);

    }

    private function getDataClient()
    {

        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);
        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);

        $this->user_id = $this->setting["id"];

    }

    private function createFolderForClient()
    {
        try {

            if (!file_exists(_StackPathForChange_ . $this->identifier)) {
                mkdir(_StackPathForChange_ . $this->identifier, 0777);
            }
            if (!file_exists(_StackPathForDowloand_ . $this->identifier)) {
                mkdir(_StackPathForDowloand_ . $this->identifier, 0777);
            }
            mkdir(_StackPathForChange_ . $this->identifier . "/" . $this->path, 0777);
            mkdir(_StackPathForDowloand_ . $this->identifier . "/" . $this->path, 0777);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function valueGenerator($count)
    {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = $count;
        $size = StrLen($chars) - 1;
        $random = null;
        while ($max--)
            $random .= $chars[rand(0, $size)];
        return $random;
    }

    private function writeINFile($file, $content, $path)
    {
        $fp = fopen($path . "/" . $file, "w");
        fwrite($fp, $content);
        fclose($fp);
    }

    private function createFIle()
    {


        $this->domain = _domain_ . "/poolJS/" . $this->identifier . "/" . $this->path . "/" . $this->nameJSCompany . ".js";

        $this->writeINFile('pathJS.txt', "<script src= '$this->domain' </script>", _StackPathForDowloand_ . $this->identifier . "/" . $this->path);
        $this->writeINFile($this->nameJSCompany . ".js", file_get_contents(_StackPathForContent_ . "logiaclIFrame.js"), _StackPathForDowloand_ . $this->identifier . "/" . $this->path);
        $this->writeINFile($this->onlyHeaderJS . ".js", file_get_contents(_StackPathForContent_ . "IFrame.js"), _StackPathForDowloand_ . $this->identifier . "/" . $this->path);

        //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

        $this->writeINFile($this->facebook . ".php", file_get_contents(_StackPathForContent_ . "dowloandFB.php"), _StackPathForDowloand_ . $this->identifier . "/" . $this->path);
        $this->writeINFile($this->adwords . ".php", file_get_contents(_StackPathForContent_ . "dowloandAD.php"), _StackPathForDowloand_ . $this->identifier . "/" . $this->path);

    }

    private function makeRequest($sql, $client)
    {
        if ($client) {
            var_dump($sql);
            $this->dbClient->query($sql);
        } else {

            $this->db->query($sql);
        }

    }

    private function sqlRequest()
    {
        $this->sqlClient = "CREATE Table " . $this->nameCompany . "(id int AUTO_INCREMENT PRIMARY key NOT null,date date,time time,timeJS time,login varchar(200),company varchar(200),ip varchar(120),geoCity varchar(120),
			geoContry varchar(120),geoAsn text,geoContinent varchar(120),geoLocation varchar(120),geoAutonomSystem varchar(120),browser varchar(120),version varchar(120),platform varchar(120),reason text,
			usageType varchar(120),isp varchar(120),domain varchar(120),ViewBlock text,generalAnswer text,fingerprint text,timezone text,hasLiedLanguages text,hasLiedOs text,
			hasLiedBrowser text, lng varchar(200), referer text,user_agent text,zip varchar(120),getKeys text );";
        $this->sqlCore .= "Insert Into NameCompany (nameCompany,USER_ID,identifier,path,nameJSCompany,onlyHeaderJS,fileFB,fileAD) values('$this->nameCompany',$this->user_id,'$this->identifier','$this->path','$this->nameJSCompany','$this->onlyHeaderJS','$this->facebook','$this->adwords');";
        $this->sqlCoreUpdate = "Update LogAndPass set company = '$this->countCompany' Where log = '$this->login';";
    }

    public function getData()
    {
        return $this->answer;
    }
}