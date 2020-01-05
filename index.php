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
                    'changeTitle' => PanelAcl::canAccessSiteClosure("changeTitle"),
                    'update' => PanelAcl::canAccessSiteClosure("update")
                ],
                'pages' => [
                    'changeSlug'     => PanelAcl::canAccessPageClosure("changeSlug"),
                    'changeStatus'   => PanelAcl::canAccessPageClosure("changeStatus"),
                    'changeTemplate' => PanelAcl::canAccessPageClosure("changeTemplate"),
                    'changeTitle'    => PanelAcl::canAccessPageClosure("changeTitle"),
                    'create'         => PanelAcl::canAccessPageClosure("create"),
                    'delete'         => PanelAcl::canAccessPageClosure("delete"),
                    'duplicate'      => PanelAcl::canAccessPageClosure("duplicate"),
                    'preview'        => PanelAcl::canAccessPageClosure("preview"),
                    'read'           => true,
                    'sort'           => PanelAcl::canAccessPageClosure("sort"),
                    'update'         => PanelAcl::canAccessPageClosure("update")
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
