<?php

class PanelAcl {
    public static function canAccessSiteClosure($action){
    
        return function ($page, $user) use ($action){
            return $user->content()->toSite()->toBool();
        };
    }

    public static function canAccessPageClosure($action){
    
        return function($page, $user) use ($action){
            $userContent = $user->content();
            $toSpecificPages = $userContent->toSpecificPages()->toPages();

            if(is_a($toSpecificPages, "Kirby\Cms\Pages")){
                if($toSpecificPages->findByKey($page->id())){
                    return true;
                }

                foreach($toSpecificPages as $parentPage){
                    if ($page->isDescendantOf($parentPage)) {
                        return true;
                    }
                }
            }

            if($userContent->toRelatedPages()->toBool()){
                $pageUsers = $page->panelAclPageUsers();
                if(is_a($pageUsers, "Kirby\Cms\Users")) {
                    if($pageUsers->findByKey($user->id())){
                        return true;
                    }
                }
            }

            return false;
        };
    }

    public static function canAccessFileClosure($action){

        return PanelAcl::canAccessPageClosure($action);
    }
}