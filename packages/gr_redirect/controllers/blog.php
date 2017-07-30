<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class BlogController Extends Controller {

    public function on_start() {
        Loader::model('grRedirect','gr_redirect');
        grRedirect::redirect('http://blog.troppomoda.com/');
        exit;
    }
    
    public function view($x=null){}
    
}
?>       