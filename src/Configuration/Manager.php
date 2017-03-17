<?php

namespace Sway\Distribution\Configuration;

class Manager
{
    /**
     * Readers for supported formats
     * @var \Sway\Distribution\Configuration\Reader[]
     */
    private $readers = array();
    
    public function __construct()
    {
        /**
         * Initialize readers
         */
        $this->readers = [
            'yml' => new YamlReader(),
            'json' => new JsonReader()   
        ];    
    }
    
    /**
     * Reads configuration file
     * @param string $file
     * @return array
     * @throws \Sway\Distribution\Configuration\Exception\ConfigurationManagerException
     */
    public function readFile(string $file) : array
    {
        /**
         * Gets extension of configuration file
         */
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        
        /**
         * If detected extension is unsupported
         */
        if (!array_key_exists($fileExtension, $this->readers)){
            throw Exception\ConfigurationManagerException::unsupportedConfigurationFileExtension($fileExtension, $file);
        }
        
        /**
         * Reads configuration file
         */
        $content = $this->readers[$fileExtension]->readFile($file);
        
        return $content;
    }
    
}


?>

