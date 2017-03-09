<?php

namespace Sway\Distribution\Container;

class Repository
{
    /**
     * Repository directory path
     * @var string
     */
    private $rootDirectoryPath = null;
    
    /**
     * Reference to container builder
     * @var \Sway\Distribution\Container\ContainerBuilder
     */
    private $containerBuilder = null;
    
    public function __construct(string $directoryPath, ContainerBuilder $container)
    {
        if (empty($this->rootDirectoryPath)){
            $this->rootDirectoryPath = $directoryPath;
        } 
        
        if (empty($this->containerBuilder)){
            $this->containerBuilder = $container;
        }
    }
    
    /**
     * Loads configuration from file
     * @param string $file
     */
    public function load(string $file)
    {
        /**
         * Compiles configuration file path
         */
        $filePath = sprintf("%s/%s", $this->rootDirectoryPath, $file);
        
        /**
         * If configuration file was not found in repository,
         * throws an exception
         */
        if (!is_file($filePath)){
            throw Exception\RepositoryException::fileNotFound($filePath);
        }
        
        /**
         * Loads configuration file content as array
         */
        $configuration = $this->containerBuilder->getConfigurationManager()->readFile($filePath);
        
        /**
         * Asserts freshly readed configuration
         */
        $this->containerBuilder->assert($configuration);
        
    }
    
}


?>