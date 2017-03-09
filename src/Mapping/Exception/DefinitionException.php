<?php

namespace Sway\Distribution\Mapping\Exception;

class DefinitionException extends \Exception
{
    /**
     * Throws an exception when passed argument is not object
     * @return \Sway\Distribution\Mapping\Exception\DefinitionException
     */
    public static function isNotAnObject() : DefinitionException
    {
        return (new DefinitionException("Passed argument is not an object"));
    }
}



?>
