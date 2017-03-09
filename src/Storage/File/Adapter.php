<?php

namespace Sway\Distribution\Storage\File;


class Adapter
{
    /**
     * File path
     * @var string
     */
    private $filePath = null;
    
    /**
     * Determines if pretty printing is enabled
     * @var bool
     */
    private $prettyPrinting = true;
    
    public function __construct(string $filePath)
    {
        if (empty($this->filePath)){
            $this->filePath = $filePath;
        }
        
        if (!is_file($this->filePath)){
            $this->createEmptyFile();
        }
    }
    
    /**
     * Creates an empty file 
     * @throws \Sway\Distribution\Storage\File\Exception\FileException
     */
    private function createEmptyFile()
    {
        if (!is_dir(dirname($this->filePath))){
            mkdir(dirname($this->filePath), 0777, true);
        }
        
        /**
         * Creates file resource
         */
        $file = fopen($this->filePath, "w");
       
        
        /**
         * If error occured
         */
        if (!$file){
            throw Exception\FileException::fileHandlerException($this->filePath);
        }
        
        /**
         * Writes empty json data
         */
        fwrite($file, json_encode([]) );
        /**
         * Ends operation
         */
        fclose($file); 
        
    }
    
    /**
     * Enables pretty printing
     */
    public function enablePrettyPrinting()
    {
        $this->prettyPrinting = true;
    }
    
    /**
     * Disables pretty printing
     */
    public function disablePrettyPrinting()
    {
        $this->prettyPrinting = false;
    }
    
    /**
     * Gets context
     * @return array
     * @throws \Sway\Distribution\Storage\File\Exception\FileException
     */
    public function getContext() : array
    {
        /**
         * Creates a file resource
         */
        $file = fopen($this->filePath, "r");
        
        /**
         * If error occured
         */
        if (!$file){
            throw Exception\FileException::fileHandlerException($this->filePath);
        }
        
        /*
         * Reads JSON context
         */
        $context = fread($file, filesize($this->filePath));
        
        fclose($file);
        
        /**
         * Decodes json
         */
        $decoded = json_decode($context, true);
        
        return is_array($decoded) ? $decoded : array();
    }
    
    /**
     * Saves context
     * @param array $context
     * @throws \Sway\Distribution\Storage\File\Exception\FileException
     */
    public function saveContext(array $context) : bool
    {
        /**
         * Creates a file resource
         */
        $file = fopen($this->filePath, "w");
        
        /**
         * If error occured
         */
        if (!$file){
            throw Exception\FileException::fileHandlerException($this->filePath);
        }
        
        fwrite($file, json_encode($context, ($this->prettyPrinting) ? JSON_PRETTY_PRINT : 0));
        
        fclose($file);
        
        
        return true;
    }
    
    /**
     * Removes file from storage
     * @throws \Sway\Distribution\Storage\File\Exception\FileException
     */
    public function remove()
    {
        /**
         * Before the delete, we must ensure that file is exists
         */
        if (is_file($this->filePath)){
            
            /**
             * If file remove failed, throws an exception
             */
            if (!unlink($this->filePath)){
                throw Exception\FileException::fileUnlinkFailed($this->filePath);
            }
        }
        else{
            throw Exception\FileException::tryToRemoveNonExistingFile($this->filePath);
        }
    }
    
}


?>