<?php   

class grTienda Extends Model {
    
    public function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    public function ident($x='') {
        $r='';
        if(!empty($x) and is_string($x)) {
            $f = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
            $t = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'ñ', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
            $r= str_replace($f,$t,trim($x));
            $r= strtolower(preg_replace(array('/[^a-zA-Z0-9 ñ-]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $r));
        }
        return $r;
    }

    public function fecha($date=null) {
        $Mes = array ('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
        $F = date_parse($date);
        if(empty($F)) return null; else return sprintf("%02d", $F['day']).' / '.$Mes[$F['month']].' / '.$F['year'];
    }

    public function paginacion($pag=null) {
        $r=0;
        if(!empty($pag)) {
            if(substr($pag,0,3)=='-P-'){
                $p= substr($pag,3);
                if(is_numeric($p)) $r= intval($p);
            }
        }
        return $r;
    }

    public function truncar( $texto='',$numTruncar=0 ) {
        $numTruncar=abs($numTruncar);
        $texto = preg_replace ('/<[^>]*>/', ' ', $texto);
        $texto = str_replace("\r", '', $texto);
        $texto = str_replace("\n", ' ', $texto);
        $texto = str_replace("\t", ' ', $texto);
        $texto = trim(preg_replace('/ {2,}/', ' ', $texto)); 
        if(!empty($numTruncar) AND strlen($texto)>$numTruncar) {
            $texto = wordwrap($texto, $numTruncar, '|');
            $texto = substr($texto, 0, strpos($texto, '|')).' ...';
        }
        return $texto;
    }
    
    public function parabuscar($s) {
        $B= null;
        $s= trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $s));
        $s= self::minusc($s);
        $s= preg_replace('/[^a-z0-9 -]/','',$s);
        if(!empty($s)) $B= explode(' ',$s);
        return $B;
    }
    
    public function minusc($s) {
        $A= array('A' => 'a',
            'Á'=>'a',
            'á'=>'a',
            'B' => 'b',
            'C' => 'c',
            'D' => 'd',
            'E' => 'e',
            'É'=>'e',
            'é'=>'e',
            'F' => 'f',
            'G' => 'g',
            'H' => 'h',
            'I' => 'i',
            'Í'=>'i',
            'í'=>'i',
            'J' => 'j',
            'K' => 'k',
            'L' => 'l',
            'M' => 'm',
            'N' => 'n',
            'Ñ'=>'n',
            'ñ'=>'n',
            'O' => 'o',
            'Ó'=>'o',
            'ó'=>'o',
            'P' => 'p',
            'Q' => 'q',
            'R' => 'r',
            'S' => 's',
            'T' => 't',
            'U' => 'u',
            'Ú'=>'u',
            'ú'=>'u',
            'Ü'=>'u',
            'ü'=>'u',
            'V' => 'v',
            'W' => 'w',
            'X' => 'x',
            'Y' => 'y',
            'Z' => 'z');
        return strtr($s,$A);
    }
    
}
?>
    