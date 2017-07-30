<?php   

class grRedirect Extends Model {
    
    public function redirect($url=null){
        if(empty($url)) exit;
        $pageObj = Page::getCurrentPage();
        $path = View::url($pageObj->cPath);
        $url.= substr($_SERVER['REQUEST_URI'], strlen($path));
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url );
        exit;
    }
        
}
?>