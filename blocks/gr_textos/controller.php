<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::block('library_file');

class GrTextosBlockController extends BlockController {
	
	var $pobj;

	protected $btTable = 'btGrTextos';
	protected $btInterfaceWidth = "600";
	protected $btInterfaceHeight = "465";
	
	public $ident = "";	
	
	public function getBlockTypeDescription() {
		return t("Añadir Textos");
	}
	
	public function getBlockTypeName() {
		return t("Textos");
	}	 
	
	public function __construct($obj = null) {		
		parent::__construct($obj); 
	}
	
	public function view(){ 
		$this->set('ident', $this->ident); 
	} 
	
	public function save($data) { 
		$args['ident'] = isset($data['ident']) ? $data['ident'] : '';
		parent::save($args);
	}
}

?>