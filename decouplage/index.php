<?php
include_once 'Config/Contracts/Configurator.php';
include_once 'Config/BaseConfig.php';
include_once 'Config/IniConfig.php';
include_once 'Controllers/IndexController.php';

use Config\Configurators\IniConfig;
use Config\Configurators\BaseConfig;
use Controllers\IndexController;

$conf = new IniConfig('conf.ini');
// $conf = new BaseConfig([
//     'hostname' => 'localhost',
//     'port' => 8889,
//     'basename' => 'test_decouplage',
//     'username' => 'toor',
//     'passwd' => ''
// ]);

$index = new IndexController($conf);
$index->renderView();
