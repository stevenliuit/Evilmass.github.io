---
title: 这可能是你见过思路最清晰 psp tunnel 联机教程了
date: 2017-08-04 21:48:39
tags: Game
---

## 写在最前面
时至今日仍坚守 PSP 战线的猎人都值得我们尊敬

<!--more-->


## 所需工具
链接：http://pan.baidu.com/s/1nv1F55v

经过大量玩家反馈，在联机过程中经常会出现掉线问题

### 掉线相关
### UPDATE ！ ！ ！ 目前最简单有效的防掉线方法
某日联机的时候开着迅雷下载东西（没资源速度不快）忘了关，联了几盘才发现没挂 YY，然后在 MHP Tunnel 的 NAT 一栏发现多了两个迅雷的端口，于是关闭迅雷之后随便填了个 6666 端口

![6666nat][6666nat]

软件设定这里的 UPNP Gateway 这里要勾选上，以及路由器的 UPNP 功能也一定要打开，然后就应该可以看到转发的 UPNP 端口了

![open_upnp][open_upnp]

然后打了 5 盘下来也没有见掉线，神奇~

#### 传统防掉线方法

    开两个 QQ 互相语音亦或者下载 YY 随便进入一个频道

#### 个人研究出来的方法（**仍在测试**）
    选择 MHP Tunnel，然后在设定里将**撷取缓存设置为 1，SSID 搜寻时间设置为 500** 
    
    猜想如下：
        目前在网上找到的 PSP Tunnel 中，撷取缓存默认为 1024，而对于像怪物猎人这样的游戏来说，这个缓存数值未免太大了（高达PVP对数据刷新和同步频率更快）。
        远程联机过程中玩家的 PSP 数据在互相交互，那么联机的数据就会暂时保存到 Tunnel 的缓存里面，直至数据量大于 1024后再进行刷新。在未达到 1024 的过程中，数据继续累积。
        此时两台 PSP 的同步数据的差别过大，判定两台 PSP 无法继续进行联机（类比联机距离，两台 PSP 隔的越远，越容易掉线）。
    
    为什么是 MHP Tunnel 呢？
        因为无论是什么版本的 PSP Tunnel 设置缓存为多少都会掉线（非常玄学


时至今日大部分教程仍然是用USB联机的方式，我个人比较推荐**无线联机**

1. 不用受USB线长限制活动
2. 不用担心碰到USB线导致掉线
3. 不用担心桥崩导致掉线
4. 可以躺着联机（雾

**注意事项**：Windows10系统无法安装XP驱动，所以想联机的话
1. 使用USB
2. 虚拟机安装 Windows XP 7 8，无线网卡安装XP驱动

下面给出几个常见的网卡型号，自行参考价位在某东购买，用USB联机的玩家无视即可
**磊科 NW336**（推荐，因为便宜）
**TP-LINK TL-WN725N**
**TP-LINK TL-WN821N**（在 Win7 下安装 XP 驱动会出现蓝屏，不推荐这个）
**迅捷 FW150US**
**水星 MW150US**
内部芯片采用RTL8188CUS或者RTL8188EU芯片的网卡理论上都支持（看产品信息有无 **PSP Link Mode**）

<br>
## USB联机
### XP系统
#### 安装 Winpcap 和 PSP Type B 驱动
Winpcap：选你喜欢的版本安装即可

PSP Type B 驱动

    -> 控制面板（经典视图）
    -> 添加硬件
    -> 是，我已连接了此硬件（Y）
    -> 拉到最下面选择“添加新的硬件设备”
    -> 安装我手动从列表选择的硬件（高级）
    -> 显示所有设备
    -> 从磁盘安装
    -> 找到 PSP Type B 的目录选择 psp.inf
    -> 安装完成

<br>
#### 添加虚拟网卡 Microsoft Loopback Adaptor

    -> 控制面板（经典视图）
    -> 添加硬件
    -> 是，我已连接了此硬件（Y）
    -> 拉到最下面选择“添加新的硬件设备”
    -> 安装我手动从列表选择的硬件（高级）
    -> 网络适配器
    -> Microsoft Loopback Adaptor
    -> 安装完成

