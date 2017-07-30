<?php     defined('C5_EXECUTE') or die("Access Denied.");

/**
 * A model triggered by the on_before_render() event to check whether the page
 * should be rendered using https and redirect as necessary
 *
 * @package JBx Force SSL
 * @author Jon Bowes <jon@jbxonline.net>
 * @category Model
 * @copyright  Copyright (c) 2010 JBxHosting Ltd. (http://www.jbxonline.net)
 * @license    http://www.jbxonline.net/license/     MIT License
 *
 */
class JbxForceSsl {

    /**
	 * Returns nothing.
	 * Redirects if Force SSL conditions are met
	 * @param $page automagically passed in by the on_before_render() event
	 * @param $ssl  true if the site is currently using https
	 *
	 */
    public function checkssl($page, $ssl) {

        $pageObj = $page->getCollectionObject();
        if (!is_object($pageObj)) {
            $cache = new Cache();
            $cache->flush();
            $pageObj = Page::getCurrentPage();
        }
        
        if (is_object($pageObj)) {

            if (!$ssl && $pageObj->getAttribute('jbx_force_ssl')) {

                // We need to redirect to a secure url
                $base_url_ssl = Config::get('BASE_URL_SSL');

                // Perform a 301 redirect
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $base_url_ssl . View::url($pageObj->cPath));
                exit; // ensure no other code is executed
            }
/*
            if ($ssl && !$pageObj->getAttribute('jbx_force_ssl')) {

                // We're still using https, but it isn't required here
                // Perform a 301 redirect to a non-secure protocol
                if (substr(BASE_URL, 0, 5) == 'https') {
                    $base_url = str_replace('https://', 'http://', BASE_URL);
                } else {
                    $base_url = BASE_URL;
                }
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $base_url . View::url($pageObj->cPath));
                exit; // ensure no other code is executed
            }
*/            
        } else {
            return false;
        }

    }

}