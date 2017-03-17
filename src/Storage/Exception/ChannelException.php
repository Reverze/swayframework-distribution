<?php

namespace Sway\Distribution\Storage\Exception;

class ChannelException extends \Exception
{
    /**
     * Throws an exception when channel was not found 
     * @param string $channelName
     * @return \Sway\Distribution\Storage\Exception\ChannelException
     */
    public static function channelNotFound(string $channelName) : ChannelException
    {
        return (new ChannelException(sprintf("Channel '%s' was not found in storage", $channelName)));
    }
    
    /**
     * Throws an exception when property path is empty
     * @param string $channelName
     * @return \Sway\Distribution\Storage\Exception\ChannelException
     */
    public static function emptyPropertyPath(string $channelName) : ChannelException
    {
        return (new ChannelException(spritnf("Given property path is empty (working on '%s' channel)", $channelName)));
    }
}


?>