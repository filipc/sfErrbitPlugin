<?php

require_once( dirname(__FILE__) . "/vendor/Errbit.php" );

class sfErrbitLogger extends sfLogger
{

    static public function errbit(){
        return Errbit::instance()
        ->configure(array(
            'api_key'           => sfConfig::get('app_errbit_api_key'),
            'host'              => sfConfig::get('app_errbit_host'),
            'port'              => sfConfig::get('app_errbit_port'),
            'secure'            => sfConfig::get('app_errbit_secure'),
            'project_root'      => sfProjectConfiguration::guessRootDir(),
            'environment_name'  => $_SERVER['SERVER_NAME']."_".sfProjectConfiguration::getActive()->getEnvironment(),
            'params_filters'    => sfConfig::get('app_errbit_params_filters'),
            'backtrace_filters' => sfConfig::get('app_errbit_backtrace_filters')
        ))
        ->start();
    }

    private static function isEnabled(){
        return ((boolean) sfConfig::get('app_errbit_enabled') === true);
    }

    static public function logThrownException(sfEvent $event)
    {
        if (self::isEnabled()) {
            self::errbit()->notify($event->getSubject());
        }
    }

    public function initialize(sfEventDispatcher $dispatcher, $options = array()){
        if (self::isEnabled()) {
            self::errbit();
        }
        parent::initialize($dispatcher, $options);
    }

    public function doLog($message, $priority){
        return true;
    }

}
