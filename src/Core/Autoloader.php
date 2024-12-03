<?php

namespace Core;
/**
 * Class Autoloader
 */
class Autoloader{

    /**
     * Enregistre notre autoloader
     */
    static function register(): void{
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     */
    static function autoload($fqcn): void{
        if (strpos($fqcn, __NAMESPACE__ . '\\') === 0){
            //$path = str_replace(__NAMESPACE__ . '\\', '', $fqcn);
            $path = str_replace('\\', '/', $fqcn);
            require __DIR__ . '/' . $path . '.php';
        }
    }

}