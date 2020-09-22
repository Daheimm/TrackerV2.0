<?php


namespace App\Controllers;

use Core\View;
use App\Models\Statistics as model;

class Statistics extends \Core\Controller
{
    private $model;
    private $res;

    public function __construct()
    {
        if (array_key_exists("company", $_POST)) $this->model = new model($_POST["company"], $_SESSION["user"]);
        else if (array_key_exists("company", $_GET)) $this->model = new model($_GET["company"], $_SESSION["user"]);
        else $this->model = new model(false, $_SESSION["user"]);
    }

    public function indexAction()
    {
        $clients = $_SESSION['user'] == 'dimaakimov528@gmail.com'? true:false;

        View::renderTemplate('Home/statistics.html',["posts" => $clients]);
    }

    public function listCompany()
    {
        echo json_encode($this->model->listCompany());
    }

    public function listTraffic()
    {
        // var_dump($_POST);
        $this->model->time($_POST["start"], $_POST["end"]);
        $this->res = $this->model->botAndTarget();
        echo json_encode($this->model->res);
    }

    public function diagramTraffic()
    {
        // var_dump($_POST);
        $this->model->time($_POST["start"], $_POST["end"]);
        $this->model->diagramTraffic();
        echo json_encode($this->model->res);
    }

    public function datatable()
    {

        $_GET["start"] = array_key_exists("start", $_GET) ? $_GET["start"] : "";
        $_GET["end"] = array_key_exists("end", $_GET) ? $_GET["end"] : "";

        $this->model->time($_GET["start"], $_GET["end"]);
        $this->model->datatable();

        echo json_encode($this->model->res);
    }

    public function ClickTargetandBot()
    {
        $this->model->time($_POST["start"], $_POST["end"]);
        $this->model->ClickTargetandBot();
        echo json_encode($this->model->res);
    }
}