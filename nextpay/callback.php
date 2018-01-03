<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once 'nextpay_payment.php';

$trans_id = isset($_POST['trans_id']) ? $_POST['trans_id'] : false ;
$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : false ;
if (!$trans_id)
    exit('خطا در انجام عملیات بانکی ، شناسه تراکنش موجود نمی باشد');
if (!is_string($trans_id) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $trans_id) !== 1))
    exit('تراکنش ارسال شده معتبر نمیباشد');
$price		= $_GET['am'];
$parameters = array
(
    'api_key'	=> '',
    'order_id'	=> $order_id,
    'trans_id' 	=> $trans_id,
    'amount'	=> $price,
);
$nextpay = new Nextpay_Payment();
$result = $nextpay->verify_request($parameters);
if( $result < 0 )
{
	$core->temp['gateway']['call']['msg']='پرداخت صورت نگرفت !';
	$core->temp['gateway']['call']['trac']=$trans_id;
	$core->temp['gateway']['call']['erja']='-';
	$core->temp['gateway']['call']['cart']='-';
	$core->temp['gateway']['call']['status']=false;
	$core->temp['gateway']['call']['reason']=$result;
}
elseif ($result==0)
{
	$core->temp['gateway']['call']['msg']='پرداخت انجام شد !';
	$core->temp['gateway']['call']['trac']=$trans_id;
	$core->temp['gateway']['call']['erja']='-';
	$core->temp['gateway']['call']['cart']='-';
	$core->temp['gateway']['call']['status']=true;
	$core->temp['gateway']['call']['reason']=$result;
}
else
{
	$core->temp['gateway']['call']['msg']='پرداخت صورت نگرفت !';
	$core->temp['gateway']['call']['trac']=$trans_id;
	$core->temp['gateway']['call']['erja']='-';
	$core->temp['gateway']['call']['cart']='-';
	$core->temp['gateway']['call']['status']=false;
	$core->temp['gateway']['call']['reason']=$result;
}