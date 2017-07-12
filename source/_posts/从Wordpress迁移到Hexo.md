---
title: 从Wordpress迁移到Hexo
date: 2017-02-06 00:34:48
tags: Linux
---

### 前言
之前有一个cc域名是Wordpress的，而me域名则是GithubPage + Hexo，可惜me域名过期没钱续费了，两边更新文章也挺麻烦，遂切换到VPS上

<!--more-->
  
<br>
### VPS基本参数
对，搬砖，最便宜的那个，现在出了China Direct Router，内存也提升到了512M，性价比极高，可惜不是KVM架构。。。

> SPECIAL 10G PROMO V3 - LOS ANGELES - CHINA DIRECT ROUTE 
  SSD: 10 GB RAID-10
  RAM: 512 MB
  CPU: 1x Intel Xeon
  Transfer: 1000 GB/mo
  Link speed: 1 Gigabit
  Location: Los Angeles (no other locations available on this plan)
  Direct route via China Telecom and China Unicom

我的推荐链接：https://bandwagonhost.com/aff.php?aff=13364
China Direct Router：在地址栏最后添加`/cart.php`
<br>
### Shadowsocks
    yum update -y
    yum install python-setuptools m2crypto libtool gcc && easy_install pip
    pip install shadowsocks

#### libsodium依赖
    curl -O -L https://download.libsodium.org/libsodium/releases/LATEST.tar.gz
    tar zxf LATEST.tar.gz
    cd libsodium*
    ./configure
    make && make install
    # 修复关联
    echo /usr/local/lib > /etc/ld.so.conf.d/usr_local_lib.conf
    ldconfig
    rm -rfv ../LATEST.tar.gz ../libsodium* && cd ~
 
#### 多端口配置
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
    
#### 配置自启动
新建启动脚本文件/etc/systemd/system/shadowsocks.service，内容如下：

    [Unit]
    Description=Shadowsocks

    [Service]
    TimeoutStartSec=0
    ExecStart=/usr/bin/ssserver -c /etc/shadowsocks.json
    
    [Install]
    WantedBy=multi-user.target
    
#### 启动 shadowsocks

    systemctl enable shadowsocks  #开机启动
    systemctl start shadowsocks  #开启服务

为了检查 shadowsocks服务是否已成功启动，可以执行以下命令查看服务的状态：

    systemctl status shadowsocks -l

#### 优化
**Openvz用户可以不用看这部分的优化**

[shadowsocks参数优化][8]
    
**KVM架构的VPS建议安装** [Google_BBR][9] 
目前看来是BBR比锐速强势

> [Kcptun--Openvz的救星][10]
  [FinalSpeed--同上][11]
 
<br>
###  [**在Centos配置ngrok**][20]
<br>
### [**服务器监控 UptimeRobot 简明使用手册**][12]
<br>
### [Git][13]
建议手动编译安装较新版本的Git，以便配置ngrok
<br>
### [SSH-Keygen][14]
<br>
### [Nginx][15]
 
    yum install nginx
    systemctl start nginx
    systemctl enable nginx
    
<br>
### [NodeJS][16]
建议yum直接安装，否则在后面部署pm2（forever）过程中会出现如下错误：`/usr/bin/env: node: No such file or directory`

    yum install nodejs

<br>
### [Hexo][17]

    npm install hexo-cli -g
    hexo init blog
    cd blog
    npm install
<br>
#### [Next主题][18]
<br>
#### 让Hexo在后台运行

    npm install pm2 -g  #全局安装pm2
当然，用forever也是可以的，只是pm2更强大更好用而已

创建一个`app.js`写入以下内容

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
    
使用

    pm2 list              # 查看已运行的服务
    pm2 show <id or name> # 查看启动服务的详细信息
    pm2 monit             # 查看pm2在服务器上的占用
    pm2 start app.js      # 启动hexo
    pm2 kill app.js       # 停止hexo   
    pm2 save              # 保存当前设置
    pm2 startup           # 开机启动hexo服务
    
