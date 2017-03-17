<?php

namespace Sway\Distribution\Mapping;

/**
 * Map contains class definitions
 */
class Map 
{
    /**
     * Array which contains class definition
     * @var \Sway\Distribution\Mapping\Definition[]
     */
    private $definitions = array();
    
    public function __construct(array $definitions = array())
    {
        if (empty($this->definitions)){
            $this->definitions = $definitions;
        }  
    }
    
    /**
     * Adds definition into map
     * @param \Sway\Distribution\Mapping\Definition $definition
     */
    public function addDefintion(Definition $definition)
    {
        $this->definitions[] = $definition;
    }
    
    /**
     * Adds definitions into map
     * @param \Sway\Distribution\Mapping\Definition[] $definitions
     */
    public function addDefinitions(array $definitions)
    {
        foreach ($definitions as $definition){
            $this->definitions[] = $definition;
        }
    }
    
    /**
     * Adds class into map
     * @param string $className
     */
    public function addClass(string $className)
    {
        $this->definitions[] = new Definition($className);
    }
    
    /**
     * Adds classses into map
     * @param string[] $classess
     */
    public function addClassess(array $classess)
    {
        foreach ($classess as $class){
            $this->definitions[] = new Definition($class);        
        }
    }
    
    /**
     * Finds definition by class name.
     * If definition is not found, returns null
     * @param string $className
     * @return \Sway\Distribution\Mapping\Definition
     */
    public function find(string $className)
    {
        foreach ($this->definitions as $definition){
            if ($definition->isClass($className)){
                return $definition;
            }
        }
        
        return null;
    }
    
    /**
     * Finds class definition which ancestor is another class.
     * Returns only one definition.
     * If not found, returns NULL.
     * @param string $classAncestor
     * @return \Sway\Distribution\Mapping\Definition
     */
    public function findOneByAncestor(string $classAncestor)
    {
        foreach ($this->definitions as $definition){
            if ($definition->isExtends($classAncestor)){
                return $definition;
            }
        }
        
        return null;
    }
    
    /**
     * Finds all class definition which ancestor is given class.
     * @param string $classAncestor
     * @return \Sway\Distribution\Mapping\Definition[] 
     */
    public function findAllByAncestor(string $classAncestor)
    {
        $matchedDefinitions = array();
        
        foreach ($this->definitions as $definition){
            if ($definition->isExtends($classAncestor)){
                $matchedDefinitions[] = $definition;
            }
        }
        
        return $matchedDefinitions;
    }
}


?>