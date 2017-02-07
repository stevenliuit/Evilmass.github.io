---
title: GSM Sniffer小测试
date: 2016-10-19 13:59:29
tags: Hack
---
最近看到osmocom-bb项目，觉得很有趣，硬件的嗅探成本较之前已经低的难以想象，遂动手做一遍

## 关于Kali配置环境的一些问题
>网上大部分教程都是用到的最新的gnu-arm-build.3脚本，但这个脚本编译的gcc版本是4.8.2，在Kali2下
gcc -v，会发现gcc的版本是4.9.2，即便编译成功，会出现如下问题：Osmocom-bb的Master分支能扫描到基站，但wireshark捕获到的全是GSMTAP，没有GSM_SMS。Luca/gsmmap分支则会扫描不到基站。Ubuntu12.04的gcc正好是4.5.2，因此在Ubuntu12.04下，我们用gnu-arm-build.2的脚本就能编译成功

##当然，解决办法还是有的:
<!--more-->

## 修改osmocom-bb以下五个文件
    src/target/firmware/board/compal/highram.lds
    src/target/firmware/board/compal/ram.lds
    src/target/firmware/board/compal_e88/flash.lds
    src/target/firmware/board/compal_e88/loader.lds
    src/target/firmware/board/mediatek/ram.lds

## 找到每个文件中的 KEEP(*(SORT(.ctors))) 一行，在其下面加入新的一行 KEEP(*(SORT(.init_array)))
例如：

    LONG(SIZEOF(.ctors) / 4 - 2)
    /* ctor pointers */
    KEEP(*(SORT(.ctors)))
    KEEP(*(SORT(.init_array)))
    /* end of list */
    LONG(0)

