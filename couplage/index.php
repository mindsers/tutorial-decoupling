<?php
include_once 'Config/IniConfig.php';
include_once 'Controllers/IndexController.php';

use Controllers\IndexController;

$index = new IndexController('conf.ini')
$index->renderView();
