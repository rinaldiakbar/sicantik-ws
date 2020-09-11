<?php
namespace App\Util;

use Cake\Routing\Router;

class External
{
    public static function build_source_url($url)
    {
        if (strpos($url, "http") === false) {
            // Add '/' if initial web service doesn't start with '/'
            if (substr($url, 0, 1) != '/') {
                $url = '/' . $url;
            }
            $url = Router::url('/api', true) . $url;
        }
        return $url;
    }
}
?>