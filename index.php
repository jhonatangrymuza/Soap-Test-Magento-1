<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// data
$mage_url = 'https://www.seusite.com.br/';
$mage_user = 'name_user';
$mage_api_key = 'api_key';

//$key = 'voltagem';
//$value = 'BIVOLT';
$sku = '1234567890'; //alterar sempre o SKU pois é ele quem identifica para editar o produto
$qty = '0';
$inStock = '0';
$habilitar = '0';

// update atributo
$update_data = array (
    'name' => 'Produto teste ceicom 03-09',
    'description' => 'Descrição Produto para testes',
    'short_description' => 'Descrição curta Produto para testes',
    'status' => $habilitar
    // 'additional_attributes' => array (
    //     'single_data' => array (
    //         array (
    //             'key' => $key,
    //             'value' => $value
    //         )
    //     )
    //     // 'multi_data' => array (
    //     //     array (
    //     //         'key' => 'voltagem',
    //     //         'value' => array (
    //     //             '0' => '127V',
    //     //             '1' => '220V'
    //     //         )
    //     //     )
    //     // )
    // )
);

$update_stock = array (
    'qty' => $qty,
    'is_in_stock' => $inStock,
    'manage_stock'=> $inStock,
);

$wsdl_url = $mage_url . 'api/v2_soap/?wsdl';
$soap = new SoapClient( $wsdl_url);

echo '<head><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>';
echo '<style>';
echo 'body{color: #666;font-family: sans-serif;font-size: 16px;}';
echo 'a{text-decoration: none;color: #666;font-weight: 600;}';
echo 'a:hover {text-decoration: underline;}';
echo '.wrapper {text-align: left;margin-left: auto;margin-right: auto;max-width: 1024;width: 100%;display: block;}';
echo '</style>';

// profiller
$time_start = microtime(true);
$session = $soap->login( $mage_user, $mage_api_key );

echo '<div class="wrapper"> ';
echo '<img style="-webkit-user-select: none;" src="http://www.ceicom.com.br/img/logo.png?v=1.0">';
echo "<p>WSDL Url: " . $wsdl_url . "</p>";
echo sprintf("<p>Got session id: %s</p>", $session);
echo sprintf("<p>Time to get session id: %s</p>", microtime(true)-$time_start);

try {
    // Produto
    $update = $soap->catalogProductUpdate($session, $sku, $update_data, NULL, 'sku');
    $update = $soap->catalogInventoryStockItemUpdate($session, $sku, $update_stock);

    echo '<hr/><pre>Return: ';
    echo $update;
    echo '<br><br>Send:  ';
    print_r($update_data);
    echo '<br><br>Stock:  ';
    print_r($update_stock);
    echo '</pre>';
    echo '</div> ';

    // Pedido
    //$result = $soap->salesOrderInfo($session, '100001276');
    //print_r($result->items);
    // echo $result->items->name . '<br>';
    // foreach( $result->items as $item ) {
    //     echo $item->name . '<br>';
    //     echo $item->sku . '<br>';
    //     echo $item->original_price . '<br>';
    //     echo $item->product_type . '<br>';
    // }
} catch (SoapFault $e) {
    var_dump($e);
}

// profiler
$time_end = microtime(true);
$time = $time_end - $time_start;
echo sprintf("<hr/><small>Total execution time: %s seconds.</small>",$time);