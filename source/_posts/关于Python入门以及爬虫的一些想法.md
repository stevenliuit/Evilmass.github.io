---
title: 关于Python入门以及爬虫的一些想法
date: 2017-01-18 23:58:10
tags: 爬虫
---

### [在这之前请认真看看这篇文章][1]

其中一句我很赞同：**遗憾地是，很多初学者的问题是：想对一门技术快速入门，却使用了系统学习的方法，还未入门，便倒在了艰苦修行的路上**
<br>

<!--more-->

### ？？？
>为什么C语言的书貌似翻了几次下来，还是从入门到放弃？
 为什么学了xx跟没学一样？
 我应该去学这个吗？
 学这个有什么用？
 
<br>

### 为什么是Python
    友好，优雅，好学
**Life Is Short, U Need Python**
<br>

### 我先看完Python再来写爬虫？

如果你只想写一写简单的爬虫，不要炫技不考虑爬虫效率，你只需要掌握：

1. 数据类型和变量
2. 字符串和编码
3. 使用list和tuple
4. 条件判断、循环
5. 使用dict和set

<br><br>
**你最大的问题是想得太多，而做得太少**

1. 直奔主题，学了什么用什么
2. 不懂就查，理解不能就跳过，回来再看
3. 不要迷失
4. 动手，反馈，总结

> 第三点是要注意的：在入门的过程中会遇到越来越多的新知识，而这些新知识或许会令你困惑，或者新奇。一路查下去的结果就是你忘了自己到底要干什么

<br>

### 直接动手，看看Python近乎零基础的人稍稍Google之后来写出来的爬虫长什么样
    import requests  # 导入requests库
    url = 'http://sports.sina.com.cn/nba/'  # 要爬取的地址
    print(requests.get(url).content)  # 打印出网页源代码
> 这就是最简单的爬虫，3行

这时候你会问：requests库是什么，源代码Chrome右键就有，为什么还要写三行Python代码？
<br>

### 让我们继续
打开源代码大概是这样子的
![2][2]
<br>
Q：假如我们要获取勇士队伍的所有新闻标题和链接，你会一行一行的Ctrl+C然后Ctrl+V么？
![3][3]
<br>
A：当然不会啊。网页源代码，找到关键字，一排下来全都是，去掉那些标签属性不就可以了
<br>
Q：那如果网页有很多新闻呢？如果每一个板块的位置不一样呢？如果文章还有许多图片需要保存呢？
A：。。。。。。
![4][4]
<br>

### 改进一下代码

    import requests
    from lxml import etree
    url = 'http://sports.sina.com.cn/nba/'
    rsp = etree.HTML(requests.get(url).content)
    title = rsp.xpath('//li[@class="item"][5]/a/text()')
    link = rsp.xpath('//li[@class="item"][6]/a/@href')
    for key, value in (zip(title, link)):
        print(key + ': ' + value)

![5][5]
<br>
Q：第二行是什麽？
A：导入了新库：lxml，使用了xpath
<br>
Q：为什么不用正则表达式？
A：正则表达式对新手来说实在是充满恶意。针对复杂的情况，仅靠正则匹配规则会浪费很多时间，而且我实际情况中用的最多还是(.*?)这种基础的用法
<br>
Q：Xpath怎么用？zip又是什么？
A：不懂？赶紧去查啊
<br>

通过以上例子，你大概能猜到我想表达什么

> 我对xx感兴趣，我要解决一个问题，目标有了。好，直接动手

> Python也好，爬虫也好，快速学习编程语言也好，有兴趣只是第一步。当你在解决问题的时候将兴趣持续下来，拥有**很强目的性**的去动手，去实践，去踩坑：踩坑也是一种学习过程。

> 这样不断地练习和获取反馈，你会学习的很快。很多非系统的知识和能力（甚至洞见）就是这样通过不断练习和试错在下意识间学到的----它们来得如此悄无声息，以至于除你之外，别人都将其视为你的一种天赋。

> 最后总结：做好分类，用Markdown记录动手过程，OneNote收集你在动手过程无法解决的问题，如果你还能尝试去写出教程，去跟别人讲明白你学的东西，那么再回头看看一开始没能解决的问题：似乎又没有这么难理解了
<br>

### 兴趣?
*如果是妹纸的话请自行查询感兴趣内容，...⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄....不要点开，不要打我*

[妹子图][6]
[Mzitu][7]
[煎蛋网ooxx][8]
>有动力了没？目的性够强了没？这下不难了吧？


下一篇文章我再详细讲Python爬虫，你先动手写个文本爬虫，学得快的话或许你已经能用urllib.urlretrieve()模块下载图片了，但还不够。。。

  [1]: http://mp.weixin.qq.com/s/XLP6K4Z4UwX8bLYeglTA_g
  [2]: https://of4jd0bcc.qnssl.com/python%E7%88%AC%E8%99%AB%E6%95%99%E7%A8%8B/%E6%BA%90%E4%BB%A3%E7%A0%81.png
[3]: https://of4jd0bcc.qnssl.com/python%E7%88%AC%E8%99%AB%E6%95%99%E7%A8%8B/%E5%A4%8D%E5%88%B6%E7%B2%98%E8%B4%B4.png
  [4]:https://of4jd0bcc.qnssl.com/python%E7%88%AC%E8%99%AB%E6%95%99%E7%A8%8B/%E5%8B%87%E5%A3%AB%E9%98%9F.png 
  [5]: https://of4jd0bcc.qnssl.com/python%E7%88%AC%E8%99%AB%E6%95%99%E7%A8%8B/%E6%A0%87%E9%A2%98-%E9%93%BE%E6%8E%A5.png
  [6]: http://meizitu.com/
  [7]: http://www.mzitu.com
  [8]: http://jandan.net/ooxx

<br><br>
> **这个打赏二维码好像没什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200

