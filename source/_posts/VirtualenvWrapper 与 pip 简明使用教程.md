---
title: VirtualenvWrapper 与 pip 简明使用教程
date: 2017-08-25 17:12:08
tags: Python
---

**有经验的 Pythoner 应尽可能写出兼容 2 和 3 的代码**

使用 virtualenv 能保证一个项目对应一个生产环境，防止宿主环境的库之间的冲突，然而只使用 virtualenv 的一个最大的缺点就是，每次开启虚拟环境之前要去虚拟环境所在目录下的 bin 目录下 source 一下 activate，这就需要我们记住每个虚拟环境所在的目录。

而 VirtualenvWrapper 将所有的虚拟环境目录全都集中起来，方便我们管理由 virtualenv 生成的各种 Python 生产环境
<!--more-->

## VirtualenvWrapper

### 安装
    pip install virtualenv virtualenvwrapper-win
### 配置两个 Python 环境
目前工作环境 Python 3.5，临时环境 Python 2.7
推荐在**非 C 盘**创建两个目录：一个 Python3，一个 Python2。安装 3 时应**勾选添加 Python 到系统环境变量**，安装 2 时则不用

### 在项目目录下创建环境
PS. 因为命名冲突存在， 所以无法在不同目录下创建两个名字相同的virtualenv环境

    mkvirtualenv myProjiect
    
### 列出 & 启动 & 退出环境
列出所有环境

    lsvirtualenv

启动环境

    workon myProject
命令行前出现`(myProject)`字样表示环境启动成功
![env][env]

退出环境

        deactivate


## pip
### 基本操作
    Commands:
      install                     Install packages.
      download                    Download packages.
      uninstall                   Uninstall packages.
      list                        List installed packages.
    General Options:
      --proxy <proxy>             Specify a proxy in the form
      --trusted-host <hostname>   Mark this host as trusted, even though it does

### 解决 --trusted-host 问题

> The repository located at pypi.python.org is not a trusted or secure host and is being ignored. 
If this repository is available via HTTPS it is recommended to use HTTPS instead, 
otherwise you may silence this warning and allow it anyways with **--trusted-host pypi.python.org**

Windows 用户
1. 首先在window的文件夹窗口输入 ： %APPDATA%
2. 若无 pip 文件夹则创建，并在该文件夹内 新建 pip.ini 配置文件，写入如下内容

    [global]
    timeout = 6000
    index-url = https://pypi.python.org/simple/
    [install]
    trusted-host=pypi.python.org
若对官方源速度不满意（其实目前速度也很快），可替换成 mirrors.aliyun.com


Unix 用户： `$HOME/.config/pip/pip.conf`

macOS用户： `$HOME/Library/Application Support/pip/pip.conf`

<br>
### 解决 --format=(legacy|columns) 问题

> DEPRECATION: The default format will switch to columns in the future. You can use --format=(legacy|columns) (or define a format=(legacy|columns) in your pip.conf under the [list] section) to disable this warning.

在pip.ini写入如下内容

    [list]
    format=columns


<br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]
[env]: https://of4jd0bcc.qnssl.com/pip/pip.png
[99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E8%B6%85%E5%B8%85_alipay.gif?imageView2/1/w/200/h/200
[100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200



