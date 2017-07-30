<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class TextosController Extends Controller {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function view($xinfo=null) {
        $xcmd=null;
        $xcat=0;
        $xdat=null;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextos ORDER BY dTitulo");
        while($rx=$rq->fetchrow()) $xdat[]=$rx;
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
        $this->set('xcat',$xcat);
    }

}
?>