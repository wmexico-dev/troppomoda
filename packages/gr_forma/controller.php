<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrFormaPackage extends Package {

    protected $pkgHandle = 'gr_forma';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.2.0';
    
    public function getPackageDescription() {
        return t("Forma");
    }
    
    public function getPackageName() {
        return t("Forma");
    }
    
    public function install() {
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/forma/', $pkg);
        $d1->update(array('cName' => 'Forma', 'cDescription' => 'Forma'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
    }
    
}
?>