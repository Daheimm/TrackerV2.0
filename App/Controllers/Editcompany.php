<?php


namespace App\Controllers;

use Core\View;
use App\Models\editCompany as modelEditCompany;

class Editcompany extends \Core\Controller
{
    protected $modelEditCompany;
    protected $company;

    public function __construct()
    {
        if (array_key_exists('company', $_POST)) {
            $this->company = $_POST["company"];
            $this->modelEditCompany = new modelEditCompany($_SESSION["user"], $this->company);
        } else if (array_key_exists('company', $_GET)) {

            $this->company = $_GET["company"];
            $this->modelEditCompany = new modelEditCompany($_SESSION["user"], $this->company);
            $this->getDataSelectedClient();
        } else {

        }
    }

    public function indexAction()
    {
        $this->modelEditCompany->getSelectedClient();
        $buffer = $this->modelEditCompany->res;
        $this->modelEditCompany->getPath();
        $path = "<script src='https://analitics.fun/" . $this->modelEditCompany->path . "</script>";
        $buffer["language"] = explode(',', $buffer["language"]);
        $buffer["targetCountry"] = array_key_exists("targetCountry", $buffer) ? $buffer["targetCountry"] = explode(',', $buffer["targetCountry"]) : "not";
        $buffer["pathTarget"] = array_key_exists("pathTarget", $buffer) ? $buffer["pathTarget"] : "not";
        $buffer["domain"] = array_key_exists("domain", $buffer) ? $buffer["domain"] : "not";
        $buffer["getKeys"] = array_key_exists("getKeys", $buffer) ? $buffer["getKeys"] : "not";

        $clients[] = $_SESSION['user'] == 'dimaakimov528@gmail.com' ? true : false;


        $res = array('lng' => $buffer["language"],
            'location' => $buffer["targetCountry"],
            "path" => $path,
            "pathTarget" => $buffer["pathTarget"],
            "domain" => $buffer["domain"],
            "getKeys" => $buffer["getKeys"],
            "clients" => $clients);

        View::renderTemplate('Home/company-edit.html', ["posts" => $res]);
    }

    public function sendDataEditCompany()
    {
        $this->modelEditCompany->insertDataCompany($_POST['lng'], $_POST['location'], $_POST['targetPage'], $_POST['botPage'], $_POST['staticKey']);

    }

    public function getDataSelectedClient()
    {
        $this->modelEditCompany->getSelectedClient();
        $buffer = $this->modelEditCompany->res;
        $this->modelEditCompany->getPath();
        $path = $this->modelEditCompany->path;
        $res = array('lng' => $buffer["language"], 'location' => $buffer["targetCountry"], "path" => $path);

    }
}