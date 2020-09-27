<?php


namespace App\Models;

use PDO;
use ZipArchive;
use App\ConfigClient;

define("_StackPathForChange_", "/poolForZipFile/stack/");
define("_domain_", "https://analitics.fun/");
define("_pathToFileForChangeContent_", "/var/www/html/");
define("_ForDowloand_", "/var/www/html/poolJS/");

class Dowloand extends \Core\Model
{
    protected $json;
    protected $login;
    protected $nameCompany;
    protected $db;
    protected $dbClient;
    protected $conClient;
    protected $setting;
    protected $user_id;
    protected $identifier;
    protected $path;
    protected $nameJSCompany;
    protected $onlyHeaderJS;
    protected $fileFB;
    protected $fileAD;
    protected $zip;
    protected $nameZipArchive;


    public function __construct($company,$login)
    {


        $this->login = $login;
        $this->nameCompany = $company;
        $this->db = static::getDB();

        $this->zip = new ZipArchive();
        $this->bootstrap();


    }

    private function bootstrap()
    {
        $this->getDataClient();
        $this->nameZipArchive = $this->valueGenerator(5);
        $this->identifier = $this->db->query("Select identifier From LogAndPass Where log ='$this->login'")->fetch()["identifier"];
        $buffer = $this->db->query("Select path,nameJSCompany,onlyHeaderJS,fileFB,fileAD From NameCompany Where nameCompany = '$this->nameCompany' and USER_ID = '$this->user_id'")->fetch();
        $this->nameJSCompany = $buffer["nameJSCompany"] . ".js";
        $this->onlyHeaderJS = $buffer["onlyHeaderJS"] . ".js";
        $this->fileAD = $buffer["fileAD"] . ".php";
        $this->fileFB = $buffer["fileFB"] . ".php";
        $this->path = $buffer["path"];
        $this->poolChangeContent();
        $this->zipFiletoArchive();
        //$this->linkClient = new MySql($this->conClient);


    }

    private function getDataClient()
    {
        $this->conClient = new ConfigClient();
        $this->setting = ($this->db->query("Select id,`dataBase`,user,password From LogAndPass Where log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);
        $this->conClient->setDatabase($this->setting["dataBase"]);
        $this->conClient->setUser($this->setting["user"]);
        $this->conClient->setPass($this->setting["password"]);
        $this->user_id = $this->setting["id"];
    }

    public function getData()
    {
        return _domain_ . "poolJS/". $this->identifier . "/" . $this->path . "/" . $this->path . ".zip";
    }


    private function poolChangeContent()
    {
        $path = $this->identifier . "/" . $this->path;

        $this->changeLogAndPass(file_get_contents(_ForDowloand_ . $path . "/" . $this->fileFB), $this->fileFB, true);
        $this->changeLogAndPass(file_get_contents(_ForDowloand_ . $path . "/" . $this->fileAD), $this->fileAD, true);
        $this->changeLogAndPass(file_get_contents(_ForDowloand_ . $path . "/" . $this->nameJSCompany), $this->onlyHeaderJS, false);


    }

    private function changeLogAndPass($content, $file, $flag)
    {
        if ($flag == true) {
            $content = str_replace("companyID", $this->path, $content);
            file_put_contents(_ForDowloand_ . $this->identifier . "/" . $this->path . "/" . $file, $content);
            $content = str_replace("namecompany", $this->nameCompany, $content);
            file_put_contents(_ForDowloand_ . $this->identifier . "/" . $this->path . "/" . $file, $content);
            $content = str_replace("namelogin", $this->login, $content);
            file_put_contents(_ForDowloand_ . $this->identifier . "/" . $this->path . "/" . $file, $content);
        } else if ($flag == false) {
            $content = str_replace('pathFile', "/poolJS/" . $this->identifier . "/" . $this->path . "/" . $file, $content);
            file_put_contents(_ForDowloand_ . $this->identifier . "/" . $this->path . "/" . $this->nameJSCompany, $content);
        }
    }

    private function zipFiletoArchive()
    {
        if ($this->zip->open(_ForDowloand_ . $this->identifier . "/" . $this->path . "/" . $this->path . ".zip", ZipArchive::OVERWRITE | ZipArchive::CREATE)) {
            {
                $this->zip->addFile(_ForDowloand_ . "$this->identifier" . "/" . $this->path . "/" . $this->fileFB, "Facebook/" . "/" . $this->fileFB);
                $this->zip->addFile(_ForDowloand_ . "$this->identifier" . "/" . $this->path . "/" . $this->fileAD, "Adwords/" . $this->fileAD);
                $this->zip->addFile(_ForDowloand_ . "$this->identifier" . "/" . $this->path . "/" . "pathJS.txt", "linkJS/" . "pathJS.txt");
                $this->zip->close();
            }
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

}