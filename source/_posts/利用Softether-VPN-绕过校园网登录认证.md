---
title: 利用Softether VPN 绕过校园网登录认证
date: 2017-03-04 20:54:53
tags: Hack
---

### Say Something
原先DNS Tunnel这东西是为了Hack或者不小心封了某个端口的时候拿来绕过防火墙的，之前用iodine感觉比较麻烦

iodine Kali内置，Debian的话`apt-get`就好了，然后拿自己的域名设置个ns记录和a记录，开了53udp，服务器和客户端分别运行iodined和iodine，最后通过tunnel连接上本地的ss，关键还不是全局代理（滑稽
Docker用法如下

    docker run -d --cap-add NET_ADMIN -p 53:53/udp -p  -p 5555:5555/tcp -e USERNAME xxx -e PASSWORD xxx -e SPW 服务端密码 -e HPW 虚拟HUB密码 siomiz/softethervpn
    
Softether VPN简直就是我这种懒人的福利
<br>

### Require
* 域名
* 带宽大、延迟低的服务器（这里用了腾讯的1元学生云，带宽调至200Mbps）
* Softether VPN

[提取密码：6pwTLX]

[官方下载页面]
<br>

### Server
下载服务器可用的版本

    wget http://www.softether-download.com/files/softether/v4.22-9634-beta-2016.11.27-tree/Linux/SoftEther_VPN_Server/64bit_-_Intel_x64_or_AMD64/softether-vpnserver-v4.22-9634-beta-2016.11.27-linux-x64-64bit.tar.gz
    
    tar -xzf softether-vpnserver-v4.22-9634-beta-2016.11.27-linux-x64-64bit.tar.gz
    
    cd vpnserver
    
    ./vpnserver start


这样VPN服务就跑起来了，下载Windows的Server，按如下配置

![下载server][下载server]
<br>

![安装管理工具][安装管理工具]
<br>

![新配置][新配置]
<br>

![添加服务器][添加服务器]
<br>

![管理虚拟HUB][管理虚拟HUB]
<br>

![添加用户][添加用户]
<br>

![DHCP][DHCP]
<br>

![启用DHCP][启用DHCP]
<br>

![ICMP][ICMP]
<br>

![加密][加密]
<br>

![53][2]
<br>

### Client
下载Windows的Client
![安装连接工具][安装连接工具]
<br>

![新建虚拟网卡][新建虚拟网卡]
<br>

![连接服务器][连接服务器]
<br>

然后，在**未登录的情况下连接VPN**，稍等一会就会连接成功并分配IP，然后就愉快的玩耍吧~
<br>
![未登录][未登录]
<br>
![速度测试][速度测试]


<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/girl_ailipay.gif?imageView2/1/w/200/h/200

[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/patapon_wechat.gif?imageView2/1/w/200/h/200


[提取密码：6pwTLX]: https://www.jianguoyun.com/p/DezKzhwQv96jBhjQoSc%20
[官方下载页面]: http://www.softether-download.com/cn.aspx
[下载server]: https://of4jd0bcc.qnssl.com/VPN/%E4%B8%8B%E8%BD%BDserver.png
[安装管理工具]: https://of4jd0bcc.qnssl.com/VPN/%E5%AE%89%E8%A3%85%E7%AE%A1%E7%90%86%E5%B7%A5%E5%85%B7.png
[新配置]: https://of4jd0bcc.qnssl.com/VPN/%E6%96%B0%E9%85%8D%E7%BD%AE.jpg
[添加服务器]: https://of4jd0bcc.qnssl.com/VPN/%E6%B7%BB%E5%8A%A0%E6%9C%8D%E5%8A%A1%E5%99%A8.jpg
[管理虚拟HUB]: https://of4jd0bcc.qnssl.com/VPN/%E7%AE%A1%E7%90%86%E8%99%9A%E6%8B%9FHUB.jpg
[添加用户]: https://of4jd0bcc.qnssl.com/VPN/%E6%B7%BB%E5%8A%A0%E7%94%A8%E6%88%B7.png
[DHCP]: https://of4jd0bcc.qnssl.com/VPN/DHCP.jpg
[启用DHCP]: https://of4jd0bcc.qnssl.com/VPN/%E5%90%AF%E7%94%A8DHCP.jpg
[ICMP]: https://of4jd0bcc.qnssl.com/VPN/ICMP.jpg
[加密]: https://of4jd0bcc.qnssl.com/VPN/%E5%8A%A0%E5%AF%86.jpg
[安装连接工具]: https://of4jd0bcc.qnssl.com/VPN/%E5%AE%89%E8%A3%85%E8%BF%9E%E6%8E%A5%E5%B7%A5%E5%85%B7.png
[新建虚拟网卡]: https://of4jd0bcc.qnssl.com/VPN/%E6%96%B0%E5%BB%BA%E8%99%9A%E6%8B%9F%E7%BD%91%E5%8D%A1.png
[连接服务器]: https://of4jd0bcc.qnssl.com/VPN/%E8%BF%9E%E6%8E%A5%E6%9C%8D%E5%8A%A1%E5%99%A8.png
[未登录]: https://of4jd0bcc.qnssl.com/VPN/%E6%9C%AA%E7%99%BB%E5%BD%95.png
[速度测试]: https://of4jd0bcc.qnssl.com/VPN/%E9%80%9F%E5%BA%A6%E6%B5%8B%E8%AF%95.png
