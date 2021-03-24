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
                    'controller' => 'personel',
                    'action'     => 'kayit'
                ]
            ],
            [
                'path'   => 'giris',
                'params' => [
                    'controller' => 'personel',
                    'action'     => 'giris'
                ]
            ],
            [
                'path'   => '<controller>/<action>',
            ]
        ];
    }
}