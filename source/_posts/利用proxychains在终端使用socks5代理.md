---
title: 利用proxychains在终端使用socks5代理
date: 2017-01-18 12:09:59
tags: Linux
---

**配置测试环境没有梯子怎么行= w =**

<!--more-->

### proxychains安装

    git clone https://github.com/rofl0r/proxychains-ng.git
    cd proxychains-ng
    ./configure –prefix=/usr –sysconfdir=/etc
    make
    sudo make install
    sudo make install-config

### 编辑proxychains配置

    vim /etc/proxychains.conf


### 将socks4 127.0.0.1 9095改为

    socks5 127.0.0.1 1080 //1080改为你自己的端口


### 使用方法

在需要代理的命令前加上 proxychains4
    
    proxychains4 wget http://xxx.com/xxx.zip

这样用每次都要在命令前输入proxychains4，比较麻烦，可以用proxychains4代理一个shell，在shell中执行的命令就会自动使用代理了

    proxychains4 -q /bin/bash
可以把上面的命令加入到用户的.bashrc或者.bash_profile中,用户登录后自动代理一个bash shell,这就有点像全局代理了

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E6%B3%A2%E5%B0%94%E5%BE%B3_alipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/patapon_wechat.gif?imageView2/1/w/200/h/200