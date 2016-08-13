<?php

namespace Config\Contracts;

interface Configurator
{
    public function get($key);
    public function set($key, $value);
    public function save();
}
