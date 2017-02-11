---
title: MHP Tunnel服务器的另类搭建方式
date: 2017-01-22 00:00:06
tags: Video Game
---

### 前言
今年回到家里之后一直好奇怎么在MHP Tunnel自建服务器，在这之前(PSP Tunnel还没出来的时候)玩家主要是靠DMZ主机来构成一个Host-Client的形式来相互联机，然而这[DMZ不安全][0]。简而言之，DMZ会把你当前的路由器或者主机暴露在外网环境，没有特殊配置很容易遭到攻击或者变成肉鸡。很久之前淘宝上很多qq刷砖多是以肉鸡来刷，宽带续费，显示的到期日期是2070-01-01。
奈何Google了很久也没有找到MHP Tunnel的Unix服务器端，又不想用DMZ形式来搭建，这时一个关键词出现了：**ngrok**
<br>

<!--more-->

### ngrok
这东西还是听过的（在我被花生壳坑了之后），多用于内网穿透。
举个例子：我有台服务器A在校内实验室，回到家之后无法用外网直接访问。但是我有一台能通过外网访问的VPS服务器B，那么在B上用ngrok反代A，那么我就能通过B的端口来访问A。
<br>
**感谢Sunny**，让这个教程简单了很多
![1][1]
如果只是搭建MHP Tunnel服务器的话就不必买服务器了
但我还是希望**对ngrok有需求**，有能力的同学可以购买VIP服务器以示支持
<br>

### Getting Start

#### 注册登录
[注册][2]---->[登录][3]
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
> Win + R进入CMD，输入**ipconfig**

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
下载[MHP Tunnel服务器搭建工具][11] 密码：**hs2g**，解压
<br>
然后在隧道管理找到你的隧道id
![12][12]
<br>
打开**Sunny-Ngrok启动工具.bat**并输入你的隧道id，回车。看到这个就表示服务器端启动成功
![19][19]
<br>
[19]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/ngrok%E5%90%AF%E5%8A%A8%E6%88%90%E5%8A%9F.png
这时我们在浏览器输入下面的那个127.0.0.1:4040的地址显示这个即表示tcp隧道已经开启了
![18][18]
[18]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/127_4040.png
<br>

#### Tunnel服务器开启
**Update**
> **不需要MHP Tunnel也可以开启服务器！**
  **不需要MHP Tunnel也可以开启服务器！**
  **不需要MHP Tunnel也可以开启服务器！**

只要ngrok和路由器的UPNP功能开启即可，只开一个PSP Tunnel勾选上**使用UPNP Gateway**然后填上**本机IP**和任意端口创建服务器。但这样在PSP Tunnel上无法公开你的服务器信息，就像隐身了一样，联机前需要把设置好的ngrok服务器地址和端口告诉别人。


> Q：为什么要用旧版的MHP Tunnel？
  A：因为不能同时开两个PSP Tunnel。MHP Tunnel当服务器，PSP Tunnel(对新游戏支持较多)当游戏端.当然你说以服务器主人的形式玩游戏，那还是比较霸气的
  
  ![13][13]
  <br>

首先在MHP Tunnel客户端的设定勾选**使用UPNP Gateway**
![14][14]
<br>
不知道是不是我这里有问题，点联机的选项会弹出一个错误，影响不大
![15][15]
[15]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E8%81%94%E6%9C%BA%E9%94%99%E8%AF%AF%E6%98%BE%E7%A4%BA.png
<br>
IP：填写你的**本机ip**
端口：填写刚才我说可以乱填的那个**3000**端口（逃
![16][16]
[16]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E5%88%9B%E5%BB%BA%E6%9C%8D%E5%8A%A1%E5%99%A8.png
<br>
这样服务器就算搭建起来了
![17][17]
[17]: https://of4jd0bcc.qnssl.com/MHP_Tunnel/%E6%90%AD%E5%BB%BA%E6%88%90%E5%8A%9F.png
<br>

#### PSP Tunnel连接测试
> 打开PSP Tunnel，ip和端口填**隧道id管理**提供给你的服务器地址和端口

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