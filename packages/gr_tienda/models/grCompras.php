<?php   

class grCompras Extends Model {
    
    function nuevo() {
        unset($_SESSION['grCompras']);
        $_SESSION['grCompras']= array();
        $_SESSION['grCompras']['numCompras']= 0;
        $_SESSION['grCompras']['total']= 0.0;
        $_SESSION['grCompras']['IDs']= array();
        $_SESSION['grCompras']['Cantidad']= array();
        $_SESSION['grCompras']['t']= time();
        $_SESSION['grCompras']['ident']= null;
    }
    
    function comprar($id=0,$cant=1) {
        $id= intval($id);
        $cant= intval($cant);
        if(in_array($id,$_SESSION['grCompras']['IDs'])) $_SESSION['grCompras']['Cantidad'][$id] += $cant;
        else { $_SESSION['grCompras']['IDs'][]= $id; $_SESSION['grCompras']['Cantidad'][$id]= $cant; }
        grCompras::check();
    }
    
    function eliminar($id=0) {
        $id= intval($id);
        if(in_array($id,$_SESSION['grCompras']['IDs'])) $_SESSION['grCompras']['Cantidad'][$id]= 0;
        grCompras::check();
    }
    
    function actualizar($id=0,$cant=1) {
        $id= intval($id);
        $cant= intval($cant);
        if($cant <0) $cant=0;
        if(in_array($id,$_SESSION['grCompras']['IDs'])) $_SESSION['grCompras']['Cantidad'][$id]= $cant;
        grCompras::check();
    }
    
    function check() {
        $db= Loader::db();
        Loader::library('grRegistro','gr_registro');
        $xrTipo= grRegistro::tipo();
        $rq= $db->query("SELECT * FROM grTienda WHERE tID=0");
        if(!($T0=$rq->fetchrow())) $this->redirect("/?e=tienda_no_id0");
        $total= 0.0;
        $numCompras= 0;
        foreach($_SESSION['grCompras']['IDs'] as $id) {
            if($_SESSION['grCompras']['Cantidad'][$id] >0) {
                $cantidad= intval($_SESSION['grCompras']['Cantidad'][$id]);
                $rq= $db->query("SELECT * FROM grTienda as t, grTiendaCategorias as c, grTiendaMarcas as m, grTiendaPromociones as p WHERE t.tCategoria=c.cID AND t.tMarca=m.mID AND t.tPromocion=p.pID AND tID>100 AND tPrecios LIKE '%".$id."%'");
                if($R=$rq->fetchrow()) {
                    $Precios= json_decode($R['tPrecios'],true);
                    if(!empty($Precios[$id]['precio'])){
                        $P= $Precios[$id];
                        $vipDesc=0;
                        if(!empty($T0['tVipStatus'])) $vipDesc= $T0['tVipDescuento'];
                        if(!empty($R['mVipStatus'])) $vipDesc= $R['mVipDescuento'];
                        if(!empty($R['pVipStatus'])) $vipDesc= $R['pVipDescuento'];
                        if(!empty($R['tVipStatus'])) $vipDesc= $R['tVipDescuento'];
                        $descuentoV= 1.0-(floatval($vipDesc)/100);
                        $precio= floatval($P['precio']);
                        if(!empty($R['tPromocion']) && !empty($P['promo'])) $precio= floatval($P['promo']);
                        if($xrTipo>0) $precio*= $descuentoV;
                        $subtotal= round($precio) * $cantidad;
                        $total += $subtotal;
                        $numCompras += $cantidad;
                    } else $_SESSION['grCompras']['Cantidad'][$id]=0;
                } else $_SESSION['grCompras']['Cantidad'][$id]=0;
            }
        }
        $_SESSION['grCompras']['total']= $total;
        $_SESSION['grCompras']['numCompras']= $numCompras;
    }
    
    function numCompras() {
        return $_SESSION['grCompras']['numCompras'];
    }
    
    function numero() {
        return $_SESSION['grCompras']['numCompras'];
    }
    
    function total() {
        return $_SESSION['grCompras']['total'];
    }
    
    function ident() {
        return $_SESSION['grCompras']['ident'];
    }
    
    function meses() {
        return $_SESSION['grCompras']['meses'];
    }
    
    function compras() {
        $C= array();
        $compras= null;
        $db= Loader::db();
        Loader::library('grRegistro','gr_registro');
        $xrTipo= grRegistro::tipo();
        $rq= $db->query("SELECT * FROM grTienda WHERE tID=0");
        if(!($T0=$rq->fetchrow())) $this->redirect("/?e=tienda_no_id0");
        $total= 0.0;
        $numCompras= 0;
        foreach($_SESSION['grCompras']['IDs'] as $id) {
            if($_SESSION['grCompras']['Cantidad'][$id] >0) {
                $cantidad= intval($_SESSION['grCompras']['Cantidad'][$id]);
                $rq= $db->query("SELECT * FROM grTienda as t, grTiendaCategorias as c, grTiendaMarcas as m, grTiendaPromociones as p WHERE t.tCategoria=c.cID AND t.tMarca=m.mID AND t.tPromocion=p.pID AND tID>100 AND tPrecios LIKE '%".$id."%'");
                if($R=$rq->fetchrow()) {
                    $Precios= json_decode($R['tPrecios'],true);
                    if(!empty($Precios[$id]['precio'])){
                        $P= $Precios[$id];
                        $vipDesc=0;
                        if(!empty($T0['tVipStatus'])) $vipDesc= $T0['tVipDescuento'];
                        if(!empty($R['mVipStatus'])) $vipDesc= $R['mVipDescuento'];
                        if(!empty($R['pVipStatus'])) $vipDesc= $R['pVipDescuento'];
                        if(!empty($R['tVipStatus'])) $vipDesc= $R['tVipDescuento'];
                        $descuentoV= 1.0-(floatval($vipDesc)/100);
                        $precio= floatval($P['precio']);
                        if(!empty($R['tPromocion']) && !empty($P['promo'])) $precio= floatval($P['promo']);
                        if($xrTipo>0) $precio*= $descuentoV;
                        $item= array();
                        $item['id']= $id;
                        $item['cantidad']= $cantidad;
                        $item['nombre']= $R['tNombre'];
                        $item['modelo']= $R['tModelo'];
                        $item['imagen']= $R['tImagen'];
                        $item['marca']= $R['mNombre'];
                        $item['promocion']= $R['pNombre'];    
                        $item['precio']= round($precio);
                        $item['unidad']= $P['info'];
                        $subtotal= round($precio) * $cantidad;
                        $item['subtotal']= $subtotal;
                        $total += $subtotal;
                        $numCompras += $cantidad;
                        $compras[]= $item;
                    } else $_SESSION['grCompras']['Cantidad'][$id]=0;
                } else $_SESSION['grCompras']['Cantidad'][$id]=0;
            }
        }
        $_SESSION['grCompras']['total']= $total;
        $_SESSION['grCompras']['numCompras']= $numCompras;
        $C['numCompras']= $numCompras;
        $C['total']= $total;
        $C['compras']= $compras;
        return $C;
    }

    function resumen() {
        $C= grCompras::compras();
        
    }

}
?>