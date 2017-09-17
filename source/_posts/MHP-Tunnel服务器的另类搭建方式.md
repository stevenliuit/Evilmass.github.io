---
title: MHP Tunnel服务器的另类搭建方式
date: 2017-01-22 00:00:06
tags: 
    - Game
---

## 前言
今年回到家里之后一直好奇怎么在MHP Tunnel自建服务器，在这之前(PSP Tunnel还没出来的时候)玩家主要是靠DMZ主机来构成一个Client/Server的形式来联机。由于找不到Tunnel的Unix服务器端，又不想用DMZ形式来搭建，所以**Ngrok**派上用场了
<br>

<!--more-->

## [Ngrok][Ngrok]
这东西还是听过的（在我被花生壳坑了之后），多用于内网穿透。

举个例子：我有台服务器A在校内实验室，回到家之后无法用外网直接访问。但是我有一台能通过外网访问的VPS服务器B，那么在B上用ngrok反代A，那么我就能通过B的端口来访问A。
<br>
**Sunny提供国内免费的ngrok转发**
![thankSunny][thankSunny]
想用自己VPS搭建的要考虑延迟问题，最好选国内主机，参考[在Centos搭建Ngrok][在Centos搭建Ngrok]（吐槽腾讯学生云，如果域名解析的是国内服务器没有备案的话（所有域名），Ngrok转发http页面会丢给你这么一个页面

![beian][beian]

<br>

## Getting Start
### 注册登录
<br>
### 开通隧道
 选免费的那个点击购买即可
 ![opentunnel][opentunnel]
<br>
### 隧道设置
> 隧道协议：mhptunnel的服务器选**tcp**即可， http多用于访问web服务器
 远程端口：就是Sunny的服务器分配给你从外部访问的端口，填写一个未被占用的端口即可
 本地端口：可以随便填写，只要这个端口未被系统占用即可，这里填20000

![settunnel][settunnel]
<br>
### 路由器开启UPNP
其实没有这个问题也不大的（应该。。。
![upnp][upnp]
<br>
我的路由器是OpenWRT系统所以设置起来没有太大问题，其他牌子的路由器进后台管理找到UPNP开启即可
<br>

### 开启Tunnel服务器
下面分别介绍两种服务器创建方式

#### TunnelSVR方式
如果你们仔细找找的话，在MHP Tunnel目录下面是有个服务端启动工具的，如果没有`TunnelSVR.ini`配置文件的自己创建一个，并填入如下信息
![svrini][svrini]

解释一下参数含义

    [Setting]
    Port=       未被占用的系统端口
    Export=     是否开放服务器，1代表开放，默认为0不开放
    Hr=         这个就不解释咯，填1即可
    MaxUser=    最大用户数量
    Name=       用户名
    Adminid=    即uuid，在psp tunnel输入: /u 即可得到
    Desc=       服务器信息说明

<br>
由于psp tunnel服务器公开机制尚未得知，所以要长期显示个人服务器的地址貌似还需要**自行反编译psp tunnel（未加壳）程序，已经解决这个问题的请务必联系我**
<br>
<br>
配置好TunnelSVR.ini之后运行TunnelSVR.exe，**下载[Sunny-Ngrok启动工具]**并输入你的隧道id，回车
![sunnyngrok][sunnyngrok]
<br>
这时我们在浏览器输入下面的那个`127.0.0.1:4040`的地址显示这个即表示tcp隧道已经开启了
![tcp][tcp]
<br>
在隧道管理找到你的隧道id
![tunnelid][tunnelid]
<br>
最后将ngrok转发的ip和port发给朋友就可以连上来了
![sunnyserver][sunnyserver]
<br>
如果不小心关了TunnelSVR.exe，再次打开会闪退，因为TunnelSVR的后台程序并未关闭，需要在任务管理器关闭
![killsvr][killsvr]
<br>

#### PSP Tunnel方式
这里用了学生云建立的ngrok服务器进行转发
PSP Tunnel勾选上**使用UPNP Gateway**然后在ip地址填上**127.0.0.1**，端口填写**40000**，勾选创建服务器（这里为了区分上面的20000端口，端口可以任意设置）
![40000][40000]
<br>
然后ngrok做如下转发设置
![vpsngrok][vpsngrok]
<br>
启动服务器
![lv][lv]
<br>
然后你问我这两种方式有什么区别？
> **通过PSP Tunnel的方式你的ID将拥有管理员的标志（原谅绿**

<br>
#### 连接测试
打开PSP Tunnel，ip和端口填**隧道id管理**提供给你的服务器地址和端口
  或者自己VPS转发出来的ip和端口

来测试下
![test][test]
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

[Ngrok]: https://ngrok.com/
[在Centos搭建Ngrok]: https://evilmass.cc/2017/01/25/%E5%9C%A8CentOS%E4%B8%8B%E9%85%8D%E7%BD%AEngrok/
[thankSunny]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/thankSunny.png
[beian]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/beian.png
[opentunnel]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/opentunnel.png
[settunnel]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/settunnel.png
[upnp]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/upnp.png
[tunnelsvr]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/tunnelsvr.png
[tcp]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/127_4040.png
[tunnelsvr]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/tunnelsvr.png
[Sunny-Ngrok启动工具]: https://www.ngrok.cc/#down-client
[test]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/test.png
[tunnelid]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/tunnelid.png
[svrini]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/svrini.png
[sunnyngrok]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/sunnyngrok.png
[sunnyserver]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/sunnyserver.png
[killsvr]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/killsvr.png
[40000]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/40000.png
[vpsngrok]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/vpsngrok.png
[tunnelupnp]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/tunnelupnp.png
[lv]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/lv.png