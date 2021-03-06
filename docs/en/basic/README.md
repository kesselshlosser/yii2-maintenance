Connection and setup
====================

Connecting and configure for the newly installed [yii2-app-basic](https://github.com/yiisoft/yii2-app-basic) basic template.

app/config/web.php
```php
<?php

use dominus77\maintenance\Maintenance;
use dominus77\maintenance\interfaces\StateInterface;
use dominus77\maintenance\states\FileState;
use dominus77\maintenance\filters\URIFilter;
use dominus77\maintenance\filters\UserFilter;
use dominus77\maintenance\controllers\frontend\MaintenanceController;
use dominus77\maintenance\controllers\backend\MaintenanceController as BackendMaintenanceController;

//...

$config = [
    //...
    'language' => 'en',
    //...
    'bootstrap' => [
        //...      
        Maintenance::class
    ],
    //...
    'container' => [
        'singletons' => [
            Maintenance::class => [
                'class' => Maintenance::class,
                'route' => 'maintenance/index',
                // Filters
                'filters' => [
                    // Routes for which to ignored mode
                    [
                        'class' => URIFilter::class,
                        'uri' => [
                            'debug/default/view',
                            'debug/default/toolbar',                            
                            'site/login',
                            'site/logout'
                        ]
                    ],
                    // Users for whom to ignored mode
                    [
                        'class' => UserFilter::class,
                        'checkedAttribute' => 'username',
                        'users' => ['admin']
                    ]                    
                ],
            ],
            StateInterface::class => [
                'class' => FileState::class,
                'subscribeOptions' => [                    
                    'template' => [
                        'html' => '@dominus77/maintenance/mail/emailNotice-html'
                    ]
                ],
                'directory' => '@runtime'
            ]
        ]
    ],    
    'controllerMap' => [
        //...
        'maintenance' => [
            'class' => MaintenanceController::class                     
        ],
        'maintenance-admin' => [
            'class' => BackendMaintenanceController::class,                                 
            'roles' => ['@'] // Authorized User
        ]
    ],
    //...
];
```
app/config/params.php
```php
<?php

return [
    //...
    'frontendUrl' => 'http://yii2-basic.loc',
];
```
app/config/console.php
```php
<?php

use dominus77\maintenance\BackendMaintenance;
use dominus77\maintenance\interfaces\StateInterface;
use dominus77\maintenance\states\FileState;
use dominus77\maintenance\commands\MaintenanceController;

//...

$config = [
    //...
    'language' => 'en',
    //...
    'bootstrap' => [
        //...
        BackendMaintenance::class
    ],    
    'container' => [
        'singletons' => [
            StateInterface::class => [
                'class' => FileState::class,
                // Configure templates for subscribers
                'subscribeOptions' => [                    
                    'template' => [
                        'html' => '@dominus77/maintenance/mail/emailNotice-html'
                    ]                     
                ],
                'directory' => '@runtime'
            ]
        ]
    ],    
    'controllerMap' => [
        //...
        'maintenance' => [
            'class' => MaintenanceController::class
        ]
    ],    
    'components' => [
        //..        
        'urlManager' => [
            'hostInfo' => $params['frontendUrl'], // http://yii2-basic.loc
            //...
        ]
    ],
    //...
];
```

Use
---
* [Filters](../common/filters.md)
* [Console commands](../common/console-commands.md)

Link to the admin interface web interface `http://yii2-basic.loc/maintenance-admin/index`

![maintenance.png](../images/maintenance-backend-basic.png)