<br>
#### crontab注意事项
crond是Centos系统的 一个服务，也就也就意味着：**crontab -e之后Command不执行的原因之一是系统没有开启crond服务**

    systemctl start crond
    systemctl enable crond #加入开机启动
<br>
### [Let's Encrypt][19]证书自动续期

    vim /home/ssl_renew.sh
    
    #!/bin/bash
    /home/letsencrypt/certbot-auto renew
    
    #添加到crontab每月运行一次
    crontab -e
    
    * * */1 * * sh /home/ssl_renew.sh

<br>
#### 配置 Nginx 代理
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
### GZip

`vim /etc/nginx/nginx.conf`
    
    http {
        gzip on; #开启Gzip
        gzip_min_length  1k;  #当返回内容大于此值时才会使用gzip进行压缩,以K为单位,当值为0时，所有页面都进行压缩
        gzip_buffers     4 16k;  #gzip文件缓存大小
        gzip_http_version 1.0;
        gzip_comp_level 6;  #gzip压缩等级，数值越高压缩得越狠，也越占资源
        gzip_types  text/plain application/x-javascript text/css application/xml;  #gzip压缩文件格式，以下涵盖了一般所需的类型
        gzip_vary on;  #跟Squid等缓存服务有关，on的话会在Header里增加"Vary: Accept-Encoding"
        gzip_types         text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript;
    }

<br>
### 本地配置
说Windows没有运行脚本环境的，你们似乎忘了**Git-Bash**
![Git-Bash][Git-Bash]
<br>
包含密码的私有文件或者ssh密匙不能推送到公共仓库，会造成隐私泄漏等安全问题
#### .gitignore写入以下内容
    .DS_Store
    Thumbs.db
    *.log
    *./           #隐藏文件夹，这里为了防止推送node_modules/.bin目录
    sync.sh       #本地推送文章的脚本
    node_modules/ #Hexo的运行环境
    public/ #hexo generate产生的静态页面
    .deploy_git/  #之前hexo-deploy-git方式产生的文件夹
Hexo目录包含hexo的运行环境，我们并不需要把这些文件都推送上去，这样整个仓库会变得很大，不利于其他服务器部署
#### 多机器配置（需要NodeJS环境)
    git clone 仓库
    npm install
<br>
### 服务器配置
#### 创建同步脚本`deploy.sh`, 该脚本在VPS的主要操作如下 
> 等待被deploy.js调用
  kill heox-pid 关闭当前正在运行的hexo进程
  git-pull 得到仓库的更新文章
  hexo clean
  hexo generate #生成更新文章的页面
  hexo server & 重新启动hexo并在后台运行

<br>
##### deploy.sh
    #!/bin/bash
    PORT=4000
    WEB_PATH='/path_to_blog' #Hexo目录
    WEB_USER='noroot' #用户
    WEB_USERGROUP='noroot' #用户组
    
    echo "Start deployment"
    cd $WEB_PATH
    echo "pulling source code..."
    git reset --hard origin/master
    #git clean -f 这一句请注释掉，否则服务器端的deploy.sh deploy.js在执行git pull操作时被删除
    git pull
    git checkout master
    echo "changing permissions..."
    chown -R $WEB_USER:$WEB_USERGROUP $WEB_PATH
    NUM=`ps -ef | grep 'hexo' | head -n1 | awk '{print$2}'` #请验证该行代码能否取出hexo进程的pid，若不能，则需要根据环境修改
    if [ -n "$NUM" ];then
        echo "kill hexo process pid: $NUM"
        kill -9 $NUM
    else
        echo "hexo process not found"
    fi
    HEXO_BASH=`which hexo`
    HEXO_CLEAN=${HEXO_BASH}" clean" 
    HEXO_GENERATE=${HEXO_BASH}" generate" #执行hexo generate命令
    HEXO_START_SERVER=${HEXO_BASH}" server -p $PORT &" #在后台启动hexo服务
    echo "HEXO_CLEAN: $HEXO_CLEAN"
    eval $HEXO_CLEAN
    echo "HEXO_GENERATE: $HEXO_GENERATE"
    eval $HEXO_GENERATE
    echo "HEXO_START_SERVER: $HEXO_START_SERVER"
    eval $HEXO_START_SERVER 
    echo "Finished."
    
