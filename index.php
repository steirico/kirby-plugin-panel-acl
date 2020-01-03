<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('steirico/kirby-plugin-panel-acl', [
    'api' => [
        'routes' => [
            [
                'pattern' => 'panel-acl/pages',
                'action'  => function () {
                    $pages = site()->pages();
                    $resultPages = $pages->toArray(function($page){
                        $image = $page->image();
                        $image = $image ? $image->thumb(76)->url() : '';

                        return [
                            'image' => [
                                'url' => $image,
                                'cover' => true
                            ],
                            'icon' => [
                                'type' => $page->blueprint()->icon(),
                                'back' => 'pattern'
                            ],
                            'text' => $page->title()->value(),
                            'link' => '/pages/'.$page->id()
                        ];

                    });
                    $res = $resultPages;
                    return $res;
                }
            ]
        ]
    ],
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
                    'changeTitle' => Closure::fromCallable("PanelAcl::canAccessSite"),
                    'update' => Closure::fromCallable("PanelAcl::canAccessSite")
                ],
                'pages' => [
                    'changeSlug'     => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'changeStatus'   => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'changeTemplate' => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'changeTitle'    => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'create'         => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'delete'         => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'duplicate'      => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'preview'        => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'read'           => true,
                    'sort'           => Closure::fromCallable("PanelAcl::canAccessPage"),
                    'update'         => Closure::fromCallable("PanelAcl::canAccessPage")
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
