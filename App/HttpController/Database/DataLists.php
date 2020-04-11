<?php


namespace App\HttpController\Database;


use App\HttpController\Admin\Admin;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

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


        $search = [];
        $fields = [];



        $this->writeJson(200,['search'=>$search,'fields'=>$fields]);
    }


    public function lists(){
        $getData = $this->request()->getRequestParam();
        if(!isset($getData['table_name']))
            throw new \Exception('not model');


        $builder = new QueryBuilder();
        $lists = $this->queryRes($builder->get('test'));


        $this->writeJson(200,$lists,'ok');
    }


    public function edit(){}

    public function add(){}

    public function del(){}

}