启动  

    pm2 start deploy.js #启动服务
<br>
### 文章更新工作流
在本地`sync.sh`写入一行
> ssh root@host -p port 'sh ./deploy.sh'  

-p 代表端口，默认22， 引号内代表连接上服务器后执行的命令，**需要用ssh-keygen实现免密码登录**

那么整个工作流就很简单了： 开启启动hexo服务`pm2 startup`，crontab定时执行`pm2 restart app`，本地`sync.sh`推送，服务器端实时更新文章～
#### 在Shell中执行Ctrl - C

    CTRL-A \001   十进制1
    CTRL-B \002   十进制2
    ....
    CTRL-Z \032   十进制26
    那么`echo -e "\003"` 代表在Shell中输入Ctrl - C

添加`echo -e "\003"`到deploy.sh最后就可以达到本地推送完成之后自动退出VPS的Shell连接
<br>
### 备份

> Hexo根目录下的`_config.yml`

> 要使用的主题目录下的`_config.yml`
  
> 保存文章的`md`文件
  
> 本地推送的脚本`sync.sh`
  
> 服务器端的`deploy.sh` `deploy.js` `blog_run.sh`

#### 七牛qshell
创建一个私有空间（服务器在国外建议选择北美的空间，已经有空间的，先创建再设置成私有即可），在个人面板 -> 密钥管理找到  `AccessKey`和`SecretKey`

##### 下载服务器可用的[qshell][qshell下载地址]并初始化

    ./qshell_linux_amd64 init AK SK

##### 在当前目录下创建`config.txt`并写入如下内容

    {
        "src_dir"            :   "待同步的目录",
        "access_key"         :   "AK",
        "secret_key"         :   "SK",
        "bucket"             :   "私有空间名",
        "zone"               :   "na0",
        "rescan_local"       :   true,
        "skip_path_prefixes" :   ".qshell"
    }
各机房对应的zone值

    华东      nb
    华北      bc
    华南      hn
    北美      na0
##### 上传备份文件到私有空间
一般选择source/_posts里面的文章做备份，配置文件_config.yml不建议上传

    ./qshell_linux_amd64 qupload config.txt

##### 写成脚本最后加入到crontab一天更新一次
    * 22 * * * sh ~/hexo-backup/backup2qiniu.sh
    
##### 更多细节请参考[qshell官方文档][qshell官方文档]

<br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


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
  [12]: https://liyuans.com/archives/uptimerobot.html
  [13]:  https://git-scm.com/book/zh/v2/%E8%B5%B7%E6%AD%A5-%E5%AE%89%E8%A3%85-Git
  [14]: https://help.github.com/articles/connecting-to-github-with-ssh/
  [15]: http://nginx.org/en/docs/install.html
  [16]: https://nodejs.org/en/download/current/
  [17]: https://hexo.io
  [18]: http://theme-next.iissnan.com/getting-started.html
  [19]: https://ksmx.me/letsencrypt-ssl-https/
  [20]: https://evilmass.cc/2017/01/25/%E5%9C%A8CentOS%E4%B8%8B%E9%85%8D%E7%BD%AEngrok/
  [Git-Bash]: https://of4jd0bcc.qnssl.com/Hexo/Git-Bash.png
  [webhook设置]: https://of4jd0bcc.qnssl.com/Hexo/webhook%E8%AE%BE%E7%BD%AE.png
  [webhook设置成功]: https://of4jd0bcc.qnssl.com/Hexo/webhook%E8%AE%BE%E7%BD%AE%E6%88%90%E5%8A%9F.png
  [文章更新成功]: https://of4jd0bcc.qnssl.com/Hexo/%E6%96%87%E7%AB%A0%E6%9B%B4%E6%96%B0%E6%88%90%E5%8A%9F.png
 [qshell下载地址]: https://developer.qiniu.com/kodo/tools/qshell#download
 [qshell官方文档]: https://github.com/qiniu/qshell/blob/master/docs/qupload.md

  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E8%B6%85%E5%B8%85_alipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200