<?php
namespace Container;

class Container
{
    private $services;
    private $alias;

    public function __contruct()
    {
        $this->services = [];
        $this->alias = [];
    }

    public function addDep($name, $service = [])
    {
        $this->services[$name] = $service;
    }

    public function getDep($name)
    {
        $serviceName = $name;
        if (isset($this->alias[$serviceName])) {
            $serviceName = $this->alias[$serviceName];
        }

        if (!isset($this->services[$serviceName])) {
            throw new \Exception("Service " . $serviceName . " doesn't exist", 1);
        }

        $args = [];
        foreach ($this->services[$serviceName]['parameters'] as $key => $value) {
            if (is_string($value) && $this->hasDep($value)) {
                $args[] = $this->getDep($value);
                continue;
            }

            $args[] = $value;
        }

        $reflected = new \ReflectionClass($this->services[$serviceName]['class']);
        return $reflected->newInstanceArgs($args);
    }

    public function hasDep($name)
    {
        return isset($this->services[$name]) || isset($this->alias[$name]);
    }

    public function setAlias($alias, $name)
    {
        if (isset($this->services[$alias])) {
            throw new \Exception("Service already exist with this name", 1);
        }

        $this->alias[$alias] = $name;
    }
}
