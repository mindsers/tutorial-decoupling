<?php
namespace Config\Configurators;

use Config\Contracts\Configurator as IConfig;

class BaseConfig implements IConfig
{
    private $pdo;
    private $data;

    public function __construct($config)
    {
        if (!is_array($config)
            || !isset($config['hostname'])
            || !isset($config['basename'])
            || !isset($config['username'])
            || !isset($config['passwd'])) {
            throw new \Exception("Need authentification parameters", 1);
        }

        $this->data = [];
        $this->pdo = new \PDO(
            'mysql:host='.$config['hostname'].';'.
            (isset($config['port']) ? 'port='. $config['port'] . ';' : '') .
            'dbname='.$config['basename'],
            $config['username'],
            $config['passwd']
        );

        $this->reloadDB();
    }

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            return '';
        }

        return $this->data[$key];
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function save()
    {
        $this->writeDB();
        $this->reloadDB();
    }

    private function reloadDB()
    {
        $query = $this->pdo->prepare(
            'SELECT `key`, `value` FROM config',
            array(\PDO::ATTR_CURSOR, \PDO::CURSOR_SCROLL)
        );

        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_LAST)) {
            $this->data[$row['key']] = $row['value'];
        }
    }

    private function writeDB()
    {
        $query = $this->pdo->prepare(
            'INSERT INTO config (`key`, `value`) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE `key` = :key, `value` = :value;'
        );

        foreach ($this->data as $key => $value) {
            $query->execute([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
}
