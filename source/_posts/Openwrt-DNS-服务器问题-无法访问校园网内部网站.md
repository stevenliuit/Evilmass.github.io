---
title: Openwrt DNS 服务器问题 - 无法访问校园网内部网站
date: 2017-09-17 13:09:11
tags: Openwrt
---

这大概是我遇到过持续时长最久的一个坑了
![nana][nana]

## 问题描述
网线直连电脑分配到了DNS服务器，可以直接访问学校的内部更新源和协会网站。本地网络连接的 DNS 服务器解析地址正常，但用路由器之后无论怎么改路由器 DNS 或者改本地无线网卡的 DNS 都打不开

<!--more-->
## 关键字： Prevent DNS-rebind attacks
–stop-dns-rebind

    Reject (and log) addresses from upstream nameservers which are in the private IP ranges. This blocks an attack where a browser behind a firewall is used to probe machines on the local network.

这项安全设置是拒绝解析包含私有IP地址的域名，这些IP地址包括如下私有地址范围：

    A:10.0.0.0~10.255.255.255     即10.0.0.0/8
    B:172.16.0.0~172.31.255.255   即172.16.0.0/12
    C:192.168.0.0~192.168.255.255 即192.168.0.0/16
    
而其初衷是要防止类似上游DNS服务器故意将某些域名解析成特定私有内网IP而劫持用户这样的安全攻击。
<br>
## 解决方法
1. 直接在配置文件中取消stop-dns-rebind配置项从而禁用该功能。这个方法确实可以一劳永逸的解决解析内网IP地址的问题，但是我们也失去了这项安全保护的特性，所以在这里我不推荐这个办法

取消勾选 **Rebind protection**：防止dns解析到私有地址
![dns][dns]
2. 使用rebind-domain-ok进行特定配置，顾名思义该配置项可以有选择的忽略域名的rebind行为
往 Domain whitelist 添加白名单地址即可

![whitelist][whitelist]

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][alipay]

**微信**  
![wechat][wechat]


[alipay]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/dmc.gif?imageView2/1/w/200/h/200
[wechat]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200


[nana]: https://of4jd0bcc.qnssl.com/Openwrt/nana.png
[dns]: https://of4jd0bcc.qnssl.com/Openwrt/dns.png
[whitelist]: https://of4jd0bcc.qnssl.com/Openwrt/whitelist.png