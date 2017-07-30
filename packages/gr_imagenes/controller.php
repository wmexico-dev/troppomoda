<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrImagenesPackage extends Package {

    protected $pkgHandle = 'gr_imagenes';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.2.0';
    
    public function getPackageDescription() {
        return t("Imágenes");
    }
    
    public function getPackageName() {
        return t("Imágenes");
    }
    
        public function install()
    {
        $fDir= realpath(dirname(__FILE__) .'/../..') . '/files/imagenes';
        mkdir($fDir);
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/_admin/imagenes', $pkg);
        $d1->update(array('cName' => 'Admin Imágenes', 'cDescription' => 'Admin Imágenes'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/_admin/imagenes/categorias', $pkg);
        $d1->update(array('cName' => 'Admin Imágenes Categorías', 'cDescription' => 'Admin Imágenes Categorías'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        BlockType::installBlockTypeFromPackage('gr_imagenes', $pkg);
    }
    
}
?>