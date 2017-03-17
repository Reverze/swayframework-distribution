<?php

namespace Sway\Distribution\Service\Exception;

class FrameworkServiceException extends \Exception
{
    /**
     * Throws an exception when variable is not exists
     * @param string $variableName
     * @return \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public static function variableNotExists(string $variableName) : FrameworkServiceException
    {
        return (new FrameworkServiceException(sprintf("Variable '%s' is not exists", $variableName)));
    }
    
    
}


?>
