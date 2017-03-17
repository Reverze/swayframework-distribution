<?php

namespace Sway\Distribution\Storage\File\Exception;


class FileException extends \Exception
{
    /**
     * Throws an exception when error occured while create file handler
     * @param string $filePath
     * @return \Sway\Distribution\Storage\File\Exception\FileException
     */
    public static function fileHandlerException(string $filePath) : FileException
    {
        return (new FileException(sprintf("Error occured while create file handler for '%s'", $filePath)));     
    }
    
    /**
     * Throws an exception while trying to remove non-existing file
     * @param string $filePath
     * @return \Sway\Distribution\Storage\File\Exception\FileException
     */
    public static function tryToRemoveNonExistingFile(string $filePath) : FileException
    {
        return (new FileException("Trying to remove non-existing file on path '%s'", $filePath));
    }
    
    /**
     * Throws an exception while file unlink failed
     * @param string $filePath
     * @return \Sway\Distribution\Storage\File\Exception\FileException
     */
    public static function fileUnlinkFailed(string $filePath) : FileException
    {
        return (new FileException("Error occured while unlink file on path '%s'", $filePath));
    }
}


?>