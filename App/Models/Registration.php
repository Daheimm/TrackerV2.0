<?php


namespace App\Models;

use PDO;

class Registration extends \Core\Model
{
    protected $db;
    protected $login;
    protected $password;
    protected $promo;
    protected $idNewUsers;
    protected $flag;
    protected $result;
    public $answer;
    public $isAnswer;


    public function __construct($login, $password, $promo)
    {
        $this->db = static::getDB();

        $this->flag = false;
        $this->login = $login;
        $this->password = $password;
        $this->promo = $promo;
        $this->registration();
    }

    private function registration()
    {

        $res = ($this->db->query("SELECT * From Core.PromoСode Where promo =  '$this->promo'"))->fetch(PDO::FETCH_ASSOC);

        if ($res["promo"] != null) {
            $log = $this->db->query("SELECT log FROM LogAndPass WHERE log = '$this->login'")->fetch(PDO::FETCH_ASSOC);

            if ($log["log"] == null) {
                $this->idNewUsers = $this->db->query("Select id From LogAndPass ORDER By id DESC Limit 1")->fetch(PDO::FETCH_ASSOC);
                $this->idNewUsers = ((int)$this->idNewUsers) + 1;
                $idPromo = (int)$res["id"];

                $date = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);

                $this->db->query("Insert Into LogAndPass(USER_ID,log,pas,idPacket,company,click,dates,activation,idPromo) Values ('$this->idNewUsers','$this->login','$this->password',1,0,0,'$date',0,$idPromo)");
                $this->isAnswer = true;
            } else {
                $this->answer = "Такой пользователь уже зарегестрирован!!!";
                $this->isAnswer = false;
            }
        } else {
            $this->answer = "Укажите действующий промо код!";
            $this->isAnswer = false;
        }
    }
}