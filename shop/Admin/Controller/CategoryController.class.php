<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends Controller {
//输出管理员列表


public function index(){
            $cats = D('category')->catTree();
//            print_r($cats);
            $this -> assign('cats',$cats);
            $this -> display();
        }

   public function add(){
            if(IS_POST){
                //分类信息入库
                $data['cat_name'] = I('cat_name');
                $data['parent_id'] = I('parent_id',0,'int');//parent_id通常是整型，所以做个小处理
                $data['cat_desc'] = I('cat_desc');
                $data['unit'] = I('unit');//数量
                $data['is_show'] = I('is_show');
                $data['sort_order'] = I('sort_order');

                $categoryModel = D('category');
                if($categoryModel->create($data)){
                    //验证通过
                    if($categoryModel->add()){
                        //插入成功
                        $this -> success('分类信息添加成功',U('index'),1);
                    }else{
                        //插入失败
                        $this -> error('分类信息添加失败');
                    }
                }else{
                    //验证失败
                    $this -> error($categoryModel->getError());
                }
                return;
            }
            //载入添加分类页面
            //获取所有的分类
            $cats = D('category')->catTree();
            $this -> assign('cats',$cats);
            $this -> display();
        }
	
	//修改分类
        public function edit(){
            $cat_id = I('id');
            if(IS_POST){
                //更新分类
                $data['cat_name'] = I('cat_name');
                $data['parent_id'] = I('parent_id',0,'int');//parent_id通常是整型，所以做个小处理
                $data['cat_desc'] = I('cat_desc');
                $data['unit'] = I('unit');//数量
                $data['is_show'] = I('is_show');
                $data['sort_order'] = I('sort_order');
                $data['cat_id'] = I('cat_id');
                $categoryModel = D('category');

                $ids = $categoryModel->getSubIds($data['cat_id']);
                if(in_array($data['parent_id'],$ids)){
                    $this -> error('抱歉，不能把当前分类及其子分类作为其上级分类');
                }

                if($categoryModel->create($data)){
                    //验证通过
                    if($categoryModel->save()){
                        //插入成功
                        $this -> success('分类信息修改成功',U('index'),1);
                    }else{
                        //插入失败
                        $this -> error('分类信息修改失败');
                    }
                }else{
                    //验证失败
                    $this -> error($categoryModel->getError());
                }
                return;
            }
            $cat = M('category')->find($cat_id);
            $cats = D('category')->catTree();
            $this -> assign('cats',$cats);
            $this -> assign('cat',$cat);
            $this -> display();
        }
	
	
	//删除分类
        public function del(){
            $cat_id = I('id',0,'int');
            $categoryModel = D('category');
            $ids = $categoryModel->getSubIds($cat_id);//这里的目的就是查下有没有子类
            if(count($ids)>1){
                $this ->error("该分类下面还存在子分类，请处理好了再来");
            }
            if(M('category')->delete($cat_id)){
                $this -> success('删除成功',U('index'),1);
            }else{
                $this ->error('删除失败');
            }
        }
	
	
}