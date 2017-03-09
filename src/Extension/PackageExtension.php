<?php

namespace Sway\Distribution\Extension;

use Sway\Distribution\Container\ContainerBuilder;

class PackageExtension implements PackageInterface
{
    /**
     * Extension's name
     * @var string
     */
    private $extensionName = null;
    
    /**
     * Extension's type
     * @var string
     */
    private $extensionType = null;
    
    /**
     * Extension's path
     * @var string
     */
    private $extensionPath = null;
    
    /**
     * Extension's namespace
     * @var string 
     */
    private $extensionNamespace = null;
    
    /**
     * This method should be overriden by package extension
     * @param ContainerBuilder $container
     */
    public function loadConfig(ContainerBuilder $container)
    {
        
        
    }
    
    /**
     * {@inheritdoc}
     * @param string $extensionName
     */
    public function setExtensionName(string $extensionName) 
    {
        $this->extensionName = $extensionName;
    }
    
    /**
     * {@inheritdoc}
     * @param string $extensionType
     */
    public function setExtensionType(string $extensionType) 
    {
        $this->extensionType = $extensionType;
    }
    
    /**
     * {@inheritdoc}
     * @param string $extensionPath
     */
    public function setExtensionPath(string $extensionPath) 
    {
        $this->extensionPath = $extensionPath;
    }
    
    /**
     * {@inheritdoc}
     * @param string $extensionNamespace
     */
    public function setExtensionNamespace(string $extensionNamespace) 
    {
        $this->extensionNamespace = $extensionNamespace;
    }
    
    /**
     * {@inheritdoc}
     * @return string
     */
    public function getExtensionType()
    {
        return $this->extensionType;
    }
    
    /**
     * {@inheritdoc}
     * @return string
     */
    public function getExtensionName()
    {
        return $this->extensionName;
    }
    
    /**
     * {@inheritdoc}
     * @return string
     */
    public function getExtensionPath()
    {
        return $this->extensionPath;
    }
    
    /**
     * {@inheritdoc}
     * @return string
     */
    public function getExtensionNamespace() 
    {
        return $this->extensionNamespace;
    }
    
}


?>

