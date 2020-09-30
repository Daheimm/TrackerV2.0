<?php


namespace App\Controllers;

use App\Models\BlackList as modelBlackList;
use Core\View;

class Blacklist extends \Core\Controller
{

    protected $blackIp = [];
    protected $mBlackList;

    public function __construct()
    {
        if (array_key_exists('blackIP', $_POST)) $this->blackIp = $_POST["blackIP"];

    }

    public function handlerBlackIp()
    {


        if (empty($this->blackIp)) {
            echo "Введите данные ip";
            exit();
        }

        $buffer = explode(',', $this->blackIp);

        if (is_array($buffer)) {
            $this->blackIp = null;
            for ($i = 0; $i < count($buffer); $i++) {
                if (filter_var($buffer[$i], FILTER_VALIDATE_IP)) {
                    $this->blackIp[] = trim($buffer[$i]);
                }
            }
            if ($this->blackIp != null) {
                $this->mBlackList = (new modelBlackList($this->blackIp, $_SESSION["user"]))->successful;
                if ($this->mBlackList) echo "Запись успешно добавленна";
                else echo "Невозможно добавит запись,обратитесь к администратору";
            } else
                echo "Некорректно введенные данные";

        } else {
            echo '<script type="text/javascript">alert("Не верный формат списка IP, используйте разделитель ', '!); document.location.href="?/registration";</script>';

        }
    }

    public function indexAction()
    {
        $clients[] = $_SESSION['user'] == 'dimaakimov528@gmail.com' ? array("clients" => true) : array("clients" => false);
        View::renderTemplate('Home/blacklist.html', ["posts" => $clients[0]]);
    }

}