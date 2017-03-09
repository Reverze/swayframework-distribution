<?php

namespace Sway\Distribution\Mapping;

interface ClassMapperInterface
{
    /**
     * Gets map with classess which are located in given subspace and which names ends with given sufix
     * @param string $inSubNamespace
     * @param string $classNameSufix
     * @return array
     */
    public function getMapFor(string $inSubNamespace, string $classNameSufix) : array;
    
    /**
     * Get all classess in sub namespace
     * @param string $subNamespace
     * @return array
     */
    public function getClasessWithSubnamespace(string $subNamespace) : array;
    
    /**
     * Gets classess with given sufix
     * @param string $classSufix
     * @param array $map
     * @return array
     */
    public function getClassessWithSufix(string $classSufix, array $map = array()) : array;
    
    
}


?>
