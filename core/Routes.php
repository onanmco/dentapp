<?php

namespace core;

class Routes
{
    public static function getRoutes()
    {
        return [
            [
                'path'   => 'api/<controller>/<action>/<id:number>',
                'params' => [
                    'namespace' => 'api'
                ]
            ],
            [
                'path'   => 'api/<controller>/<action>',
                'params' => [
                    'namespace' => 'api'
                ]
            ],
            [
                'path'   => '',
                'params' => [
                    'controller' => 'home',
                    'action'     => 'index'
                ]
            ],
            [
                'path'   => 'kayit',
                'params' => [
                    'controller' => 'user',
                    'action'     => 'signup'
                ]
            ],
            [
                'path'   => 'giris',
                'params' => [
                    'controller' => 'user',
                    'action'     => 'login'
                ]
            ],
            [
                'path'   => 'iletisim',
                'params' => [
                    'controller' => 'contact',
                    'action'     => 'index'
                ]
            ],
            [
                'path'   => 'randevu',
                'params' => [
                    'controller' => 'appointment',
                    'action'     => 'calendar'
                ]
            ],
            [
                'path'   => '<controller>/<action>',
            ],
            
        ];
    }
}