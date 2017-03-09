<?php

namespace Sway\Distribution\Configuration\Exception;

class JsonReaderException extends \Exception
{
    /**
     * Throws an exception when read json file failed
     * @param string $filePath
     * @return \Sway\Distribution\Configuration\Exception\JsonReaderException
     */
    public static function readJsonFailed(string $filePath) : JsonReaderException
    {
        return (new JsonReaderException(sprintf("Cannot read file on path '%s'", $filePath)));
    }
}

?>

