<?php

namespace Sway\Distribution\Mapping\NamespaceMap;

use Sway\Distribution\Mapping\IOMap\IOMap;

/**
 * Remember!
 * Only supports PSR-4 autoloading
 */
class NamespaceMap
{
    /**
     *
     * @var \Sway\Distribution\Mapping\IOMap\IOMap
     */
    private $iomap = null;
    
    public function __construct(IOMap $iomap)
    {
        if (empty($this->iomap)){
            $this->iomap = $iomap;
        }
    }
    
    /**
     * Generates class map
     * @param string $classSufix
     * @param string $subnamespace
     * @return array
     */
    public function generateClassMap()
    {
        /**
         * Gets files generated by iomap
         */
        $filesMap = $this->iomap->getFiles();
        
        /**
         * Class map
         */
        $classMap = array();
        
        foreach ($filesMap as $file){
            $namespace = $this->readNamespaceFromFile($file);
            
            if (empty($namespace)){
                continue;
            }
            
            /**
             * Explodes file path to get filename and finnaly get class name
             */
            $explodedPath = explode(DIRECTORY_SEPARATOR, $file);
            
            $fileName = $explodedPath[sizeof($explodedPath) - 1];
            
            $className = str_replace(".php", "", $fileName);
            
            
            $classMap[] = sprintf("%s\%s", $namespace, $className);
                   
        }
        
        return $classMap;
    }
    
    /**
     * Reads defined namespace in script file
     * @param string $file
     * @return string or null
     */
    protected function readNamespaceFromFile(string $file)
    {
        /**
         * Gets content of script file
         */
        $scriptContent = file_get_contents($file);
        
        /**
         * Regex pattern to get defined namespace in script file
         */
        $regexPattern = '/^.*namespace\s(.*)\;/m';
               
        $matchedArray = array();
        
        preg_match_all($regexPattern, $scriptContent, $matchedArray);
        
        return $matchedArray[1][0] ?? null;
    }
    
}

?>
