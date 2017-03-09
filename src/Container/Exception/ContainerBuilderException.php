<?php

namespace Sway\Distribution\Container\Exception;

class ContainerBuilderException extends \Exception
{
    /**
     * Throws an exception when directory was not found
     * @param string $directoryPath
     * @return \Sway\Distribution\Container\Exception\ContainerBuilderException
     */
    public static function directoryNotFound(string $directoryPath) : ContainerBuilderException
    {
        return (new ContainerBuilderException(sprintf("Directory was not found on path '%s'", $directoryPath)));
    }
}


?>