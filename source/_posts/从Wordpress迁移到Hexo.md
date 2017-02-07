---
title: 从Wordpress迁移到Hexo
date: 2017-02-06 00:34:48
tags: Linux
---

### 前言
之前有一个cc域名是Wordpress的，而me域名则是GithubPage + Hexo，可惜me域名过期没钱续费了，两边更新文章也挺麻烦，遂切换到VPS + Hexo + Webhooks

<!--more-->

一时手贱注册N多一年免费域名，前排出（赠）售（送）以下域名，还有个Namecheap的SSL证书
> [evil0mass.tk][1]
  [evil0mass.me][2]
  [evilmass.xyz----这个拿来做MHP Tunnel的服务器了][3]
  [evilmass.tk][5]
  [evilmass.ml][6]
  [evilmass.cn----这个没有实名认证白送了一块钱给腾讯][7]
  
<br>

### Getting Start
VPS：Bandwagonhost China-Direct
System Version： Centos 7 x86_64（之前Centos的脚本开机启动怎么都设置不好，残念～～
关键字：**systemctl enable**
<br>
#### Shadowsocks
    yum update -y
    yum install python-setuptools m2crypto libtool gcc && easy_install pip
    pip install shadowsocks

<br>
##### libsodium依赖
    curl -O -L https://download.libsodium.org/libsodium/releases/LATEST.tar.gz
    tar zxf LATEST.tar.gz
    cd libsodium*
    ./configure
    make && make install
    # 修复关联
    echo /usr/local/lib > /etc/ld.so.conf.d/usr_local_lib.conf
    ldconfig
    rm -rfv ../LATEST.tar.gz ../libsodium* && cd ~
    
<br>
##### 多端口配置
    {
        "server":"0.0.0.0",
        "local_address":"127.0.0.1",
        "local_port":1080,
        "port_password":{
            "9000":"password0",
            "9001":"password1",
            "9002":"password2",
            "9003":"password3"
        },
        "timeout":600,
        "method":"chacha20",
        "fast_open": true
    }
<br>
###### 配置自启动
新建启动脚本文件/etc/systemd/system/shadowsocks.service，内容如下：

    [Unit]
    Description=Shadowsocks

    [Service]
    TimeoutStartSec=0
    ExecStart=/usr/bin/ssserver -c /etc/shadowsocks.json
    
    [Install]
    WantedBy=multi-user.target
    
<br>
##### 启动 shadowsocks

    systemctl enable shadowsocks  #开机启动
    systemctl start shadowsocks  #开启服务

为了检查 shadowsocks服务是否已成功启动，可以执行以下命令查看服务的状态：

    systemctl status shadowsocks -l
    
 <br>   
##### Shadowsocks优化
**Openvz用户可以不用看这部分的优化**

[shadowsocks参数优化][8]
    
**KVM架构的VPS建议安装** [Google_BBR][9] 
目前看来是BBR比锐速强势

> [Kcptun--Openvz的救星][10]
  [FinalSpeed--同上][11]


<br>
#### [Git][13]
建议手动编译安装较新版本的Git，以便配置ngrok
<br>
#### [SSH-Keygen][14]
<br>
#### [Nginx][15]
 
    yum install nginx
    systemctl start nginx
    systemctl enable nginx
    
**喜欢手动编译安装Nginx是病，得治（雾**

手动编译安装参考
> Zlib：http://zlib.net/
  pcre：https://ftp.pcre.org/pub/pcre/
  Open-SSL：https://www.openssl.org/source/
 
<br>
#### [NodeJS][16]
##### 二进制包安装
    cd /home
    wget https://nodejs.org/dist/v7.5.0/node-v7.5.0-linux-x64.tar.xz
    tar -xf node-v7.5.0-linux-x64.tar.xz
    ./node-v7.5.0-linux-x64/bin/node -v
输出`v7.5.0`即可
###### 软连接
    ln -s /home/node-v7.5.0-linux-x64/bin/node /usr/local/bin/node
    ln -s /home/node-v7.5.0-linux-x64/bin/npm /usr/local/bin/npm
    
<br>
###### 添加到PATH
    vim /etc/profile
    
    #添加到最后
    PATH=$PATH:/home/node-v7.5.0-linux-x64/bin
    
    #即刻生效
    source /etc/profile 

<br>
##### 源码编译安装（你这样是要被电的。。。

    yum install gcc-c++ screen  #在耗时较多的任务又怕shell断开连接，可以开启screen
    wget https://nodejs.org/dist/v7.5.0/node-v7.5.0.tar.gz
    tar -zxvf node-v7.5.0.tar.gz
    cd node-v7.5.0
    ./configure
    make && make install  #我试过用树莓派编译，6个多小时
    
<br>
#### [Hexo][17]

    npm install hexo-cli -g
    hexo init blog
    cd blog
    npm install
    npm install hexo-deployer-git --save

##### [Next主题][18]

<br>
#### 让hexo在后台运行
这里提供两种方式： `forever` `supervisord`
##### **推荐forever**

    npm install forever -g
    vim hexo_run.js
    
<br>    
###### 脚本内容
    var spawn = require('child_process').spawn;
    free = spawn('hexo', ['server']);
    
    free.stdout.on('data', function (data) {
            console.log('standard output:\n' + data);
    });
    
    free.stderr.on('data', function (data) {
            console.log('standard error output:\n' + data);
    });
    
    free.on('exit', function (code, signal) {
            console.log('child process exit, exit: ' + code);
    });
    
