<?php

namespace Sway\Distribution\Mapping\Exception;

class ClassFounderException extends \Exception
{
    /**
     * Throws an exception when search parameters are not defined
     * @return \Sway\Distribution\Mapping\Exception\ClassFounderException
     */
    public static function noneSearchParametersDefined() : ClassFounderException
    {
        return (new ClassFounderException(sprintf("None search parameters were defined")));
    }
    
    /**
     * Throws an exception when framework working directory path is not specifeid
     * @return \Sway\Distribution\Mapping\Exception\ClassFounderException
     */
    public static function frameworkWorkingDirectoryIsNotSpecifed() : ClassFounderException
    {
        return (new ClassFounderException("Framework's working directory path is not specified"));
    }
}

?>