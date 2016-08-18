<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo 'dasdasd';
        $a = 1;
        $b = $a+$a+$a++;
        echo $b;
    }
}