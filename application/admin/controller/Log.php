<?php
namespace app\admin\controller;

use think\Loader;
use app\common\helper\Form;

class RecordController extends Controller {
    
    
    public function _initialize(){
        
        parent::_initialize();
        
        $this->controller = $this->request->param('model');
        $this->action = $this->request->action();
        $this->model = Loader::model($this->controller);
        
    }
    
    
    public function index(){
        
        $where = Form::get();
        $record = $this->model->search($where)->paginate(20, false, ['query'=>request()->get()]);
        $paginator = $this->model->getPaginator($record);
        
        //$this->assign('thead', $this->model->thead);
        //$this->assign('record', $record);
        $this->assign('paginator', $paginator);
        
        return $this->fetch($this->controller . '/' . $this->action);
        
    }
    
    
    public function edit(){
        
        try{
            $id = $this->request->get('id');
            $record = empty($id)? [] : $this->model->where('id', $id)->find()->getData();
            
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        
        $this->assign('title', $record? '编辑记录' : '新建记录');
        $this->assign('record', json_encode($record, true));
        
        return $this->fetch($this->controller . '/' . $this->action);
    }
    
    
    
    public function save(){
        
        try{
            
            $data = $this->request->post();
            $this->model->allowField(true)->isUpdate($data['id'])->data($data)->save();
        }catch(Exception $e){
            $this->error(Runtime::instance()->error($e));
        }
        
        $this->success('保存成功');
        
    }
    
    
    public function delete(){
        
        try{
            
            $id = $this->request->get('id');
            if(empty($id)) throw new Exception('缺少必要参数');
    
            $this->model->destroy($id);
            
        }catch(Exception $e){
            $this->error(Runtime::instance()->error($e));
        }
        
        $this->success('删除成功');
        
    }
    
    
    
}
