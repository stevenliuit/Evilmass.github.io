---
title: Windows配置Github多用户
date: 2017-05-27 15:00:26
tags: Linux
---

## 取消全局用户设定
    git config --global --unset user.name
    
    git config --global --unset user.email

如果有一个日常操作的Github账户，可以不用取消全局设定，但需要在后续操作的仓库里设定第二个用户和邮箱
<!--more-->
## SSH-Keygen
    ssh-keygen -t rsa -C "first_email"
    
    ssh-keygen -t rsa -C "second_email"
![first_second][first_second]
<br>
## 在ssh目录下创建并添加config文件
![config][config]
<br>
## Added To Github
![first_github][first_github]
<br>
第二个Github账户也是同样的操作，执行`ssh -T git@first.github.com`应该有如下显示
![ssh_T][ssh_T]
<br>
## Test
创建并克隆仓库到本地，修改仓库里的 .git -> config 文件，将https方式改为ssh，并在 github.com 前面添加**first**
![first_config][first_config]
<br>

    git config user.name first
    git config user.email first@email.com

    git remote rm origin
    git remote add origin git@first.github.com:first/test.git

    git add .
    git commit -m "first"
    git push origin master

如果遇到warning

> warning: push.default is unset; its implicit value is changing in Git 2.0 from ‘matching’ to ‘simple’. To squelch this messageand maintain the current behavior after the default changes, use…

使用下面命令设置

    git config --global push.default simple

![done][done]

<br><br>

**这个打赏二维码好像没什么不对**

**支付宝** 
![支付宝][支付宝]
**微信**  
![微信][微信]
[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%95%B2%E7%A2%97_alipay.gif?imageView2/1/w/200/h/200
[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200



[first_config]: https://of4jd0bcc.qnssl.com/ssh/first_config.png
[first_second]: https://of4jd0bcc.qnssl.com/ssh/first_second.png
[config]: https://of4jd0bcc.qnssl.com/ssh/config2.png
[first_github]: https://of4jd0bcc.qnssl.com/ssh/first_github.png
[ssh_T]: https://of4jd0bcc.qnssl.com/ssh/ssh_T.png
[done]: https://of4jd0bcc.qnssl.com/ssh/done.png