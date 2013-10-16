# Symfony 1.4 Errbit & Airbrake Plugin

This plugin allows to connect existing Symfony 1.4 application to [Errbit](https://github.com/errbit/errbit) (or Airbrake). 

## Installation

### Git Clone

    git clone git@github.com:filipc/sfErrbitPlugin.git

## Usage

1. Place source of plugin into /plugins directory of your Symfony 1.4 project

2. Enable plugin in Symfony ProjectConfiguration class

``` php
// /config/ProjectConfiguration.class.php

$this->enablePlugins('sfErrbitPlugin');

```

2. Setup Errbit configuration

To setup an Errbit instance you need to configure it with an array of parameters. 
Only two of them are mandatory.

``` yml
# /plugins/sfErrbitPlugin/config/app.yml


all:
  errbit:
    enabled           : 0
    api_key           : 'YOUR API KEY'
    host              : 'YOUR ERRBIT HOST, OR api.airbrake.io FOR AIRBRAKE'  

# optional parameters below (uncomment if needed)

#    port              : 80
#    secure            : false
#    params_filters    : => array('/password/', '/card_number/'),
#    backtrace_filters : => array('#/some/long/path#' => '')

```

## License & Copyright

Licensed under the MIT license. Including source of git://github.com/flippa/errbit-php.git

## Contributors

- main idea @flippa
- Symfony1.4 integration @filipc
