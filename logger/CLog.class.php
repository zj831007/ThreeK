<?php
/**
 * @brief class for logging
 *
 * @example:
 *

<?php
require_once '../config.php';
require_once ABS_PATH . 'common-lib/log.class.php';

$GLOBALS['LOG'] = array(
    'intLevel'      => 8,
    'strLogFile'    => 'test.log',
    'strWfLogFile'  => 'test.log.wf',
);

CLog::notice('haha');
CLog::fatal('biaji');
?>

 *
 * @PHP version 5+
 * @todo
**/

require_once(dirname(__FILE__)."/CCommon.class.php");

define('LOG_LEVEL_NONE',    0);
define('LOG_LEVEL_FATAL',   1);
define('LOG_LEVEL_WARNING', 2);
define('LOG_LEVEL_NOTICE',  4);
define('LOG_LEVEL_TRACE',   8);
define('LOG_LEVEL_DEBUG',   16);
define('LOG_LEVEL_ALL',     32);

define('LOG_MAX_FILE_SIZE', 1024000000);

class CLog
{
    public $_arrLogLevels = array(
        LOG_LEVEL_NONE      => 'NONE',
        LOG_LEVEL_FATAL     => 'FATAL',
        LOG_LEVEL_WARNING   => 'WARNING',
        LOG_LEVEL_NOTICE    => 'NOTICE',
        LOG_LEVEL_TRACE     => 'TRACE',
        LOG_LEVEL_DEBUG     => 'DEBUG',
        LOG_LEVEL_ALL       => 'ALL',
    );

    protected $_intLevel;
    protected $_strLogFile;
    protected $_strWfLogFile;
 
    private function __construct($intLevel, $strLogFile, $strWfLogFile)
    {
        $this->_intLevel      = $intLevel;
        $this->_strLogFile    = $strLogFile;
        $this->_strWfLogFile  = $strWfLogFile;
    }

    public function getInstance()
    {

        $log = $GLOBALS['LOG']['obj'];
        if (!is_object($log)) {
            $GLOBALS['LOG']['obj'] = new CLog(
                $GLOBALS['LOG']['intLevel'],
                $GLOBALS['LOG']['strLogFile'],
                $GLOBALS['LOG']['strWfLogFile']);
            $log = $GLOBALS['LOG']['obj'];
        }
        return $log;
    }

    public function writeLog($intLevel, $str, $bolEcho = false)
    {

        if ($intLevel > $this->_intLevel) {
            return;
        }

        $strLevel   = $this->_arrLogLevels[$intLevel];
        if (strlen($strLevel) == 0) {
            $strLevel = $intLevel;
        }

        $strLogFile = ($intLevel > LOG_LEVEL_WARNING)
            ? $this->_strLogFile
            : $this->_strWfLogFile;
        if (strlen($strLogFile) == 0) {
            $strLogFile = sprintf('undefined.log.%s', date('Ymd',time()));
        }
        $strLogFile = $strLogFile.date('Ymd',time());
        
        $str = sprintf("%s %s ip[%s] logId[%u] %s\n", $strLevel, date("m-d H:i:s:",time()),
                        CLIENT_IP, LOG_ID, $str);

        if ($bolEcho === true) {
            echo "$str <hr>\n";
        }

        clearstatcache();
        $arrFileStats = stat($strLogFile);
        if (is_array($arrFileStats) && floatval($arrFileStats['size']) > LOG_MAX_FILE_SIZE) {
            //unlink($strLogFile);
        }
        return file_put_contents($strLogFile, $str, FILE_APPEND);
    }

    public static function debug($str, $bolEcho = false)
    {
        $log = CLog::getInstance();

        if (!is_object($log)) {
            return false;
        }

        return $log->writeLog(LOG_LEVEL_DEBUG, $str, $bolEcho);
    }

    public static function trace($str, $bolEcho = false)
    {
        $log = CLog::getInstance();
        if (!is_object($log)) {
            return false;
        }
        return $log->writeLog(LOG_LEVEL_TRACE, $str, $bolEcho);
    }

    public static function notice($str, $bolEcho = false)
    {
        $log = CLog::getInstance();
        if (!is_object($log)) {
            return false;
        }
        return $log->writeLog(LOG_LEVEL_NOTICE, $str, $bolEcho);
    }

    public static function warning($str, $bolEcho = false)
    {
        $log = CLog::getInstance();
        if (!is_object($log)) {
            return false;
        }
        return $log->writeLog(LOG_LEVEL_WARNING, $str, $bolEcho);
    }

    public static function fatal($str, $bolEcho = false)
    {
        $log = CLog::getInstance();
        if (!is_object($log)) {
            return false;
        }
        return $log->writeLog(LOG_LEVEL_FATAL, $str, $bolEcho);
    }
}

/* vim: set et ts=4 et: */
?>
