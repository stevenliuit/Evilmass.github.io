---
title: HackSojump
date: 2017-08-26 13:27:54
tags: Hack
---

项目地址：[HackSojump]

问卷星 Python 脚本
1. 自动获取可用代理进行刷卷
2. 云打码识别验证码进行刷卷

限于篇幅，云打码刷卷的代码请在Github查阅，喜欢的请给个 Star
![sojump][sojump]

<!--more-->

## 随机获取答案

    def get_post_data():
        try:
            tmp_data = []
            duplicate = post_data = {}
            html = requests.get('https://sojump.com/jq/' + curID + '.aspx', verify=False)
            num_reg = "id='div(.*?)'"
            num = re.findall(num_reg, html.text, re.S)[-1].split('_')[0].strip('question')  # 题目总数
            topic_reg = "rel='q(.*?)'"
            topic = re.findall(topic_reg, html.text, re.S)
            for t in topic:
                duplicate[str(t.split('_')[0])] = str(t.split('_')[1])  # 题目选项最大序号
            for x in range(1, int(num)):
                answer = choice(('1', duplicate[str(x)]))  # 随机答案
                tmp_data.append(str(x) + '$' + answer + '}')
            tmp_data.append(num + '$' + '1')  # 最后一个答案不需要"}"
            post_data = {'submitdata': ''.join(tmp_data)}
            return post_data
            # 自定义问卷数据
            # return {'submitdata': '1$2'}  
        except Exception as e:
            raise e
## 单次提交

    def single_post(proxy):
        count = 0
        ip = str(proxy['http'].split('//')[1])
        try:
            judge = requests.get(url='http://sojump.com/jq/' + curID + '.aspx', proxies=proxy, headers=post_headers, timeout=5)
            if judge.status_code == 200:
                rn = re.findall('var rndnum="(.*?)";', judge.text)[0] 
                url = 'http://sojump.com/handler/processjq.ashx?submittype=1&curID=' + curID + '&t=' + t + '&starttime=' + starttime + '    &rn=' + rn
                while(count < 5): 
                    rsp = requests.post(url=url, headers=post_headers, proxies=proxy, data=get_post_data(), verify=False, timeout=5)
                    if u'不符合' in rsp.text:
                        print(u'答案不符合要求')
                        break
                    if u'验证码' in rsp.text:
                        print(u'%s 不可用' % ip)
                        break
                    if 'complete' in rsp.text:
                        finished =  rsp.text.split('&')[2].strip('jidx=')
                        print(u'代理IP -> %s 可用，问卷ID -> %s，已填写 -> %s 份' % (ip, curID, finished))
                    count += 1
            else:
                print('http_status_code: %s' % judge.status_code)
        except Exception as e:
            # raise e
            print(ip + ' -> timeout')

## 多进程
    def multi_post(proxies):
        multiprocessing.freeze_support()
        pool = multiprocessing.Pool(multiprocessing.cpu_count())
        result = pool.map_async(single_post, proxies)
        pool.close()
        pool.join()

## 为什么没有多线程
其实是有的，而且线程数控制在 50 就可以了，只是。。。

> **一般问卷 200 份的数据足矣**

> **开着这么多线程去刷卷跟攻击人家服务器有什么区别呢**

> **取消回显能增加多线程刷卷速度（逃**

