<?php

namespace App\Models;

use PDO;

class Auth extends \Core\Model
{
    protected $db;
    protected $login;
    protected $pass;
    protected $idNewUsers;
    protected $flag;
    protected $result;
    public $answer;
    public $isAnswer;


    public function __construct($login, $password)
    {
        $this->db = static::getDB();

        $this->flag = false;
        $this->login = $login;
        $this->pass = $password;


        $this->VerifyUserOurDB();

    }

    private function VerifyUserOurDB()
    {

        $res = ($this->db->query("SELECT log FROM LogAndPass WHERE log = '$this->login'"))->fetch(PDO::FETCH_ASSOC);

        if ($res != null) {
            $res = ($this->db->query("SELECT count(*) FROM LogAndPass WHERE log = '$this->login' AND pas = '$this->pass'"))->fetch(PDO::FETCH_ASSOC);

            if ((int)$res == 1) {

                $activation = $this->db->query("Select activation From LogAndPass Where log = '$this->login' AND pas = '$this->pass'")->fetch(PDO::FETCH_ASSOC);

                if ((int)$res == 1) {
                    if ((int)$activation["activation"] == 1) $this->isAnswer = true;
                    else {
                        $this->answer = "Your Account has not yet been activated";
                        $this->isAnswer = false;
                    }
                } else {
                    $this->isAnswer = false;
                    $this->answer = "You must register, after which your account will be considered by the Administration";
                }
            } else {
                $this->answer = "You entered a valid password";
                $this->isAnswer = false;
            }
        } else {
            $this->answer = "Login is not correct or does not exist";
            $this->isAnswer = false;
        }
    }
}