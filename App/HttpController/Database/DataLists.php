<?php


namespace App\HttpController\Database;


use App\HttpController\Admin\Admin;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Exception\Exception;

class DataLists extends Admin
{

    public function onException(\Throwable $throwable): void
    {

        $this->writeJson(200,$throwable->getMessage(),'error');
    }


    /**
     * GET SQL RES
     * @param $query
     * @return mixed
     * @throws Exception
     * @throws \Throwable
     */
    protected function queryRes(QueryBuilder $query){
        return DbManager::getInstance()->query($query)->getResult();
    }

    public function fields(){





    }


    public function lists(){
        $getData = $this->request()->getRequestParam();
        $getData['table_name'];

        $builder = new QueryBuilder();
        $lists = $this->queryRes($builder->get('test'));


        $this->writeJson(200,$lists,'ok');
    }


}