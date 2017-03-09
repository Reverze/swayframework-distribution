<?php

namespace Sway\Distribution\Composer;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Composer;
use Composer\IO\IOInterface;

use Sway\Distribution\FrameworkDistribution;
use Sway\Distribution\Extension\ExtensionManager;
use Sway\Distribution\Container\ContainerBuilder;

class EventHandler
{
    /**
     * Framework root directory path
     * @var string
     */
    private static $frameworkRootDirectory = null;
    
    
    /**
     * Input\Output interface
     * @var \Composer\IO\IOInterface
     */
    private static $io = null;
    
    /**
     * Framework distribution interface
     * @var \Sway\Distribution\FrameworkDistribution
     */
    private static $frameworkDistribution = null;
    
    public function __construct()         
    {
        
    }
    
    public static function postUpdate(Event $event)
    {
        $vendorPath = $event->getComposer()->getConfig()->get('vendor-dir');
        
        $composer = $event->getComposer();
        
        $repositoryManager = $composer->getRepositoryManager();
        
        $installationManager = $composer->getInstallationManager();
        $localRepository = $repositoryManager->getLocalRepository();
        
        $packages = $localRepository->getPackages();
        
        
        foreach ($packages as $package){
            if ($package->getType() === 'sf-package'){
                var_dump($package->getAutoload());
                $installPath = $installationManager->getInstallPath($package);
                
                var_dump($installPath);
            }
        }
    }
    
    public static function onPostUpdate(Event $event)
    {
        static::initializeIO($event->getIO());
        static::initializeComposer($event->getComposer());
        static::initializeDistribution();
        
        $repositoryManager = $event->getComposer()->getRepositoryManager();
        $installationManager = $event->getComposer()->getInstallationManager();
        $localRepository = $repositoryManager->getLocalRepository();
        
        /**
         * Initializes extension manager
         */
        $extensionManager = new ExtensionManager(self::$frameworkDistribution->getStorage());
        
        
        foreach ($localRepository->getPackages() as $package){
            if ($package->getType() === 'sf-package'){
                $autoload = $package->getAutoload();
                
                /**
                 * Only PSR-4 autoloader is supported
                 */
                if (!array_key_exists('psr-4', $autoload)){
                    throw Exception\EventHandlerException::onlyPSR4Autoload();
                }
                
                $extensionManager->registerExtension(
                        $package->getName(),
                        $autoload['psr-4'],
                        $package->getType());
                
                
            }
        }
        
        /**
         * Creates a new container builder
         */
        $containerBuilder = new ContainerBuilder(self::$frameworkDistribution->getStorage());
        $extensionManager->loadExtensionConfigs($containerBuilder);
        
    }
    
    private static function initializeDistribution()
    {
        /**
         * Initializes framework distribution interface
         */
        static::$frameworkDistribution = new FrameworkDistribution(self::$frameworkRootDirectory);
    }
    
    /**
     * Initialize Input\Output inteface
     * @param \Composer\IO\IOInterface $ioInterface
     */
    private static function initializeIO(IOInterface $ioInterface)
    {
        static::$io = $ioInterface;
    }
    
    /**
     * Writes error to standard output stream
     * @param string $message
     */
    private static function writeError(string $message)
    {
        static::$io->writeError($message);
    }
    
    private static function initializeComposer(Composer $composer)
    {
        /**
         * Gets framework root directory
         */
        static::$frameworkRootDirectory = static::getRootDir($composer->getConfig()->get('vendor-dir'));
             
    }
    
    /**
     * Gets root directory path
     * @param string $vendorDir Vendor dir path
     * @return string
     */
    private static function getRootDir(string $vendorDir) : string
    {
        return dirname($vendorDir);
    }
    
}


?>