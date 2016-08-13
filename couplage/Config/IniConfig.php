<?php

namespace Config\Configurators;

class IniConfig
{
    private $ini;
    private $path;

    public function __construct($iniFile)
    {
        $this->path = $iniFile;
        $this->ini = parse_ini_file($iniFile, true);
    }

    public function get($key)
    {
        $keys = explode('.', $key);

        $data = $this->ini;
        foreach ($keys as $index => $value) {
            $data = isset($data[$value]) ? $data[$value] : null;
        }

        return $data;
    }

    public function set($key, $value)
    {
        $keys = explode('.', $key);

        if (count($keys) > 2) {
            throw new \Exception("Key can only have one nesting level", 1);
        }

        $this->ini = $this->rset($keys, $value, $this->ini);
    }

    private function rset($keys, $value, $array)
    {
        $allKeys = $keys;

        if (isset($array[$keys[0]]) && is_array($array[$keys[0]])) {
            $tmpAr = $array[$keys[0]];
            array_splice($keys, 0, 1);
            $array[$allKeys[0]] = $this->rset($keys, $value, $tmpAr);
        } else {
            if (isset($keys[1])) {
                array_splice($keys, 0, 1);
                $array[$allKeys[0]] = $this->rset($keys, $value, []);
            } else {
                $array[$keys[0]] = $value;
            }
        }

        return $array;
    }

    public function save()
    {
        $content = $this->makeIniString($this->ini);

        if (!$handle = fopen($this->path, 'w')) {
            return false;
        }

        $success = fwrite($handle, $content);
        fclose($handle);

        return $success;
    }

    private function makeIniString($array)
    {
        $string = "";

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $string .= '[' . $key . ']' . PHP_EOL;
                $string .= $this->makeIniString($value);
            } elseif ($value === "") {
                $string .= $key . ' = ' . PHP_EOL;
            } else {
                $string .= $key . ' = "' . $value . '"' . PHP_EOL;
            }
        }

        return $string;
    }
}
