HubicApiBundle
================

This Bundle provides a simple integration of the hubiC-API (https://api.hubic.com/) for Symfony2.
The API wrapper is not the "official" hubic.com library for PHP/Symfony2!

usage:
    <?php
    $hubic_api = $this->container->get('ckrupa_hubic_api');
    $result = $hubic_api->send('/account/credentials');

    ...

    if($hubic_api->isLoggedIn())
    {
        die('valid oauth token!');
    }

## Installation

### Step 1: Composer require

    $ php composer.phar require "ckrupa/hubic-api-bundle":"dev-master"

### Step 2: Enable the bundle in the kernel

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Ckrupa\HubicApiBundle\CkrupaHubicApiBundle(),
            // ...
        );
    }

### Step 3: Setup oAuth

    oAuth is done by [__HWIOAuthBundle__](https://github.com/hwi/HWIOAuthBundle) so checkout the documentation.

