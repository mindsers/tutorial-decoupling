<?php

namespace Controllers;

use Config\Configurators\IniConfig;

/**
 * IndexController
 */
class IndexController
{
    private $iniConfig;

    public function __construct($configFile)
    {
        $this->iniConfig = new IniConfig($configFile);
    }

    public function renderView()
    {
        echo ' ['.$this->iniConfig->get('db.login').'] ';

        $this->iniConfig->set('db.login', 'aaa');
        $this->iniConfig->set('db.passwd', 'bbb');
        $this->iniConfig->save();

        echo ' ['.$this->iniConfig->get('db.login').'] ';
    }
}
