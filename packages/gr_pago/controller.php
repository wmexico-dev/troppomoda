<?php     

defined('C5_EXECUTE') or die(_("Access Denied."));

Class GrPagoPackage extends Package {

	protected $pkgHandle = 'gr_pago';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.0.5';
	
	public function getPackageDescription() {
		return t("Pago");
	}
	
	public function getPackageName() {
		return t("Pago");
	}
	
	public function install()
	{
		Loader::model('single_page');
		$pkg = parent::install();
        $fDir= realpath(dirname(__FILE__) .'/../..') . '/files/pago';
        mkdir($fDir);
        $d1 = SinglePage::add('/pago/', $pkg);
        $d1->update(array('cName' => 'Pago', 'cDescription' => 'Pago'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);  
        $d1 = SinglePage::add('/pago/paypal/', $pkg);
        $d1->update(array('cName' => 'Pago Paypal', 'cDescription' => 'Pago Paypal'));
        $d1->setAttribute('exclude_nav',1);
        $d1->setAttribute('exclude_page_list',1);   
        $d1->setAttribute('exclude_sitemapxml',1);      
	}

}

?>