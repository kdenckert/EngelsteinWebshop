<?php

require_once '../classes/controller/Controller.php';
require_once '../classes/system/System.php';


$system = new System();
$controller = new Controller();
echo $controller->run();
