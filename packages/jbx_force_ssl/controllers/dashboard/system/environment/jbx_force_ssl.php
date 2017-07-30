<?php   defined('C5_EXECUTE') or die('Access Denied.');

class DashboardSystemEnvironmentJbxForceSslController extends DashboardBaseController {
	
	var $helpers = array('form','concrete/interface','concrete/file');
    
    public function view() {
        
		// in order to use https, we need to have defined BASE_URL_SSL in the config
        $base_url_ssl = Config::get('BASE_URL_SSL');
        if ($base_url_ssl) {
			$base_url_ssl = str_replace('https://', '', $base_url_ssl);
			$this->set('base_url_ssl', $base_url_ssl);
        }
        
    }
	
	public function save(){
		
		if ($this->post('base_url_ssl') != '') {
			$base_url_ssl = $this->post('base_url_ssl');
			
			$base_url_ssl = str_replace('https://', '', $base_url_ssl);
			$base_url_ssl = str_replace('http://', '', $base_url_ssl);
			
			$base_url_ssl = 'https://' . $base_url_ssl;
			
			$url = parse_url($base_url_ssl);
			$base_url_ssl = $url['scheme'] . '://' . $url['host'];
			
			Config::save('BASE_URL_SSL', $base_url_ssl);
            Cache::flush();
		}
		
		$this->redirect('/dashboard/system/environment/jbx_force_ssl','url_saved');
	}
	
	public function url_saved() {
		$this->set('message', t('Base SSL URL saved.'));
		$this->view();
	}
    
}