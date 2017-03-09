<?php

namespace Sway\Distribution\Container\Exception;

class RepositoryException extends \Exception
{
    /**
     * Throws an exception when configuration file was not found
     * @param string $filePath
     * @return \Sway\Distribution\Container\Exception\RepositoryException
     */
    public static function fileNotFound(string $filePath) : RepositoryException
    {
        return (new RepositoryException(spritnf("File not found on path '%s'", $filePath)));
    }
}


?>
