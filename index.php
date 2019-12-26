<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('steirico/kirby-plugin-panel-acl', [
    'blueprints' => [
        'users/my-role' => [
            'title'    => 'MyRole',
            'name'    => 'MyRole',
            'description'    => 'MyRole',
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
                        return true;
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
            ]
        ]
    ]
]);
