<?php   
class grForma Extends Model {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    private function atxt($s) {
        $A=null;
        $S=explode('|',$s);
        if(!empty($S)) foreach($S as $v) {
            $V=explode('~',$v);
            if(!empty($V)) $A[$V[0]]=$V[1];
        }
        return $A;
    }
    
    function Config() {
        $Config= array(
            'asunto'=> 'Forma TROPPO',
            'remitente'=> 'tienda@troppomoda.com',
            'correo'=> 'tienda@troppomoda.com',
            // 'bcc'=> 'troppo@qom.mx',
            'redirect'=> null,
            'referer'=> 'troppo'      
        );
        return $Config;
    }
    
    function Enviar($xdat=null) {
        if(empty($xdat) || !is_array($xdat)) $this->redirect('/?e=forma_Enviar_xdat');
        $Cnf = grForma::Config();
        $email_regular_expression="/^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~ ])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~ ]+\\.)+[a-zA-Z]{2,4}\$/";
        if(!filter_var($Cnf['correo'], FILTER_VALIDATE_EMAIL)) $this->redirect('/?e=forma_config_correo');
        $correo=$Cnf['correo'];
        if(!empty($xdat['xcorreo'])) {$correo=$xdat['xcorreo'];unset($xdat['xcorreo']);}
        $redirect='/forma/';
        if(!empty($Cnf['redirect'])) $redirect=$Cnf['redirect'];
        if(!empty($xdat['xredirect'])) {$redirect=$xdat['xredirect'];unset($xdat['xredirect']);}
        if(substr($redirect, -1)!='/') $redirect.='/';
        if(!empty($xdat['xcmd'])) {$redirect.='enviada-'.$xdat['xcmd'];unset($xdat['xcmd']);}
        $remitente=$correo;
        if(!empty($Cnf['remitente'])) {
            if(!filter_var($Cnf['remitente'], FILTER_VALIDATE_EMAIL)) $this->redirect('/?e=forma_config_remitente');
            $remitente=$Cnf['remitente'];
        }
        if(!empty($xdat['xremitente'])) if(filter_var($xdat['xremitente'], FILTER_VALIDATE_EMAIL)) {$remitente=$xdat['xremitente'];unset($xdat['xremitente']);}
        $asunto='Forma Automática Web';
        if(!empty($Cnf['asunto'])) $asunto=$Cnf['asunto'];
        if(!empty($xdat['xasunto'])) {$asunto=$xdat['xasunto'];unset($xdat['xasunto']);}
        $asunto=mb_encode_mimeheader($asunto,'UTF-8');
        $titulo=null;
        if(!empty($xdat['xtitulo'])) {$titulo=$xdat['xtitulo'];unset($xdat['xtitulo']);}
        $titulos=null;
        if(!empty($xdat['xtitulos'])) {if(is_array($xdat['xtitulos'])) $titulos=$xdat['xtitulos']; unset($xdat['xtitulos']);}
        $encabezado='From: '.$remitente."\nMIME-Version: 1.0\n";
        $content="Content-Type: text/plain; charset=UTF-8;\nContent-Transfer-Encoding: 8bit\n";
        if(!empty($xdat['xconfirma'])) {
            $cx=$xdat['xconfirma'];
            unset($xdat['xconfirma']);
            if(!empty($xdat[$cx])) if(filter_var($xdat[$cx], FILTER_VALIDATE_EMAIL)) {
                $datos="\nSe ha enviado la información y recibirá respuesta a la brevedad\n\n";
                $datos.=$_SERVER['HTTP_REFERER']."\n";
                $datos.=date("Y-m-d H:i:s O")."\n\n";
                if(!empty($titulo)) $datos.= $titulo."\n\n";
                $datos.="--------------------------------------------------\n\n";
                mail($xdat[$cx],$asunto,$datos,$encabezado.$content);
            }
        }
        $copia=null;
        if(!empty($Cnf['copia'])) {
            if(!filter_var($Cnf['copia'], FILTER_VALIDATE_EMAIL)) $this->redirect('/?e=forma_config_copia');
            $copia=$Cnf['copia'];
        }
        if(!empty($xdat['xcopia'])) if(filter_var($xdat['xcopia'], FILTER_VALIDATE_EMAIL)) {$copia=$xdat['xcopia'];unset($xdat['xcopia']);}
        if(!empty($copia)) $encabezado.='CC: '.$copia."\n";
        if(!empty($Cnf['bcc'])) {
            if(!filter_var($Cnf['bcc'], FILTER_VALIDATE_EMAIL)) $this->redirect('/?e=forma_config_bcc');
            $encabezado.="BCC: ".$Cnf['bcc']."\n";
        }
        $file=null;
        if(!empty($xdat['xfile'])) {
          $f=$xdat['xfile']; unset($xdat['xfile']);
          if(!empty($_FILES[$f]['name']))
            if (is_uploaded_file($_FILES[$f]['tmp_name']))
              if (empty($_FILES[$f]['error'])) $file=$f;
        }
        $reply=null;
        $datos="FORMA WEB\n";
        $datos.=$_SERVER['HTTP_REFERER']."\n";
        $datos.=date("Y-m-d H:i:s O")."\n\n";
        if(!empty($titulo)) $datos.= $titulo."\n\n";
        $datos.="--------------------------------------------------\n\n";
        while (list($k, $v) = each($xdat)) {
            if(!empty($v)) {
                if(strtolower($k)=='e-mail' || strtolower($k)=='email') if(filter_var($v, FILTER_VALIDATE_EMAIL)) $reply=$v;
                if(!empty($titulos[$k])) $k=$titulos[$k];
                $datos.="$k:  $v\n\n";      
            }
        }
        $datos.="--------------------------------------------------\n\n";
        if(!empty($reply)) $encabezado.= 'Reply-To: '.$reply."\n";
        if(empty($file)) $encabezado.=$content;
        else {
            $limite= '--'. md5(rand());
            $encabezado.="Content-Type: multipart/mixed;\n  boundary=\"".$limite."\"\n";
            $datos= "This is a multi-part message in MIME format.\n--".$limite."\n".$content."\n".$datos."\n--".$limite."\n"."Content-Type: ".$_FILES[$file]['type'].";\n  name=\"".$_FILES[$file]['name']."\"\nContent-Transfer-Encoding: base64\nContent-Disposition: attachment;\n  filename=\"".$_FILES[$file]['name']."\"\n\n";
            $datos.= chunk_split(base64_encode(file_get_contents($_FILES[$file]['tmp_name'])))."\n--".$limite."--"."\n";
        }
        mail($correo,$asunto,$datos,$encabezado);
    }
    
