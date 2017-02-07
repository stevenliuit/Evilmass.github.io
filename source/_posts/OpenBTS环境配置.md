---
title: OpenBTS 5.0环境配置 
date: 2016-10-02 9:45:34  
categories: Hack
toc: true  //在此处设定是否开启目录，需要主题支持。
---

###  在git clone时推荐使用<a href="https://evilmass.github.io/2017/01/18/%E5%88%A9%E7%94%A8proxychains%E5%9C%A8%E7%BB%88%E7%AB%AF%E4%BD%BF%E7%94%A8socks5%E4%BB%A3%E7%90%86/" target="_blank">proxychains</a>操作
<br>

###  System: <a href="https://mirror.umd.edu/ubuntu-iso/14.04/ubuntu-14.04.4-desktop-amd64.iso" target="_blank">Ubuntu-14.04.4-desktop-amd64</a>
<br>

<!--more-->



###  USTC Sources:
<pre>
    deb https://mirrors.ustc.edu.cn/ubuntu/ trusty main restricted universe multiverse
    deb-src https://mirrors.ustc.edu.cn/ubuntu/ trusty main restricted universe multiverse
    deb https://mirrors.ustc.edu.cn/ubuntu/ trusty-security main restricted universe multiverse
    deb-src https://mirrors.ustc.edu.cn/ubuntu/ trusty-security main restricted universe multiverse
    deb https://mirrors.ustc.edu.cn/ubuntu/ trusty-updates main restricted universe multiverse
    deb-src https://mirrors.ustc.edu.cn/ubuntu/ trusty-updates main restricted universe multiverse
    deb https://mirrors.ustc.edu.cn/ubuntu/ trusty-backports main restricted universe multiverse
    deb-src https://mirrors.ustc.edu.cn/ubuntu/ trusty-backports main restricted universe multiverse
</pre>

<br><br>






### 1:install a series of dependency
<pre>
    sudo apt-get update && apt-get upgrade
    sudo apt-get install aptitude libtalloc2 libtalloc2-dbg python-talloc python-talloc-dbg python-talloc-dev libtalloc-dev automake libusb-dev libpcsclite-dev libusb-0.1-4 libpcsclite1 libccid pcscd libtool shtool autoconf git-core pkg-config make gcc build-essential libgmp3-dev libmpfr-dev libx11-6 libx11-dev texinfo flex bison libncurses5 libncurses5-dbg libncurses5-dev libncursesw5 libncursesw5-dbg libncursesw5-dev zlibc zlib1g-dev libmpfr4 libmpc-dev libpcsclite-dev libfftw3-dev libfftw3-doc vim # 没有Vim用我要死了
    aptitude install libtool shtool automake autoconf git-core pkg-config make gcc
</pre>

<br><br>






### 2：Create several directories , download ARM compiler
  参考<a href="http://evilmass.cc/gsm-sniffer/">Gsm Sniffer小测试</a>配置armtoolchain环境，但这次我们用gnu-arm-build.3.sh这个脚本
  armtoolchain：<a href="http://pan.baidu.com/s/1mhILGtq" target="_blank">http://pan.baidu.com/s/1mhILGtq 密码：qfhn</a>
<br><br>




### 3：Download and compile osmocomBB
<pre>  
    git clone git://git.osmocom.org/libosmocore.git
    git clone git://git.osmocom.org/osmocom-bb.git
    git clone git://git.osmocom.org/libosmo-dsp.git 
</pre>

When installing libosmocore execute ./configure many people will encounter No package 'talloc' found such a mistake , because they can not talloc, Here is the solution
<pre>    
    get https://www.samba.org/ftp/talloc/talloc-2.1.7.tar.gz
    tar -zxvf talloc-2.1.7.tar.gz
    cd talloc-2.1.7/
    ./configure
    make
    sudo make install
</pre>

talloc-2.1.7：<a href="http://pan.baidu.com/s/1bpKapgr" target="_blank">http://pan.baidu.com/s/1bpKapgr 密码：u8dz</a>



#### install libosmocore
<pre>    
    d libosmocore/
    autoreconf -i
    ./configure 
    make
    sudo make install 
    sudo ldconfig 
    cd ..
</pre>

#### install libosmo-dsp
<pre>
    cd libosmo-dsp/
    autoreconf -i
    ./configure
    make 
    sudo make install
    sudo ldconfig
    cd ..
