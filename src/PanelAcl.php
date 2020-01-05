<?php

class PanelAcl {
    public static function canAccessSite($page, $user){
        return $user->content()->toSite()->toBool();
    }

    public static function canAccessPage($page, $user){
        $userContent = $user->content();
        $toSpecificPages = $userContent->toSpecificPages()->toPages();

        if(is_a($toSpecificPages, "Kirby\Cms\Pages")){
            if($toSpecificPages->findByKey($page->id())){
                return true;
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
    }
}