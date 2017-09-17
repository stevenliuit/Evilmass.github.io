---
title: WordPress 博客添加返回顶部按钮
date: 2017-01-24 23:22:58
tags: Wordpress
---

当Wordpress文章较长或者评论较多导致页面较长时，如果要从下端位置返回顶端，使用鼠标滚轮是非常不方便的，这时就需要添加一个返回顶部按钮

<!--more-->

进入WordPress 控制面板-“外观”-“编辑”，找到footer.php，在之前添加以下代码:

    <div id=”full” style=”width:50px; height:95px;position:fixed; left:50%; top:490px; margin-left:540px; z-index:100; text-align:center; cursor:pointer;”> <a><img src=”这里换成你图片的绝对地址” border=0 alt=”返回顶部“></a> </div><script type=”text/javascript”>var isie6 = window.XMLHttpRequest ? false : true; function newtoponload() { var c = document.getElementById(“full”); function b() { var a = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop; if (a > 0) { if (isie6) { c.style.display = “none”; clearTimeout(window.show); window.show = setTimeout(function () { var d = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop; if (d > 0) { c.style.display = “block”; c.style.top = (400 + d) + “px” } }, 300) } else { c.style.display = “block” } } else { c.style.display = “none” } } if (isie6) { c.style.position = “absolute” } window.onscroll = b; b() } if (window.attachEvent) { window.attachEvent(“onload”, newtoponload) } else { window.addEventListener(“load”, newtoponload, false) } document.getElementById(“full”).onclick = function () { window.scrollTo(0, 0) };</script>

<br>

推荐一个:
![0][0]

如果发现返回按钮的样式跟自己的博客不太协调，可以尝试修改步骤一中代码的style的值以自定义按钮位置
<br><br>
[0]: https://of4jd0bcc.qnssl.com/Blog/gotop.jpg

> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]:  https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200
  [100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200