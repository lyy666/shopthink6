<?php
/**
 * Created by PhpStorm.
 * User: cc
 * Date: 2016/4/21
 * Time: 15:56
 */
namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller{
    function index(){
        if($_SESSION['user_id']) {
            $this->display();
        }
    }
    function head(){
        if($_SESSION['user_id']) {
            $this->user_id = $_SESSION['user_id'];
            $this->user_name = $_SESSION['user_name'];
        }
            $this->display();
    }
    function left(){
        $this->display();
    }
    function right(){
        //�ж��Ƿ���user_id��Ҫ���о͸���user_id�õ���̨��¼�û��Ļ�������Ϣ
        $admin_user = M('admin_user');
        $admin_info = $admin_user->where('user_id='.$_SESSION['user_id'])->find();
        $admin_info['reg_time'] = date("Y-m-d H:i:s",$admin_info['reg_time']);
        $ip = get_client_ip(0,false);
        $this->ip=$ip;
        $this->admin_info=$admin_info;
        $this->display();
    }
    function get_profile(){

    }
}