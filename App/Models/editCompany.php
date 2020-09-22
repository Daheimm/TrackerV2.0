<?php


namespace App\Models;

use App\ConfigClient;
use App\Config;

use PDO;

define("_StackPathForChange_", "/var/www/html/poolForZipFile/stack/");
define("_StackPathForDowloand_", "/var/www/html/poolJS/");
define("_OriginalFile_", "/var/www/html/poolForZipFile/file/");

class editCompany extends \Core\Model
{
    protected $login;
    protected $company;
    protected $conClient;
    protected $db;
    protected $dbClient;
    protected $setting;
    public $res;
    public $path;
    protected $USER_ID;
    protected $pathTarget;


    public function __construct($login, $company)
    {
        $this->login = $login;
        $this->company = $company;
        $this->conClient = new ConfigClient();
        $this->db = static::getDB();
        $this->getDataClient();
        $this->dbClient = static::getDBClient($this->conClient);
    }

    public function insertDataCompany($lng, $location, $pathTarget, $pathBlack, $keys)
    {
        $countCompany = null;
        $this->pathTarget = $pathTarget;
        if (($countCompany = ($this->dbClient->query("Select count(*) from Setting Where nameCompany = '$this->company'")->fetch(PDO::FETCH_ASSOC)["count(*)"])) == 0) {

            $this->dbClient->query("Insert Into Setting (language,targetCountry, nameCompany,pathTarget,pathBlack,domain,getKeys) 
                                             
                                              value ('$lng','$location','$this->company','$pathTarget','$pathBlack','$pathBlack','$keys')");


        } else if ($countCompany == 1) {

            $this->dbClient->query("Update Setting 
                                            set language = '$lng', 
                                            targetCountry  = '$location', 
                                            nameCompany = '$this->company',
                                            pathTarget = '$pathTarget',
                                            domain = '$pathBlack',
                                            getKeys = '$keys'
                                            Where nameCompany = '$this->company'");


        }

        $this->db->query("Update NameCompany set domain = '$pathBlack' Where USER_ID = $this->USER_ID and nameCompany = '$this->company'");
        $this->changeDataInJsFile();
    }

    private function changeDataInJsFile()
    {
        $res = $this->db->query("Select identifier,path,nameJSCompany,onlyHeaderJS
                                    From NameCompany
                                    Where nameCompany = '$this->company' and USER_ID = '$this->USER_ID'")->fetch(PDO::FETCH_ASSOC);

        $path = _StackPathForDowloand_ . $res["identifier"] . "/" . $res["path"];
        $pathToFile = ($path . "/" . $res["nameJSCompany"] . ".js");

        $pathOriginalContent = _OriginalFile_ ."logiaclIFrame.js";

        $content = file_get_contents($pathOriginalContent);
        $content = str_replace("pathFile", ($path . "/" . $res["onlyHeaderJS"] . ".js"), $content);
        file_put_contents($pathToFile, $content);

    }

    private function getDataClient()
    {
        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);

        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->conClient->setHost("localhost");
        $this->USER_ID = $this->setting["id"];

    }

    public function getSelectedClient()
    {
        $this->res = $this->dbClient->query("Select language,targetCountry, nameCompany,pathTarget,pathBlack,domain,getKeys From Setting Where nameCompany = '$this->company'")->fetch(PDO::FETCH_ASSOC);
    }

    public function getPath()
    {
        $pathServer = 'poolJS';
        $this->path = $this->db->query("SELECT identifier,path,nameJSCompany From NameCompany Where nameCompany = '$this->company'")->fetch(PDO::FETCH_ASSOC);

        $this->path = "{$pathServer}/{$this->path["identifier"]}/{$this->path["path"]}/{$this->path["nameJSCompany"]}.js";
    }
}