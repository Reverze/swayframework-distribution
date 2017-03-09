<?php

namespace Sway\Distribution\Storage;

use Sway\Distribution\Storage\File\Adapter;

class Locator
{
    /**
     *
     * @var string
     */
    private $directoryToLocate = null;
    
    /**
     * Default file extension
     * @var string
     */
    private $defaultExtension = 'json';
    
    
    public function __construct(string $workingDirectory)
    {
        $this->directoryToLocate = $workingDirectory;  
    }
    
    /**
     * Locates and creates adapater if channel was found
     * @param string $channelName
     * @return Adapter or null
     */
    public function locate(string $channelName)
    {
        $filePath = sprintf("%s/%s.%s",
                $this->directoryToLocate,
                strtolower($channelName),
                $this->defaultExtension);
        
        /**
         * If Resource was found, create an adapter
         */
        if (is_file($filePath)){
            $adapter = new Adapter($filePath);
            return $adapter;
        }
        else{
            return null;
        }
    }
    
    /**
     * Creates a new adapter instance
     * @param string $channelName
     * @return Adapter
     */
    public function createAdapter(string $channelName) : Adapter
    {
        $filePath = sprintf("%s/%s.%s",
                $this->directoryToLocate,
                strtolower($channelName),
                $this->defaultExtension);
        
        return (new Adapter($filePath));
    }
}


?>
