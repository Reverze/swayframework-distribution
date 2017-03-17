<?php

namespace Sway\Distribution\Extension\Exception;

class ExtensionException extends \Exception
{
    /**
     * Throws an exception when class which is used to initialize extension is not extends PackageExtension class
     * @param string $className
     * @return \Sway\Distribution\Extension\Exception\ExtensionException
     */
    public static function invalidExtensionClassInstance(string $className) : ExtensionException
    {
        return (new ExtensionException("Class '%s' which inits extension must extends '\Sway\Distribution\Extension\PackageExtension' class", $className));
    }
}


?>
