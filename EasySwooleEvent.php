<?php
namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');


        self::loadConf();
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        //create orm config
        $mySqlConf = Config::getInstance()->getConf('config.mysql_conf');
        $dbConfig = new \EasySwoole\ORM\Db\Config();
        $dbConfig->setHost($mySqlConf['host']);
        $dbConfig->setPort($mySqlConf['port']);
        $dbConfig->setUser($mySqlConf['user']);
        $dbConfig->setPassword($mySqlConf['password']);
        $dbConfig->setDatabase($mySqlConf['database']);
        //link pool config
        $dbConfig->setGetObjectTimeout(3.0);
        $dbConfig->setIntervalCheckTime(30*1000);
        $dbConfig->setMaxIdleTime(15);
        $dbConfig->setMaxObjectNum(20);
        $dbConfig->setMinObjectNum(5);
        $dbConfig->setAutoPing(5);
        DbManager::getInstance()->addConnection(new Connection($dbConfig));


//        $mysqliC = new \EasySwoole\Mysqli\Config([
//            'host'=>'127.0.0.1',
//            'port'=>3306,
//            'user'=>'root',
//            'password'=>'1234560',
//            'database'=>'db_es_cms',
//            'timeout'=>'5',
//            'charset'=>'utf8mb4',
//        ]);

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    public static function loadConf(){

        //
        $files = File::scanDirectory(EASYSWOOLE_ROOT .'/App/Config');

        if(is_array($files)){
            foreach ($files['files'] as $file){
                $fileNameArr = explode('.',$file);
                $fileSuffix = end($fileNameArr);
                if($fileSuffix == 'php'){
                    \EasySwoole\EasySwoole\Config::getInstance()->loadFile($file);//
                }
            }
        }

    }
}