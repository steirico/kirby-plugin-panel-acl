<?php

use \Kirby\Cms\User;
use \Kirby\Cms\Site;
use \Kirby\Cms\Page;
use \Kirby\Cms\File;

class PanelAclUser extends User {
    public function canAccessSite(string $action, Site $site): bool {
        return $this->content()->toSite()->toBool();
    }

    public function canAccessPage(string $action, Page $page = null): bool {
        if ($page == null) {
            return false;
        }
        
        $userContent = $this->content();
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
                if($pageUsers->findByKey($this->id())){
                    return true;
                }
            }
        }

        return false;
    }

    public function canAccessFile(string $action, File $file): bool {
        return $this->canAccessPage($action, $file->page());
    }
}