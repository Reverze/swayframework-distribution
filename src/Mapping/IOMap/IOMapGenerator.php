<?php

namespace Sway\Distribution\Mapping\IOMap;

/**
 * Helps to generate map for specified files in filesystem
 */
class IOMapGenerator
{
    /**
     * Generates IO map 
     * @param array $directories
     * @param array $generatorParameters
     * @return \Sway\Distribution\Mapping\IOMap\IOMap
     */
    public static function generate(array $directories, array $generatorParameters) : IOMap
    {
        /**
         * Creates a new IOMap instance
         */
        $iomap = new IOMap();
        
        /* Creates regex pattern by given generator parameters */
        $regexPatternIterator = static::createRegexPatternIterator($generatorParameters['fileExtension'] ?? 'php');
        
        foreach ($directories as $directoryToSearchIn){
            $arrayMap = static::generateArrayMapFor($directoryToSearchIn, $regexPatternIterator);  
            $iomap->pushArray(array_keys($arrayMap));
        }
        
        return $iomap;
    }
    
    /**
     * Creates regex pattern for iterator
     * @param string $extension
     * @return string
     */
    protected static function createRegexPatternIterator(string $extension = 'php') : string
    {
        return sprintf('/^.+\.%s$/i', $extension);
    }
    
    /**
     * Generates array map for given directory
     * @param string $directory
     * @param string $pattern
     * @return array
     */
    protected static function generateArrayMapFor(string $directory, string $pattern) : array
    {
        $directoryIterator = new \RecursiveDirectoryIterator($directory);
        $iterator = new \RecursiveIteratorIterator($directoryIterator);
        $regexIterator = new \RegexIterator($iterator, $pattern, \RecursiveRegexIterator::GET_MATCH);
        
        return iterator_to_array($regexIterator);
    }
}


?>
