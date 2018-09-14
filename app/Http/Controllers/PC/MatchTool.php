<?php
/**
 * Created by PhpStorm.
 * User: ricky
 * Date: 2017/10/20
 * Time: 16:34
 */

namespace App\Http\Controllers\PC;


trait MatchTool
{
    //php获取中文字符拼音首字母
    private function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gbk//ignore', $str);
        $s2 = iconv('gbk', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    /**
     * 集锦/录像 路径
     * @param $id
     * @param $type
     * @return string
     */
    public static function subjectLink($id, $type) {
        $len = strlen($id);
        if ($len < 4) {
            return "";
        }
        $first = substr($id, 0, 2);
        $second = substr($id, 2, 3);
        $url = '/live/subject/' . $type . '/' . $first . '/' . $second . '/' . $id . '.html';
        $url = asset($url);
        $url = str_replace('https', 'http', $url);
        return $url;
    }

    /**
     * 集锦/录像 线路的路径
     * @param $id
     * @param $type
     * @param bool $isMobile
     * @return string
     */
    public static function subjectChannelLink($id, $type, $isMobile = false) {
        $len = strlen($id);
        if ($len < 4) {
            return "";
        }
        $first = substr($id, 0, 2);
        //dump($first);
        $second = substr($id, 2, 3);
        $mobile = $isMobile ? '/mobile' : '';
        return '/live/subject/' . $type . '/channel' . $mobile . '/' . $first . '/' . $second . '/' . $id . '.json';
    }

    /**
     * 热门录像内容
     * @param $id
     * @param $isMobile
     * @return string
     */
    public static function hotVideoJsonLink($id, $isMobile = false) {
        $index = floor($id / 10000);
        $mobile = $isMobile ? '/mobile' : '';
        return '/live/videos/channel' . $mobile . '/' . $index . '/' . $id .'.json';
    }


}