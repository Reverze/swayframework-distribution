<?php

namespace Sway\Distribution\Configuration\Exception;

class ConfigurationManagerException extends \Exception
{
    /**
     * Throws an exception when file configuration use unsupported configuration format
     * @param string $fileExtension
     * @param string $filePath
     * @return \Sway\Distribution\Configuration\Exception\ConfigurationManagerException
     */
    public static function unsupportedConfigurationFileExtension(string $fileExtension, string $filePath) : ConfigurationManagerException
    {
        return (new ConfigurationManagerException(sprintf("Extension '%s' is not supported. File: '%s'", $fileExtension, $filePath)));
    }
}


?>
