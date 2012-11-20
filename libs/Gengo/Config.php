<?php
/**
 * Gengo API Client
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that came
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL:
 * http://gengo.com/services/api/dev-docs/gengo-code-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@gengo.com so we can send you a copy immediately.
 *
 * @category   Gengo
 * @package    API Client Library
 * @copyright  Copyright (c) 2009-2012 Gengo, Inc. (http://gengo.com)
 * @license    http://gengo.com/services/api/dev-docs/gengo-code-license   New BSD License
 */

class Gengo_Config
{
    protected static $instance = null;
    protected $data;

    protected function __construct()
    {
        $configpath = GENGO_BASE . '/config.ini';
        if (file_exists($configpath))
        {
            $this->data = parse_ini_file($configpath);
        }
        else {
            $this->data = array();
        }
    }

    public function __get($name)
    {
        return $this->get($name, null);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        if (array_key_exists($name, $this->data))
        {
            return !empty($this->data[$name]);
        }
        return false;
    }

    public function __unset($name)
    {
        if (array_key_exists($name, $this->data))
        {
            unset($this->data[$name]);
        }
    }

    public function get($name, $default = null, $must_exists = false)
    {
        if (array_key_exists($name, $this->data))
        {
            if ($must_exists && empty($this->data[$name]))
            {
                throw new Gengo_Exception("Configuration field: {$name} is missing or empty.");
            }
            return $this->data[$name];
        }
        if ($must_exists)
        {
            throw new Gengo_Exception("Configuration field: {$name} is missing or empty.");
        }
        return $default;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
