<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class GrTiendaPackage extends Package {

    protected $pkgHandle = 'gr_tienda';
    protected $appVersionRequired = '5.3.0';
    protected $pkgVersion = '1.5.0';
    
    public function getPackageDescription() {
        return t("Tienda");
    }
    
    public function getPackageName() {
        return t("Tienda");
    }
    
    public function install()
    {
        $fDir= realpath(dirname(__FILE__) .'/../..') . '/files/tienda';
        mkdir($fDir);
        Loader::model('single_page');
        $pkg = parent::install();
        $d1 = SinglePage::add('/_admin/tienda', $pkg);
        $d1->update(array('cName' => 'Admin Tienda Artículos', 'cDescription' => 'Admin Tienda Artículos'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/_admin/tienda/categorias', $pkg);
        $d1->update(array('cName' => 'Admin Tienda Categorías', 'cDescription' => 'Admin Tienda Categorías'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/_admin/tienda/marcas', $pkg);
        $d1->update(array('cName' => 'Admin Tienda Marcas', 'cDescription' => 'Admin Tienda Marcas'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/_admin/tienda/promociones', $pkg);
        $d1->update(array('cName' => 'Admin Tienda Promociones', 'cDescription' => 'Admin Tienda Promociones'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);
        $d1 = SinglePage::add('/tienda', $pkg);
        $d1->update(array('cName' => 'Tienda', 'cDescription' => 'Tienda'));
        $d1 = SinglePage::add('/tienda/compras', $pkg);
        $d1->update(array('cName' => 'Tienda Compras', 'cDescription' => 'Tienda Compras'));
    }
    
}
?>