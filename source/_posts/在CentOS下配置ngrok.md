---
title: 在CentOS下配置ngrok
date: 2017-01-25 00:54:58
tags: 杂
---

![1][1]

<!--more-->

### Requirement
#### 域名设置主机记录
![主机记录][2]
如果没有**`*`**记录的话会被重定向至主机，无法打开隧道页面
<br>
#### Git

    yum install git
<br>    
#### Go
如果之前安装过旧版本的Go请先卸载

    yum remove golang
    
下载go1.4.2源码包

    wget https://storage.googleapis.com/golang/go1.7.linux-amd64.tar.gz
    
解压到/usr/local/

    tar -C /usr/local/ -zxf go1.7.linux-amd64.tar.gz
    
添加环境变量

    vim /etc/profile
    
在最后面添加以下两句保存并退出：

    export GOROOT=/usr/local/go
    export PATH=$GOROOT/bin:$PATH
    
使之前的配置生效

    source /etc/profile
    
<br>
### Server

    git clone https://github.com/inconshreveable/ngrok.git

在使用官方服务的时候，我们使用的是官方的 SSL 证书，所以如果直接编译的话，默认的连接地址会到官方的 ngrok.com 去，所以我们需要自己生成证书。

**NGROK_DOMAIN这里修改为自己的域名**

    cd /root/ngrok 
    NGROK_DOMAIN="evilmass.cc"
    
    openssl genrsa -out rootCA.key 2048
    openssl req -x509 -new -nodes -key rootCA.key -subj "/CN=$NGROK_DOMAIN" -days 5000 -out rootCA.pem
    openssl genrsa -out device.key 2048
    openssl req -new -key device.key -subj "/CN=$NGROK_DOMAIN" -out device.csr
    openssl x509 -req -in device.csr -CA rootCA.pem -CAkey rootCA.key -CAcreateserial -out device.crt -days 5000
 
 openssl 就是生成 SSL 证书文件的过程，之后会在 ngrok 目录下生成 root，device 等六个文件。 然后需要拷贝到配置的目录中，在编译的时候会使用这些文件。
 
    \cp rootCA.pem assets/client/tls/ngrokroot.crt -f
    \cp device.crt assets/server/tls/snakeoil.crt  -f
    \cp device.key assets/server/tls/snakeoil.key -f
`\cp` 命令可强制覆盖


编译ngrokd

    cd /root/ngrok
    make release-server
    
> 如果安装的时候卡在了**gopkg.in/inconshreveable/go-update.v0 (download)**或者卡在**gopkg.in/yaml.v1 (download)**，则代表需要安装新的git
注意git版本应大于1.7.9.5
源码编译安装请参考：[安装Git][3]

CentOS下Git安装的一个坑：

    Can't locate ExtUtils/MakeMaker.pm in @INC (@INC contains: /usr/local/lib64/perl5 /usr/local/share/perl5 /usr/lib64/perl5/vendor_perl /usr/share/perl5/vendor_perl /usr/lib64/perl5 /usr/share/perl5 .) at Makefile.PL line 3.

    BEGIN failed--compilation aborted at Makefile.PL line 3.

    make[1]: *** [perl.mak] Error 2

    make: *** [perl/perl.mak] Error 2

解决:

    yum install perl-ExtUtils-MakeMaker


构建完成以后可以在bin目录下看到**ngrokd**，这个文件，这个就是我们后面要开启的服务器端(Server)

<br>
### Client

go开发环境为我们提供了强大的跨平台交叉编译功能，在Linux下即可完成Windows版的编译。

    cd /root/ngrok
    
执行如下命令编译Windows 64位客户端

    GOOS=windows GOARCH=amd64 make release-client
    
**GOOS**：Target Host OS
**GOARCH**：Target Host ARCH

> Linux 平台 32 位系统：GOOS=linux GOARCH=386
  Linux 平台 64 位系统：GOOS=linux GOARCH=amd64
  Windows 平台 32 位系统：GOOS=windows GOARCH=386
  Windows 平台 64 位系统：GOOS=windows GOARCH=amd64
  MAC 平台 32 位系统：GOOS=darwin GOARCH=386
  MAC 平台 64 位系统：GOOS=darwin GOARCH=amd64
  ARM 平台：GOOS=linux GOARCH=arm
  
通过前面的步骤，就会在bin目录里面生成所有的客户端文件，客户端平台是文件夹的名字（windows_amd64），客户端放在对应的目录下。
没有错误的话，Windows客户端ngrok就编译成功了，我们可以在./bin/windows_amd64/目录下找到执行文件ngrok.exe。将其下载到Windows上。
<br>

