<?php   
class grPaypal Extends Model {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    function Config() {
        $fDir= realpath(dirname(__FILE__) .'/../../..') . '/files/pago';
        $Config = array(
            'nombre' => 'Troppomoda',
            'ruta' => $fDir,
            'remitente' => 'tienda@troppomoda.com',
            'correo' => 'tienda@troppomoda.com',
            'business' => 'pagos@troppomoda.com',
            'sandbox' => 0
        );
        $Config['info'] = array(
            'lc'=>'ES',
            'charset'=>'utf-8',
            'no_note'=>'1',
            'return'=> 'https://www.troppomoda.com/pago/paypal/procesado/',
            'cancel_return'=> 'https://www.troppomoda.com/pago/paypal/cancelado/',
            'notify_url'=> 'https://www.troppomoda.com/pago/paypal/data/',
            'image_url'=> 'https://www.troppomoda.com/packages/gr_pago/img/logo_troppo.png',
            'currency_code' => 'MXN',
            'amount'=> 0.0,
            'item_name'=> null,
            'item_number'=> null,
            'business'=> $Config['business']
        );
        return $Config;
    }

    function Pagado($Info=null) {
        if(empty($Info)) $this->redirect('/?e=paypal_pagado_no_info');
        $Cnf = grPaypal::Config();
        $Pago = null;
        $Cliente = null;
        if(!empty($Info['custom'])) $Cliente= json_decode($Info['custom'],true);
        if($Info['payment_status']=='Completed') {
            $db = Loader::db();
            $rq = $db->query("SELECT * FROM grPago WHERE pOrden=". grPaypal::stxt($Info['item_number']));
            if($rx=$rq->fetchrow()) {
                $Pago = $rx;
                $rq = $db->query("UPDATE grPago SET pStatus='Pagado' WHERE pID=".$rx['pID']);
            }
        }
        $subject = mb_encode_mimeheader('Paypal Orden '.$Info['item_number'],'UTF-8');
        $headers = 'From: '. $Cnf['remitente']. PHP_EOL.
            'Reply-To: '. $Cnf['remitente']. PHP_EOL.
            'MIME-Version: 1.0'. PHP_EOL.
            'Content-Type: text/plain; charset=UTF-8; format=flowed'. PHP_EOL.
            'Content-Transfer-Encoding: 8bit'. PHP_EOL;
        $message = 'Se ha recibido información de Pago de Paypal'. PHP_EOL.
            '---'. PHP_EOL.
            'Orden : '.$Info['item_number']. PHP_EOL.
            'Concepto : '.$Info['item_name']. PHP_EOL;
        if(!empty($Cliente['Cliente'])) $message.= 'Cliente : '.$Cliente['Cliente']. PHP_EOL;
        if(!empty($Cliente['NombreCompleto'])) $message.= 'Nombre : '.$Cliente['NombreCompleto']. PHP_EOL;
        if(!empty($Cliente['CorreoElectronico'])) $message.= 'Correo: '.$Cliente['CorreoElectronico']. PHP_EOL;
        $message.= '---'. PHP_EOL.
            'Cuenta Paypal : '.$Info['receiver_email']. PHP_EOL.
            'ID Transacción : '.$Info['txn_id']. PHP_EOL.
            'Tipo Transacción : '.$Info['txn_type']. PHP_EOL.
            'ID Sistema : '.$ID. PHP_EOL;
        $message.= '---'. PHP_EOL.
            'Correo Cliente : '.$Info['payer_email']. PHP_EOL.
            'ID Cliente : '.$Info['payer_id']. PHP_EOL.
            'Status Cliente : '.$Info['payer_status']. PHP_EOL.
            'Nombre Cliente : '.$Info['first_name']. PHP_EOL.
            'Apellido Cliente : '.$Info['last_name']. PHP_EOL.
            '---'. PHP_EOL.
            'Status Pago : '.$Info['payment_status']. PHP_EOL.
            'Tipo Pago : '.$Info['payment_type']. PHP_EOL.
            'Monto Pago : '.$Info['mc_gross']. PHP_EOL.
            'Moneda Pago : '.$Info['mc_currency']. PHP_EOL.
            'Fecha Pago : '.$Info['payment_date']. PHP_EOL.
            '---'. PHP_EOL;
        if(!empty($Pago)) {
            $PagoInfo= json_decode($Pago['pInfo'],true);
            $message.= 'Detalles Orden de Compra'. PHP_EOL.
            'Total Orden : $'.number_format($Pago['pTotal'],2). PHP_EOL.
            'Cliente : '.$Pago['pCliente']. PHP_EOL.
            'Nombre : '.$PagoInfo['NombreCompleto']. PHP_EOL.
            'Correo : '.$Pago['pEmail']. PHP_EOL.
            $PagoInfo['OrdenCompra']. PHP_EOL.
            '---'. PHP_EOL;
            }
        mail($Cnf['correo'], $subject, $message, $headers);
    }

}
?>
    