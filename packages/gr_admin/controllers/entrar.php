<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class EntrarController Extends Controller {

    public function on_start() {
        $this->redirect('/login/');
    }

}
?>