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
        $this->display();
    }

    public function add_news_act(){
        //接收数据
        $data['cat_name'] = I('post.cat_name');
        $data['cat_id'] = I('post.cat_id');
        $data['keywords'] = I('post.keywords');
        $data['cat_desc'] = I('post.cat_desc');
        print_r($data);

    }


    public function list_arr($array,$pid=0){
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