<?php

namespace Sway\Distribution;

class FrameworkDistributionException extends \Exception
{
    /**
     * Throws an exception when framework directory was not found
     * @param string $frameworkDirectory
     * @return \Sway\Distribution\FrameworkDistributionException
     */
    public static function frameworkDirectoryNotFound(string $frameworkDirectory) : FrameworkDistributionException
    {
        return (new FrameworkDistributionException(sprintf("Framework directory not found on path '%s'", $frameworkDirectory)));
    }
    
    /**
     * Throws an exception when framework temponary directory was not found
     * @param string $frameworkTemponaryDirectoryPath
     * @return \Sway\Distribution\FrameworkDistributionException
     */
    public static function frameworkTemponaryDirectoryNotFound(string $frameworkTemponaryDirectoryPath) : FrameworkDistributionException
    {
        return (new FrameworkDistributionException(sprintf("Framework temponary directory not found on path '%s'", $frameworkTemponaryDirectoryPath)));
    }
}

?>