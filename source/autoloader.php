<?php



spl_autoload_register(function ($class) {
    
  
   
    //$file = realpath($_SERVER["DOCUMENT_ROOT"]) . '/../framework/libs/' . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
    //look in libs
    
    $folders = array(

        'libs',
        'Controllers',
        'Views'
        );
    
    foreach ($folders as $folder) {
        $file = realpath($_SERVER["DOCUMENT_ROOT"]) . '//..//' . $folder . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($file)) {

            require_once $file;
            break;
        }
    }
});