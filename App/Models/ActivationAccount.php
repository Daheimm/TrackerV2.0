<?php


namespace App\Models;
use PDO;
use App\ConfigClient;

class ActivationAccount extends \Core\Model
{
    protected $link;
    protected $login;
    protected $nameDataBase;
    protected $password;
    protected $user;
    protected $configClient;
    protected $customerId;


    public function __construct($login)
    {
        $this->customerId = $this->valueGenerator();
        $this->link = static::getDB();
        $this->login = $login;
        $this->configClient = new ConfigClient();
    }

    public function bootstrap()
    {
        $this->createMetaData();
        $this->makeRequest("create user '$this->user'@localhost identified by '$this->password'");
        $this->makeRequest("Create database $this->nameDataBase");

        $this->makeRequest("Update LogAndPass set `dataBase` = '$this->nameDataBase', 
                                                        user = '$this->user',
                                                        password = '$this->password',
                                                        identifier = '$this->customerId',
                                                        activation = 1  
                                                        Where log = '$this->login'");

        $this->makeRequest("grant all privileges on $this->nameDataBase.* to digital@'%'");
        $this->makeRequest("grant all privileges on $this->nameDataBase.* to " . $this->user . "@localhost");

        $this->connectCLient();
        $this->link = static::getDBClient($this->configClient);


        $this->makeRequest("create table Setting(id int auto_increment primary key,
                                                    targetCountry text,
                                                    language text,
                                                    nameCompany text,
                                                    pathTarget text,
                                                    domain text,
                                                    getKeys text);");

        $this->makeRequest("INsert Into Setting(targetCountry,language) values('empty','empty')");

        $this->makeRequest("CREATE Table BlackList(id int AUTO_INCREMENT PRIMARY key NOT null,ip text, description text)");
    }

    private function connectCLient()
    {
        $this->configClient->setUser($this->user);
        $this->configClient->setPass($this->password);
        $this->configClient->setDatabase($this->nameDataBase);
        $this->configClient->setHost("localhost");
    }


    private function createMetaData()
    {
        $this->nameDataBase = (explode("@", $this->login))[0];
        $this->user = (explode("@", $this->login))[0];
        $this->password = $this->link->query("Select pas From LogAndPass Where log = '$this->login'")->fetch();
        $this->password = $this->password["pas"];
    }

    private function valueGenerator()
    {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = 25;
        $size = StrLen($chars) - 1;
        $random = null;
        while ($max--)
            $random .= $chars[rand(0, $size)];
        return $random;
    }

    private function makeRequest($sql)
    {
        return $this->link->query($sql);
    }

}