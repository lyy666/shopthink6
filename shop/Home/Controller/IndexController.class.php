<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if($_SESSION['user_id']){
            $this->user_id=$_SESSION['user_id'];
            $user_id =$_SESSION['user_id'];
            //根据session的user_id得到user_name
            $user = M('users');
            $user_info = $user->where('user_id ='.$user_id)->select();
            //print_r($user_info);
            $this->user_info=$user_info;
        }
        $this->data='';
        $this->display();
    }
}