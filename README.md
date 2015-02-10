wxpay-php
=========

php项目基于微信支付JS SDK和JS API的接入开发

---

### 前言

因为抢红包风波，微信封杀了支付宝链接，不得不紧急加入微信支付。

微信支付的开发文档太坑，不才已被虐哭，趁现在还在坑里，记录一下留个纪念。

### 开发相关资料(排名不分先后)

* [微信商户服务中心](https://mp.weixin.qq.com/paymch/readtemplate?t=mp/business/faq2_tmpl)

* [商户平台开发者文档](http://pay.weixin.qq.com/wiki/doc/api/jsapi.php)

* [微信JS-SDK说明文档](http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html)

* [支付开发教程（微信商户平台版）](https://mp.weixin.qq.com/paymch/readtemplate?t=mp/business/course3_tmpl)

* [代公众号使用JS SDK说明](https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1421823488&t=0.37369911512359977)

* [JSSDK demo页面](http://demo.open.weixin.qq.com/jssdk/)

* [JSSDK demo示例代码下载](http://demo.open.weixin.qq.com/jssdk/sample.zip)

### 注意点

* 微信大小写非常敏感，timestamp和timeStamp以及appId和appid不要弄错，要在对的接口使用对的大小写，里面是混着用的。

* 商户号和微信商户号要区分出来，也就是MCHID

### 分享代码

因为目前用的项目的框架是比较奇葩的，我之前已经把官方的代码临时整合并修改成适合项目的代码，已经不能直接用了，后面的日子将逐步等工作闲暇的时候，整理出直接能用的来分享给还没入坑有需要的童鞋们。
