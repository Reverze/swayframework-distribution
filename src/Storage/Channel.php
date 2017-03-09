<?php

namespace Sway\Distribution\Storage;

use Sway\Distribution\Storage\File\Adapter;

class Channel
{
    /**
     * Channel name
     * @var string
     */
    private $channelName = null;
    
    /**
     * Channel adapter
     * @var \Sway\Distribution\Storage\File\Adapter;
     */
    private $adapter = null;
    
    /**
     * Channel working context
     * @var array
     */
    private $context = array();
    
    private $updateContextDisabled = false;
    
    public function __construct(Adapter $adapter, string $channelName)
    {
        if (empty($this->channelName)){
            $this->channelName = $channelName;
        }
        
        if (empty($this->adapter)){
            $this->adapter = $adapter;
        }
        
        $this->initializeContext();
    }
    
    /**
     * Auto saves channel context
     */
    public function __destruct() 
    {
        $this->updateContext();
    }
    
    /**
     * Initializes channel context
     */
    private function initializeContext()
    {
        $this->context = $this->adapter->getContext();
    }
    
    /**
     * Sets value under property path
     * @param string $propertyPath
     * @param mixed $value
     * @throws \Sway\Distribution\Storage\Exception\ChannelException
     */
    public function set(string $propertyPath, $value)
    {
        /**
         * If propertyPath is empty, throws an exception
         */
        if (empty($propertyPath) or !strlen($propertyPath)){
            throw Exception\ChannelException::emptyPropertyPath($this->channelName);
        }
        
        /**
         * Creates path map
         */
        $propertyPathMap = $this->mapPropertyPath($propertyPath);
        
        /**
         * At first we must find first element specified by map
         */
        $elementPointer = &$this->context;
       
        foreach ($propertyPathMap as $propertyKey){
            if (isset($elementPointer[$propertyKey])){
                $elementPointer = &$elementPointer[$propertyKey];
            }
            else{
                if (!is_array($elementPointer)){
                    $address = &$elementPointer;
                    $elementPointer = array();
                    $elementPointer = $address;
                    
                }
                $elementPointer[$propertyKey] = array();
                $elementPointer = &$elementPointer[$propertyKey];
            }
        }
        
        /**
         * The node was found, so we can put a value
         */
        $elementPointer = $value;
    }
    
    public function setDirect(array $context)
    {
        $this->context = $context;
    }
    
    /**
     * Gets value under property
     * @param string $propertyPath
     * @return mixed
     * @throws \Sway\Distribution\Storage\Exception\ChannelException
     */
    public function get(string $propertyPath)
    {
        /**
         * If propertyPath is empty, throws an exception
         */
        if (empty($propertyPath) or !strlen($propertyPath)){
            throw Exception\ChannelException::emptyPropertyPath($this->channelName);
        }
        
        /**
         * Creates path map
         */
        $propertyPathMap = $this->mapPropertyPath($propertyPath);
        
        /**
         * At first we must find first element specified by map
         */
        $elementPointer = &$this->context;
       
        $missed = false;
        
        foreach ($propertyPathMap as $propertyKey){
            if (isset($elementPointer[$propertyKey])){
                $elementPointer = &$elementPointer[$propertyKey];
            }
            else{
                $missed = true;
                break;
            }
        }
        
        if ($missed === true && $propertyPath !== '/'){
            return null;
        }
        
        /**
         * The node was found, so we can put a value
         */
        return $elementPointer; 
        
    }
    
    /**
     * Checks if value under property path is exists
     * @param string $propertyPath
     * @return bool
     * @throws \Sway\Distribution\Storage\Exception\ChannelException
     */
    public function has(string $propertyPath) : bool
    {
        /**
         * If propertyPath is empty, throws an exception
         */
        if (empty($propertyPath) or !strlen($propertyPath)){
            throw Exception\ChannelException::emptyPropertyPath($this->channelName);
        }
        
        /**
         * Creates path map
         */
        $propertyPathMap = $this->mapPropertyPath($propertyPath);
        
        /**
         * At first we must find first element specified by map
         */
        $elementPointer = &$this->context;
       
        $missed = false;
        
        foreach ($propertyPathMap as $propertyKey){
            if (isset($elementPointer[$propertyKey])){
                $elementPointer = &$elementPointer[$propertyKey];
            }
            else{
                $missed = true;
                break;
            }
        }
        
        return !$missed;
        
    }
    
    /**
     * Convert property path into property path map
     * @param string $propertyPath
     * @return array
     */
    private function mapPropertyPath(string $propertyPath) : array
    {
        /**
         * Explodes path into array which property's path elements 
         */
        $propertyPathMap = explode('/', $propertyPath);
        
        return $propertyPathMap;
    }
    
    /**
     * Updates context in storage
     */
    private function updateContext()
    {
        if (!$this->updateContextDisabled){
            $this->adapter->saveContext($this->context);
        }
    }
    
    /**
     * Destroys channel 
     */
    public function destroy()
    {
        $this->updateContextDisabled = true;
        $this->adapter->remove();
    }
    
    public function transitory()
    {
        $this->updateContextDisabled = true;
    }
    
    
}


?>
