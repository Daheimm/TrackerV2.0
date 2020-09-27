<?php


namespace App\Controllers;

use App\Models\Logs as modelLogs;

use Core\View;

class Logs extends \Core\Controller
{
    protected $modelLogs;

    public function __construct()
    {
        if (array_key_exists('login', $_POST)) $this->modelLogs = new modelLogs($_POST["login"]);
        else $this->modelLogs = new modelLogs($_SESSION["user"]);
    }

    public function indexAction()
    {
        View::renderTemplate('Home/logs.html');
    }

    public function getAllLogins()
    {
        echo json_encode($this->modelLogs->getAllUser());
    }

    public function getAllCompany()
    {

        if (array_key_exists('login', $_POST)) {
            echo json_encode($this->modelLogs->getAllCompany($_POST["login"]));
        }
    }

    public function getAllDataFromClient()
    {

        $_GET["start"] = array_key_exists("start", $_GET) ? $_GET["start"] : "";
        $_GET["end"] = array_key_exists("end", $_GET) ? $_GET["end"] : "";

        $this->modelLogs->getAllDataFromClient($_GET["company"], $_GET["start"], $_GET["end"]);

        echo json_encode($this->modelLogs->successful);
    }

    public function headerTable()
    {
        $this->modelLogs->headerTable($_POST["company"]);
        echo json_encode($this->modelLogs->successful);
    }
}