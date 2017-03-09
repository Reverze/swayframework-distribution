<?php

namespace Sway\Distribution\Composer\Exception;

class EventHandlerException extends \Exception
{
    /**
     * Throws an exception when PSR-4 autoloader is not defined
     * @return \Sway\Distribution\Composer\Exception\EventHandlerException
     */
    public static function onlyPSR4Autoload() : EventHandlerException
    {
        return (new EventHandlerException("Only PSR-4 autoloader is supported"));
    }
}

?>

