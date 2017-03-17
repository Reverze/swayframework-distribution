<?php

namespace Sway\Distribution\Service;

use Sway\Component\Service;
use Sway\Component\Parameter;

/**
 * This is Framework service which allows to direct managing framework.
 * This service is registered manually (from code by InitFramework)
 */
class FrameworkService
{
    /**
     * Stored variables
     * @var string
     */
    private $variables = array();
    
    public function __construct(array $params = array())
    {
        $this->initializeParams($params);      
    }
    
    /**
     * Initialize parameters
     * @param array $params
     */
    private function initializeParams(array $params)
    {
        $this->variables = $params;
    }
    
    /**
     * Gets value of defined variable
     * @param string $variableName Framework variable's name
     * @return mixed
     * @throws \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public function getVar(string $variableName) 
    {
        /**
         * If variable is defineed
         */
        if (array_key_exists($variableName, $this->variables)){
            return $this->variables[$variableName];
        }
        
        throw Exception\FrameworkServiceException::variableNotExists($variableName);
    }
    
    /**
     * Alias to get value of 'app_working_directory'
     * @return string
     * @throws \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public function getApplicationWorkingDirectory() : string
    {
        if (array_key_exists('app_working_directory', $this->variables)){
            return $this->variables['app_working_directory'];
        }
        
        throw Exception\FrameworkServiceException::variableNotExists('app_working_directory');
    }
    
    /**
     * Alias to get value of 'vendor_directory'
     * @return string
     * @throws \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public function getVendorDirectory() : string
    {
        if (array_key_exists('vendor_directory', $this->variables)){
            return $this->variables['vendor_directory'];
        }
        
        throw Exception\FrameworkServiceException::variableNotExists('vendor_directory');
    }
    
    /**
     * Alias to get service container
     * @return Container
     * @throws \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public function getServiceContainer() : \Sway\Component\Service\Container
    {
        if (array_key_exists('service_container', $this->variables)){
            return $this->variables['service_container'];
        }
        
        throw Exception\FrameworkServiceException::variableNotExists('service_container');
    }
    
    /**
     * Alias to get parameter container
     * @return \Sway\Distribution\Service\Sway\Component\Parameter\Container
     * @throws \Sway\Distribution\Service\Exception\FrameworkServiceException
     */
    public function getParameterContainer() : Sway\Component\Parameter\Container
    {
        if (array_key_exists('parameter_container', $this->variables)){
            return $this->variables['parameter_container'];
        }
        
        throw Exception\FrameworkServiceException::variableNotExists('parameter_container');
    }
}

?>