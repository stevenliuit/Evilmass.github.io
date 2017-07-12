---
title: MHP Tunnel服务器的另类搭建方式
date: 2017-01-22 00:00:06
tags: Video Game
---
### 前言
今年回到家里之后一直好奇怎么在MHP Tunnel自建服务器，在这之前(PSP Tunnel还没出来的时候)玩家主要是靠DMZ主机来构成一个Client/Server的形式来联机，然而[DMZ不安全][0]。由于找不到Tunnel的Unix服务器端，又不想用DMZ形式来搭建，所以**Ngrok**派上用场了
<br>

<!--more-->

### Ngrok
这东西还是听过的（在我被花生壳坑了之后），多用于内网穿透。

举个例子：我有台服务器A在校内实验室，回到家之后无法用外网直接访问。但是我有一台能通过外网访问的VPS服务器B，那么在B上用ngrok反代A，那么我就能通过B的端口来访问A。
<br>
**Sunny提供国内免费的ngrok转发**
![1][1]
想用自己VPS搭建的要考虑延迟问题，最好选国内主机，参考[在Centos搭建Ngrok][在Centos搭建Ngrok]（吐槽腾讯学生云，现在不备案的话，Ngrok转发http页面会丢给你这么一个页面

![备案][备案]

<br>

### Getting Start

#### 注册登录
[注册页面][2]---->[登录页面][3]
 <br>
 
#### 开通隧道
 选免费的那个点击购买即可
 ![4][4]
 <br>
 
#### 隧道设置
![5][5]

> 隧道协议：mhptunnel的服务器选**tcp**即可， http多用于访问web服务器
 远程端口：就是Sunny的服务器分配给你从外部访问的端口，填写一个未被占用的端口即可
 本地端口：鉴于MHP TUNNEL我找不到Unix版本，所以我们在Windows搭建。这里我们填写本机:端口，我本机是192.168.1.149:3000，3000也可以随便填写，只要这个端口未被系统占用即可

#### 查看本机ip地址
![6][6]
<br>
![7][7]
<br>
找到你**现在连接到网络的网卡**的ip，我这里是192.168.1.149

![22][22]
[22]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%88%91%E7%9A%84ip%E5%9C%B0%E5%9D%80.png
<br>

#### 路由器开启UPNP
![8][8]
<br>
我的路由器是OpenWRT系统设置起来没有太大问题，其他牌子的路由器进后台管理找到UPNP开启即可

![9][9]
<br>
**记得保存并应用**
![10][10]
<br>

#### 启动ngrok
<br>
然后在隧道管理找到你的隧道id
![12][12]
<br>
下载并打开**Sunny-Ngrok启动工具.bat**并输入你的隧道id，回车。看到这个就表示服务器端启动成功

![19][19]
<br>
[19]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/ngrok%E5%90%AF%E5%8A%A8%E6%88%90%E5%8A%9F.png
这时我们在浏览器输入下面的那个127.0.0.1:4040的地址显示这个即表示tcp隧道已经开启了

![18][18]
[18]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/127_4040.png
<br>

#### 开启Tunnel服务器
如果你们仔细找找的话，在MHP Tunnel目录下面是有个服务端启动工具的，长这样：
![tunnelsvr][tunnelsvr]

PSP Tunnel勾选上**使用UPNP Gateway**然后填上**本机IP**和**TunnelSVR默认的30000端口**创建服务器。但这样在PSP Tunnel上无法公开你的服务器信息，就像隐形了一样，联机前需要把设置好的ngrok服务器地址和端口告诉别人

![30000][30000]

<br>
这样服务器就算搭建起来了

![17][17]
[17]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%90%AD%E5%BB%BA%E6%88%90%E5%8A%9F.png
<br>

#### PSP Tunnel连接测试
> 打开PSP Tunnel，ip和端口填**隧道id管理**提供给你的服务器地址和端口
  或者自己VPS转发出来的ip和端口

来测试下
![20][20]
[20]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%B5%8B%E8%AF%95%E8%81%94%E6%9C%BA%E6%95%88%E6%9E%9C.png
<br>
感谢X叔第一时间陪我测试（找了好久人。。。

以上全部

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
  ![alipay][99]

**微信**  
  ![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200

[0]: [https://zhidao.baidu.com/question/573928865.html]
[1]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%84%9F%E8%B0%A2Sunny.png
[备案]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/beian.png
[在Centos搭建Ngrok]: https://evilmass.cc/2017/01/25/%E5%9C%A8CentOS%E4%B8%8B%E9%85%8D%E7%BD%AEngrok/
[2]: https://www.ngrok.cc/login/register
[3]: https://www.ngrok.cc/login
[4]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E5%BC%80%E9%80%9A%E9%9A%A7%E9%81%93.png
[5]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E9%9A%A7%E9%81%93%E8%AE%BE%E7%BD%AE.png 
[6]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/win_R.png 
[7]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%9F%A5%E7%9C%8B%E6%9C%AC%E6%9C%BAip%E5%9C%B0%E5%9D%80.png
[8]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%89%BE%E5%88%B0UPNP.png
[9]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E5%BC%80%E5%90%AFUPNP.png
[10]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E4%BF%9D%E5%AD%98%E5%B9%B6%E5%BA%94%E7%94%A8UPNP.png
[11]: http://pan.baidu.com/s/1c29oU9E 
[12]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/client_id.png
[13]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E9%9C%B8%E6%B0%94%E7%99%BB%E5%BD%95.png
[14]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/mhp_tunnel%E5%BC%80%E5%90%AFUPNP.png
[tunnelsvr]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/tunnelsvr.png
[30000]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/30000.png