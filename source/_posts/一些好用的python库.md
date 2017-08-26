---
title: 一些好用的 Python 库
date: 2017-08-26 12:54:41
tags: Python
---

分别是 `pyautogui`、`splinter`、`prettytable`

这里简单介绍用法。使用详情请参考 REF
<!--more-->

## PrettyTable

### 安装
    pip instal pyautogui

### no such module name?
在这里犯了个最 zz 的错误，当安装好库之后调用模块出现： `ImportError: No module named 'prettytable`

**Python 文件名不能和库名一样**
**Python 文件名不能和库名一样**
**Python 文件名不能和库名一样**

### 基本操作
    #!/usr/bin/env python3
    # -*- coding:utf-8 -*-
    
    from prettytable import PrettyTable
    
    
    def func():
        x = PrettyTable()
        x.field_names = ["City name", "Area", "Population", "Annual Rainfall"]
        x.add_row(["Adelaide",1295, 1158259, 600.5])
        x.add_row(["Brisbane",5905, 1857594, 1146.4])
        x.add_row(["Darwin", 112, 120900, 1714.7])
        x.add_row(["Hobart", 1357, 205556, 619.5])
        x.add_row(["Sydney", 2058, 4336374, 1214.8])
        x.add_row(["Melbourne", 1566, 3806092, 646.9])
        x.add_row(["Perth", 5386, 1554769, 869.4])
        print(x)
    
    
    if __name__ == '__main__':
        func()
![prettytable][prettytable]

<br>
## PyAutoGUI
### 安装
    pip instal pyautogui

### 基本操作

    # !/usr/bin/env python3
    # -*- coding:utf-8 -*-
    
    import os
    import time
    import pyautogui as pag
    
    '''
        pyautogui.moveTo(1555, 1055, duration=2, tween=pyautogui.easeInOutQuad)  # 2秒内缓缓移动到目标位置
        pyautogui.click(x=moveToX, y=moveToY, clicks=num_of_clicks, interval=secs_between_clicks, button='left')
        # 消息类
        pyautogui.alert(text='', title='', button='OK')
        pyautogui.confirm(text='', title='', buttons=['OK', 'Cancel'])  # OK和Cancel按钮的消息弹窗
        pyautogui.confirm(text='', title='', buttons=range(10))  # 依次弹出 num 的消息弹窗
        pyautogui.prompt(text='', title='' , default='')
        pyautogui.password(text='', title='', default='', mask='*')
        # 缓动/渐变（Tween / Easing）函数
        tween=pyautogui.easeInQuad  # 开始很慢，不断加速
        tween=pyautogui.easeOutQuad  # 开始很快，不断减速
        tween=pyautogui.easeInOutQuad # 开始和结束都快，中间比较慢
        tween=pyautogui.easeInBounce # 一步一徘徊前进
        tween=pyautogui.easeInElastic  # 徘徊幅度更大，甚至超过起点和终点
        
        pyautogui.screenshot('filename', region=(0, 0, 300 ,400))  #从(0,0)到(300, 400)
        print(pyautogui.KEYBOARD_KEYS)
    
    '''
    
    pag.FAILSAFE = False  # 防止失控，如果把鼠标光标在屏幕左上角，PyAutoGUI函数就会产生 pyautogui.FailSafeException 异常
    pag.PAUSE = 1  # pyautogui 整体函数延迟时间
    
    
    def run():
        path = 'path'
        # screen_width, screen_height = pag.size()  # 获取当前屏幕分辨率
        # current_mouse_X, current_mouse_Y = pag.position()  # 获得当前鼠标位置
        # pag.click(screen_width - 1, screen_height - 1)
        tg_location = ()
        tg_location = pag.locateCenterOnScreen(os.path.join(path, 'tg.png'))
        if tg_location:  # 锁定图片坐标
            close(tg_location)
        else:
            pag.moveTo(pag.locateCenterOnScreen(os.path.join(path, 'top.png')))  # 找到隐藏在任务栏的程序
            pag.click()
            tg_location = pag.locateCenterOnScreen(os.path.join(path, 'tg.png'))
            close(tg_location)
    
    
    def close(location=()):
        pag.rightClick(location)
        pag.moveRel(100, -100)
        pag.click()
    
        # pag.typewrite('Hello World!', interval=0.25)  # 每个字母间隔0.25s输出
        # pag.press('space')  # 一次按键
        # pag.keyDown('shift')  # 按键不放
        # 复制操作
        # pag.hotkey('ctrl', 'a')
        # pag.hotkey('ctrl', 'c')
        # pag.hotkey('ctrl', 'v')
        # time.sleep(1)
        # pag.press('enter')
    
    
    if __name__ == '__main__':
        run()
   
<br>
## Splinter
### 安装
    pip install splinter
浏览器模块
[Google Chrome Web Driver][Google Chrome Web Driver]
[Firefox Web Driver][Firefox Web Driver]

### 基本操作
Options选项

> disable-infobars  // 禁用网页上部的提示栏，比如2.28的webdriver开始会提示你Chrome正受到自动测试软件的控制

    # !/usr/bin/env python3
    # -*- coding:utf-8 -*-
    
    from splinter import Browser
    from time import sleep
    
    
    def func():
        with Browser('chrome', executable_path=r'chromedriver.exe', user_agent="bot") as browser:
            browser.visit('index.html')
            browser.fill('steps', 'steps')
            sleep(1)
            browser.fill('uid', 'uid')
            sleep(1)
            browser.fill('pc_value', 'pc_value')
            sleep(1)
            browser.find_by_value('submit').first.click()
            browser.quit()
    
    
    if __name__ == '__main__':
        func()

<br>
## REF
[doc-prettytable][doc-prettytable]

[doc-pyautogui][doc-pyautogui]

[doc-splinter][doc-splinter]


<br>
> **这个打赏二维码好像没什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]

[env]: https://of4jd0bcc.qnssl.com/pip/pip.png
[prettytable]: https://of4jd0bcc.qnssl.com/pip/prettytable.png
[Google Chrome Web Driver]: https://sites.google.com/a/chromium.org/chromedriver/
[Firefox Web Driver]:https://github.com/mozilla/geckodriver/releases
[doc-pyautogui]: https://muxuezi.github.io/posts/doc-pyautogui.html
[doc-splinter]: https://splinter.readthedocs.io/en/latest/
[doc-prettytable]: http://ptable.readthedocs.io/en/latest/tutorial.html

[99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200

[100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200
