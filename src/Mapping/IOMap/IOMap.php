<?php

namespace Sway\Distribution\Mapping\IOMap;

class IOMap
{
    /**
     * Stores all files defined in map
     * @var string
     */
    private $files = array();
    
    public function __construct()
    {        
        
    }
    
    /**
     * Adds file into map
     * @param string $fileAbsolutePath
     */
    public function add(string $fileAbsolutePath)
    {
        array_push($this->files, $fileAbsolutePath);
    }
    
    /**
     * Pushes array files map into map
     * @param array $arrayFilesMap
     */
    public function pushArray(array $arrayFilesMap)
    {
        $this->files = array_merge($this->files, $arrayFilesMap);
    }
    
    /**
     * Gets files in map
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
    
}


?>