<?php

namespace Sway\Distribution\Configuration;


class JsonReader extends Reader
{
    /**
     * {@inheritdoc}
     * @param string $filePath
     */
    public function readFile(string $filePath) : array
    {
        $json = json_decode(file_get_contents($filePath), true);
        
        if (!$json){
            throw Exception\JsonReaderException::readJsonFailed($filePath);
        }
        
        return $json;
    }
    
}


?>