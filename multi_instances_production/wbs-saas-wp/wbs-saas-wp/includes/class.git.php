<?php

namespace WBSSaaS;

/**
 * Static class WBSaaS\Git
 * 
 * @since 2.0.0
 * 
 */

class Git {

    public static $path = WBSSAAS_PLUGIN_DIR . DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR;

    public static function getHead(): string
    {
        return trim(substr(file_get_contents(self::$path . 'HEAD'), 4));
    }

    public static function getBranch(): string
    {
        $branch = explode( '/', self::getHead() );
        return $branch[2];
    }

    public static function getHash(): string
    {
        return trim( file_get_contents( sprintf( self::$path . self::getHead() ) ) );
    }

    public static  function getCommit(): string
    {
        return substr( self::getHash(), 0, 7);
    }

    public static function getDate(): string
    {
        return date( DATE_RFC2822, filemtime( self::$path . '/refs/heads/' . self::getBranch() ) );
    }

}