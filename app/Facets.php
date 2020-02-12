<?php

namespace UKRI\Search;

class Facets implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('ep_feature_active', [$this, 'deactivate'], 10, 3);
    }

    public function deactivate($active, $featureSettings, $feature)
    {
        if ($feature->slug == 'facets') {
            return false;
        }
        return $active;
    }
}
