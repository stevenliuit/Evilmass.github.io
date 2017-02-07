---
title: '视频下载神器:You-Get'
date: 2017-01-16 15:00:10
tags: 爬虫
---

#### [项目地址][1]
<br>
#### [安装FFPEG][2]
<br>

<!--more-->

> xpath获取我推荐一个Chrome的插件: **Xpath Finder**

#### 效果
![Xpath-title][3]
<br>
![Xpath-link][4]
<br>

#### 用Python实战下

    # !/usr/bin/env python
    # -*- coding:utf-8 -*-
    
    import os
    import multiprocessing
    
    links = []
    for x in open('F:\\Aria2_Data\\you-get\\大神岛出云\\download.txt', 'r'):
        links.append(x.strip('\n'))
    
    
    def get(url):
        command ='you-get ' + url
        # print(command)
        os.system(command)
    
    
    def run():  # 多进程
        multiprocessing.freeze_support()
        pool = multiprocessing.Pool(multiprocessing.cpu_count())
        results = pool.map_async(get, links)
        pool.close()
        pool.join()
    
    
    if __name__ == '__main__':
        run()
<br>

#### Result
![Result][5]


  [1]: https://github.com/soimort/you-get
  [2]: http://adaptivesamples.com/how-to-install-ffmpeg-on-windows/
  [3]: https://of4jd0bcc.qnssl.com/Xpath/xpath-title.png
  [4]: https://of4jd0bcc.qnssl.com/Xpath/xpath-link.png
  [5]: https://of4jd0bcc.qnssl.com/Xpath/finished.png

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E6%89%93%E9%BC%93_alipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200
