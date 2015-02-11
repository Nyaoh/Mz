<?php

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
    
date_default_timezone_set('Europe/Paris');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library')
)));

require_once 'Zend/Application.php';
require_once 'Zend/Config.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Config/Json.php';
require_once 'Zend/Config/Writer.php';
require_once 'Zend/Config/Writer/Ini.php';

assert(version_compare(phpversion(), '5.3.16', '>'), 'Requiert au minimum la version 5.3.16 de PHP');

if (! is_file(APPLICATION_PATH . '/configs/cached_config.ini')) {
    $configApplication = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', null, array('allowModifications' => true));

    $iterator = new DirectoryIterator(APPLICATION_PATH . '/configs/conf.d');

    foreach ($iterator as $fileinfo) {
        if (! $fileinfo->isDot() && $fileinfo->isFile() && $fileinfo->isReadable() && $fileinfo->getExtension() == 'ini') {
            $tempConfig = new Zend_Config_Ini($fileinfo->getPathname());
            $configApplication->merge($tempConfig);
            unset($tempConfig);
        }
    }

    $writer = new Zend_Config_Writer_Ini(
        array(
            'config' => $configApplication,
            'filename' => APPLICATION_PATH . '/configs/cached_config.ini'
        )
    );
    
    $writer->write();
}

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/cached_config.ini'
);

$application->bootstrap()->run();
