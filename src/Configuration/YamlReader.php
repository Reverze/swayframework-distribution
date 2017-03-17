<?php

namespace Sway\Distribution\Configuration;

use Symfony\Component\Yaml;

class YamlReader extends Reader
{
    /**
     * {@inheritdoc}
     * @param string $filePath
     */
   public function readFile(string $filePath) : array
   {
       $parsed = Yaml\Yaml::parse(file_get_contents($filePath));
       
       return is_array($parsed) ? $parsed : array();
   }
    
}


?>