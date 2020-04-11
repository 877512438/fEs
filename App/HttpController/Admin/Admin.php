<?php


namespace App\HttpController\Admin;

use EasySwoole\Http\AbstractInterface\Controller;

class Admin extends Controller
{

    protected $loginMsg = 'ok success';
    protected $authMsg = 'ok success';
    protected $userInfo = null;
    protected $userRule = [];

    protected function onRequest(?string $action): ?bool
    {

//        if($this->checkLogin()==false){
//            $this->writeJson(200,[],$this->loginMsg);
//            return false;
//        }
//
//
//        if($this->checkAuthRule()==false){
//            $this->writeJson(200,[],$this->authMsg);
//            return false;
//        }

        return true;
    }


    /**
     * CKECK LOGIN
     * @return bool
     */
    protected function checkLogin(){
        $getData = $this->request()->getHeaders();
        if(!isset($getData['user_token'])){
            $this->loginMsg = 'not login';
            return false;
        }

    }


    /**
     * CHECK Rule
     * @return bool
     */
    public function checkAuthRule(){
        $serverInfo = $this->request()->getServerParams();

        if(!in_array($serverInfo['path_info'],$this->userRule)){
            $this->authMsg = 'not power';
            return false;
        }
    }



    public function index(){}


    protected function writeJson($statusCode = 200, $result = null, $msg = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "code" => $statusCode,
                "data" => $result,
                "msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            return true;
        } else {
            return false;
        }
    }

}