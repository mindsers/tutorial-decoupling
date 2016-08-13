<?php
require_once 'Config/Contracts/Configurator.php';
require_once 'Config/BaseConfig.php';
require_once 'Config/IniConfig.php';
require_once 'Controllers/IndexController.php';
require_once 'Container/Container.php';

use Container\Container;

// Configuration des dépendances
$container = new Container();
$container->addDep('iniconf', [
    'class' => 'Config\Configurators\IniConfig',
    'parameters' => [
        'conf.ini'
    ]
]);

$container->addDep('dbconf', [
    'class' => 'Config\Configurators\BaseConfig',
    'parameters' => [
        [
            'hostname' => 'localhost',
            'port' => 8889,
            'basename' => 'test_decouplage',
            'username' => 'toor',
            'passwd' => ''
        ]
    ]
]);

$container->addDep('index', [
    'class' => 'Controllers\IndexController',
    'parameters' => [
        'configurator'
    ]
]);

// Configuration des alias
$container->setAlias('configurator', 'dbconf');

// Utilisation
$index = $container->getDep('index');
$index->renderView();
