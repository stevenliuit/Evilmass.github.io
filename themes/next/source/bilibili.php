<?php
/**
 *  Author： METO
 *  Version: 0.1.0
 */

class bilibili{

    protected $_COOKIE='sid=5j8vbyou; pgv_pvi=4616502272; fts=1486559491; LIVE_BUVID=26d6937dd37d38e470e10a1ed31bcbed; LIVE_BUVID__ckMd5=2f78c96e7a60cf5a; buvid3=AD83F32D-7C93-46B1-8AD8-0E6F281DDE2E33083infoc; DedeUserID=12258089; DedeUserID__ckMd5=fd258fa60157d5d9; _cnt_dyn=null; _cnt_pm=0; _cnt_notify=4; uTZ=-480; LIVE_LOGIN_DATA=abf35cfea5239b1a1f36b64ef4fe87237f8203c1; LIVE_LOGIN_DATA__ckMd5=baa22690327be168; user_face=http%3A%2F%2Fi2.hdslb.com%2Fbfs%2Fface%2F7ba005f9dba4504bd796a0134b69ead3525c051e.jpg; pgv_si=s4290575360; CNZZDATA2724999=cnzz_eid%3D746443973-1486559492-%26ntime%3D1486912318';
    protected $_USERAGENT='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.101 Safari/537.36';
    protected $_REFERER='http://live.bilibili.com/';

    private function getinfo(){
        $data=json_decode(self::curl('http://live.bilibili.com/User/getUserInfo'),1);
        $a=$data['data']['user_intimacy'];
        $b=$data['data']['user_next_intimacy'];
        $per=round($a/$b*100,2);
        echo "===============================\n";
        echo "name: {$data['data']['uname']} \n";
        echo "level: {$data['data']['user_level']} \n";
        echo "exp: {$a}/{$b} {$per}%\n";
        echo "sign: ".self::sign()."\n";
        echo "===============================\n";
    }

    private function sign(){
        $raw=json_decode(self::curl('http://live.bilibili.com/sign/doSign'),1);
        return $raw['msg'];
    }

    public function cron(){
        header('Content-Type: text/txt; charset=UTF-8');
        echo date('[Y-m-d H:i:s]',time())."\n";
        $raw=json_decode(self::curl('http://live.bilibili.com/User/userOnlineHeart'),1);
        if(!isset($raw['data'][1]))echo " > SUCCESS\n";
        else echo " > INFO: already send @ ".date('Y-m-d H:i:s',$raw['data'][1])."\n";

        self::getinfo();
    }

    protected function curl($url){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl,CURLOPT_REFERER,$this->_REFERER);
        curl_setopt($curl,CURLOPT_COOKIE,$this->_COOKIE);
        curl_setopt($curl,CURLOPT_USERAGENT,$this->_USERAGENT);
        $result=curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}

(new bilibili)->cron();