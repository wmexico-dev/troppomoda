<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrRedirectPackage extends Package {

    protected $pkgHandle = 'gr_redirect';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.0.0';
    
    public function getPackageDescription() {
        return t("Redirect");
    }
    
    public function getPackageName() {
        return t("Redirect");
    }
    
        public function install()
    {
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/blog', $pkg);
        $d1->update(array('cName' => 'Blog', 'cDescription' => 'Blog'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);
    }
    
}
?>