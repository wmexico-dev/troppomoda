<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrRegistroPackage extends Package {

    protected $pkgHandle = 'gr_registro';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.0.2';
    
    public function getPackageDescription() {
        return t("Registro");
    }
    
    public function getPackageName() {
        return t("Registro");
    }
    
    public function install()
    {
        $fDir= realpath(dirname(__FILE__) .'/../..') . '/files/registro';
        mkdir($fDir);
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/_admin/registro', $pkg);
        $d1->update(array('cName' => 'Admin Registro', 'cDescription' => 'Admin Registro'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/registro', $pkg);
        $d1->update(array('cName' => 'Registro', 'cDescription' => 'Registro'));
    }
    
}
?>