<br>    
###### 常用命令
    forever list                 # 查看forever已经运行的应用
    forever start hexo_run.js    # 启动hexo
    forever stop hexo_run.js     # 停止hexo

<br>
##### **supervisor**
使用 hexo server 启动的 Hexo 服务是非 Daemon 模式的。
创建 Supervisor 配置文件： `vi /etc/supervisor/conf.d/blog.conf`

    [program:blog]
    command=/your_path_to/hexo/node_modules/hexo server
    directory=/your_path_to/hexo/
    autostart=true
    autorestart=true
    startsecs=5
    stopsignal=HUP
    stopasgroup=true
    stopwaitsecs=5
    stdout_logfile_maxbytes=20MB
    stdout_logfile=/var/log/supervisor/%(program_name)s.log
    stderr_logfile_maxbytes=20MB
    stderr_logfile=/var/log/supervisor/%(program_name)s.log
启动 Supervisor 守护进程

    supervisord
 如果出现以下错误，输入`sudo unlink /tmp/supervisor.sock`。然后启动supervisor服务。
 > Error: Another program is already listening on a port that one of our HTTP servers is configured to use. Shut this program down first before starting supervisord
 
查看 blog 程序（即 Hexo 服务）的状态：

    supervisorctl status
> blog                             RUNNING    pid 28974, uptime 0:00:32

可以看出，blog 程序已经处于运行状态，监听端口为 hexo server 命令的默认端口 4000。在浏览器中访问 http://VPS-IP:4000 可以看到博客的运行效果。

<br>
#### [Let's Encrypt][19]
##### 证书自动续期
    vim /home/ssl_renew.sh
    
    #!/bin/bash
    /home/letsencrypt/certbot-auto renew
    
    #添加到crontab每月运行一次
    crontab -e
    
    * * 1 * * sh /home/ssl_renew.sh
<br>
##### 配置 Nginx 代理
作为一个对外公开的网站，使用 4000 端口显然是不合适的。可以直接改成 80 端口，但是这样直接把 Hexo 服务暴露给用户，并不恰当。更好的办法是使用 Nginx 做代理。
    
    vim /etc/nginx/nginx.conf

    server {
    location / {
        proxy_pass http://localhost:4000;
    }
    access_log  /var/log/nginx/blog.access.log;
    error_log /var/log/nginx/blog.error.log;
    }

重启 Nginx：
    service nginx restart
<br>
#### Webhooks
**请勿在vps上面执行git push或者hexo d之类的操作，容易产生conflict，设置好wenhooks之后更新文章都在本地进行**

##### github上面webhooks地址填： http://your-vps-ip:8888/ 以及下面config.json用到的your secret
    cd hexo安装路径
    git clone 你的github-page仓库
    mv 仓库/.git . && rm -rfv 仓库  #实际上我们就只要仓库那个.git而已

##### auto-publish-hexo
    cd hexo安装路径
    git clone https://github.com/zhipengyan/auto-publish-hexo
    cd auto-publish-hexo
    npm install

##### 打开目录下的config.json进行修改

    {
        "time_zone": "Asia/Shanghai", //所在时区，在log中显示时间了，vps一般不是本地时区
        "webhook_secret": "your secret", //github webhooks设置的secret
        "path": { //如果hexo的配置为默认的话不用修改下面的
        "hexo_path": "../", //hexo目录相对路径
        "hexo_source_path": "../source" //hexo source目录的相对路径，也就是文章目录
        },
        "listen_port": 8888 //监听的端口
    }
##### 开启screen使用`npm start`或者`node index.js`运行
<br>
####  [**在Centos配置ngrok**][20]
<br>
#### [**服务器监控 UptimeRobot 简明使用手册**][12]

<br>

  [1]: http://evil0mass.tk
  [2]: http://evil0mass.me
  [3]: http://evilmass.xyz
  [4]: http://evilmass.cc
  [5]: http://evilmass.tk
  [6]: http://evilmass.ml
  [7]: http://evilmass.cn
  [8]: https://github.com/iMeiji/shadowsocks_install/wiki/shadowsocks-optimize
  [9]: https://github.com/iMeiji/shadowsocks_install/wiki/%E5%BC%80%E5%90%AFTCP-BBR%E6%8B%A5%E5%A1%9E%E6%8E%A7%E5%88%B6%E7%AE%97%E6%B3%95
  [10]: https://blog.kuoruan.com/110.html
  [11]: https://www.91yun.org/archives/2775
  [12]: https://liyuans.com/archives/uptimerobot.html/
  [13]:  https://git-scm.com/book/zh/v2/%E8%B5%B7%E6%AD%A5-%E5%AE%89%E8%A3%85-Git
  [14]: https://help.github.com/articles/connecting-to-github-with-ssh/
  [15]: http://nginx.org/en/docs/install.html
  [16]: https://nodejs.org/en/download/current/
  [17]: https://hexo.io/
  [18]: http://theme-next.iissnan.com/getting-started.html
  [19]: https://ksmx.me/letsencrypt-ssl-https/
  [20]: https://evilmass.cc/2017/01/25/%E5%9C%A8CentOS%E4%B8%8B%E9%85%8D%E7%BD%AEngrok/