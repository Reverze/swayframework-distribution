<?php

namespace Sway\Distribution\Container;

use Sway\Distribution\Storage\StorageDriver;
use Sway\Distribution\Storage\Channel;
use Sway\Distribution\Configuration;

class ContainerBuilder
{
    /**
     * Storage driver
     * @var \Sway\Distribution\Storage\StorageDriver
     */
    private $storage = null;
    
    /**
     * Configuration manager
     * @var \Sway\Distribution\Configuration\Manager
     */
    private $configurationManager = null;
    
    private $transitoryStorage = false;
    
    public function __construct(StorageDriver $storage)
    {
        if (empty($this->storage)){
            $this->storage = $storage;
        }
        
        $this->configurationManager = new Configuration\Manager();
    }
    
    /**
     * 
     * @param string $directoryPath
     * @return \Sway\Distribution\Container\Repository
     * @throws \Sway\Distribution\Container\Exception\ContainerBuilderException
     */
    public function loadFrom(string $directoryPath) : Repository
    {
        /**
         * If directory is not exists
         */
        if (!is_dir($directoryPath)){
            throw Exception\ContainerBuilderException::directoryNotFound($directoryPath);
        }
        
        $repository = new Repository($directoryPath, $this);
        return $repository;
    }
    
    /**
     * Gets configuration manager
     * @return \Sway\Distribution\Configuration\Manager
     */
    public function getConfigurationManager() : Configuration\Manager
    {
        return $this->configurationManager;
    }
    
    /**
     * Asserts configuration into storage
     * @param array $configuration
     */
    public function assert(array $configuration)
    {
        /**
         * Initialize storage channel
         */
        $channel = $this->storage->getChannel('application-framework-configuration');
        
        /**
         * Gets framework configuration
         */
        $frameworkConfiguration = $channel->get('/');
        
        
        $output = array_replace_recursive($frameworkConfiguration, $configuration);
        
        $channel->setDirect($output);
        
    }
    
    /**
     * Asserts application, framework and vendors configuration into storage
     * @param array $configuration
     * @param string $appConfigPath Application absolute configuration path file is required to recognize working application
     */
    public function assertApp(array $configuration, string $appConfigPath)
    {
        /**
         * Gets channel which contains framework and vendors configuration
         */
        $frameworkConfigurationChannel = $this->storage->getChannel('application-framework-configuration');
        $frameworkConfiguration = $frameworkConfigurationChannel->get('/');
        
        /**
         * Gets application configuration channel
         */
        $applicationConfigurationChannel = $this->storage->getChannel(
                sprintf("apps-confds/%s-cache-conf", md5($appConfigPath))
        );
        
        $applicationConfigurationChannel->setDirect(array_replace_recursive($frameworkConfiguration, $configuration));
    }
    
    /**
     * Get stored framework configuration
     * @return array
     */
    public function getFrameworkConfiguration() : array
    {
        return $this->storage->getChannel('application-framework-configuration')->get('/');
    }
    
    /**
     * Gets framework configuration channel
     * @return Channel
     */
    public function getFrameworkConfigurationChannel() : Channel
    {
        $channel = $this->storage->getChannel('application-framework-configuration'); 
        if ($this->transitoryStorage){
            $channel->transitory();
        }
        return $channel;
    }
    
    /**
     * Checks if framework configuration is stored
     * @return array
     */
    public function hasFrameworkConfiguration() : bool
    {
        $frameworkConfiguration = $this->storage->getChannel('application-framework-configuration')->get('/');
        
        return (bool) sizeof($frameworkConfiguration);
    }
    
    /**
     * Checks if application, framework and vendors configuration is stored
     * @param string $appConfigPath
     * @return bool
     */
    public function hasApplicationConfiguration(string $appConfigPath) : bool
    {
        /**
         * Gets application configuration channel
         */
        $applicationConfiguration = $this->storage->getChannel(
                sprintf("apps-confds/%s-cache-conf", md5($appConfigPath))
        )->get('/');
        
        return (bool) sizeof($applicationConfiguration);
    }
    
    /**
     * Gets application configuration channel
     * @param string $appConfigPath
     * @return Channel
     */
    public function getApplicationConfigurationChannel(string $appConfigPath) : Channel
    {
        $channel = $applicationConfiguration = $this->storage->getChannel(
                sprintf("apps-confds/%s-cache-conf", md5($appConfigPath)));
        
        if ($this->transitoryStorage){
            $channel->transitory();
        }
        return $channel;
    }
    
    /**
     * Enabled transistory storage - it means that configuration is not stored anywhere expect script memory
     */
    public function transitoryStorage()
    {
        $this->transitoryStorage = true;
    }
    
    /**
     * Deprected, array_replace_recursive is better
     * @param array $sourceConfiguration
     * @param Channel $channel
     * @param string $path
     */
    private function appendRecursive(array $sourceConfiguration, Channel $channel, string $path)
    {
        
        $propertyPath = $path;
        
        //var_dump($path);
        
        foreach ($sourceConfiguration as $property => $value){
            /*
             * Build property path
             */        
            if (!strlen($propertyPath)){
                $propertyPath = $property;
            }
            else{
                $propertyPath .= '/' . $property;
            }
            
            if ($channel->has($propertyPath)){
                $element = $channel->get($propertyPath);
                
                
                if (is_array($element)){
                    $this->appendRecursive($sourceConfiguration[$property], $channel, $propertyPath);
                    //$propertyPath = '';
                    continue;
                }
                else{
                    $channel->set($propertyPath, $value);
                    //$propertyPath = '';
                    continue;
                }
            }
            else{
                $channel->set($propertyPath, $value);  
                $propertyPath = '';
                continue;
            }
            
            //$propertyPath = '';
        }
    }
}


?>