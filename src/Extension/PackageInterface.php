<?php

namespace Sway\Distribution\Extension;

use Sway\Distribution\Container\ContainerBuilder;

interface PackageInterface
{
    public function loadConfig(ContainerBuilder $container);
    
    /**
     * Sets extension name. Name assing possible only once
     */
    public function setExtensionName(string $extensionName);
    
    /**
     * Sets extension's type.
     */
    public function setExtensionType(string $extensionType);
    
    /**
     * Sets extension's path
     */
    public function setExtensionPath(string $extensionPath);
    
    /**
     * Sets extension namespace
     */
    public function setExtensionNamespace(string $extensionNamespace);
    
    /**
     * Gets extension type
     */
    public function getExtensionType();
    
    /**
     * Gets extension name
     */
    public function getExtensionName();
    
    /**
     * Gets extension path
     */
    public function getExtensionPath();
    
    /**
     * Gets extension namespace
     */
    public function getExtensionNamespace();
}


?>