## 获取代理
这里安利一个免费代理网站：[GatherProxy][GatherProxy]，**需要梯子**注册个账号，一次大概能获取到 200+ 的 HTTP 代理和 1000+ 的 Socks 代理（可用），然而懒人表示连登录的验证码都懒得填，于是乎发现 POST 登陆不需要验证码（WTF？？？
<br>
### 取消验证码登陆
    
    def login():
        with requests.Session() as tmp:
            final = None
            is_login = tmp.get(url=manage_url, headers=manage_headers, verify=False)
            if u'防重复填写' in is_login.text:
                print(u'利用cookie登陆成功')
                final = is_login
            if u'刷票' in is_login.text:
                print(u'访问频率过快已被禁止，请解封IP')
                exit(0)
            else:
                __EVENTVALIDATION = tmp.get(url='https://www.sojump.com/login.aspx', verify=False)
                login_data['__EVENTVALIDATION'] = str(re.findall('id="__EVENTVALIDATION" value="(.*?)"', __EVENTVALIDATION.text, re.S)[0])
        
                direct_login = tmp.post(url=login_url, headers=login_headers, data=login_data, verify=False)
                if u'防重复填写' in direct_login.text:
                    print(u'问卷星账号登陆成功')
                    save_cookies(tmp)
                    final = direct_login
        return final


    def save_cookies(tmp):
        manage_headers['Cookie'] = '.ASPXANONYMOUS=' + tmp.cookies['.ASPXANONYMOUS'] + '; ASP.NET_SessionId=h42i5qi2qtfc13bst354sqpl;' \
            + ' lllogcook=1; UM_distinctid=15df4f515f9c6b-06820b32589124-791238-144000-15df4f515fabeb;' \
            + ' SojumpSurvey=' + tmp.cookies['SojumpSurvey'] + ';' \
            + ' WjxUser=WjxUser=UserName=' + UserName + '&Type=1;' \
            + ' _cnzz_CV4478442=%E7%94%A8%E6%88%B7%E7%89%88%E6%9C%AC%7C%E5%85%8D%E8%B4%B9%E7%89%88%7C1503056280855;' \
            + ' CNZZDATA4478442=cnzz_eid%3D231769859-1503051247-https%253A%252F%252Fwww.sojump.com%252F%26ntime%3D1503051247'
        with open ('userInfo/cookie.txt', 'w') as cookie:
            cookie.write(manage_headers['Cookie'])
        return manage_headers['Cookie']
    
    
    def disable_captcha(rsp):  # 取消登录验证码
        cancel_data = {}
        __VIEWSTATE = re.findall(b'__VIEWSTATE\" value=\"(.*?)\"', rsp.content, re.S)[0]
        __VIEWSTATEGENERATOR = re.findall(b'__VIEWSTATEGENERATOR\" value=\"(.*?)\"', rsp.content, re.S)[0]
        __SCROLLPOSITIONX = re.findall(b'__SCROLLPOSITIONX\" value=\"(.*?)\"', rsp.content, re.S)[0]
        __SCROLLPOSITIONY = re.findall(b'__SCROLLPOSITIONY\" value=\"(.*?)\"', rsp.content, re.S)[0]
        cancel_data['__VIEWSTATE'] = __VIEWSTATE.decode(rsp.encoding)
        cancel_data['__VIEWSTATEGENERATOR'] = __VIEWSTATEGENERATOR.decode(rsp.encoding)
        cancel_data['__SCROLLPOSITIONX'] = __SCROLLPOSITIONX.decode(rsp.encoding)
        cancel_data['__SCROLLPOSITIONY'] = __SCROLLPOSITIONY.decode(rsp.encoding)
        cancel_data['ctl02$ContentPlaceHolder1$btnSave2'] = '保存设置'
        c_rsp = requests.post(url=manage_url, headers=manage_headers, data=cancel_data, verify=False)
        if u'保存设置' in c_rsp.text:
            print(u'已取消验证码设置')
        return c_rsp
