<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('steirico/kirby-plugin-panel-acl', [
    'api' => [
        'routes' => [
            [
                'pattern' => 'panel-acl/pages',
                'action'  => function () {
                    $user = kirby()->user();
                    $pages = new Kirby\Cms\Pages();

                    $userContent = $user->content();
                    $toSpecificPages = $userContent->toSpecificPages()->toPages();
                    if(is_a($toSpecificPages, "Kirby\Cms\Pages")){
                        $pages->add($toSpecificPages);
                    }

                    $lastAllowedId = '';
                    $toRelatedPages = site()->index()->filter(function($page) use ($user, &$lastAllowedId){
                        $pageUsers = $page->panelAclPageUsers();
                        if(is_a($pageUsers, "Kirby\Cms\Users")) {
                            if(!empty($lastAllowedId) && strpos($page->id(), $lastAllowedId) === 0){
                                return false;
                            }
                            if($pageUsers->findByKey($user->id())){
                                $lastAllowedId = $page->id();
                                return true;
                            } else {
                                $lastAllowedId = '';
                                return false;
                            }
                        } else {
                            return false;
                        }
                    });
                    if(is_a($toRelatedPages, "Kirby\Cms\Pages")){
                        $pages->add($toRelatedPages);
                    }


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
                            'link' => '/pages/'.$page->panelId()
                        ];

                    });
                    $res = $resultPages;
                    return $res;
                }
            ]
        ]
    ],
    'userModels' => [
        'panel-acl' => 'PanelAclUser'
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
                    'acl-pages' => true,
                    'settings' => false,
                    'panel' => true
                ],
                'site' => [
                    'changeTitle'   => "user.canAccessSite('changeTitle', site)",
                    'update'        => "user.canAccessSite('update', site)"
                ],
                'pages' => [
                    'changeSlug'     => "user.canAccessPage('changeSlug', page)",
                    'changeStatus'   => "user.canAccessPage('changeStatus', page)",
                    'changeTemplate' => "user.canAccessPage('changeTemplate', page)",
                    'changeTitle'    => "user.canAccessPage('changeTitle', page)",
                    'create'         => "user.canAccessPage('create', page)",
                    'delete'         => "user.canAccessPage('delete', page)",
                    'duplicate'      => "user.canAccessPage('duplicate', page)",
                    'preview'        => "user.canAccessPage('preview', page)",
                    'read'           => true,
                    'sort'           => "user.canAccessPage('sort', page)",
                    'update'         => "user.canAccessPage('update', page)"
                ],
                'files' => [
                    'changeName' => "user.canAccessFile('changeName', file)",
                    'create'     => "user.canAccessFile('create', file)",
                    'delete'     => "user.canAccessFile('delete', file)",
                    'read'       => "user.canAccessFile('read', file)",
                    'replace'    => "user.canAccessFile('replace', file)",
                    'update'     => "user.canAccessFile('update', file)"
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
