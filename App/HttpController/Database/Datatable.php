<?php


namespace App\HttpController\Database;


use App\HttpController\Admin\Admin;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Exception\Exception;

class Datatable extends Admin
{


    public function onException(\Throwable $throwable): void
    {

        $this->writeJson(200,$throwable->getMessage(),'error');
    }

    /**
     * QUERY_SQL
     * @param string $sql
     * @throws \Throwable
     */
    protected function querySql(string $sql){
        try{
            $queryBuild = new QueryBuilder();
            return DbManager::getInstance()->query($queryBuild->raw($sql),true)->getResult();
        }catch (\Exception $e){
            $this->writeJson(200,['msg'=>$e->getMessage(),'code'=>$e->getCode()],'error');
            return;
        }
        $this->writeJson(200,[],'success');
    }

    /**
     * get DB table lists
     * @return \EasySwoole\ORM\Db\Result
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function showTable(){
        $getData = $this->request()->getRequestParam('dbName','tableName');
        if(!isset($getData['db_name']))
            $getData['db_name'] = DbManager::getInstance()->getConnection()->getConfig()->getUser();

        $sql = "SHOW TABLE STATUS FROM `{$getData['db_name']}`";
        if(!empty($getData['tableName']))
            $sql .= " WHERE NAME LIKE '%{$getData['table_name']}%' OR Comment LIKE '%{$getData['table_name']}%'";

        $sql .= ";";
        $queryBuild = new QueryBuilder();
        $res = DbManager::getInstance()->query($queryBuild->raw($sql),true)->getResult();
        $this->writeJson(200,$res,'success');
    }

    /**
     * @return bool
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    protected function hasTable(string $tableName,string $dbName = null){
        if($dbName == null)
            $dbName = DbManager::getInstance()->getConnection()->getConfig()->getUser();

        if(!isset($tableName) || empty($tableName))
            throw new Exception('Undefined index: table_name');

        $sql = "SHOW TABLE STATUS FROM `{$dbName}` WHERE NAME = '{$tableName}' ;";
        $queryBuild = new QueryBuilder();
        $res = DbManager::getInstance()->query($queryBuild->raw($sql),true)->getResult();
        if(empty($res))
            return false;
        else
            return true;
    }


    /**
     * create_table
     * @throws \Throwable
     */
    public function createTable(){

//        $getData = [
//            'attrs'=>[
//                ['name'=>'title','type'=>'int UNSIGNED NOT NULL','default'=>0,'comment'=>'biaoti'],
//                ['name'=>'title2','type'=>'int UNSIGNED NOT NULL','default'=>0,'comment'=>'biaoti2'],
//            ],
//            'pk'=>true,
//            'pk_name'=>'ids',
//            'table_name'=>'test'
//        ];

        $getData = $this->request()->getRequestParam();

        if(!isset($getData['table_name']))
            throw new \Exception('Undefined index: table_name');

        if($this->hasTable($getData['table_name'])){
            $this->writeJson(200,['code'=>0,'msg'=>'has old table'],'success');
            return;
        }


        $fieldsStr = '';
        foreach ($getData['attrs'] as $vo){
            $fieldsStr .= "`{$vo['name']}` {$vo['type']} ";
            if(isset($vo['default']))$fieldsStr .=" DEFAULT {$vo['default']} ";
            if(isset($vo['comment']))$fieldsStr .=" COMMENT '{$vo['comment']}' ";
            $fieldsStr .=',';
        }

        $primaryKey = '';
        $primaryKeyAttr = '';
        if(isset($getData['pk'])){
            if(isset($getData['pk_name'])){
                $primaryKey = " PRIMARY KEY (`{$getData['pk_name']}`) ";
                $primaryKeyAttr = " `{$getData['pk_name']}` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK' ,";
            }else{
                $primaryKey = " PRIMARY KEY (`id`) ";
                $primaryKeyAttr = " `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK' ,";
            }
        }else{
            $fieldsStr = rtrim($fieldsStr,',');
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$getData['table_name']}` ( {$primaryKeyAttr} {$fieldsStr} {$primaryKey})";

        if(isset($getData['engine']))
            $sql .= " ENGINE={$getData['engine']}";

        if(isset($getData['char_type']))
            $sql .= " DEFAULT CHARACTER SET = {$getData['char_type']} COLLATE = {$getData['collate']}";

        if(isset($getData['table_comment']))
            $sql .= " COMMENT={$getData['table_comment']}";

        $sql .=';';
        $this->querySql($sql);
    }


    /**
     * DROP_TABLE
     * @throws \Throwable
     */
    public function delTable(){
        $getData = $this->request()->getRequestParam();
        $sql = "DROP TABLE {$getData['table_name']} ;";
        $this->querySql($sql);
    }


    /**
     * EDIT TABLE NAME
     * @throws \Throwable
     */
    public function editTableName(){
        $getData = $this->request()->getRequestParam();
        $sql = "ALERT TABLE {$getData['table_name']} RENAME TO {$getData['new_name']};";
        $this->querySql($sql);
    }


    /**
     * EDIT TABLE COMMENT
     * @throws \Throwable
     */
    public function editTableComment(){
        $getData = $this->request()->getRequestParam();
        $sql = "ALERT TABLE {$getData['table_name']} COMMENT {$getData['table_comment']};";
        $this->querySql($sql);
    }

    /**
     * EDIT TABLE ENGINE
     * @throws \Throwable
     */
    public function editTableEngine(){
        $getData = $this->request()->getRequestParam();
        $sql = "ALERT TABLE {$getData['table_name']} ENGINE {$getData['engine']};";
        $this->querySql($sql);
    }


    /**
     * SHOW TABLE ATTRS
     * @throws \Throwable
     */
    public function showTableAttrs(){
        $getData = $this->request()->getRequestParam();

        if(!isset($getData['db_name']))
            $getData['db_name'] = DbManager::getInstance()->getConnection()->getConfig()->getUser();
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME ='{$getData['table_name']}' TABLE_SCHEMA='{$getData['db_name']}' ;";
        $this->querySql($sql);
    }


    /**
     * ADD TABLE ATTRS
     * @throws \Throwable
     */
    public function addTableAttrs(){
        $getData = $this->request()->getRequestParam();

        $sql = "ALTER TABLE `{$getData['table_name']}` ADD COLUMN `{$getData['name']}` {$getData['type']} ";
        if(isset($getData['default']))$sql .=" DEFAULT {$getData['default']} ";
        if(isset($getData['comment']))$sql .=" COMMENT '{$getData['comment']}' ";
        if(isset($getData['after']))$sql .=" AFTER `{$getData['after']}` ";
        $sql .=';';
        $this->querySql($sql);
    }

    /**
     * EDIT TABLE ATTRS
     * @throws \Throwable
     */
    public function editTableAttrs(){
        $getData = $this->request()->getRequestParam();

        if(!isset($getData['new_name']) || empty(trim($getData['new_name'])))
            $getData['new_name'] = $getData['name'];

        $sql = "ALTER TABLE `{$getData['table_name']}` CHANGE `{$getData['name']}` `{$getData['new_name']}` {$getData['type']} ";
        if(isset($getData['default']))$sql .=" DEFAULT {$getData['default']} ";
        if(isset($getData['comment']))$sql .=" COMMENT '{$getData['comment']}' ";
        if(isset($getData['after']))$sql .=" AFTER `{$getData['after']}` ";

        $sql = "ALTER TABLE `{$getData['table_name']}` ADD COLUMN `{$getData['name']}` {$getData['type']} ";
        if(isset($getData['default']))$sql .=" DEFAULT {$getData['default']} ";
        if(isset($getData['comment']))$sql .=" COMMENT '{$getData['comment']}' ";
        if(isset($getData['after']))$sql .=" AFTER `{$getData['after']}` ";
        $sql .=';';
        $this->querySql($sql);
    }

    /**
     * DEL ATTRS
     * @throws \Throwable
     */
    public function delTableAttrs(){
        $getData = $this->request()->getRequestParam();
        $sql = "ALTER TABLE `{$getData['table_name']}` DROP COLUMN `{$getData['name']}` ;";
        $this->querySql($sql);
    }


    /**
     * SHOW TABLE INDEX
     * @throws \Throwable
     */
    public function showTableIndex(){
        $getData = $this->request()->getRequestParam();
        $sql = "SHOW INDEX FROM {$getData['table_name']}";
        $this->querySql($sql);
    }


}