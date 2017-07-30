<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrAdminPackage extends Package {

    protected $pkgHandle = 'gr_admin';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.0.0';
    
    public function getPackageDescription() {
        return t("Admin");
    }
    
    public function getPackageName() {
        return t("Admin");
    }
    
        public function install()
    {
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/_admin', $pkg);
        $d1->update(array('cName' => 'Admin', 'cDescription' => 'Admin'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/entrar', $pkg);
        $d1->update(array('cName' => 'Entrar', 'cDescription' => 'Login'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);
        $d1->setAttribute('exclude_sitemapxml',1);
    }
    
}
?>