这时候打开设备管理器就可以看到 LibUSB-Win32 Devices 下面的 PSP Type B 驱动 和 Microsoft Loopback Adaptor 了
![all_installed][all_installed]

**这时候需要重启系统**：不重启可能会出现进入集会所看不到人的现象

<br>
#### PSP插件设置
根据系统选择联机插件，将AdhocToUSB.prx复制到PSP记忆棒根目录/seplugins下，同时打开game.txt，直接添加以下内容（没有game.txt自己建一个）

    ms0:/seplugins/AdhocToUSB.prx 1

PSPGO添加     ef0:/AdhocToUSB.prx 1

或许平时还有开金手指等其他插件的，建议都设置成0（关闭），因为这些插件可能会与联机插件存在冲突问题（在联机的时候看不到人etc。。。
![adhoc_to_usb][adhoc_to_usb]
<br>

#### 测试USB联机
打开 Tunnel\PSPTunnel大陆修改版\PSP-Tunnel.exe
按图设置即可
![tunnel_settings][tunnel_settings]
<br>

然后选择服务器双击进入，最下面的服务器是我用腾讯的学生云搭建起来的，有兴趣了解怎么搭建服务器的参考这篇教程：[MHP-Tunnel服务器的另类搭建方式]

无法进入的话就去皓月服吧
![tunnel_hosts][tunnel_hosts]
<br>

以怪物猎人p3为例

1. 进入在线集会所
2. 在 PSP Tunnel 里面的设定 -> 无线网卡 -> 选择 Micosoft Lookback Adaptor 网卡 -> 默认 SSID -> 选择你的游戏（PSP_AULJM05800_L_MHP3Q000）
4. 打开 bridge.exe -> 序号 1 代表选择单线程（如果多线程不稳定的话） -> 选择 **你的** Micosoft Lookback Adaptor网卡序号，这里是 1 -> 出现如下文字即代表USB连接成功

    Thread:1
    Searching PSP USB Device
    UsbOpenDevice(): PSP Found
    UsbCheckDevice()=[0]: Connection ok
    error undefine data(recv from psp):0 909ACCEF FFFFFFFF 0 //这条属于是正常情况，无视即可
    psp message1.0 0x00000000
    Module Start:sceNet_Service Ver1.7 found
    Adhoc Hook success.

![bridge][bridge]
<br>
看到右边的在线联机列表有你的名字就代表你可以愉快的玩耍了~
![rally][rally]

### Windows 7 8 10 系统
#### 进入禁用签名驱动模式
默认系统不允许安装未通过验证的驱动，所以直接安装 PSP Type B 驱动是安装不上的，会返回**第三方 INF不包含数字签名信息**

[Win7]

[Win8]

[Win10]

进入该模式后在设备管理器安装 Winpcap 和 PSP Type B 以及 虚拟网卡
#### 关于虚拟网卡有一点值得注意
在 **Windows 8** 和 **Windows 10** 下 Micosoft Lookback Adaptor 已经改名为 **Microsoft KM-TEST 环回适配器**
![km_test][km_test]

**然后重启电脑**
**然后重启电脑**
**然后重启电脑**

开bridge -> 选虚拟网卡 -> 联机

## 无线联机
下载无线网卡的 XP 驱动并安装

    -> 设备管理器 
    -> 找到你的网卡（有可能系统已经帮你安装好了，或者未安装好出现了黄色感叹号的 NI C设备） 
    -> 选择网卡并更新驱动 
    -> 浏览计算机以查找驱动程序软件 
    -> 从计算机的设备驱动列表中选择 
    -> 从磁盘安装 
    -> 找到无线网卡 XP 驱动的目录 
    -> 安装完成
    -> 在网卡属性里将 PSP Link Mode 设置为 Enable
    -> 重启电脑

设置这里有点区别，就是要勾上 **PSP SSID 自动搜寻**
![auto_search][auto_search]

然后进入在线集会所，无线网卡连接上 PSP 游戏发出的 WIFI 信号（PSP_AULJM05800_L_MHP3Q000），Tunnel选择无线网卡，成功联机

