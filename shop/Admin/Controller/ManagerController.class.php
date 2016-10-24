<?php
/**
 * Created by PhpStorm.
 * User: lk
 * Date: 2016/4/21
 * Time: 9:30
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;

class ManagerController extends Controller{
    function login(){
        $this->display();
    }
    function login_act(){

        //接收数据
        $admin_user = I('post.admin_user');
        $admin_psd = I('post.admin_psd');
        $verify = I('post.captcha');
        //对数据进行处理
        $admin_user = !empty($admin_user) ? $admin_user :'';
        $admin_psd = !empty($admin_psd) ? $admin_psd :'';
        $verify = !empty($verify) ? $verify :'';
        //判断是否为空
        if($admin_user && $admin_psd && $verify) {
            if ($this->check_verify($verify)) {
                $admin = M('admin_user');
                $res = $admin->where("user_name ='" . $admin_user . "' and password='" . $admin_psd . "'")->find();
                if ($res) {
                    $_SESSION['user_name'] = $res['user_name'];
                    $_SESSION['user_id'] = $res['user_id'];
                    $data['reg_time'] = time();
                    $data['last_ip'] = get_client_ip(0,false);
                    $u =  M('admin_user');
                    //保存登录的时间
                    $rr = $u->where('user_id ='.$_SESSION['user_id'])->save($data);
                    $a = U("index/index");
                    $this->success('登录成功', 'http://127.0.0.1'.$a);

                } else {
                    $aa = U("manager/login");
                    $this->error('登录失败', 'http://127.0.0.1'.$aa);
                }
            }  else{
                $aa = U("manager/login");
                $this->error('验证码错误', 'http://127.0.0.1'.$aa);
            }
        } else {
           $aa = U("manager/login");
           $this->error('用户名密码验证码不能为空', 'http://127.0.0.1'.$aa);
        }
    }
    function verify(){
        $config =    array(
            'fontSize'    =>    20,
            'length'      =>     4,
            'useNoise'    =>    true,

        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }
    //检查验证码
    function check_verify($code, $id = ''){
        $config = array(
            'reset' => false
        );
        $verify = new \Think\Verify($config);
        return $verify->check($code, $id);
    }
}