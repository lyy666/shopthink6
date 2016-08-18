<?php
/**
 * Created by PhpStorm.
 * User: lk
 * Date: 2016/4/19
 * Time: 23:14
 */
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
header("content-type:text/html;charset=utf-8");
class UserController extends Controller {
    public function login(){
        $this->url='login';
        $this->data='用户中心';
        $this->display();
    }
    public function _empty(){
        echo '404页面';
    }
    public function act_login(){
        //首先判断是否是以post的形式传值的
        if(IS_POST) {
            //接收数据用thinkphp里面自带的I方法接收数据
            $user =  I("post.username");
            $username = !empty($user) ? $user : '';
            $pas = !empty($_POST['password']) ? md5($_POST['password']) : '';
            $verify1 = I('post.user_verify','','trim');
            $verify = !empty($verify1)? $verify1:'';
            //判断用户名和密码是否为空
            if ($username && $pas && $verify) {
              if($this->check_verify($verify)) {
                  $users = M('users');
                  $res = $users->where("user_name ='" . $username . "' and password='" . $pas . "'")->find();
                  if ($res) {
                      $_SESSION['user_id'] = $res['user_id'];
                      $aa = U('index/index');
                      $this->success('登录成功', 'http://127.0.0.1' . $aa);
                  } else {
                      $a = U('user/login');
                      $this->error('登录失败', 'http://127.0.0.1' . $a);
                  }
              }else{
                  $a = U('user/login');
                  $this->error('请输入正确的验证码', 'http://127.0.0.1' . $a);
              }
            } else {
                $url = U('user/login');
                $this->error('用户名或密码或验证码不能为空', 'http://127.0.0.1' . $url);
            }
        }else{
            $this->error('非法请求','http://127.0.0.1'.U('user/login'));
        }
    }

    //登录后退出
    public function login_out(){
            session_unset();
            session_destroy();
            cookie(null);
        //判断是否还有user_id后再跳转
        if(!$_SESSION['user_id']){
            $aa = U('index/index');
            $this->success('成功','http://127.0.0.1'.$aa);
        }else{
            $aa = U('user/login');
            $this->error('失败','http://127.0.0.1'.$aa);
        }
    }
    //注册的页面
    public function register(){
        //向ur_here页面传值
        $this->url='register';
        $this->data='用户注册中心';
        //显示注册页面
        $this->display();
    }
    //注册处理页面
    public function register_act(){
        //接收数据
        $user = $_POST['User'];
        //print_r($_POST['User']);
        //接收数据并做判断
        $user['user_name'] = !empty($_POST['User']['user_name'])?$_POST['User']['user_name']:'';
        $user['password'] =!empty($_POST['User']['password'])?$_POST['User']['password']:'';
        $user['password2'] =!empty($_POST['User']['password2'])?$_POST['User']['password2']:'';
        $user['user_email'] =!empty($_POST['User']['user_email'])?$_POST['User']['user_email']:'';
        $user['user_qq'] =!empty($_POST['User']['user_qq'])?$_POST['User']['user_qq']:'';
        $user['user_tel'] =!empty($_POST['User']['user_tel'])?$_POST['User']['user_tel']:'';
        $user['user_sex'] =!empty($_POST['User']['user_sex'])?$_POST['User']['user_sex']:'';
        //遇到多重数组的情况下，就将其转换成字符串在存入数组中
        $user['user_hobby'] = !empty($_POST['User']['user_hobby'])?implode(',',$_POST['User']['user_hobby']):'';
        $user['user_xueli'] =!empty($_POST['User']['user_xueli'])?$_POST['User']['user_xueli']:'';
        $user['user_introduce'] =!empty($_POST['User']['user_introduce'])?$_POST['User']['user_introduce']:'';
        $user['reg_time'] = time();
        $user['user_verify'] =I ( "post.user_verify", "", "trim" );

        if(!empty($user['user_name'])){
            $users = M('users');
            $res = $users->where("user_name ='".$user['user_name']."'")->find();
            if(empty($res)) {
                if(!empty($user['password'])){
                    if(!empty($user['password2'])){
                        if($user['password'] == $user['password2']){
                            $user['password'] = md5($user['password']);
                            if($this->check_verify($user['user_verify'])){
                                $users = M('users');
                                $res = $users->data($user)->add();
                                if($res){
                                    $aa = U('user/login');
                                    $this->success('注册成功','http://127.0.0.1'.$aa);
                                }else{
                                    $aa = U('user/register');
                                    $this->error('注册失败','http://127.0.0.1'.$aa);
                                }
                            }else{
                                $aa = U('user/register');
                                $this->error('请输入正确的验证码','http://127.0.0.1'.$aa);
                            }
                        }else{
                            $aa = U('user/register');
                            $this->error('两次密码不一致','http://127.0.0.1'.$aa);
                        }
                    }else{
                        $aa = U('user/register');
                        $this->error('确认密码不能为空','http://127.0.0.1'.$aa);
                    }
                }else{
                    $aa = U('user/register');
                    $this->error('密码不能为空','http://127.0.0.1'.$aa);
                }
            }else{
                $aa = U('user/register');
                $this->error('用户名已经存在','http://127.0.0.1'.$aa);
            }
        }else{
            $aa = U('user/register');
            $this->error('用户名不能为空','http://127.0.0.1'.$aa);
        }

    }
    //验证码的使用直接的调用
    public function Verify(){
        $config =    array(
            'fontSize'    =>    30,
            'length'      =>     4,
            'useNoise'    =>    true,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
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