### 运行测试
#### 服务器端
    cd /root/ngrok
    NGROK_DOMAIN="evilmass.cc"
    #http
    bin/ngrokd -domain="$NGROK_DOMAIN" -httpAddr=":6060" -httpsAddr=":6061" -tunnelAddr=":6062" 
    
    #https设置了tls
    #bin/ngrokd -domain="$NGROK_DOMAIN" -httpAddr=":6060" -httpsAddr=":6061" -tunnelAddr=":6062" -tlsKey=./device.key -tlsCrt=./device.crt
    

**httpAddr**：访问普通的http使用的端口号，用后面用`子域名.evilmass.cc:6060` 来访问服务
**httpsAddr**：访问的https使用的端口号，同上，只不过是需要https的服务访问才用这个端口
**tunnelAddr**：通道的端口号，**这个端口是Ngrok用来通信的，所以这个端口在服务器上和客户端上设置必须要对应才可以正常的连接**，默认不填写是4433

    
##### 验证端口是否打开

    nc -v -w 10 -z 127.0.0.1 6060-6062
    
如果显示的3个端口都有响应（都显示了succeeded就是正常）

##### 打开防火墙
如果是centOS的系统，防火墙应该是 firewall-cmd 来控制。对应的命令就是，其中端口号要写自己的：

    firewall-cmd --permanent --zone=public --add-port=6060-6062/tcp  //永久
    #firewall-cmd  --zone=public --add-port=6060-6062/tcp   //临时
    
如果是ubuntu之类的系统，防火墙一般是iptables来控制，对应的命令就是，也要修改自己的端口号才可以：

    iptables -A INPUT -p tcp --dport 6060-6062 -j ACCEPT
    iptables -A OUTPUT -p tcp --sport 6060-6062 -j ACCEPT


#### 客户端
建立一个文件ngrok.cfg，与刚才编译好的windows客户端放在一起。写入如下内容：

    server_addr: "evilmass.cc:6062"  #6062是服务器端的通信端口
    trust_host_root_certs: "true"
    #http_proxy: "http://user:password@proxy-ip:port"  #代理设置
    tunnels:
        router:
            auth: "username:password"  #打开此页面需要先验证
            proto:
            http: 192.168.1.1:80  #or https
        ssh:
            proto:
                tcp: 22
        mhp:
            proto:
                tcp: 182.168.1.149:3000

在客户端目录下运行

    ngrok.exe -log="ngrok_log.txt" -config="ngrok.cfg" start router mhp
日志： `-log=ngrok_log.txt`是记录ngrok的日志，如果不能访问就可以查看到底是什么问题

启动服务： start `router` `mhp`

如果显示了tunnel status: online就是服务器和客户端是正常连接的
![多隧道设置][多隧道设置]
<br>
如果设定了`auth`，则开启页面需要填写`username`和`password`：
![两步认证][两步认证]
<br>
通过浏览器访问`http://router.evilmass.cc:6060`就可以连接到现在的内网主机`192.168.1.1:80`上的服务。
![测试隧道][测试隧道]

<br>
### 配置 raspberry pi 客户端

先在需要的地方建立一个目录，然后建立一个和window下一样的ngrok.cfg的文件，内容也相同

将arm版本的ngrok上传到树莓派上对应的目录

进入这个目录以后，通过 chmod +x ngrok 将ngrok设置成可执行文件

执行命令 

    ./ngrok -config="ngrok.cfg" start raspberry

这样就可以通过`raspberry.evilmass.cc:6062`访问树莓派上的80端口对应的服务。（树莓派可以直接安装一个nginx， `apt-get install nginx` 然后默认80端口就可以显示nginx默认的页面）


**懒才是推动科技发展的动力啊**


<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][5]

**微信**  
![wechat][6]


  [1]: https://of4jd0bcc.qnssl.com/ngrok/ngrok.png
  [2]: https://of4jd0bcc.qnssl.com/ngrok/%E4%B8%BB%E6%9C%BA%E8%AE%B0%E5%BD%95.png
  [3]: https://git-scm.com/book/zh/v2/%E8%B5%B7%E6%AD%A5-%E5%AE%89%E8%A3%85-Git
  [5]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/dmc.gif?imageView2/1/w/200/h/200
  [6]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200
  [多隧道设置]: https://of4jd0bcc.qnssl.com/ngrok/%E5%A4%9A%E9%9A%A7%E9%81%93%E8%AE%BE%E7%BD%AE.png
  [两步认证]: https://of4jd0bcc.qnssl.com/ngrok/%E4%B8%A4%E6%AD%A5%E9%AA%8C%E8%AF%81.png
  [测试隧道]: https://of4jd0bcc.qnssl.com/ngrok/%E6%B5%8B%E8%AF%95%E9%9A%A7%E9%81%93.png