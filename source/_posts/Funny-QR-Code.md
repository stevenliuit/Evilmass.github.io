---
title: Funny QR Code
date: 2017-01-16 21:18:19
tags: 杂
---

### 效果
![alipay.gif][1]

<!--more-->

![patapon_wechat][2]
![20][20]
<br><br>



### Require
    1. Visual QR Codes Generator 
    2. VirtualDub
    3. Format Factory
    4. PhotoShop CS6
[百度盘提取码8jjc][3] 
**吐槽1: 百度云没Vip叫*爸爸*都没用** 

下载大文件速度慢的请自行下载: 
> [Format Factory][4]
  [PhotoShop CS6下载&破解][5]

<br><br>


### Let's Do It

#### 首先, 获得个人支付宝或者微信转账的二维码并[转换成url][6] 
![7][7]
<br><br>

#### 找一张你喜欢的Gif
![8][8]
<br><br>

#### 用PhotoShop转换成mp4格式
 **请用独显开Photoshop!**
 **请用独显开Photoshop!**
 **请用独显开Photoshop!**
重要的事情说三次
![9][9]
<br><br>

#### 用格式工厂将mp4转化为avi格式, **输出选项 -> 视频编码 -> MJPEG**
![10][10]
<br>
如果你直接把 avi 扔进 Visual QR Codes Generator里, 生成的时候它就会报错, 因为这坑爹货用的是 **opencv**
<br><br>

#### 打开 VirtualDub.exe， 导入那个 avi 视频（open-open video file）: **Video->Filters->Add->Convert format**，选择 **32-Bit RGB**，点击 ok，最后点击 file-save av AVI 保存处理后的视频文件, 这样我们就得到了导出的视频文件
![11][11]
<br><br>

#### 将改好的avi文件与二维码合并

在空白栏填上第一步得到的url
Version可修改二维码样式
EC Level默认H即可
![22][22]
<br>
接下来我们在视频压缩对话框的压缩程序选项选上**全帧**，然后点确认，导出视频：
![12][12]
<br>
彩色勾选上, 速度可根据个人喜好调整(鬼畜什么的), 融合过程需要等待一段时间
![13][13]
<br><br>


#### 二维码->Gif
再次用到PhotoShop, 选择文件 -> 导入 -> 视频帧到图层
![14][14]
<br>
存储为Web所用格式
![23][23]
<br>
![21][21]
<br>
gif速度过快可选中所有帧, 适当调整延迟时间
![24][24]
<br>
可适当调大像素, 保存为Gif
![15][15]
<br>

> 赶紧拿手机扫一扫试试吧~
 [这里有一个失败的例子: 太大了][29]


**吐槽2: 这过程好繁琐啊，哪天能写个脚本自动化什么的，毕竟你是学Python的人啊(*雾***
<br>

**Update：看来不用重复造轮子了** 
>[Python 艺术二维码生成器 (GIF动态二维码、图片二维码)][25]

> [在线生成网页版][26]

> [Windows版(不需要Python环境)][28]

貌似还在更新, 有人反馈不支持微信和支付宝的二维码, 我找到了个解决办法并提交了issue: [在MyQR中使用微信和支付宝二维码链接][27]

给出windos的info.txt示例(注释仅作说明, info.txt文件内不能出现中文):

    ```python
    # an example for info.txt:
    words=http://t.cn/xxx  # 缩短后的URL
    p=117.gif  # 原始文件
    n=new.gif  # 生成的文件名
    v=10   # -v 控制边长, 范围是1至40, 数字越大边长越大, 对应生成的时间也越长, 别手贱调成40, 不知道要跑多久    
    l=H  # -l 控制纠错水平，范围是L、M、Q、H，从左到右依次升高
    c  # 加上参数 -c 可以使产生的图片由黑白变为彩色的
    con=1.5  #参数-con 用以调节图片的对比度，1.0 表示原始图片，更小的值表示更低对比度，更大反之。默认为1.0。
    bri=1.3  # 参数 -bri 用来调节图片的亮度，其余用法和取值与 -con 相同
    # 还有d参数可以控制文件输出位置, 遗憾的是没有自定义二维码样式的参数
    # 重要: 一个参数一行, 等号左右不要空格, 如果不使用某个参数, 则将其删去(words是必要的)
    # 注意1: 该程序只是一个简单的打包, 测试时,有的电脑快, 有的电脑慢(原因不明)
    # 注意2: 第一次使用可能会自动下载一些库文件,原因是程序依赖的库 imageio
    ```
<br>

### Reference
1. [QR code(Quick Response Code)][17]

2. [如何优雅地让人给你打钱][18]

3. [如何制作自定义背景的二维码？（包括动态）][19]
<br><br>

> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%8B%8D%E6%A1%8C_ailipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200
  [1]: https://of4jd0bcc.qnssl.com/QR/alipay2.gif?imageView2/2/w/300
  [2]: https://of4jd0bcc.qnssl.com/QR/patapon_wechat.gif?imageView2/2/w/300
  [3]: http://pan.baidu.com/s/1eSmjWrS
  [4]: http://www.pcgeshi.com/
  [5]: http://rj.baidu.com/soft/detail/23675.html
  [6]: http://tool.oschina.net/qr?type=2
  [7]: https://of4jd0bcc.qnssl.com/QR/%E5%BE%AE%E4%BF%A1%E6%89%93%E8%B5%8F.jpg?imageView2/2/w/400
  [8]: https://of4jd0bcc.qnssl.com/QR/%E7%86%8A%E6%9C%AC%E7%86%8A%E6%B3%BC%E6%B0%B4.gif
  [9]: https://of4jd0bcc.qnssl.com/QR/gif_mp4.png
  [10]: https://of4jd0bcc.qnssl.com/QR/mp4_avi.png
  [11]: https://of4jd0bcc.qnssl.com/QR/opencv.png
  [20]: https://of4jd0bcc.qnssl.com/QR/wechat.gif?imageView2/2/w/200
  [12]: https://of4jd0bcc.qnssl.com/QR/quan.png
  [13]: https://of4jd0bcc.qnssl.com/QR/modify_gif2.png
  [14]: https://of4jd0bcc.qnssl.com/QR/finish.png
  [15]: https://of4jd0bcc.qnssl.com/QR/finish4.png
  [21]: https://of4jd0bcc.qnssl.com/QR/finish2.png
  [17]: https://en.wikipedia.org/wiki/QR_code
  [18]: http://spacekid.me/qart-code/
  [19]: https://www.chenxublog.com/2016/05/22/pic-qrcode-colorful.html
  [20]: https://of4jd0bcc.qnssl.com/QR/wechat.gif?imageView2/2/w/200
  [22]: https://of4jd0bcc.qnssl.com/QR/modify_gif.png
  [23]: https://of4jd0bcc.qnssl.com/QR/finish3.png
  [24]: https://of4jd0bcc.qnssl.com/QR/finish5.png
  [25]: https://github.com/sylnsfar/qrcode
  [26]: http://www.amazing-qrcode.com/
  [27]: https://github.com/sylnsfar/qrcode/issues/19
  [28]: https://github.com/sylnsfar/qrcode_win
  [29]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%95%B2%E7%A2%97_ailipay.gif

  