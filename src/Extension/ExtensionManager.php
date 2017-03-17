<?php

namespace Sway\Distribution\Extension;

use Sway\Distribution\Container\ContainerBuilder;
use Sway\Distribution\Storage\StorageDriver;
use Sway\Distribution\Storage\Channel;

class ExtensionManager
{
    /**
     * Extensions container
     * @var \Sway\Distribution\Extension\PackageExtension[]
     */
    private $extensions = array();  
    
    /**
     * Storage driver
     * @var \Sway\Distribution\Extension\StorageDriver
     */
    private $storage = null;
    
    /**
     * Storage channel for extension manager
     * @var \Sway\Distribution\Storage\Channel
     */
    private $channel = null;
    
    public function __construct(StorageDriver $storage)
    {
        if (!is_array($this->extensions)){
            $this->extensions = array();
        }     

        if (empty($this->storage)){
            $this->storage = $storage;
        }
        
        if (empty($this->channel)){
            $this->channel = $this->storage->getChannel(sprintf("%s/%s", "ext-mg", md5("registered-extesions")));
        }
    }
    
    /**
     * Registers extension
     * @param string $name
     * @param array $autoload
     * @param string $type
     * @throws \Sway\Distribution\Extension\Exception\ExtensionException
     */
    public function registerExtension(string $name, array $autoload, string $type = 'sf-package')
    {
        /**
         * Extension namespaces
         */
        $extensionNamespaces = array();
        
        /**
         * Stores extension namespaces into standalone array
         */
        foreach ($autoload as $namespace => $sourceDir){
            array_push($extensionNamespaces, sprintf("\\%s", $namespace));
        }
        
        /**
         * Looking for InitExtension class.
         * Remember! One InitExtension class per one extension (package).
         * If you create many InitExtension classess, only one class will be chosen (at first in order by autoload)
         */
        $initExtensionClass = null;
        
        foreach ($extensionNamespaces as $extensionNamespace){
            $sugarPath = sprintf("%s%s",$extensionNamespace, 'InitExtension');
            if (class_exists($sugarPath)){
                $initExtensionClass = $sugarPath;
                break;
            }
        }
        
        if (!empty($initExtensionClass)){
            $reflectionClass = new \ReflectionClass($initExtensionClass);
            
            /**
             * Creates a potential InitExtension class instance without calling constructor
             * to check if class extends PackageExtension class
             */
            $fakeInstance = $reflectionClass->newInstanceWithoutConstructor();
            
            if (!$fakeInstance instanceof PackageExtension){
                throw Exception\ExtensionException::invalidExtensionClassInstance($initExtensionClass);
            }
        }
        
        $initExtensionObject = null;
        
        /**
         * If extension doesnt have own InitExtension class, use default
         */
        if (empty($initExtensionClass)){
            $initExtensionObject = new PackageExtension();
        }
        else{
            $initExtensionObject = new $initExtensionClass();
        }
        
        /** Sets extension name */
        $initExtensionObject->setExtensionName($name);
        $initExtensionObject->setExtensionType($type);
        
        array_push($this->extensions, $initExtensionObject);
        $this->storeExtension($initExtensionObject);
    }
    
    /**
     * Stores data about extension
     * @param \Sway\Distribution\Extension\PackageExtension $extension
     */
    private function storeExtension(PackageExtension $extension)
    {
        /**
         * Reflects class which represent extension
         */
        $reflectionClass = new \ReflectionClass($extension);
        
        /**
         * Gets root namespace of extension
         */
        $extensionNamespace = $reflectionClass->getNamespaceName();
        
        
        if ($extensionNamespace !== 'Sway\Distribution\Extension'){
            $this->channel->set(sprintf('extension/registered/%s', $this->filterExtensionName($extension->getExtensionName())), [
                'type' => $extension->getExtensionType(),
                'name' => $extension->getExtensionName(),
                'class' => get_class($extension),
                'namespace' => $extensionNamespace,
                'path' => dirname($reflectionClass->getFileName())
            ]);
        
        }        
    }
    
    /**
     * Removes danger characters from extension name
     * @param string $extensionName
     * @return string
     */
    private function filterExtensionName(string $extensionName)
    {
        $extensionName = str_replace('/', '-', $extensionName);       
        return $extensionName;
    }
    
    
    public function loadExtensionConfigs(ContainerBuilder $container)
    {
        foreach ($this->extensions as $extension){
            $extension->loadConfig($container);
        }
    }
    
    public function wakeup()
    {
        $this->loadRegisteredExtensions();
    }
    
    /**
     * Loads registered extensions from cache storage
     */
    public function loadRegisteredExtensions()
    {
        $registeredExtensions = $this->channel->get('extension/registered');
        
        foreach ($registeredExtensions as $registeredExtension){
            $extension = new $registeredExtension['class']();
            $extension->setExtensionName($registeredExtension['name']);
            $extension->setExtensionType($registeredExtension['type']);
            $extension->setExtensionPath($registeredExtension['path']);
            $extension->setExtensionNamespace($registeredExtension['namespace']);
            
            array_push($this->extensions, $extension); 
        }
        
    }
    
    /**
     * Gets extensions
     * @return array
     */
    public function getExtensions() : array
    {
        return $this->extensions;
    }
    
    
}


?>
