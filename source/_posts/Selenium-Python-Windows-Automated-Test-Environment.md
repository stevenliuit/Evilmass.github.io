---
title: Selenium + Python + Windows = Automated Test Environment
date: 2017-01-15 16:09:44
tags:
    - Python
---

### Selenium
[Selenium][1] 

Download **selenium-3.0.2.tar.gz**, Unarchive it, and run:

    python setup.py install
<br>

<!--more-->

### ChromeDriver
[ChromeDriver][2]

> 1. Download **chromedriver_win32.zip**
2. Unarchive it to the chrome.exe directory
3. Set the **path** into system environment variables

![ChromeDriver][3]
 
<br>

### FirefoxDriver
[FirefoxDriver][4]

> Same as the above operation

<br>

### Run
    # !/usr/bin/env python
    # -*- coding:utf-8 -*-

    driver = webdriver.Chrome()
    driver.get("https://www.baidu.com")  # open url
    driver.find_element_by_link_text(u'地图').click()  # match the text and click
    driver.implicitly_wait(10)  # wait 10s for next operation
    print(driver.title)
    driver.close()
<br>

### Reference
> [Selenium with Python][5]

[1]:https://pypi.python.org/pypi/selenium
[2]:https://sites.google.com/a/chromium.org/chromedriver/downloads
[3]:https://of4jd0bcc.qnssl.com/Selenium/chromeDriver.png
[4]:https://github.com/mozilla/geckodriver/releases
[5]:http://selenium-python.readthedocs.io/index.html

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/dmc.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/patapon_wechat.gif?imageView2/1/w/200/h/200