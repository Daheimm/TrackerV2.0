<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/Filter/FacebookIFrame/Handler.php");
$handler = new Handler();

if ($handler->getData() == 'true') {

    $content = file_get_contents("pathFile");
    echo str_replace("site", $handler->path, $content);
} else
    echo 'console.log("ничего нету")';

    ?>