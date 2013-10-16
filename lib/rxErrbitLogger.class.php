<?php

require_once( dirname(__FILE__) . "/vendor/Errbit.php" );

class rxErrbitLogger
{

    static private function errbit(){
        return Errbit::instance()
        ->configure(array(
            'api_key'           => sfConfig::get('app_errbit_api_key'),
            'host'              => sfConfig::get('app_errbit_host'),
            'port'              => sfConfig::get('app_errbit_port'),
            'secure'            => sfConfig::get('app_errbit_secure'),
            'project_root'      => sfProjectConfiguration::guessRootDir(),
            'environment_name'  => sfProjectConfiguration::getActive()->getEnvironment(),
            'params_filters'    => sfConfig::get('app_errbit_params_filters'),
            'backtrace_filters' => sfConfig::get('app_errbit_backtrace_filters')
        ))
        ->start();
    }

    static public function logThrownException(sfEvent $event)
    {
        if ((boolean) sfConfig::get('app_errbit_enabled') === true) {
            self::errbit()->notify($event->getSubject());
        }
    }

}
