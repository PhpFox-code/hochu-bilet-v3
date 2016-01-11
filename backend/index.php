<?php
    ini_set('display_errors', 'on'); // Display all errors on screen
    error_reporting(E_ALL & ~E_NOTICE);
    header("Cache-Control: public");
    header("Expires: " . date("r", time() + 3600));
    header('Content-Type: text/html; charset=UTF-8');
    ob_start();
    @session_start();

    define('HOST', str_replace('backend', '', dirname(__FILE__))); // Root path
    define('APPLICATION', 'backend'); // Choose application - backend or frontend. If frontend - set ""
    define('PROFILER', FALSE); // On/off profiler
    define('START_TIME', microtime(TRUE)); // For profiler. Don't touch!
    define('START_MEMORY', memory_get_usage()); // For profiler. Don't touch!

    // Uncomment next row if multilang needed
    // require_once 'Plugins/I18n/I18n.php';
    // Autoload
    function autoload($className) {
        // If I18n
        $arr = explode('\\', $className);
        if (end($arr) == 'I18n') { return; }
        // else
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
        require '../'.$fileName;
    }
    spl_autoload_register('autoload');

    Core\Route::factory()->execute();
    Plugins\Profiler\Profiler::view();
