Sententiaregum Custom User Bundle
=================================

Bundle which integrates the user model to an symfony2 application and extends this model in order to have a full 
complete bundle.

###Enable Bundle

Register bundle:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Sententiaregum\Bundle\UserBundle\SententiaregumUserBundle
    ];
}
```

Enable routing:

``` yaml
# app/config/routing.yml

sententiaregum_user:
    resource: "@SententiaregumUserBundle/Controller/"
    type:     annotation
    prefix:   /api
```

Configuration reference:

[Default values of configuration](config_reference.md)
