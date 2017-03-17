<?php

namespace Sway\Distribution;

use Sway\Distribution\Storage\StorageDriver;
use Sway\Distribution\Container\ContainerBuilder;
use Sway\Distribution\Extension\ExtensionManager;
use Sway\Distribution\Service;
use Sway\Distribution\Mapping;


class FrameworkDistribution
{
    /**
     * Framework root directory
     * @var string
     */
    private $frameworkRootDirectory = null;
    
    /**
     * Distribution storage interface
     * @var \Sway\Distribution\Storage\StorageDriver
     */
    private $storage = null;
    
    /**
     * Container builder
     * @var \Sway\Distribution\Container\ContainerBuilder
     */
    private $containerBuilder = null;
    
    /**
     * Extension manager
     * @var \Sway\Distribution\Extension\ExtensionManager
     */
    private $extensionManager = null;
    
    /**
     * Initializes framework distribution instance
     * @param string $rootDirectory
     * @throws \Sway\Distribution\FrameworkDistributionException
     */
    public function __construct(string $rootDirectory)
    {
        /**
         * Stores framework root directory
         */
        if (empty($this->frameworkRootDirectory)){
            $this->frameworkRootDirectory = $rootDirectory;
        }
             
        /**
         * If framework root directory not exists on file system,
         * throws an exception
         */
        if (!is_dir($this->frameworkRootDirectory)){
            throw FrameworkDistributionException::frameworkDirectoryNotFound($this->frameworkRootDirectory);
        }
        
        $this->initializeStorageDriver();
        $this->initializeContainerBuilder();
    }
    
    /**
     * Initializes storage driver
     * @throws \Sway\Distribution\FrameworkDistributionException
     */
    public function initializeStorageDriver()
    {
        /**
         * Gets framework temponary data directory path
         */
        $frameworkTempDirectoryPath = sprintf("%s/%s", $this->frameworkRootDirectory, 'tmp');
        
        /**
         * If directory was not found,
         * throws an exception
         */
        if (!is_dir($frameworkTempDirectoryPath)){
            throw FrameworkDistributionException::frameworkTemponaryDirectoryNotFound($frameworkTempDirectoryPath);
        }
        
        /**
         * Stores storage interface instance
         */
        $this->storage = new StorageDriver($frameworkTempDirectoryPath, 'sway-framework-distribution');
        
        /**
         * Class \Framework is not available after composer update
         */
        
    }
    
    public function initDistribution()
    {
        if (class_exists('\Framework')){
            /**
             * Registers distribution storage as service
             */
            \Framework::getServiceContainer()->registerService('distribution_storage', $this->storage);
            /**
             * Registers distribution class founder as service
             */
            \Framework::getServiceContainer()->registerService('distribution_class_founder',
                    new Mapping\ClassFounder($this->getExtensionManager(),
                        $this->storage, [
                        'frameworkPwd' => $this->frameworkRootDirectory
                    ]));
            /**
             * Registers extension manager as service
             */
            \Framework::getServiceContainer()->registerService('distribution_extension_manager',
                    $this->extensionManager);
            
            
        }
    }
    
    /**
     * Initialize interface to framework as service
     * @param array $params
     */
    public function initializeFrameworkService(array $params = array())
    {
        /**
         * Params to pass for framework service
         */
        $frameworkParams = array();
        
        foreach ($params as $paramName => $paramValue){
            $frameworkParams[$paramName] = $paramValue;
        }
        
        $frameworkService = new Service\FrameworkService($frameworkParams);
        \Framework::getServiceContainer()->registerService('framework', $frameworkService);
    }
    
    /**
     * Initializes configuration container builder
     */
    public function initializeContainerBuilder()
    {
        if (empty($this->containerBuilder)){
            $this->containerBuilder = new ContainerBuilder($this->getStorage());
        }
    }
    
    /**
     * Gets storage interface
     * @return StorageDriver
     */
    public function getStorage() : StorageDriver
    {
        return $this->storage;
    }
    
    /**
     * Gets container builder
     * @return ContainerBuilder
     */
    public function getContainerBuilder() : ContainerBuilder
    {
        return $this->containerBuilder;
    }
    
    /**
     * Gets extension manager
     * @return ExtensionManager
     */
    public function getExtensionManager() : ExtensionManager
    {
        if (empty($this->extensionManager)){
            $this->extensionManager = new ExtensionManager($this->getStorage());
            $this->extensionManager->wakeup();
        }
        
        return $this->extensionManager;
    }
    
    
    
    
}

?>