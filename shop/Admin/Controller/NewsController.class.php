<?php
/**
 * Created by PhpStorm.
 * User: cc
 * Date: 2016/4/21
 * Time: 15:56
 */
namespace Admin\Controller;
use Think\Controller;

class NewsController extends Controller{
    public function new_cat_list(){
        //获得新闻分类的列表
        $arcrticle = M('article_cat');
        $list = $arcrticle->select();
        $this->list = $list;
        $this->display();
    }
    public function add_news(){
        $article_cat = M('article_cat');
        $select1 = $article_cat->select();
        $select = $this->list_arr($select1);

        print_r($select);
        $this->display();
    }
    function list_arr($array,$pid=0){
        $arr=array();
        foreach($array as $v){
            if($v['parent_id']==$pid){
                $arr[]=$v;
                $arr=array_merge($arr,$this->list_arr($array,$v['cat_id']));
            }
        }
        return $arr;
}
}