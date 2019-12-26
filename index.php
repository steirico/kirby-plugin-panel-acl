<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('steirico/kirby-plugin-panel-acl', [
    'blueprints' => [
        'users/panel-acl' => [
            'title'    => 'Panel ACLs',
            'name'    => 'panel-acl',
            'description'    => 'User-based ACLs for the Kirby Panel',
            'extends' => 'users/default',
            'tabs' => (function(){
                $re = Kirby\Data\Data::read(__DIR__ . '/blueprints/tabs/panel-acl.yml', 'yaml');
                return $re;
            })(),
            'permissions' => [
                'access' => [
                    'users' => false,
                    'site' => true,
                    'settings' => false,
                    'panel' => true
                ],
                'site' => [
                    'changeTitle' => false,
                    'update'      => false
                ],
                'pages' => [
                    'changeSlug'     => function(){ 
                        return false;
                    },
                    'changeStatus'   => false,
                    'changeTemplate' => false,
                    'changeTitle'    => false,
                    'create'         => false,
                    'delete'         => false,
                    'duplicate'      => false,
                    'preview'        => false,
                    'read'           => function($page, $user){
                        return true;
                    },
                    'sort'           => false,
                    'update'         => false
                ],
                'files' => [
                    'changeName' => false,
                    'create'     => false,
                    'delete'     => false,
                    'replace'    => false,
                    'update'     => false
                ],
                'users' => [
                    'changeEmail'    => false,
                    'changeLanguage' => false,
                    'changeName'     => false,
                    'changePassword' => false,
                    'changeRole'     => false,
                    'create'         => false,
                    'delete'         => false,
                    'update'         => false
                ],
                'user' => [
                    'changeEmail'    => false,
                    'changeLanguage' => true,
                    'changeName'     => false,
                    'changePassword' => true,
                    'changeRole'     => true,
                    'delete'         => false,
                    'update'         => false
                ]
            ]
        ]
    ]
]);
