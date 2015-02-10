<?php
include_once("conf/WxPay.pub.config.php");

// 获取微信用户的openId，相信在接微信支付的时候，已经能够获取到openId了
$openId = "o5k3_xxxxxxxxxxxxxxxxxx";

$jssdk = new JsSdk();
$signPackage = $jssdk->GetSignPackage();
$timeStamp = $signPackage['timestamp'];
$nonceStr = $signPackage['nonceStr'];

$unifiedOrder = new UnifiedOrderPub();
$unifiedOrder->setParameter("openid",$openId);//用户openId
$unifiedOrder->setParameter("body", $orderGroupDesc);//商品描述
$unifiedOrder->setParameter("out_trade_no", $orderGroupId);//商户订单号 
$unifiedOrder->setParameter("total_fee", $totalPrice * 100);//总金额,单位为分
$unifiedOrder->setParameter("notify_url",WxPayConfPub::NOTIFY_URL);//通知地址 
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
$unifiedOrder->setParameter("nonce_str", $nonceStr);//随机字符串

$prepayId = $unifiedOrder->getPrepayId();

$payPackage = [
    "appId" => WxPayConfPub::APPID,
    "nonceStr" => $nonceStr,
    "package" => "prepay_id=" . $prepayId,
    "signType" => "MD5",
    "timeStamp" => $timeStamp
];
$paySign = $unifiedOrder->getSign($payPackage);
$payPackage['paySign'] = $paySign;

$result = [
    "prepayId" => $prepayId,
    "payPackage" => $payPackage,
    "signPackage" => $signPackage
];      
