<?php

namespace Controllers;

use Config\Contracts\Configurator as IConfig;

/**
 * IndexController
 */
class IndexController
{
    private $config;

    public function __construct(IConfig $configurator)
    {
        $this->config = $configurator;
    }

    public function renderView()
    {
        echo ' ['.$this->config->get('db.login').'] ';

        if ($this->config->get('db.login') === 'ddddd') {
            $this->config->set('db.login', 'aaaaa');
            $this->config->set('db.passwd', 'bbbbb');
        } else {
            $this->config->set('db.login', 'ddddd');
            $this->config->set('db.passwd', 'ooooo');
        }

        $this->config->save();
        echo ' ['.$this->config->get('db.login').'] ';
    }
}