</pre>

<br><br>




#### Compile osmocombb
   参考<a href="https://evilmass.github.io/2016/10/19/GSM-Sniffer%E5%B0%8F%E6%B5%8B%E8%AF%95/" target="_blank">Gsm Sniffer小测试</a>修正cell_log问题
<pre>  
    cd  osmocom-bb    
    git checkout sylvain/testing     
    vim src/target/firmware/Makefile
</pre>

##### # CFLAGS +=-DCONFIG_TX_ENABLE <- 去掉前面的注释

<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/tx_enable.png" alt="tx_enable" />
<pre>
    cd src
    make #HOST_layer23_CONFARGS=--enable-transceiver 
</pre>

<br><br>





### 6:download openbts5.0
<pre>    sudo apt-get install software-properties-common python-software-properties
        sudo add-apt-repository ppa:git-core/ppa #(press enter to continue)
        sudo apt-get update
        sudo apt-get remove git && apt-get install git
        cd /root/armtoolchain
        git clone https://github.com/RangeNetworks/dev.git
</pre>

<br><br>




### 7:then install a series of dependency    
<pre>    sudo apt-get install ntp bind9 libboost-dev
</pre>

<br><br>




### 8:git ssh key
<pre>
    ssh-keygen -t rsa -b 4096 -C "your_email@example.com" #主题显示有问题，这里是英文的双引号，下同
    eval "$(ssh-agent -s)"
    ssh-add ~/.ssh/id_rsa
    more ~/.ssh/id_rsa.pub
</pre>

<a href="https://help.github.com/articles/adding-a-new-ssh-key-to-your-github-account/" target="_blank">在GitHub账户添加你的SSH-KEYS</a>
<pre>
    ssh -T git@github.com 
</pre>

输入yes
<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/ssh_T.png" alt="ssh_T" />
若有其他问题请参考<a href="https://help.github.com/categories/ssh/" target="_blank">GitHub--Help</a>
<br><br>


### 9:install openbts5.0
<pre>
    cd /root/armtoolchain/dev
    ./clone.sh #这里建议用梯子
    ./switchto.sh 5.0 
</pre>


### download asterisk-11.7.0.tar.gz and coredumper-1.2.1.tar.gz
因为GFW的缘故这两个文件在执    ./build.sh的时候下载不能（#若是有梯子可以直接执行）
百度盘：<a href="http://pan.baidu.com/s/1bptBPVL" target="_blank">http://pan.baidu.com/s/1bptBPVL 密码：e72m</a>
 
### 墙外的官方连接
<a href="http://downloads.asterisk.org/pub/telephony/asterisk/releaasterisk-11.7.0.tar.gz" target="_blank">http://downloads.asterisk.org/pub/telephony/asterisk/releaasterisk-11.7.0.tar.gz</a>
<a href="https://storage.googleapis.com/google-code-archive-downloadscode.google.com/google-coredumper/coredumper-1.2.1.tar.gz" target="_blank"> https://storage.googleapis.com/google-code-archive-downloadscode.google.com/google-coredumper/coredumper-1.2.1.tar.gz</a>
    # 将asterisk-11.7.0.tar.gz复制到dev/asterisk
    # 将coredumper-1.2.1.tar.gz复制到dev/libcoredumper


在dev目录下注释掉bulid.sh的一些命令，否则会影响编译（要不下那两个文件干嘛= w =）
 
<code>gedit build.sh</code>   
<pre> 
    #installIfMissing libzmq5 
    #rm -rf range-asterisk* asterisk-*
</pre>

<code>gedit asterisk/build.sh</code>
<pre>
    #if [ ! -f asterisk-$VERSION.tar.gz ] 
    #then 
    #    sayAndDo wget  http://downloads.asterisk.org/pub/telephony/aste    releases/asterisk-  $VERSION.tar.gz 
    #fi 
    #if [ -d asterisk-$VERSION ] 
    #then 
    #   sayAndDo rm -rf asterisk-$VERSION 
    #fi
</pre>

<code>gedit libcoredumper/build.sh</code>
<pre>
    #if [ ! -f coredumper-$VERSION.tar.gz ] 
    #then 
    #    sayAndDo wget http://google-coredumper.googlecode.com/files/coredumper-$VERSION.tar.gz 
    #fi 
    #if [ -d coredumper-$VERSION ] 
    #then 
    #   sayAndDo rm -rf coredumper-$VERSION 
    #fi
