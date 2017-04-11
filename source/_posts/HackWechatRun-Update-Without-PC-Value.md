---
title: HackWechatRun - Update Without PC Value
date: 2017-04-11 19:21:32
tags: Hack
---

## 思路
有不少人反馈抓取PC的过程和时间都很繁复，也不人性化，能否解决PC问题让程序更通用些。于是我自己开了一个[Issue]，列出了PC生成的函数逻辑
关键代码精简下来就是两个函数

        public static String getRealDeviceId()
        {
            return UUID.randomUUID().toString();
        }
        public static String getDeviceID()
        {
            String str2 = getRealDeviceId();
            String str3 = "an" + MD5Util.MD5(str2);
            return str3.toLowerCase();
        }
随后[Z]跟我讨论了一个问题：如果是在同一手机上登录，这个PC是否可以**共用**？

通过抓包分析，得出如下结果：
> 绑定手机和微信后，只要**通过登陆验证**就存在伪造PC的可能

鉴于微信登陆太复杂，我们选择绑定手机，并通过验证码登录
<!--more-->

<br>
## 验证
        
发送验证码
![auth_code][auth_code]
<br>
发送登陆请求
![login][login]
<br>
`mobile_login.py`的代码如下

    # !/usr/bin/env/python3
    # -*- coding:utf-8 -*-
    
    import uuid
    import os
    import requests
    from hashlib import md5
    from time import sleep
    
    users = {}
    
    
    def createPC():
        data = {}
        UUID = uuid.uuid4()  # Ramdom UUID => Random PC
        pc = "an" + md5(bytes(str(UUID).encode('utf-8'))).hexdigest()
        return pc
    
    def getAuthCode(phone_number):
        url = 'https://walk.ledongli.cn/rest/users/auth_code/v3';
        login = requests.request('post', url, data={'phone': phone_number}, verify=False)
        if "{\"ret\":\"60秒内请勿重复发送\",\"errorCode\":-300002}" in login.text:
            print(u'60秒内请勿重复发送, 请稍后再试')
            exit(0)
        return login.text
    
        
    def mobileLogin(phone_number):
        url = 'https://walk.ledongli.cn/rest/users/login/v3?uid=70589504'
        playload = {
            'phone': str(phone_number),
            'verify_code': str(users['auth_code']),
            'pc': users['pc'],
            'is_old_user': '0',
            'type': '2'
        }
        login = requests.request('post', url, data=playload, verify=False)
        return login.text
            
    
    if __name__ == '__main__':
        phone_number = int(input('Please input phone number: '))
        users['pc'] = createPC()
        getAuthCode(phone_number)
        print('Wait...')
        sleep(3)
        users['auth_code'] = input('Please input auth_code: ')
        mobileLogin(phone_number)
        print('Now, your valuable PC is: ', users['pc'])

获得的新PC为**an9af2c1ae5e6284694bcfa4e51ff62ed6**
<br>
![new_pc][new_pc]
<br>
我们完成一次手机验证码登陆之后往`hackWechatRun.py`写入这个新PC，看看能否正常工作
![17210][17210]
<br>
PHP同样也返回正确结果
![17910][17910]
<br>
![17910_2][17910_2]
<br>
多用户只需重复以上步骤即可

<br>
## To Do Lists
1. 利用[接收短信验证码的平台][sms]，省去绑定自己手机的麻烦
2. 其余编程语言的实现
3. 程序界面
4. 提交漏洞（雾，闷声发大财吼不吼啊！

<br>
## 结语
其实到这里，**HackWechatRun**就告一段落了，从有想法到自己造完轮子的这个过程并不是一蹴而就的：

> 手机不支持微信运动 -> 找到能同步微信步数的APP -> 因为限制排除部分APP -> 开始动手-> 列出可行的方法 -> 验证 -> 失败（**多次**） -> 寻找新思路（任何想到的方法都可以试一遍） -> 验证（反编译APP） -> 成功 -> 添加新功能（Without PC） -> Github -> 后续完善

其中**反编译APP**和**Without PC**是解决类似问题的一个思路（逆向）

再举个例子
![栗子][栗子]
> 本想更新文章，结果拖到准备睡觉，但又怕忘记 --> 列出一些文章步骤要点明天继续写
结果列出步骤后想把细节补完，喝了杯水继续 --> 主体有了
整理下语言 --> 哦，文章有了

实现想法的代码过程呢？ 一样的 :) 于是这个过程被我们称之为：**迭代**

最后，本项目出于个人兴趣完成，与乐动力APP无任何利益关系，请勿将此项目用作任何商业用途，**希望你们好好跑步**～
<br>
**这个打赏二维码好像没什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200


[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200


[issue]: https://github.com/Evilmass/HackWechatRun/issues/5
[Z]: https://github.com/zhouweining/
[login]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/login.png
[auth_code]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/auth_code.png
[17210]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/17210.png?imageView2/1/w/600/h/600
[17910]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/17910.png?imageView2/1/w/500/h/500
[17910_2]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/17910_2.png?imageView2/1/w/600/h/600
[new_pc]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/new_pc.png
[sms]: http://www.51ym.me/
[栗子]: https://of4jd0bcc.qnssl.com/HackWechatRun/update/%E6%A0%97%E5%AD%90.png