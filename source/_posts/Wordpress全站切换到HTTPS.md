---
title: Wordpress全站切换到HTTPS
date: 2017-01-31 20:07:30
tags: 杂
---
这也算个大坑了,之前一直被dalao吐槽我的网站为什么还是HTTP,然后我知道原来还有Let's Encrypt这东西,apt-get之后一直pass就可以了,这年头弄个HTTPS比之前省事多了。。。然而最后我用了StartSSL（滑稽
<br>

<!--more-->

### 首先,来玩玩自行签证
试试这个懒人版的脚本: [给Nginx配置一个自签名的SSL证书](http://www.liaoxuefeng.com/article/0014189023237367e8d42829de24b6eaf893ca47df4fb5e000)
然后用Chrome打开站点就会发现证书错误![https-error](https://of4jd0bcc.qnssl.com/Https/https-error.png)(强行黑了一波Chrome233
<br>

### StartSSL
注册帐号,然后得到一个ClientLogin证书,导入后就可以直接登录了
![client-login](https://of4jd0bcc.qnssl.com/Https/StartSSL-client-login.jpg)

配置Validations Wizard之前需要Certificates Wizard,在FreeUser那一栏下面选DV SSL Certificate就好了
然后按这里操作: [Startssl 现在就启用 HTTPS,免费的！](http://www.vincentguo.cn/default/130.html?flag=recommend)

有一点需要提一下,如果你开启了**域名隐私保护**,那么你需要先关闭这个功能才能接收到验证邮件
密匙建议放在你wordpress的站点文件夹或者nginx/ssl下面（没有ssl文件夹自己mkdir一个）
如无意外,我们就拿到一个为期一年的HTTPS证书了,解压证书到你的VPS
<br>

### 导入证书到站点
找到nginx.conf这个文件,做如下更改: 

    server{
        listen 80 default_server;
        listen 443 default ssl; #开启HTTPS
        ssl on;
        ssl_certificate /your-path-to/StartSSL颁发给你的.crt; #解压得到的csr文件
        ssl_certificate_key /your-path-to/your-name.key; #ssl文件夹下自己生成的key
        
        #重定向
        if ($server_port = 80) {
            return 301 https://$server_name$request_uri;
        }
        if ($scheme = http) {
            return 301 https://$server_name$request_uri;
        }
        error_page 497 https://$server_name$request_uri;
        server_name your-domain; #你的站点地址
    
        root  /your-path-to/your-domain; #wordpress下的域名文件夹

重启Nginx: `service nginx restart`
应该是可以免输入密码的,但是不嫌麻烦的话还是输两次吧,哪天忘了key也是件很蛋疼的事。。。

基本上完成上述步骤后,网站就能够实现全站强制HTTPS访问了。但是按照Chrome的标准的话,此时只能得到一个灰锁或带三角的灰锁而非完全的绿锁。
**这其中可能有两个原因:**
    一个是未使用新型加密套件
 另一个是网站加载了不安全的脚本。前者是服务器的问题,后者是主题的问题
 
![https站点显示](https://of4jd0bcc.qnssl.com/Https/https%E7%AB%99%E7%82%B9%E5%B1%95%E7%A4%BA.jpg)
<br>

### WordPress相关设置
#### 登录后台更改站点固定链接为https
![更改固定链接为https](https://of4jd0bcc.qnssl.com/Https/%E5%9B%BA%E5%AE%9A%E9%93%BE%E6%8E%A5%E6%9B%B4%E6%94%B9.jpg)
<br>
配置完https后你会发现打开原有文章会显示404 not found,那是因为网上教程大多为修改.htaccess文件或者修改Apache设置,而本人的服务器后台是LNMP,故网上查找到了以下解决方案:
修改nginx.conf，在server{下添加下代码

    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    rewrite /wp-admin$ $scheme://$host$uri/ permanent;
</br>

然后据说WordPress后台的https登录可能会造成循环跳转,**没有这个问题的话请无视**
把这段代码添加到wp-config.php的php标签之后即可

    $_SERVER['HTTPS'] = 'on';
    define('FORCE_SSL_LOGIN', true);
    define('FORCE_SSL_ADMIN', true);

如图: 
![https后台跳转](https://of4jd0bcc.qnssl.com/Https/wordpress%E5%90%8E%E5%8F%B0%E8%AE%BF%E9%97%AE403.png)
<br>

### 还有什么？
你可以在[SSL-Test](https://www.ssllabs.com/ssltest/)测试下你的评分
一开始看到这个分数我是大写的懵逼: 
![SSL-Test-B](https://of4jd0bcc.qnssl.com/Https/SSL-Test-B.jpg)
</br>
不能忍,来提高下分数吧,参考[提升服务器 SSL 安全性](https://molun.net/https-access-to-the-whole-site/)
在ssl文件在下输入：`openssl dhparam -out dhparam.pem 4096`

然后在nginx.conf下添加

    ssl_dhparam /your-path-to/dhparam.pem; #刚才生成的dhparam.pem
    ssl_session_cache shared:SSL:10m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    ssl_prefer_server_ciphers on;
    ssl_ciphers "ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:AES:CAMELLIA:DES-CBC3-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!aECDH:!EDH-DSS-DES-CBC3-SHA:!EDH-RSA-DES-CBC3-SHA:!KRB5-DES-CBC3-SHA";
    
    add_header Strict-Transport-Security max-age=15768000;
    ssl_stapling on;
    ssl_stapling_verify on;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 10s;
    
重启Nginx后再测试一次,应该能得到不错的分数:
![SSL-Test-A+](https://of4jd0bcc.qnssl.com/Https/SSL-Test-A+.jpg)

**然后文章里面所有图片或者文件资源要自行切换到https链接,否则打开文章页面Chrome还是会提示有不安全的资源**

七牛图床的图片用https地址访问：[如何通过SSL的形式来访问七牛云存储上的资源](https://support.qiniu.com/hc/kb/article/73535/)

添加个https域名,然后设置成默认地址,以后访问图片就是https

CDN的话:谁打赏个面包钱,我攒起来上CDN。。。
<br>

以上,全部,WordPress全站切换到HTTPS结束

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E6%B3%A2%E5%B0%94%E5%BE%B3_alipay.gif?imageView2/1/w/200/h/200
  [100]:  https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/patapon_wechat.gif?imageView2/1/w/200/h/200