![fix_cell_log](https://of4jd0bcc.qnssl.com/GsmSniffer/fix_cell_log.png)

## 执行编译
    cd /root/armtoolchain/osmocom-bb/src
    make -e CROSS_TOOL_PREFIX=arm-none-eabi-


</br>

## 环境配置
虚拟机Vmware + Ubuntu12.04 + c118 + 数据连接线 + FT232RL模块
Ubuntu12.04:[下载地址](magnet:?xt=urn:btih:9645EAC5BE3309982D6BCD559DDB30E8A7D163C9 c118)
种子文件:[pan.baidu.com/s/1gfjoZ3l](http://pan.baidu.com/s/1gfjoZ3l)密码:jjth
C118:
![C118](https://of4jd0bcc.qnssl.com/GsmSniffer/C118.jpg?imageView2/1/w/200/h/200)

数据连接线:
![数据线1](https://of4jd0bcc.qnssl.com/GsmSniffer/%E6%95%B0%E6%8D%AE%E7%BA%BF.jpg?imageView2/1/w/200/h/200)
![数据线2](https://of4jd0bcc.qnssl.com/GsmSniffer/%E6%95%B0%E6%8D%AE%E7%BA%BF%202.jpg?imageView2/1/w/200/h/200)

FT232RL模块:
![FTR232R](https://of4jd0bcc.qnssl.com/GsmSniffer/FT232RL.jpg?imageView2/1/w/200/h/200)

PS.记住所有操作在sudo -s root权限下操作。
Ubuntu 的软件源配置文件是 /etc/apt/sources.list。将系统自带的该文件做个备份，将该文件替换为下面内容，即可使用 TUNA 的软件源镜像。
    
    deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise main multiverse restricted universe
    deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-backports main multiverse restricted universe
    deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-proposed main multiverse restricted universe
    deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-security main multiverse restricted universe
    deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-updates main multiverse restricted universe
    deb-src http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise main multiverse restricted universe
    deb-src http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-backports main multiverse restricted universe
    deb-src http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-proposed main multiverse restricted universe
    deb-src http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-security main multiverse restricted universe
    deb-src http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ precise-updates main multiverse restricted universe

>apt-get update

## 环境依赖
    sudo apt-get install aptitude libtalloc2 libtalloc2-dbg python-talloc python-talloc-dbg python-talloc-dev libtalloc-dev automake libusb-dev libpcsclite-dev libusb-0.1-4 libpcsclite1 libccid pcscd libtool shtool autoconf git-core pkg-config make gcc build-essential libgmp3-dev libmpfr-dev libx11-6 libx11-dev texinfo flex bison libncurses5 libncurses5-dbg libncurses5-dev libncursesw5 libncursesw5-dbg libncursesw5-dev zlibc zlib1g-dev libmpfr4 libmpc-dev libpcsclite-dev libfftw3-dev libfftw3-doc


    aptitude install libtool shtool automake autoconf git-core pkg-config make gcc

## 配置文件
这里建议用迅雷下载好再拖到虚拟机里面
打包下载放到/root目录下即可[pan.baidu.com/s/1i5sGAKt](http://pan.baidu.com/s/1i5sGAKt) 密码: gvuv

    http://bb.osmocom.org/trac/raw-attachment/wiki/GnuArmToolchain/gnu-arm-build.2.sh
    http://ftp.gnu.org/gnu/gcc/gcc-4.5.2/gcc-4.5.2.tar.bz2
    http://ftp.gnu.org/gnu/binutils/binutils-2.21.1a.tar.bz2
    ftp://sources.redhat.com/pub/newlib/newlib-1.19.0.tar.gz

把下载好的3个包放到src目录下
目录结构
![目录结构](https://of4jd0bcc.qnssl.com/GsmSniffer/%E7%9B%AE%E5%BD%95%E7%BB%93%E6%9E%84.png)

    cd /root/armtoolchain
    chmod +x gnu-arm-build.2.sh
    ./gnu-arm-build.2.sh

大约20分钟后出现下面代码代表编译完成
> Build complete! Add /root/arm_toolchain/install/bin to your PATH to make arm-elf-gcc and friends accessible directly

把以下代码加到~/.bashrc的最后一行
>export PATH=$PATH:/root/armtoolchain/install/bin

执行一下让其生效
>source ~/.bashrc

当你在命令行输入arm再按两下tab出现下图的时候，代表编译环境配置好了
![arm链](https://of4jd0bcc.qnssl.com/GsmSniffer/arm%E9%93%BE.png)
## libosmocore## 
    cd /root
    git clone git://git.osmocom.org/libosmocore.git
    cd libosmocore/
    autoreconf -i
    ./configure
    make
    sudo make install
    sudo ldconfig -i

这里可能有个坑，执行完上述代码后在命令行输入arm再按两下tab的时候，arm链会莫名其妙的消失，需要再去~/.bashrc再配置一遍,如果arm链正常就不需要配置了
## Osmocom-bb
    cd /root
    git clone git://git.osmocom.org/osmocom-bb.git
    cd osmocom-bb
    git checkout --track origin/luca/gsmmap #选择luca/gsmmap分支 
    cd src
    make #交叉编译

如果没什么问题，软件环境和固件就都编译好了。
Ununtu 12.04自带FT232R驱动，所以直接连接就能使用，不需要再装驱动。
## 加载Firmware到手机raw中## 
    cd /root/armtoolchain/osmocom-bb/src/host/osmocon ./osmocon -m c123xor -p /dev/ttyUSB0 ../../target/firmware/board/compal_e88/layer1.compalram.bin

上面命令需要在关机下执行，然后短按开机键
手机屏幕显示Layer 1 osmocom-bb 字样就表示成功了
![Layer1](https://of4jd0bcc.qnssl.com/GsmSniffer/Layer1.jpg?imageView2/1/w/200/h/200)
## 扫描基站
    cd /root/armtoolchain/osmocom-bb/src/host/layer23/src/misc/
    ./cell_log –O

![ARFCN](https://of4jd0bcc.qnssl.com/GsmSniffer/ARFCN.jpg?imageView2/1/w/200/h/200)

THE_ATFCN_ID就是扫描到的日志中参数ARFCN的值，尽可能选信号好的

    cd /root/armtoolchain/osmocom-bb/src/host/layer23/src/misc/
    ./ccch_scan -i 127.0.0.1 -a THE_ATFCN_ID

上图第一个基站的ARFCN就是40

苹果手机可以执行：`*3001#12345#*`
进入工程模式后，选择GSM Cell Environment-&gt;GSM Cell Info-&gt;GSM Serving Cell,就可以看到目前手机连接的基站ARFCN值了，应该在第二步中，也能看到这个ID存在。

更多姿势请看这里:[osmocom-bb中cell_log的多种使用姿势](http://www.92ez.com/?action=show&amp;id=23342)

因为osmocomBB执行之后默认会在本地开启4729端口，这时候的GSM协议已经被封装上了TCP-IP，可以在本地用wireshark抓到，所以我们使用wireshark去监听4729的端口
wireshark打开错误的话需要到/usr/share/wireshark/init.lua文件注释掉倒数第二行
> wireshark -k -i lo -f ’port 4729

在wireshark中过滤gsm_sms协议数据，过滤之后得到的数据里面就包含短信的明文信息
![短信拦截](https://of4jd0bcc.qnssl.com/GsmSniffer/%E7%9F%AD%E4%BF%A1%E6%8D%95%E8%8E%B7.jpg)

## Some Questions
GSM sniffer嗅探一段时间出错问题的解决方法:
这里我分成两种情况来说。
当你看到此文时你可能已经成功嗅探，只是没有打补丁。意思是你已经配置好了环境，那么打补丁就更简单了。首先你需要删掉

> osmocom-bb/src/host/layer23/src/misc/app_ccch_scan.c
> osmocom-bb/src/host/layer23/src/misc/app_ccch_scan.o

这两个文件，删除之后下载补丁文件app_ccch_scan.c文件，链接: [http://pan.baidu.com/s/1bpDPrDt](http://pan.baidu.com/s/1bpDPrDt) 密码: 3wvp
下载此文件放入osmocom-bb/src/host/layer23/src/misc/目录下，如果出现没有权限的错误，请赋予权限。然后切换到目录下执行编译

    cd osmocom-bb/src/
    make

如果出现git的版本问题，执行下这个命令
>git checkout -f

然后再编译，编译完成后就可以了。

第二种情况是你看到这篇文章后打算重新来一遍，那么你前面的步骤依旧，直到你执行了这句

>git checkout --track origin/luca/gsmmap

如果出现git版本问题没有成功，请执行上面说到的那个git版本问题解决的命令再切换分支，切换分支成功之后，下载补丁文件覆盖掉原有的文件，然后执行make编译，编译完成即可。

## 进阶
![多机嗅探](https://of4jd0bcc.qnssl.com/GsmSniffer/%E5%A4%9A%E6%9C%BA%E5%97%85%E6%8E%A2.jpg)
一台C118一次只能针对一个ARFCN进行拦截而且每次嗅探需要打开多个窗口
要是能写个自动化的嗅探脚本，再保存到数据库在Web界面实时展示信息就好了
是的。大牛们已经做出多机嗅探和Web展示的脚本。这里要膜拜一下~

## WEB界面搭建配置
### Debian:
    sudo apt-get install mysql-server python-mysqldb

### PHP:
    apt-get install php5 php-pear php5-mysql
    service apache2 restart

### Linux Fedora, CentOS系统：
    yum install MySQL-python


## 下载web界面程序
链接:[ http://pan.baidu.com/s/1nuHYzZb](http://pan.baidu.com/s/1nuHYzZb) 密码: 43h4
> getusb.sh
> m.py
> smshack_nosql.py
> smshack.sql

以上4个文件放在root文件夹下
sms_web文件夹复制到/var/www/下

## 安装phpadmin,按提示配置MySql数据库
    sudo apt-get install phpmyadmin
    cd /var/www/html
    sudo ln -s /usr/share/phpmyadmin phpmyadmin


## 导入数据库
打开浏览器，输入http://localhost/phpmyadmin
通过界面导入smshack.sql![导入sql](https://of4jd0bcc.qnssl.com/GsmSniffer/%E5%AF%BC%E5%85%A5smssql.png)

## 修改/var/www/sms_web/bin下的sms.php文件的MySql用户名密码,否则会刷新不出SMS信息#
![sms_php](https://of4jd0bcc.qnssl.com/GsmSniffer/sms_php.png)

## 修改m.py设置登陆MySql的用户名密码、数据库名称
![m脚本](https://of4jd0bcc.qnssl.com/GsmSniffer/m.png)
执行
> python m.py

并按提示操作

## 浏览器访问http://localhost/sms_web
![web](https://of4jd0bcc.qnssl.com/GsmSniffer/web.jpg)

## 关于嗅探
因为我们买的便宜货，每个手机只能嗅探一个信道，具体一些的，可以参考下面的图（我们现在只能抓Downlink的数据包）：
![channel](https://of4jd0bcc.qnssl.com/GsmSniffer/ARFCN_Channel.jpg)

因为想要Sniffer Uplink的包，要修改硬件，C118主板上的RX filters要换掉，换成我们需要的HHM1625&amp;&amp;HHM1623C1滤波器组件，才能抓Uplink的数据包。
有关信道号ARFCN的问题，可以参考下面的图：
![ARFCN_channel](https://of4jd0bcc.qnssl.com/GsmSniffer/channel.jpg)

## Tips
>现在2G短信越来越少了，多等等会有的。理论上话音一样能够被监听及解码，只是涉及技术更为复杂。
CP210x的接线，RX和TX有可能需要对调。运行cp210x-program需要先安装ibusb-dev，如果输出是“No devices found”或“Unable to send request, 3709 result=-110”，则有问题
*可以参考：CP210x Tutorial

![再给力点](https://of4jd0bcc.qnssl.com/GsmSniffer/%E8%83%BD%E4%B8%8D%E8%83%BD%E5%86%8D%E7%BB%99%E5%8A%9B%E7%82%B9.jpg)

## 后期计划
捕获上行包
因为想要嗅探Uplink的包，要修改硬件，C118主板上的RX filters要换掉，换成我们需要的HHM1625&amp;&amp;HHM1623C1滤波器组件，才能抓Uplink的数据包。要使手机能够成为『passive uplink sniffer』，必须动到电烙铁，替换掉RX filters。
![过滤器](https://of4jd0bcc.qnssl.com/GsmSniffer/%E8%BF%87%E6%BB%A4%E5%99%A8.jpg)
替换前：
![更改前](https://of4jd0bcc.qnssl.com/GsmSniffer/before.jpg)
摘掉后：
![摘掉后](https://of4jd0bcc.qnssl.com/GsmSniffer/change.jpg)
替换后：
![替换](https://of4jd0bcc.qnssl.com/GsmSniffer/after.jpg)
## 使用OsmocomBB RSSI monitor查看信号强弱：## 
    ./osmocom-bb/src/host/osmocon/osmocon -p /dev/ttyUSB0 -m c123xor -c ./osmocom-bb/src/target/firmware/board/compal_e88/rssi.highram.bin ./osmocom-bb/src/target/firmware/board/compal_e88/chainload.compalram.bin

![rssi](https://of4jd0bcc.qnssl.com/GsmSniffer/rssi.jpg)
由于RSSI太大，不便于像OsmocomBB那样直接加载，所以要先用-C参数加载一个小的chainloader程序去加载我们真正的RSSI Payload程序。
参考：http://bb.osmocom.org/trac/wiki/rssi.bin

多机嗅探强烈建议看:[如何让GSM Sniffer变得更加智能化](http://www.92ez.com/?action=show&amp;id=23363)

## GSM网络相关知识
>推荐看看 GSM network and services 2G1723 2006
![MS_BSS](https://of4jd0bcc.qnssl.com/GsmSniffer/MS_BSS.jpg)

从协议图中得知，移动设备(MS)和基站(BTS)间使用Um接口，最底层就是刷入手机的layer1物理传输层，之上分别是layer2数据链路层和layer3网络层。
![LAPDm](https://of4jd0bcc.qnssl.com/GsmSniffer/LAPDm.jpg)
位于图中layer2的LAPDm，是一种保证数据传输不会出错的协议。一个LAPDm帧共有23个字节（184个比特），提供分片管理控制等功能。
layer3的协议则可以分为RR/MM/CM三种，这里只列出嗅探相关的功能：
>RR(Radio Resource Management)：channel, cell（控制等信息，可以忽略）
MM(Mobility Management)：Location updating（如果需要接收方号码，需要关注这个动作）
CM(Connection Management)：Call Control(语音通话时的控制信息，可以知道何时开始捕获TCH),SMS（这里的重点）

![Layer3](https://of4jd0bcc.qnssl.com/GsmSniffer/Layer3.jpg)
参考GSM的文档 TS 04.06 得知 LAPDm 的Address field字段中，定义了 3.3.3 Service access point identifier (SAPI)。SAPI=3就是我们要的Short message service。
使用tcpdump配合show_gsmtap_sms.py脚本在console列出短信明文。
>tcpdump -l -ilo -nXs0 udp and port 4729 | python2 -u show_gsmtap_sms.py

## 一些名词解释## 
> MS：Mobile Station，移动终端；
> IMSI：International Mobile Subscriber Identity，国际移动用户标识号，是TD系统分给用户的唯一标识号，它存储在SIM卡、HLR/VLR中，最多由15个数字组成；
> MCC：Mobile Country Code，是移动用户的国家号，中国是460；
> MNC：Mobile Network Code ，是移动用户的所属PLMN网号，中国移动为00、02，中国联通为01；
> MSIN：Mobile Subscriber Identification Number，是移动用户标识；
> NMSI：National Mobile Subscriber Identification，是在某一国家内MS唯一的识别码；
> BTS：Base Transceiver Station，基站收发器；
> BSC：Base Station Controller，基站控制器；
> MSC：Mobile Switching Center，移动交换中心。移动网络完成呼叫连接、过区切换控制、 无线信道管理等功能的设备，同时也是移动网与公用电话交换网(PSTN)、综合业务数字网(ISDN)等固定网的接口设备；
> HLR：Home location register。保存用户的基本信息，如你的SIM的卡号、手机号码、签约信息等，和动态信息，如当前的位置、是否已经关机等；
> VLR：Visiting location register，保存的是用户的动态信息和状态信息，以及从HLR下载的用户的签约信息；
> CCCH：Common Control CHannel，公共控制信道。是一种“一点对多点”的双向控制信道，其用途是在呼叫接续阶段，传输链路连接所需要的控制信令与信息。

## GSM Sniffer 嗅探的一些疑惑解答:
### 为何只有改过滤波器的机器才能嗅探到上行的短信？
>首先，我们可以站在手机制造商的角度去看这个问题。
作为一个手机，应该具备的功能是接收基站发给自己的信号，以及主动向基站发送信号。手机与基站的信号传递分为上行和下行。下行就是基站下发到手机上的信号，上行就是手机发往基站的信号。手机收发短信的时候并不需要接收周围其他手机的信号，其他手机爱发什么发什么，管我屁事。上行频率跟下行频率是不同的。
手机制造商在制造手机的时候只 需要手机支持两种频率，第一，基站发送给手机的下行频率。我们嗅探下行，其实就是因为手机本身可以接收下行的频率。那么我们再想一下，我们需要嗅探上行的 短信，该怎么办呢？上行是手机发往基站的信号，我们只要让手机能接收上行的频率就可以了。
对，就是这样。暂且我们将手机想象成一个小型基站，其实在发短信这个过程中，手机确实充当的是发射台的角色，基站则变身为接收端，如果此时我们的手机可以接收上行频率，那么我们就可以嗅探上行短信了。但是手机本身是不能接收上行频率的，因为手机的本质工作也不需要这个。
那么，我们需要支持上行频率该怎么办呢？没错，就是修改手机的滤波器。滤波器，顾名思义，就是过滤电磁波的原件，空气中电磁波那么多，我们需要的只是一个频段的频率，所以，滤波器就把接收到的所有的信号过滤，只需要GSM 频段的电磁波。我们都知道，GSM信号的频率是有一个范围的，不同国家之间啊也有差异。但是基本上是在850Mhz到900Mhz之间这一段。上行频率不在这个频段。我们修改滤波器，将能够接收的频率范围扩宽，把上行频率的范围包含进来，这样我们既可以嗅探上行，也可以嗅探下行了。
这在RTL-SDR中是一样的，SDR接收的频率范围比较广，包含了GSM上行和下行的所有频段，所以SDR也可以用来做GSM Sniffer。

### 问题一：改过的机子搜索到的基站好少，而且信号差很多，是不是改机把主板改坏了？
>解释：这个问题是最常见的，很多朋友在拿到改好的机子之后测试普遍有这个感觉，改过的机子跟没改的机子进行对比，很明显就能发现，搜索到的基站数量并没有没改的多。而且信号衰减也严重。信号衰减是很正常的。这里得说到改机的作用。

### 我们为何要改机呢？
>很多朋友都没有去思考这个问题。不改机为什么收不到上行呢？博主在这要说的是，既然是做学问，搞研究，爱折腾，就应该去问一下自己这些问题，而不是看到网上有人说改机可嗅探上行就觉得一定要改机，改机出问题了找不到答案就开始各种问。与其花费大量时间去寻找信号衰减的答案，还不如先思考些改机的原理。在这块很多朋友做的还是不够啊。
博主在一开始就说到了修改滤波器可以收到上行。修改滤波器的目的就是扩宽接收频率的范围，那么问题来了，接收的范围越大，接收到的电波信号越多，那么噪音就越多，什么是噪音呢？噪音就是指的与自己需要的不相关的东西，再加上我们改机的时候拆除了主板上的屏蔽盖，很容易受到各种电磁干扰，所以会导致信号衰减严重。这并不是因为手机主板的问题。有的时候选用的滤波器原件品质也可能会影响到信号。博主改机目前能达到的水平是信号衰减控制在5db~12db之间。

### 问题二：为什么我嗅探了好久都没收到上行短信，或者为何我收到的上行短信数量很少呢？
>解释：这个问题也不难想通。你想一下，基站对手机是一对多的关系，单位时间里面一个基站发出来的短信少则几十条，多则几万条，平均分配到每个信道每个频点上也不算少。
而对于上行来说，博主在开始就介绍了上行嗅探的原理，上行是手机对基站，一对一的关系，试想一下，如果你周围同时有几万人在发短信，而且是在向同一个基站发送短信，而且这个距离你手机接收上行信号没有问题，那么，你接收到的上行短信肯不比下行的少。问题是这种情况现实中有吗？答案是否。现实中不可能有几万人在你周围同时向一个基站发短信。况且，你的手机接收别人的手机信号，距离也是有限制的。
我们在接收下行的时候，基站能够覆盖一大片区域，是因为他的功率非常大。而手机发短信的时候功率是非常小的，你手机能接收到上行信号的距离非常有限。这就导致了接收上行短信的数量非常少。而不是因为手机本身有问题导致的。
希望我的解释能够让你眼前一亮，豁然开朗。

## 参考文献## 
1.[https://github.com/osmocom/osmocom-bb](https://github.com/osmocom/osmocom-bb)
2.[http://bb.osmocom.org/trac/wiki/TitleIndex](http://bb.osmocom.org/trac/wiki/TitleIndex)
3.[http://wulujia.com/2013/11/10/OsmocomBB-Guide/](http://wulujia.com/2013/11/10/OsmocomBB-Guide/)
4.[https://blog.hqcodeshop.fi/archives/253-iPhone-cell-Field-Test-mode.html](https://blog.hqcodeshop.fi/archives/253-iPhone-cell-Field-Test-mode.html)
5.[http://bbs.pediy.com/showthread.php?t=182574](http://bbs.pediy.com/showthread.php?t=182574)
6.[http://www.92ez.com/?action=show&amp;id=23342](http://www.92ez.com/?action=show&amp;id=23342)
7.[https://www.nigesb.com/gsm-hacker-sheet.html](https://www.nigesb.com/gsm-hacker-sheet.html)
8.[http://le4f.net/post/post/gsm-sniffer-hacking-toolkits-demo](http://le4f.net/post/post/gsm-sniffer-hacking-toolkits-demo)
9.[http://le4f.net/post/post/compile-osmocombb&amp;problems-about-gsm-sniffer](http://le4f.net/post/post/compile-osmocombb&amp;problems-about-gsm-sniffer)

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]

[99]:https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E8%B6%85%E5%B8%85_alipay.gif?imageView2/1/w/200/h/200
[100]:https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200