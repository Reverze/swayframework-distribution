<?php

namespace Sway\Distribution\Mapping;

use Sway\Distribution\Extension;
use Sway\Distribution\Storage;

/**
 * Helps to create class map with given criterias
 */
class ClassFounder
{
    /**
     *
     * @var \Sway\Distribution\Extension\ExtensionManager
     */
    private $extensionManager = null;
    
    /**
     * Storage driver
     * @var \Sway\Distribution\Storage\StorageDriver
     */
    private $storageDriver = null;
    
    /**
     * Framework working directory
     * @var string
     */
    private $frameworkWorkingDirectory = null;
    
    
    public function __construct(Extension\ExtensionManager $extensionManager, Storage\StorageDriver $storageDirver, 
            array $distributionParameters)
    {
        if (empty($this->extensionManager)){
            $this->extensionManager = $extensionManager;
        }
        
        if (empty($this->storageDriver)){
            $this->storageDriver = $storageDirver;
        }
        
        if (!array_key_exists('frameworkPwd', $distributionParameters)){
            throw Exception\ClassFounderException::frameworkWorkingDirectoryIsNotSpecifed();
        }
        
        $this->frameworkWorkingDirectory = $distributionParameters['frameworkPwd'];
    }
    
    /**
     * Searchs classess by given criterias
     * @param array $searchParameters
     * @return array
     * @throws \Sway\Distribution\Mapping\ClassFounderException
     */
    public function searchBy(array $searchParameters)
    {
        /**
         * If none parameters are defined
         */
        if (sizeof($searchParameters) === 0){
            throw Exception\ClassFounderException::noneSearchParametersDefined();
        }
        
        /**
         * Creates hash to identify at cache storage
         */
        $searchParametersHash = md5(sprintf("%s#%s", $searchParameters['subnamespace'] ?? "",
                $searchParameters['sufix'] ?? ""));
        
        /**
         * Gets storage channel which stores all stored class maps
         */
        $classMapStorageChannel = $this->storageDriver->getChannel('clsmap/' . $searchParametersHash);
        
        /**
         * If class map is stored, returns
         */
        if ($classMapStorageChannel->has('map')){
            return $classMapStorageChannel->get('map');
        }
        
        /**
         * If class map is not stored, creates a new class mapper
         */
        $classMapper = $this->createClassMapper();
        
        $classMap = $classMapper->getMapFor($searchParameters['subnamespace'], $searchParameters['sufix']);
        
        $classMapStorageChannel->set('map', $classMap);
        
        return $classMap;
    }
    
    
    /**
     * Creates class mapper
     * @return \Sway\Distribution\Mapping\ClassMapper
     */
    public function createClassMapper() : ClassMapper
    {
        /**
         * Directories to search in
         */
        $directoriesToSearchIn = array();
        
        /**
         * At first we add framework working directory to search in
         */
        $directoriesToSearchIn[] = $this->frameworkWorkingDirectory;
        
        /**
         * And we add extension directories
         */
        foreach ($this->extensionManager->getExtensions() as $extension){
            $directoriesToSearchIn[] = $extension->getExtensionPath();
        }
        
        $iomap = IOMap\IOMapGenerator::generate($directoriesToSearchIn, [
            'fileExtension' => 'php'
        ]);
        
        $namespaceMap = new NamespaceMap\NamespaceMap($iomap);
        $declaredClassess = $namespaceMap->generateClassMap();
        
        $classMapper = new ClassMapper($this->storageDriver);
        $classMapper->setDeclaredClassess($declaredClassess);
        return $classMapper;
    }
    
    
    
}


?>

