<?php


namespace App\HttpController\Admin;

use EasySwoole\Http\AbstractInterface\Controller;

class Admin extends Controller
{

    protected function onRequest(?string $action): ?bool
    {
        var_dump('123456789');
        var_dump($this->request()->getServerParams());
        $this->writeJson(200,['a'=>$action],'no login');
        return false;
    }

    public function index(){

        $this->response()->write('admin/index');

    }


}