    public function paises() {
        $Paises=array(
'AF'=>'Afganistán',
'AL'=>'Albania',
'DE'=>'Alemania',
'AD'=>'Andorra',
'AO'=>'Angola',
'AI'=>'Anguilla',
'AQ'=>'Antártida',
'AG'=>'Antigua y Barbuda',
'AN'=>'Antillas Holandesas',
'SA'=>'Arabia Saudí',
'DZ'=>'Argelia',
'AR'=>'Argentina',
'AM'=>'Armenia',
'AW'=>'Aruba',
'AU'=>'Australia',
'AT'=>'Austria',
'AZ'=>'Azerbaiyán',
'BS'=>'Bahamas',
'BH'=>'Bahrein',
'BD'=>'Bangladesh',
'BB'=>'Barbados',
'BE'=>'Bélgica',
'BZ'=>'Belice',
'BJ'=>'Benin',
'BM'=>'Bermudas',
'BY'=>'Bielorrusia',
'MM'=>'Birmania',
'BO'=>'Bolivia',
'BA'=>'Bosnia y Herzegovina',
'BW'=>'Botswana',
'BR'=>'Brasil',
'BN'=>'Brunei',
'BG'=>'Bulgaria',
'BF'=>'Burkina Faso',
'BI'=>'Burundi',
'BT'=>'Bután',
'CV'=>'Cabo Verde',
'KH'=>'Camboya',
'CM'=>'Camerún',
'CA'=>'Canadá',
'TD'=>'Chad',
'CL'=>'Chile',
'CN'=>'China',
'CY'=>'Chipre',
'VA'=>'Ciudad del Vaticano (Santa Sede)',
'CO'=>'Colombia',
'KM'=>'Comores',
'CG'=>'Congo',
'CD'=>'Congo, República Democrática del',
'KR'=>'Corea',
'KP'=>'Corea del Norte',
'CI'=>'Costa de Marfíl',
'CR'=>'Costa Rica',
'HR'=>'Croacia (Hrvatska)',
'CU'=>'Cuba',
'DK'=>'Dinamarca',
'DJ'=>'Djibouti',
'DM'=>'Dominica',
'EC'=>'Ecuador',
'EG'=>'Egipto',
'SV'=>'El Salvador',
'AE'=>'Emiratos Árabes Unidos',
'ER'=>'Eritrea',
'SI'=>'Eslovenia',
'ES'=>'España',
'US'=>'Estados Unidos',
'EE'=>'Estonia',
'ET'=>'Etiopía',
'FJ'=>'Fiji',
'PH'=>'Filipinas',
'FI'=>'Finlandia',
'FR'=>'Francia',
'GA'=>'Gabón',
'GM'=>'Gambia',
'GE'=>'Georgia',
'GH'=>'Ghana',
'GI'=>'Gibraltar',
'GD'=>'Granada',
'GR'=>'Grecia',
'GL'=>'Groenlandia',
'GP'=>'Guadalupe',
'GU'=>'Guam',
'GT'=>'Guatemala',
'GY'=>'Guayana',
'GF'=>'Guayana Francesa',
'GN'=>'Guinea',
'GQ'=>'Guinea Ecuatorial',
'GW'=>'Guinea-Bissau',
'HT'=>'Haití',
'HN'=>'Honduras',
'HU'=>'Hungría',
'IN'=>'India',
'ID'=>'Indonesia',
'IQ'=>'Irak',
'IR'=>'Irán',
'IE'=>'Irlanda',
'BV'=>'Isla Bouvet',
'CX'=>'Isla de Christmas',
'IS'=>'Islandia',
'KY'=>'Islas Caimán',
'CK'=>'Islas Cook',
'CC'=>'Islas de Cocos o Keeling',
'FO'=>'Islas Faroe',
'HM'=>'Islas Heard y McDonald',
'FK'=>'Islas Malvinas',
'MP'=>'Islas Marianas del Norte',
'MH'=>'Islas Marshall',
'UM'=>'Islas menores de Estados Unidos',
'PW'=>'Islas Palau',
'SB'=>'Islas Salomón',
'SJ'=>'Islas Svalbard y Jan Mayen',
'TK'=>'Islas Tokelau',
'TC'=>'Islas Turks y Caicos',
'VI'=>'Islas Vírgenes (EE.UU.)',
'VG'=>'Islas Vírgenes (Reino Unido)',
'WF'=>'Islas Wallis y Futuna',
'IL'=>'Israel',
'IT'=>'Italia',
'JM'=>'Jamaica',
'JP'=>'Japón',
'JO'=>'Jordania',
'KZ'=>'Kazajistán',
'KE'=>'Kenia',
'KG'=>'Kirguizistán',
'KI'=>'Kiribati',
'KW'=>'Kuwait',
'LA'=>'Laos',
'LS'=>'Lesotho',
'LV'=>'Letonia',
'LB'=>'Líbano',
'LR'=>'Liberia',
'LY'=>'Libia',
'LI'=>'Liechtenstein',
'LT'=>'Lituania',
'LU'=>'Luxemburgo',
'MK'=>'Macedonia, Ex-República Yugoslava de',
'MG'=>'Madagascar',
'MY'=>'Malasia',
'MW'=>'Malawi',
'MV'=>'Maldivas',
'ML'=>'Malí',
'MT'=>'Malta',
'MA'=>'Marruecos',
'MQ'=>'Martinica',
'MU'=>'Mauricio',
'MR'=>'Mauritania',
'YT'=>'Mayotte',
'MX'=>'México',
'FM'=>'Micronesia',
'MD'=>'Moldavia',
'MC'=>'Mónaco',
'MN'=>'Mongolia',
'MS'=>'Montserrat',
'MZ'=>'Mozambique',
'NA'=>'Namibia',
'NR'=>'Nauru',
'NP'=>'Nepal',
'NI'=>'Nicaragua',
'NE'=>'Níger',
'NG'=>'Nigeria',
'NU'=>'Niue',
'NF'=>'Norfolk',
'NO'=>'Noruega',
'NC'=>'Nueva Caledonia',
'NZ'=>'Nueva Zelanda',
'OM'=>'Omán',
'NL'=>'Países Bajos',
'PA'=>'Panamá',
'PG'=>'Papúa Nueva Guinea',
'PK'=>'Paquistán',
'PY'=>'Paraguay',
'PE'=>'Perú',
'PN'=>'Pitcairn',
'PF'=>'Polinesia Francesa',
'PL'=>'Polonia',
'PT'=>'Portugal',
'PR'=>'Puerto Rico',
'QA'=>'Qatar',
'UK'=>'Reino Unido',
'CF'=>'República Centroafricana',
'CZ'=>'República Checa',
'ZA'=>'República de Sudáfrica',
'DO'=>'República Dominicana',
'SK'=>'República Eslovaca',
'RE'=>'Reunión',
'RW'=>'Ruanda',
'RO'=>'Rumania',
'RU'=>'Rusia',
'EH'=>'Sahara Occidental',
'KN'=>'Saint Kitts y Nevis',
'WS'=>'Samoa',
'AS'=>'Samoa Americana',
'SM'=>'San Marino',
'VC'=>'San Vicente y Granadinas',
'SH'=>'Santa Helena',
'LC'=>'Santa Lucía',
'ST'=>'Santo Tomé y Príncipe',
'SN'=>'Senegal',
'SC'=>'Seychelles',
'SL'=>'Sierra Leona',
'SG'=>'Singapur',
'SY'=>'Siria',
'SO'=>'Somalia',
'LK'=>'Sri Lanka',
'PM'=>'St. Pierre y Miquelon',
'SZ'=>'Suazilandia',
'SD'=>'Sudán',
'SE'=>'Suecia',
'CH'=>'Suiza',
'SR'=>'Surinam',
'TH'=>'Tailandia',
'TW'=>'Taiwán',
'TZ'=>'Tanzania',
'TJ'=>'Tayikistán',
'TF'=>'Territorios Franceses del Sur',
'TP'=>'Timor Oriental',
'TG'=>'Togo',
'TO'=>'Tonga',
'TT'=>'Trinidad y Tobago',
'TN'=>'Túnez',
'TM'=>'Turkmenistán',
'TR'=>'Turquía',
'TV'=>'Tuvalu',
'UA'=>'Ucrania',
'UG'=>'Uganda',
'UY'=>'Uruguay',
'UZ'=>'Uzbekistán',
'VU'=>'Vanuatu',
'VE'=>'Venezuela',
'VN'=>'Vietnam',
'YE'=>'Yemen',
'YU'=>'Yugoslavia',
'ZM'=>'Zambia',
'ZW'=>'Zimbawe'
);
        return $Paises;
    }
    
}
?>