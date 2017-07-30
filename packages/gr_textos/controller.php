<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrTextosPackage extends Package {

    protected $pkgHandle = 'gr_textos';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.5.0';
    
    public function getPackageDescription() {
        return t("Textos");
    }
    
    public function getPackageName() {
        return t("Textos");
    }
    
    public function install() {
        $fDir= realpath(dirname(__FILE__) .'/../..') . '/files/textos';
        mkdir($fDir);
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/_admin/textos', $pkg);
        $d1->update(array('cName' => 'Admin Textos', 'cDescription' => 'Admin Textos'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/_admin/textos/informacion', $pkg);
        $d1->update(array('cName' => 'Admin Información', 'cDescription' => 'Admin Textos Información'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/textos', $pkg);
        $d1->update(array('cName' => 'Textos', 'cDescription' => 'Textos'));
    }
    
}
?>