<?php

namespace Sway\Distribution\Mapping;

/**
 * Represents class definition in class map
 */
class Definition
{
    /**
     * Class's name with full namespace path
     * @var string
     */
    private $className = null;
    
    /**
     * Name of file which contains class definition
     * @var string
     */
    private $fileName = null;
    
    public function __construct(string $className)
    {
        if (empty($this->className)){
            $this->className = $className;
        }
        
        $this->className = $this->prepareClassName($this->className);
        $this->getSomeDetailsAboutClass();
    }
    
    /**
     * Prepared class names
     */
    protected function prepareClassName(string $className) : string
    {
        /**
         * Classs name should not ends with slash character
         * For example: 'ArrayIterator\' is not allowed
         */
        if ($className[strlen($className) - 1] === '\\'){
            $className = substr($className, 0, strlen($className) - 2);
        }
        
        $explodedClassName = explode("\\", $className); 
        
        /**
         * We want to precede the name of class with '\' if class is declared in root namespace
         * For example:
         * Given argument: ArrayIterator
         * Output: \ArrayIterator
         */
        if (sizeof($explodedClassName) === 1){
            $className = sprintf("\%s", $explodedClassName[0]);
        }
        
        return $className;
    }
    
    /**
     * Gets some details about class
     */
    protected function getSomeDetailsAboutClass()
    {
        /**
         * We initialize class reflector to get some details 
         * about current class
         */
        $classReflector = new \ReflectionClass($this->className);
        
        /**
         * Stores name of file which contains class definition
         */
        $this->fileName = ($classReflector->getFileName() ? $classReflector->getFileName() : "");
        
    }
    
    /**
     * Gets class name
     * @return string
     */
    public function getClassName() : string
    {
        return $this->className;
    }
    
    /**
     * 
     * @param string $className
     * @return bool
     */
    public function isClass(string $className) : bool
    {
        return ($this->className === $this->prepareClassName($className));  
    }
    
    /**
     * Gets filename of file which contains class definition
     * @return string
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }
    
    /**
     * Creates a new class instance
     * @param array $args
     * @return object
     */
    public function getClassInstance(array $args = array())
    {
        $classReflector = new \ReflectionClass($this->className);
        return $classReflector->newInstance($args);
    }
    
    /**
     * Creates a new class instance without invoking class's constructor.
     * Class's instances created without constructor shouldn't be used
     * in normal way.
     * @return object
     */
    public function getClassInstanceWithoutConstructor()
    {
        $classReflector = new \ReflectionClass($this->className);
        return $classReflector->newInstanceWithoutConstructor();
    }
    
    /**
     * Checks if class extends another class
     * @param string $parentClassName
     * @return boolean
     */
    public function isExtends(string $parentClassName)
    {
        /**
         * Gets class instance for debug (without calling constructor)
         */
        $debugInstance = $this->getClassInstanceWithoutConstructor();
        
        if ($debugInstance instanceof $parentClassName){
            return true;
        }
        
        return false;
    }
    
    /**
     * Checks if given object is parent of class
     * @param object $object
     * @return boolean
     * @throws \Sway\Distribution\Mapping\Exception\DefinitionException
     */
    public function isParent($object)
    {
        /**
         * Passed argument must be an object
         */
        if (!is_object($object)){
            throw Exception\DefinitionException::isNotAnObject();
        }
        
        $objectReflector = new \ReflectionClass($object);
        
        /**
         * Gets class name of given object
         */
        $objectClassName = $objectReflector->getName();
        
        $debugInstance = $this->getClassInstanceWithoutConstructor();
        
        
        if ($debugInstance instanceof $objectClassName){
            return true;
        }
        
        return false;
    }
    
    
}


?>