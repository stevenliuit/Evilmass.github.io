---
title: Requests简明使用手册-4399小游戏刷分
date: 2017-02-09 15:03:10
tags: 
    - Python
    - 爬虫
---

### 主要参数
> 游戏地址：[黄金矿工双人版][黄金矿工双人版]
 获取Token地址： http://score.4399.com/get_token.php
 提交成绩地址： http://score.4399.com/submitscore_forusercenter.php
 game_key：可惜这个参数要自己手动提交分数才能获取，不然就可以实现页面获取，直接输入游戏地址刷分
 **verify**：提交分数的验证字符串
 
<!--more-->

<br>
### 获取Flash
Chrome -> F12 -> Network -> 查找`*.swf` -> 得到游戏地址
> 这里有个坑：黄金矿工的swf文件是没有加密函数的！所以我们找找别的的swf：[Flappy Bird][Flappy Bird]

    import urllib.request
    
    swf_url = 'http://szhong.4399.com/4399swf/upload_swf/ftp18/gamehwq/20160129/09/main.swf'
    swf_save = 'your_path\\game.swf'
    urllib.request.urlretrieve(swf_url, swf._save)

通过浏览器页面缓存也可以得到这个swf文件
    
<br>
### [JPEXS Free Flash Decompiler][JPEXS Free Flash Decompiler]

通过JPEXS Free Flash Decompiler反编译Flash游戏，在mainTimeline中找到进行加密函数
<br>
![打开swf][打开swf]
<br>
![查找加密函数][查找加密函数]
<br>

**验证字符 = 3次md5加密（特定字符 + 数据 + 特定字符）（纳尼）**

<br>
### Verify
手动提交分数就可以看到获取token和提交分数的POST请求
![抓包分析][抓包分析]
<br>
![POST具体数据][POST具体数据]
<br>
### 构造verify并验证

    from hashlib import md5

    score = '611'
    username = '0evilmass'
    verify = 'd6973ae7cb5d28248c65335f7d17b17d'
    token = 'e79671619c11c4a94ae06bb62129e480'
    game_id = '3883'
    game_key = '38c7d7b60ee491da'
    gs = '1'
    starttime = '1486618681866'
    
    verifyStr = "SDALPlsldlnSLWPElsdslSE" + game_key + score + game_id + starttime + gs + token + "PKslsO"
    
    print(md5(md5(md5(verifyStr.encode('utf-8')).hexdigest().encode('utf-8')).hexdigest().encode('utf-8')).hexdigest())
    
> \>>> d6973ae7cb5d28248c65335f7d17b17d  # 可以看到构造的verify与POST提交的一致

**我能怎么办，hexdigest()和eoncode()又不能去掉，我也很绝望啊**

<br>
### POST提交
**POST提交分数请把Cookies带上**
**POST提交分数请把Cookies带上**
**POST提交分数请把Cookies带上**

    # !/usr/bin/env python3
    # -*- coding:utf-8 -*-
    
    '''
    通过JPEXS Free Flash Decompiler反编译Flash游戏，在mainTimeline中找到进行加密的函数：
        var verifyStr:String = "SDALPlsldlnSLWPElsdslSE" + temKey + lGameScore + gameID + starttime + gs + _tokenData + "PKslsO";
        var verify:String = MD5.hash(MD5.hash(MD5.hash(verifyStr)));
    请修改对应的游戏id和key以及以score，带上cookies使用
    '''

    import requests
    from hashlib import md5
    
    
    token_url = 'http://score.4399.com/get_token.php'
    score_url = 'http://score.4399.com/submitscore_forusercenter.php'
    score_headers = {
        'Cookie': 'put- your - cookies - here'
        'Host': 'score.4399.com',
        'Origin': 'http://sda.4399.com',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
        
    }
    score_session = requests.Session()
    score = '30000000'  # 得分
    score_data = {
        'game_id': '3883',  # id
        'game_key': '38c7d7b60ee491da',  # key
        'gs': '2',
        'Pkid' :'0',
        'autocommit': '1',
        'uid' :'2312128495',
        'username' :'evil0mass',
        'time': '1486232396121',
        'starttime': '1486232324909'
    }
    
    
    def get_token():
        return requests.get(token_url).text.strip('&token=')
    
    
    def encode(Str):  #对verifyStr进行3次md5加密得到verify
    return md5(md5(md5(Str.encode('utf-8')).hexdigest().encode('utf-8')).hexdigest().encode('utf-8')).hexdigest()


    def run():
        score_data['score'] = score
        score_data['token'] = get_token()
        verifyStr= "SDALPlsldlnSLWPElsdslSE" + score_data['game_key'] + score_data['score'] + score_data['game_id'] + score_data['starttime'] + score_data['gs'] + score_data['token'] + "PKslsO"
        score_data['verify'] = encode(verifyStr)
        return score_session.post(url=score_url, headers=score_headers, data=score_data).text


    if __name__ == '__main__':
        if score in run():  #若出现验证错误请重新执行
            print(u'Post数据成功，得分为', score)
        else:
            print(u'验证错误')
<br>
### Done
![刷分成功][刷分成功]
<br>
**脚本仅作测试，请勿恶意刷分**
**脚本仅作测试，请勿恶意刷分**
**脚本仅作测试，请勿恶意刷分**
<br>
### Reference
[教你如何刷 4399 小游戏的分数！][教你如何刷 4399 小游戏的分数！]
<br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%8B%8D%E6%A1%8C_ailipay.gif?imageView2/1/w/200/h/200
[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200

[黄金矿工双人版]: http://www.4399.com/flash/3883.htm
[Flappy Bird]: http://www.4399.com/flash/131199.htm#search1
[JPEXS Free Flash Decompiler]: https://www.free-decompiler.com/flash/
[打开swf]:  https://of4jd0bcc.qnssl.com/4399/%E6%89%93%E5%BC%80swf.png
[查找加密函数]: https://of4jd0bcc.qnssl.com/4399/%E6%9F%A5%E6%89%BE%E5%8A%A0%E5%AF%86%E5%87%BD%E6%95%B0.png
[手动提交]:  https://of4jd0bcc.qnssl.com/4399/%E6%89%8B%E5%8A%A8%E6%8F%90%E4%BA%A4.png
[抓包分析]:  https://of4jd0bcc.qnssl.com/4399/%E6%8A%93%E5%8C%85%E5%88%86%E6%9E%90.png
[POST具体数据]: https://of4jd0bcc.qnssl.com/4399/POST%E5%85%B7%E4%BD%93%E6%95%B0%E6%8D%AE.png
[刷分成功]: https://of4jd0bcc.qnssl.com/4399/%E5%88%B7%E5%88%86%E6%88%90%E5%8A%9F.png
[教你如何刷 4399 小游戏的分数！]: https://www.hrwhisper.me/get-a-good-score-you-like-at-4399/

