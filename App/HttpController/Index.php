<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Mysqli\Client;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

class Index extends Controller
{

    function index()
    {
//        \EasySwoole\Component\Timer::getInstance()->after(1*1000,function (){
//            echo "ten seconds later1 \n";
//
//            \EasySwoole\Component\Timer::getInstance()->loop(3*1000,function (){
//                echo "ten seconds later3 \n";
//            });
//        });
//
//        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
//        if(!is_file($file)){
//            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
//        }
//        $this->response()->write(file_get_contents($file));

//        $queryBuild = new QueryBuilder();
//        var_dump(DbManager::getInstance()->query($queryBuild->get('test'),true));


//        $mysqliC = new \EasySwoole\Mysqli\Config([
//            'host'=>'127.0.0.1',
//            'port'=>3306,
//            'user'=>'root',
//            'password'=>'1234560',
//            'database'=>'db_es_cms',
//            'timeout'=>'5',
//            'charset'=>'utf8mb4',
//        ]);
//
//        $c = new Client($mysqliC);
//        $c->queryBuilder()->get('test');
//        var_dump($c->execBuilder());


        var_dump($this->request()->getRequestParam());


    }

    public function index1(){

        $this->response()->write('123456789');

    }


    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }
}