<br>
### 下载并过滤代理
    # 需要梯子登陆
    download_proxies = {'http': 'socks5://127.0.0.1:1080'}
    
    # 这次是真的用多线程了
    class proxyThread(threading.Thread):
        def __init__(self, proxy):
            super(proxyThread, self).__init__()
            self.proxy = proxy
        def run(self):
            try:
                ip = self.proxy['http'].split('://')[1]
                rsp = requests.get(url=check_proxy_url, headers=check_proxy_headers, proxies=self.proxy, timeout=5, verify=False)
                if rsp.status_code == 200:
                    filter_lists.append(ip + '\n')
            except Exception as e:
                # raise e
                pass
    
    
    def proxy_login():
        tmp = requests.Session()
        try:
            login_url = 'http://www.gatherproxy.com/zh/subscribe/login'
            reg = 'class=\"blue\">(.*?)<'
            html = requests.get(url=login_url, proxies=download_proxies)
            login_rsp = tmp.post(url=login_url, headers=get_proxy_headers, data=proxy_data, proxies=download_proxies)
            login_confirm = login_rsp.text.encode('gbk', 'ignore').decode('gbk')
            if 'Expired' not in login_confirm:
                print(u'登陆失败，请检查获取代理的登陆信息是否正确')
                exit(0)
            login_confirm_reg = '\"page-title\">(.*?)<' 
            print(''.join(re.findall(login_confirm_reg, login_confirm, re.S)))
        except Exception as e:
            raise e
        return tmp
    
    
    def download_proxy(tmp):
        http_lists = socks_lists = []
        http_count = socks_count = 0
        
        # Socks代理获取
        socks_url = 'http://www.gatherproxy.com/sockslist/plaintext'
        socks_rsp = tmp.post(url=socks_url, headers=get_proxy_headers, proxies=download_proxies).text
        with open('proxy/socks.txt', 'w') as socks:
            socks.write(socks_rsp.replace('\r', ''))
        with open('proxy/socks.txt', 'r') as ttp:
            for s in ttp.readlines():
                socks_lists.append(s.strip('\n'))
                socks_count +=1
        print('alive socks proxy counts: -> %s' % socks_count)
        
        # HTTP代理获取
        sid_url = 'http://www.gatherproxy.com/zh/subscribe/infos'
        sid_reg = 'sid=(.*?)\"'
        sid_rsp = tmp.get(url=sid_url, headers=get_proxy_headers, proxies=download_proxies)
        sid = re.findall(sid_reg, sid_rsp.text.encode('gbk', 'ignore').decode('gbk'))[0]
        dwn_data = 'ID=' + sid + '&C=&P=&T=&U=0'
        dwn_url = 'http://www.gatherproxy.com/zh/proxylist/downloadproxylist/?sid=' + sid
        dwn_rsp = tmp.post(url=dwn_url, headers=get_proxy_headers, data=dwn_data, proxies=download_proxies)
        http_lists = dwn_rsp.text.encode('gbk', 'ignore').decode('gbk').split('\r\n')
        if http_lists[0] == '503':
            print(u'代理访问出现503')
            exit(0)

        # 保存到本地
        with open('proxy/http.txt', 'w') as ipl:
            for ip in http_lists:
                ipl.write(ip + '\n')
                http_count +=1 
        print('alive http proxy counts: -> %s' % http_count)
    
    # 处理 IP:PORT 形式的代理供筛选用
    def generate_proxy(txt, type_of_proxy):
        proxies = []
        if type_of_proxy == 'socks':
            with open(txt, 'r') as proxy:
                for p in proxy.readlines():
                    pp = {'http': 'socks5://' + str(p.strip('\n')), 'https': 'socks5://' + str(p.strip('\n'))}  # socks
                    proxies.append(pp)
        if type_of_proxy == 'http':
            with open(txt, 'r') as proxy:
                for p in proxy.readlines():
                    pp = {'http': 'http://' + str(p.strip('\n')), 'https': 'http://' + str(p.strip('\n'))}  # http
                    proxies.append(pp)
        return proxies
    
    # 多线程筛选可用代理
    def filter_http_proxy():
        print('filtering http proxy')
        filtered_ip_count = 0
        proxies = generate_proxy('proxy/http.txt', 'http')
        
        for proxy in proxies:
            threads.append(proxyThread(proxy))
        for t in threads:
            t.start()
            while True:  # 限制多线程数目
                if len(threading.enumerate()) < thread_num:
                    break
    
        with open('proxy/http_filter.txt', 'w') as phft:
            for ip in filter_lists:
                phft.write(ip)
                filtered_ip_count += 1
        print('filtered ip counts: -> %s' % filtered_ip_count)
        return filter_lists

## To Do
保存可用代理到数据库和 Django 展示在下一篇文章更新，问卷星刷卷不是重点，重点是突发奇想的想建立并维护一个长期可用的代理池，提供 API，要用的时候就取出来

一个问卷星脚本让我接触到了 **Flask、Bootstrap 和 Django**，也算不错的收获了

[sojump]: https://of4jd0bcc.qnssl.com/pip/sojump.png
[HackSojump]: https://github.com/Evilmass/HackSojump
[GatherProxy]: http://www.gatherproxy.com/zh/