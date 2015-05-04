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

define('GENGO_BASE', dirname(__FILE__));

class Init
{
    public function __construct()
    {
        // We have created an error handler for convenience.
        // If you wish to use it, uncomment the following line.
        // set_error_handler(array($this, 'error_handler'));

        // include this api classpath
        $include_path = get_include_path() . PATH_SEPARATOR;
        $include_path .= GENGO_BASE .'/libs';
        set_include_path($include_path);

        // set our own class autoloader if one doesn't already exist
        if (false === spl_autoload_functions())
        {
            if (function_exists('__autoload'))
            {
                spl_autoload_register('__autoload');
            }
        }
        spl_autoload_register(array($this, 'autoload'));

        // set:
        // - internal character encoding
        mb_internal_encoding('UTF-8');
    }

    public function error_handler($errno, $errstr, $errfile = '', $errline = -1, $errctx = null)
    {
        $ex = new Gengo_Exception($errstr, $errno);
        $ex->setFile($errfile);
        $ex->setLine($errline);
        throw $ex;
    }

    /**
     * We use our own autoloader, but restricted to Gengo classes
     */
    protected static function autoload($classname)
    {
        if (false !== strpos($classname, 'Gengo') ||
            false !== strpos($classname, 'Zend_'))
        {
            $classpath = str_replace('_', '/', $classname) . '.php';
            $filepath = sprintf("%s/libs/%s", GENGO_BASE, $classpath);

            if (file_exists($filepath))
            {
                require_once $classpath;
            }
        }
    }
}
new Init();