## FAQ
1. 如何切换集会所房间
    -> 设定
    -> 网卡选择（找不到无线网卡 (只能聊天)）
    -> 确定（退出房间）
    -> 设定
    -> 更改房间号
    -> 选回联机网卡
    -> 确定（进入新房间）
    这样的操作不会在旧集会所留下自己记录，如若切换房间留下记录（直接改房间号码），别人将无法进入之前的房间补上空位，需要其余3人重新进入集会所清除完你留下的记录。
这个基本操作是联机过程中的的好习惯~

2. Winpcap开启失败

        WinPcap初始化失败。
        WinPcap read handle initialize failed
        WinPcap write handle initialize failed
        无法开启网卡 Realtek RTL8192EU Wireless LAN 802.11n USB 2.0 Network Adapter。

    第一种情况：你没有安装Winpcap
    第二种情况：你之前安装的Winpcap版本太高（V4.1.2或者V4.1.3），卸载原来的Winpcap重启电脑再安装V4.0.2即可
    第二种情况：未将无线网卡的 PSP Link Mode 设置为Enable
    第三种情况：无线网卡的 XP 驱动不能在该系统正常工作（装完驱动蓝屏），换张网卡或者USB吧

3. 一直显示 Searching PSP USB Device
    Winpcap 是否安装？
    PSP Type B 驱动是否安装？
    虚拟网卡的序号选择是否正确？
    AdHocToUSB.prx 是否复制到 PSP 的 seplugins 目录？
    AdhocToUSB.prx 插件是否开启？

4. Tunnel 打开显示 Access violation at address 错误
    ![access_error][access_error]

    软件兼容性问题，选择 XP 的 SP3 兼容模式运行即可
    
    ![xp_sp3][xp_sp3]
    
### 后续研究
1. 防掉线机制
2. 反汇编 MHP Tunnel 修复内置联机列表失效的问题

**Alipay** 
![Alipay][Alipay]

**Wechat**  
![Wechat][Wechat]

[Alipay]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200
[Wechat]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/patapon_wechat.gif?imageView2/1/w/200/h/200


[MHP-Tunnel服务器的另类搭建方式]:https://evilmass.cc/2017/01/22/MHP-Tunnel%E6%9C%8D%E5%8A%A1%E5%99%A8%E7%9A%84%E5%8F%A6%E7%B1%BB%E6%90%AD%E5%BB%BA%E6%96%B9%E5%BC%8F/
[all_installed]: https://of4jd0bcc.qnssl.com/psp_tunnel/all_installed.png
[adhoc_to_usb]: https://of4jd0bcc.qnssl.com/psp_tunnel/adhoc_to_usb.png
[tunnel_settings]: https://of4jd0bcc.qnssl.com/psp_tunnel/tunnel_settings.png
[tunnel_hosts]: https://of4jd0bcc.qnssl.com/psp_tunnel/bridge.png
[bridge]: https://of4jd0bcc.qnssl.com/psp_tunnel/bridge.png
[Win7]: http://jingyan.baidu.com/article/acf728fd495b9ff8e410a377.html
[Win8]: http://jingyan.baidu.com/article/ca2d939d0e47ceeb6c31cea0.html
[Win10]: http://jingyan.baidu.com/article/624e74594dbc8d34e8ba5aa6.html
[km_test]: https://of4jd0bcc.qnssl.com/psp_tunnel/km_test.png
[rally]: https://of4jd0bcc.qnssl.com/psp_tunnel/rally.png
[auto_search]: https://of4jd0bcc.qnssl.com/psp_tunnel/auto_search.png
[6666nat]: https://of4jd0bcc.qnssl.com/psp_tunnel/6666nat.png
[open_upnp]: https://of4jd0bcc.qnssl.com/psp_tunnel/open_upnp.png
[access_error]: https://of4jd0bcc.qnssl.com/psp_tunnel/access_error.png
[xp_sp3]: https://of4jd0bcc.qnssl.com/psp_tunnel/xp_sp3.png