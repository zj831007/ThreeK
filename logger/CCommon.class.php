<?php
/**
 * @brief common include header, require this first
 *
 * do global defines, logid/client ip/...
 * @version for php 5+
**/

class CCommon // used as a namespace
{
    public static function getLogID()
    {
        $arr = gettimeofday();
        return ((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) |
                0x80000000);
    }

    public static function timeMsecDiff ($fromTime, $toTime) {
	    return ($toTime['sec'] - $fromTime['sec']) * 1000 + ($toTime['usec'] - $fromTime['usec']) / 1000;
    }

    public static function getClientIP()
    {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"]) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif ($_SERVER["HTTP_CLIENT_IP"]) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif ($_SERVER["REMOTE_ADDR"]) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "0.0.0.0";
        }

        $pos = strpos($ip, ',');
        if ($pos > 0) $ip = substr($ip, 0, $pos);
        return trim($ip);
    }

    public static function getHostname()
    {
        return $_ENV['HOSTNAME'];
    }

    public static function numToIP($num)
    {
        $tmp = (double)$num;
        return sprintf('%u.%u.%u.%u', $tmp & 0xFF, (($tmp >> 8) & 0xFF),
                (($tmp >> 16) & 0xFF), (($tmp >> 24) & 0xFF));
    }

    public static function ipToNum($ip)
    {
        if (!preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/is', $ip)) {
            return 0;
        }
        $n = ip2long($ip);
        /** convert to network order */
        $n =       (($n & 0xFF) << 24)
                | ((($n >> 8) & 0xFF) << 16)
                | ((($n >> 16) & 0xFF) << 8)
                | (($n >> 24) & 0xFF);
        return $n;
    }

    public static function StringToHex($strData)
    {
        $strData    =   strval($strData);
        $strHex =   '';
        for ($i=0, $cnt=strlen($strData); $i<$cnt; $i++) {
            $intVal =   ord($strData{$i});
            $low    =   $intVal % 16;
            $hi     =   ($intVal - $low) / 16;
            $strHex .=  chr($hi > 9 ? 55 + $hi : 48 + $hi)
                . chr($low > 9 ? 55 + $low : 48 + $low);
        }
        return $strHex;
    }
    
    public static function unUTF8($chUTF8)
    {
        $ch1 = ord($chUTF8[0]);
        $ch2 = ord($chUTF8[1]);
        $ch3 = ord($chUTF8[2]);
        $val = ((0x1F & $ch1) << 12) + ((0x7F & $ch2) << 6) + (0x7F & $ch3);
        return sprintf('&#%u;', $val);
    }

	public static function & buildQueryPageBar($arrPageBarInfo, $urlPath, $queryParams)
	{
		$strPageBar = "";
		if( $arrPageBarInfo["head_page"] )
		{
			$strPageBar .= '<a href="'.$urlPath.'?pageno=0&'.$queryParams.'">[首页]</a>&nbsp;';
		}
		if( $arrPageBarInfo["pre_page"] )
		{
			$strPageBar .= '<a href="'.$urlPath.'?pageno='.($arrPageBarInfo["current_page"] - 2).
							'&'.$queryParams.'">[上一页]</a>&nbsp;';
		}

		for( $i = $arrPageBarInfo["start_page"]; $i <= $arrPageBarInfo["end_page"]; ++$i )
		{
			if( $i == $arrPageBarInfo["current_page"] )
			{
				$strPageBar .= "$i&nbsp;";
			}
			else
			{
				$strPageBar .= '<a href="'.$urlPath.'?pageno='.($i - 1).'&'.$queryParams.'">['.$i.']</a>&nbsp;';
			}
		}

		if( $arrPageBarInfo["next_page"] )
		{
			$strPageBar .= '<a href="'.$urlPath.'?pageno='.$arrPageBarInfo["current_page"].
							'&'.$queryParams.'">[下一页]</a>&nbsp;';
		}
		if( $arrPageBarInfo["tail_page"] )
		{
			$strPageBar .= '<a href="'.$urlPath.'?pageno='.($arrPageBarInfo["total_page"] - 1).
							'&'.$queryParams.'">[尾页]</a>&nbsp;';
		}

		return $strPageBar;
	}
    
	public static function getPageBarInfo($intCurPage, $intTotalPage, $intPageShowNum, & $arrPageBarInfo )
    {
		$arrPageBarInfo["total_page"] = $intTotalPage;
		$arrPageBarInfo["current_page"] = $intCurPage;
		$arrPageBarInfo["show_page_num"] = $intPageShowNum;
		$arrPageBarInfo["head_page"] = true;
		$arrPageBarInfo["pre_page"] = false;
		$arrPageBarInfo["next_page"] = false;
		$arrPageBarInfo["tail_page"] = false;

		if( $intTotalPage <= 1 )
		{
			$arrPageBarInfo["head_page"] = false;
			$arrPageBarInfo["start_page"] = 1;
			$arrPageBarInfo["end_page"] = -1;
			return true;
		}

		if( $intCurPage > 1 )
		{
			$arrPageBarInfo["pre_page"] = true;
		}
		if( $intCurPage < $intTotalPage )
		{
			$arrPageBarInfo["next_page"] = true;
		}

		if( $intTotalPage < $intPageShowNum )
		{
			$arrPageBarInfo["start_page"] = 1;
			$arrPageBarInfo["end_page"] = $intTotalPage;
			return true;
		}
        
        $intLeft = intval(($intPageShowNum - 1) / 2);
        $intRight = $intPageShowNum - $intLeft - 1;
        if( $intCurPage <= $intLeft )
        {
			$arrPageBarInfo["start_page"] = 1;
            $arrPageBarInfo["end_page"] = $intPageShowNum;
        }
        else if( $intCurPage + $intRight > $intTotalPage )
        {
			$arrPageBarInfo["start_page"] = $intTotalPage - $intPageShowNum + 1;
            $arrPageBarInfo["end_page"] = $intTotalPage;
        }
        else
        {
            $arrPageBarInfo["start_page"]  = $intCurPage - $intLeft;
            $arrPageBarInfo["end_page"] = $intCurPage + $intRight;
        }

        if( $arrPageBarInfo["end_page"] < $intTotalPage )
        {
            $arrPageBarInfo['tail_page'] = true;
        }

		return true;
    }
	function redirectUrl ($strUrl)
	{
		echo "<meta http-equiv='Pragma' content='no-cache'>".
			"<meta http-equiv='Refresh'content='0;URL=".$strUrl."'>";
	}
}

define("LOG_ID",        CCommon::getLogID());
define("CLIENT_IP",     CCommon::getClientIP());
define("CLIENT_IP_NUM", CCommon::ipToNum(CLIENT_IP));
define("HOSTNAME",      CCommon::getHostname());

?>
