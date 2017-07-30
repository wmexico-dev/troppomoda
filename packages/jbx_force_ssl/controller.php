<?php     defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Force SSL Package
 * Adds a page attribute 'jbx_force_ssl' - set to true to require https
 * Adds an event 'on_before_render()' to perform checks and redirect
 *
 * @package JBx Force SSL
 * @author Jon Bowes <jon@jbxonline.net>
 * @category Package
 * @copyright  Copyright (c) 2010 JBxHosting Ltd. (http://www.jbxonline.net)
 * @license    http://www.jbxonline.net/license/     MIT License
 *
 */
class JbxForceSslPackage extends Package {

    protected $pkgHandle = 'jbx_force_ssl';
    protected $appVersionRequired = '5.5';
    protected $pkgVersion = '2.6';

    public function getPackageDescription() {
        return t("Force the site into SSL for specific pages");
    }

    public function getPackageName() {
        return t("Force SSL");
    }

    public function install() {

        $pkg = parent::install();

        Loader::model('attribute/categories/collection');
        $att = CollectionAttributeKey::add('boolean', array(
            'akIsAutoCreated' => 1,
            'akHandle' => 'jbx_force_ssl',
            'akName' => 'Force SSL'
        ), $pkg);

        // in order to use https, we need to have defined BASE_URL_SSL in the config
        Cache::flush();
        $base_url_ssl = Config::get('BASE_URL_SSL');
        if (!$base_url_ssl) {
            $base_url_ssl = str_replace('http://', 'https://', BASE_URL);
            Config::save('BASE_URL_SSL', $base_url_ssl);
            Cache::flush();
        }

		Loader::model('single_page');
		$p = SinglePage::add('/dashboard/system/environment/jbx_force_ssl', $pkg);
		$p->update(array('cName'=>t('Force SSL Settings'), 'cDescription'=>t('Manage your Force SSL Settings')));

    }

	public function upgrade() {
        parent::upgrade();
        Loader::model('package');
        $pkg = Package::getByHandle($this->pkgHandle);

        // in order to use https, we need to have defined BASE_URL_SSL in the config
        Cache::flush();
        $base_url_ssl = Config::get('BASE_URL_SSL');
        if (!$base_url_ssl) {
            $base_url_ssl = str_replace('http://', 'https://', BASE_URL);
            Config::save('BASE_URL_SSL', $base_url_ssl);
            Cache::flush();
        }

		Loader::model('single_page');
		$sp    = SinglePage::getListByPackage($pkg);
        $pages = array();
        foreach ($sp as $page) {
            $pages[] = $page->cPath;
        }

        if (!in_array('/dashboard/system/environment/jbx_force_ssl', $pages)) {
            $p = SinglePage::add('/dashboard/system/environment/jbx_force_ssl', $pkg);
			$p->update(array('cName'=>t('Force SSL Settings'), 'cDescription'=>t('Manage your Force SSL Settings')));
        }
    }

    public function on_start() {

        // add event
        Events::extend(
            'on_before_render',
            'JbxForceSsl',
            'checkssl',
            'packages/' . $this->pkgHandle . '/models/jbx_force_ssl.php',
            array($_SERVER['HTTPS'])
        );

    }

}