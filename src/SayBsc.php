<?php
namespace ynrzxka\accessStatistics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SayBsc
{
    public function __construct()
    {
        $this->_init();
    }

    private function _init()
     {
         if(Request::capture ()->isMethod ('post'))
         {
             return true;
         }

         if (session_status() !== PHP_SESSION_ACTIVE) session_start();

         $key = session_id();

         session_destroy();

         $params['url'] = Request::capture ()->getUri ();
         $params['session_id'] = $key;
         $params['created_at'] = time ();
         $params['referer'] = self::_getReferer();
         $params['ip'] = self::_getIp();
         $params['browser'] = self::_getBrowser();
         $params['region'] = 'fsdf';
         $params['created_date'] = date('Y-m-d');
         $params['created_hour'] = date('H');
         DB::table('admin_home_visit')->insert($params);
     }

    private static function _getIp(){
         if(isset($_SERVER['HTTP_X_REAL_IP']))
         {
             $client_ip = $_SERVER['HTTP_X_REAL_IP'];
         } else {
             $client_ip = Request::capture()->getClientIp();
         }
         return $client_ip;
     }

    private static function _getReferer()
    {
        $local_url = parse_url(Request::capture ()->getUri ());
        if(!isset($local_url['scheme']) || !isset($local_url['host']))
        {
            return '直接访问';
        }
        $referer = (parse_url(@$_SERVER['HTTP_REFERER']));
        if(!isset($referer['scheme']) || !isset($referer['host']))
        {
            return '直接访问';
        }
        if(($referer['scheme'].'://'. $referer['host']) == $local_url['scheme'].'://'. $local_url['host'])
        {
            return '直接访问';
        }
        return $referer['scheme'].'://'. $referer['host'];
    }

    private  static function _getBrowser()
    {
        if(empty($_SERVER['HTTP_USER_AGENT']))
        {
            return 'robot！';
        }
        if( (false == strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident')!==FALSE) )
        {
            return 'IE11';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 10.0'))
        {
            return 'I10';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0'))
        {
            return 'IE9';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0'))
        {
            return 'IE8';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0'))
        {
            return 'IE7';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0'))
        {
            return 'IE6';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Edge'))
        {
            return 'Edge';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox'))
        {
            return 'Firefox';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome'))
        {
            return 'Chrome';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari'))
        {
            return 'Safari';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera'))
        {
            return 'Opera';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE'))
        {
            return '360SE';
        }

        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessage'))
        {
            return 'MicroMessage';
        }
        return 'unknown';
    }

    private  static function _getLanguage()
    {
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || !$_SERVER['HTTP_ACCEPT_LANGUAGE'])
        {
            return 'unknown';
        }

        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $lang = substr($lang, 0, 5);
        if (preg_match('/zh-cn/i',$lang))
        {
            $lang = '简体中文';

        } else if (preg_match('/zh/i',$lang))
        {
            $lang = '繁体中文';
        } else {
            $lang = 'English';
        }
        return $lang;
    }

    private static function _getSystem()
    {
        if(!isset($_SERVER['HTTP_USER_AGENT']) || !$_SERVER['HTTP_USER_AGENT'])
        {
            return 'unknown';
        }

        $os = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $os))
        {
            $os = 'Windows';
        } else if (preg_match('/mac/i', $os))
        {
            $os = 'MAC';
        } else if (preg_match('/linux/i', $os))
        {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $os))
        {
            $os = 'Unix';
        } else if (preg_match('/bsd/i', $os))
        {
            $os = 'BSD';
        } else {
            $os = 'Other';
        }
        return $os;
    }
 }