</pre>

<br>

<pre>
    cd /root/armtoolchain/dev/liba53
    sudo make install
    cd ../ #回到dev目录
    ./build.sh SDR1 #按照SDR1形式编译
</pre>

如果一切顺利,编译成功,会在/dev/BUILDS/下生成 2016-xx-xx--xx-xx-xx文件夹 以时间命名,如下图
<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/bulid_timestamp.png" alt="bulid_timestamp" />

<br><br>


### 10:open bts
<pre>
    cd /root/armtoolchain/dev/BUIDLS/2016-xx-xx--xx-xx-xx/    
    sudo dpkg -i *.deb
    #如果此处会报错提示依赖不满足,我们修复一下之后再重新安装deb
    sudo apt-get -f install && sudo dpkg -i *.deb
    #现在我们可以运行OpenBTS了
    sudo start asterisk 
    sudo start sipauthserve 
    sudo start smqueue 
    sudo start openbts
</pre>

#### 创建Transceiver
在/dev/openbts/apps/文件夹中创建一个文件，名为transceiver.sh， 打开后将以下两行代码贴入： 
<pre>
    #!/bin/bash 
    exec /root/armtoolchain/osmocom-bb/src/host/layer23/src/transceiver/transceiver 115
</pre>

其中结尾的“115”是指手机用来同步的ARFCN编号，这个需要测量后选取信号最好的ARFCN 

#### 进入osmocom-bb目录
<pre>
   
    cd /root/armtoolchain/osmocom-bb/src/host/osmocon 
</pre>


#### 以下是通过给手机刷入rssi.bin来进行ARFCN测量的方法
<pre>
    sudo ./osmocon -p /dev/ttyUSB0 -m c123xor -c ./src/target/firmware/board/compal_e88/rssi.highram.bin
</pre>

<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/test_afrcn.png" alt="test_arfcn" />

#### 下面需要修改OpenBTS.db设置
#### 进入osmocon文件夹，并刷入trx的固件，运行完命令后按一下手机的开机键（不是长按，只用按一下），下图是正确的显示（LOST -xxxx！），如果卡在finish不动，那么ctrl+z停止进程然后抠电池重新刷
<pre>
    cd /root/armtoolchain/osmocom-bb/src/host/osmocon 
    sudo ./osmocon -p /dev/ttyUSB0 -m c123xor ../../target/firmware/board/compal_e88/trx.compalram.bin 
</pre>

<br>

#### 此处另开一个终端（第二个），进入OpenBTS的运行文件夹, 运行transceiver脚本，开始使用c118作为收发装置

<pre>
    cd /root/armtoolchain/dev/openbts/apps
    chmod +x transceiver.sh
    sudo ./transceiver.sh
</pre>

正确的现实如下图
<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/transeriver.png" alt="transeriver" />

#### 此处再另开一个终端（第三个），同样进入OpenBTS的运行文件夹
<pre>
    cd /root/armtoolchain/dev/openbts/apps 
    ./OpenBTSCLI 
</pre>

<br><br>




### 11:config openbts

<pre> 
    config GSM.Radio.NeedBSIC 1 
    config GSM.RACH.MaxRetrans 3 
    config GSM.RACH.TxInteger 8 
    config GSM.Radio.C0 #你设置的ARFCN
    config Control.LUR.OpenRegistration .* 
    #config GSM.Identity.MCC 001
    #config GSM.Identity.MNC 01
</pre>


#### 重启，有些设置才能生效，重启后依然是重复1.刷机，2.transceiver，3.OpenBTS控制台 
<pre>
    sudo reboot 
</pre>

<br><br>




### 12:Mobile search and access OpenBTS
<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/join_openbts.jpg" alt="join_openbts" />

### 13:View tmsis 控制台的命令，查看连接的手机IMSI 
<pre>
    tmsis
</pre>

<br><br>



### 14:try send sms 控制台的命令，给指定IMSI 发送短信，如下 
<pre>
    sendsms YOUTMSIS number messege
</pre>

<img src="https://of4jd0bcc.qnssl.com/GsmSniffer/send_sms.png" alt="send_sms" />


<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]

[99]:https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E8%B6%85%E5%B8%85_alipay.gif?imageView2/1/w/200/h/200
[100]:https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200