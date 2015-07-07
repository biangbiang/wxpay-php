wxpay-php
=========

php项目基于微信支付JS SDK和JS API的接入开发

因为php框架繁多，而且项目开发进度比较赶，线上代码迁移出来的时间也有限，此处的代码没有经过备案域名和线上服务器测试，肯定会有很多问题，所以样例代码仅供参考

---

### 前言

因为抢红包风波，微信封杀了支付宝链接，不得不紧急加入微信支付。

微信支付的开发文档太坑，不才已被虐哭，趁现在还在坑里，记录一下留个纪念。

### 开发相关资料(排名不分先后)

* [微信商户服务中心](https://mp.weixin.qq.com/paymch/readtemplate?t=mp/business/faq2_tmpl)

* [商户平台开发者文档1](http://pay.weixin.qq.com/wiki/doc/api/index.html)

* [商户平台开发者文档2](http://pay.weixin.qq.com/wiki/doc/api/jsapi.php)

* [商户平台开发者文档3](http://pay.weixin.qq.com/wiki/doc/api/index.php?chapter=1_1)

* [微信JS-SDK说明文档](http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html)

* [支付开发教程（微信商户平台版）](https://mp.weixin.qq.com/paymch/readtemplate?t=mp/business/course3_tmpl)

* [代公众号使用JS SDK说明](https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1421823488&t=0.37369911512359977)

* [JSSDK demo页面](http://demo.open.weixin.qq.com/jssdk/)

* [JSSDK demo示例代码下载](http://demo.open.weixin.qq.com/jssdk/sample.zip)

### 注意点

* 微信5.0后的版本才支持微信支付

* 微信支付的jssdk接口只能在微信客户端里面才有效果，普通浏览器无法测试

* 微信支付的相关代码必须部署在线上，域名必须备案，而且域名必须跟 <微信公众平台> 里相关设置里的域名一致

* 微信大小写非常敏感，timestamp和timeStamp以及appId和appid不要弄错，要在对的接口使用对的大小写，里面是混着用的

* 商户号和微信商户号要区分出来，也就是MCHID，开通微信支付后有一封邮件，里面会有相关的信息，登陆商户后台也能找到

* 只有服务号才能接入微信支付，不同的服务号之前可以跨号支付，订阅号里面不能调用微信支付，会报“不允许跨号支付”的错误

### 关于签名

我开发的时候遇到三次签名生成：

1. wx.config 里面有一次 signature 的签名生成，调用的是官方sdk的方法，它用的是 SHA1 加密，生成之后的签名没有转大写

2. 获取prepay\_id的时候，中间会生成一次签名，调用的是官方sdk的方法，它用的是 MD5 加密，生成之后的签名统一转成大写

3. wx.chooseWXPay 里面有最后一次 paySign 的签名生成，我调用了步骤2里面的那个接口，最后测试成功了

### 分享代码

因为目前用的项目的框架是比较奇葩的，我之前已经把官方的代码临时整合并修改成适合项目的代码，不能直接用了，后面的日子将逐步等工作闲暇的时候，整理出直接能用的来分享给还没入坑有需要的童鞋们。

---

## 代码相关

### 开发环境

本地相关开发环境如下：

* 编辑器: MacVim

* php: 5.5.13，接入微信支付的相关扩展都装了，主要应该是curl扩展

* nginx: 1.4.2

* 操作系统: Yosemite 10.0.2

### 创建相关文件

    WxPay.pub.config.sample.php => WxPay.pub.config.php // 配置文件
    access_token.sample.json => access_token.json       // 临时存储access_token
    jsapi_ticket.sample.json => jsapi_ticket.json       // 临时存储jsapi_ticket

### 代码文件结构

    .
    ├── LICENSE
    ├── README.md -------------------------说明文档
    ├── callback --------------------------回调
    │   └── notifyUrl.php -----------------回调接口文件
    ├── conf ------------------------------配置
    │   ├── WxPay.pub.config.php ----------配置文件
    │   └── WxPay.pub.config.sample.php ---配置文件样本
    ├── index.php -------------------------主入口文件
    ├── lib -------------------------------类库
    │   ├── CommonUtilPub.php -------------所有接口的基类
    │   ├── JsSdk.php ---------------------微信支付新推出的js sdk
    │   ├── Log.php -----------------------日志类库
    │   ├── SDKRuntimeException.php -------异常类库
    │   ├── UnifiedOrderPub.php -----------统一支付接口类
    │   ├── WxpayClientPub.php ------------请求型接口的基类
    │   └── WxpayServerPub.php ------------响应型接口的基类
    ├── log -------------------------------日志
    │   ├── access_token.json -------------access_token临时存储文件
    │   ├── access_token.sample.json ------access_token临时存储文件样本
    │   ├── jsapi_ticket.json -------------jsapi_ticket临时存储文件
    │   ├── jsapi_ticket.sample.json ------jsapi_ticket临时存储文件样本
    │   └── notify_url.log ----------------回调接口日志文件
    └── master.sh -------------------------git提交shell懒人脚本

    4 directories, 19 files
