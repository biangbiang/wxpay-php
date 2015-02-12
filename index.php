<?php
include_once("conf/WxPay.pub.config.php");

include_once("lib/JsSdk.php");
include_once("lib/CommonUtilPub.php");
include_once("lib/SDKRuntimeException.php");
include_once("lib/WxpayClientPub.php");
include_once("lib/UnifiedOrderPub.php");

// 获取微信用户的openId，相信在接微信支付的时候，已经能够获取到openId了
$openId = "o5k3_xxxxxxxxxxxxxxxxxx";

$appId = WxPayConfPub::APPID;
$appSecret = WxPayConfPub::APPSECRET;
// 获取jssdk相关参数
$jssdk = new JsSdk($appId, $appSecret);
$signPackage = $jssdk->GetSignPackage();
$timeStamp = $signPackage['timestamp'];
$nonceStr = $signPackage['nonceStr'];


// 获取prepay_id
// 具体参数设置可以看文档http://pay.weixin.qq.com/wiki/doc/api/index.php?chapter=9_1
$unifiedOrder = new UnifiedOrderPub();
$unifiedOrder->setParameter("openid",$openId);//用户openId
$unifiedOrder->setParameter("body", "贡献一分钱");//商品描述，文档里写着不能超过32个字符，否则会报错，经过实际测试，临界点大概在128左右，稳妥点最好按照文档，不要超过32个字符
$unifiedOrder->setParameter("out_trade_no", "123456");//商户订单号 
$unifiedOrder->setParameter("total_fee", "1");//总金额,单位为分
$unifiedOrder->setParameter("notify_url",WxPayConfPub::NOTIFY_URL);//通知地址 
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
$unifiedOrder->setParameter("nonce_str", $nonceStr);//随机字符串
//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
$prepayId = $unifiedOrder->getPrepayId();

// 计算paySign
$payPackage = [
    "appId" => WxPayConfPub::APPID,
    "nonceStr" => $nonceStr,
    "package" => "prepay_id=" . $prepayId,
    "signType" => "MD5",
    "timeStamp" => $timeStamp
];
$paySign = $unifiedOrder->getSign($payPackage);
$payPackage['paySign'] = $paySign;

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
      <meta content="yes" name="apple-mobile-web-app-capable" />
      <meta content="telephone=no,email=no" name="format-detection" />
    <title>微信支付接入</title>
    <link rel="stylesheet" href="http://biang.io/dist/css/bootstrap.css" />
  </head>
  <body>
    <div>
      <h3 id="menu-pay">微信支付接口</h3>
      <span class="desc">发起一个微信支付请求</span>
      <button class="btn btn_primary" id="chooseWXPay">chooseWXPay</button>
    </div>

  </body>
  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
  <script>
  wx.config({
      debug: true, // 调试开关
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
        'checkJsApi',
        'chooseWXPay'
      ]
  });

  wx.ready(function () {
    document.querySelector('#chooseWXPay').onclick = function () {
      wx.chooseWXPay({
          timestamp: <?php echo $payPackage["timeStamp"];?>,
          nonceStr: '<?php echo $payPackage["nonceStr"];?>',
          package: '<?php echo  $payPackage['package'];?>',
          signType: '<?php echo $payPackage["signType"];?>', // 注意：新版支付接口使用 MD5 加密
          paySign: '<?php echo $payPackage["paySign"];?>',
          success: function () {
            alert('支付成功');
            // Add Your Code Here If You Need
          }
      });
    };
  });

  wx.error(function (res) {
    alert('验证失败:' + res.errMsg);
    // Add Your Code Here If You Need
  });
  </script>
</html>
