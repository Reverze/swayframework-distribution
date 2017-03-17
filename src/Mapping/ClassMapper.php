<?php

namespace Sway\Distribution\Mapping;

use Sway\Distribution\Storage\StorageDriver;

class ClassMapper implements ClassMapperInterface
{
    /**
     *
     * @var \Sway\Distribution\Storage\StorageDriver
     */
    private $storage = null;
    
    /**
     * Custom declared classess
     * @var array
     */
    private $declaredClassess = null;
    
    public function __construct(StorageDriver $storage)
    {
        if (empty($this->storage)){
            $this->storage = $storage;
        }
    }
    
    /**
     * Sets declared clasess
     * @param array $declaredClassess
     */
    public function setDeclaredClassess(array $declaredClassess)
    {
        $this->declaredClassess = $declaredClassess;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMapFor(string $inSubNamespace, string $classNameSufix) : array
    {   
        $classessInSubnamespace = $this->getClasessWithSubnamespace($inSubNamespace);
        
        $classess = $this->getClassessWithSufix($classNameSufix, $classessInSubnamespace);
        
        return $classess;
    }
    
    /**
     * Gets all declared classess at runtime
     * @return array
     */
    private function getDeclaredClassess() : array
    {
        if (is_array($this->declaredClassess)){
            return $this->declaredClassess;
        }
        
        return get_declared_classes();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getClasessWithSubnamespace(string $subNamespace) : array
    {
        if (empty($subNamespace) || !strlen($subNamespace)){
            return $this->getDeclaredClassess();
        }
        
        if ($subNamespace === "\\"){
            return $this->getDeclaredClassess();
        }
        
        $classess = array();
        
        foreach ($this->getDeclaredClassess() as $className){
            /**
             * Explodes class name to check if class is stored in namespace
             */
            $explodedClassName = explode("\\", $className);
            
            if (in_array($subNamespace, $explodedClassName)){
                array_push($classess, $className);
            }
            
        }
        
        return $classess;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getClassessWithSufix(string $classSufix, array $map = array()) : array
    {
        if (empty($map)){
            $map = $this->getDeclaredClassess();
        }
        
        $classess = array();
        
        foreach ($map as $class){
            $classNameLength = strlen($class);
            $classSufixLength = strlen($classSufix);
            
            if ($classNameLength > $classSufixLength){
                if ( substr($class, $classNameLength - $classSufixLength, $classSufixLength) === $classSufix){
                    array_push($classess, $class);
                }
            }
        }
        
        return $classess;
    }
    
    public function onConsoleLaunch($eventArgs)
    {
        
        
    }
    
}


?>

