<?php

namespace Sway\Distribution\Storage;

class StorageDriver
{
    /**
     * Storage path
     * @var string
     */
    private $storagePath = null;
    
    /**
     * Storage channels
     * @var \Sway\Distribution\Storage\Channel[]
     */
    private $channels = array();
    
    /**
     * If transistory storage is enabled, all changes are not reflected in physical storage
     * @var bool
     */
    private $transitory = false;
    
    public function __construct(string $storageRootPath, string $storageSubDirectory = null)
    {
        /**
         * If sub directory is not specified
         */
        if (empty($storageSubDirectory) || !strlen($storageSubDirectory)){
            $this->storagePath = $storageRootPath;
        }
        
        /**
         * If sub directory is specified
         */
        if (!empty($storageSubDirectory) && strlen($storageSubDirectory)){
            $this->storagePath = sprintf("%s/%s", $storageRootPath, $storageSubDirectory);
        }
        
        /**
         * If directory not exists
         */
        if (!is_dir($this->storagePath)){
            mkdir($this->storagePath);
        }
    }
    
    /**
     * Gets channel instance 
     * @param string $channelName
     * @return boolean|\Sway\Distribution\Storage\Channel
     */
    public function getChannel(string $channelName) : Channel
    {
        $channelName = strtolower($channelName);
        
        /**
         * If channel has been loaded
         */
        if (array_key_exists($channelName, $this->channels)){
            return $this->channels[$channelName];
        }
        
        /**
         * Initializes a new Locator instance to search channel
         */
        $locator = new Locator($this->storagePath);
        
        $adapter = $locator->locate($channelName);
        
        
        /**
         * If resource was not found (adapter is null)
         */
        if (empty($adapter)){
            $adapter = $locator->createAdapter($channelName);
        }
        
        /**
         * Creates a new channel
         */
        $channel = new Channel($adapter, $channelName);
        
        if ($this->isTransistoryEnabled()){
            $channel->transitory();
        }
        
        $this->channels[$channelName] = $channel;
        return $channel;
    }
    
    /**
     * Destroys channel
     * @param string $channelName
     */
    public function destroyChannel(string $channelName)
    {
        $channelName = strtolower($channelName);
        
        /**
         * If channel has been loaded before
         */
        if (array_key_exists($channelName, $this->channels)){
            /**
             * Destroys channel storage and removes from list
             */
            $this->channels[$channelName]->destroy();
            unset($this->channels[$channelName]);
        }
        
        $locator = new Locator($this->storagePath);
        $adapter = $locator->locate($channelName);
        
        if (empty($adapter)){
            //nothing to do, exiting
            return;
        }
        
        $channel = new Channel($adapter, $channelName);
        $channel->destroy();
    }
    
    /**
     * If you enable transitory on storage,
     * all storage channels will be transistoried
     */
    public final function enableTransistory()
    {
        $this->transitory = true;  
    }
    
    /**
     * 
     * @return bool
     */
    private function isTransistoryEnabled()
    {
        return $this->transitory;
    }
    
    
    public final function disableTransistory()
    {
        $this->transitory = false;
    }
        
    
}


?>
