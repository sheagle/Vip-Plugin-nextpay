<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
global $core;
$err = '';
$Site_Url = j_url;
include_once 'nextpay_payment.php';
$order_id = $core->temp['gateway']['invoice'];
$price = round($core->temp['gateway']['price']);
$parameters = array
(
"api_key"=> '',
"order_id"=> $order_id,
"amount"=>$price,
"callback_uri"=> $core->temp['gateway']['callbackurl']
);
$nextpay = new Nextpay_Payment($parameters);
$result = $nextpay->token();
if(intval($result->code) == -1)
{
	$nextpay->send($result->trans_id);
}
else
{
	echo 'خطا : '.$nextpay->code_error(intval($result->code));
}