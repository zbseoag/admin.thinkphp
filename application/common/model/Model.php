<?php
namespace app\common\model;

class Model extends \think\Model{

    protected $connection = null;
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    
    
    const STATUS_CREATE = 0;//新建
    const STATUS_AUDITED = 1;//已审核
    const STATUS_CANCELED = -1;//已取消
    const STATUS_DELETED = -2;//已删除
    
    const STATUS_ENABLE = 1; //已启用
    const STATUS_DISABLE = -1;//已禁用
    
    
    protected $search = [];
    
    public $operate = array(
            
        //新建 => 不可对账结算
        self::STATUS_CREATE => array('deny' => ['cancel']),
        //已核审  => 不可修改,不可再审,不可删除
        self::STATUS_AUDITED => array('deny' => ['save', 'audit', 'delete']),
        //已取消  => 不可操作
        self::STATUS_CANCELED => '',
        //已删除  => 不可操作
        self::STATUS_DELETED => '',
            
    );
    
    
    public static function instance($data = []){
        return new static($data);
    }
    
    
    public static function getStatusAttr($value = ''){
        
        $options = array(
            self::STATUS_CREATE   => '新建',
            self::STATUS_AUDITED  => '已审核',
            self::STATUS_CANCELED => '已取消',
            self::STATUS_DELETED  => '已删除',
    
            self::STATUS_ENABLE   => '已启用',
            self::STATUS_DISABLE  => '已禁用',
            
        );
        
        //获取所有选项
        if(func_num_args() == 0) return $options;
        return isset($options[$value])? $options[$value] : $value;
        
    }
    
    
    
    /**
     * 根据$this->seacch 配置,自动生成查询条件
     * @param unknown $where
     */
    public function search($where){
    
        $search = array_keys($where);
        if($this->search) $search = $this->search + array_combine($search, $search);

        //当前表别名
        $table = $this->getAlias();
        foreach($search as $alias => $field){

            $make = '';
            if(stripos($field, '%') !== false){
                $make = $field; $field = str_replace('%', '', $field);
            }
            if(is_numeric($alias)) $alias = $field;
            if(!isset($where[$alias])) continue;
            
            //如果字段本身不带有表别名
            if(!strpos($field, '.') && $table) $field = $table . '.' . $field;
            
            if(empty($make)){
                $condition[$field] = $where[$alias];
            }else{
                $condition[$field] = array('like', str_replace($field, $where[$alias], $make));
            }
           
            
        }

        if(isset($condition)) $this->where($condition);
        
        return $this;
        
    }
    
    
    /**
     * 生成分页
     * @param unknown $paginator
     * @param string $style
     * @return string[]|NULL[]
     */
    public function getPaginator(&$record, $style = 'default'){
    
        $options = array(
             'default' => "每页<b> {$record->listRows()}</b>条, 当前 <b>{$record->currentPage()}/{$record->lastPage()}</b>页, 共 <b>{$record->total()}</b>条记录",
        );
    
        if(!isset($options[$style])) $style = 'default';
    
        $paginator = [
             'info' => $options[$style],
             'page' => $record->render(),
        ];
    
        return $paginator;
    
    }

    
    /**
     * 可操作性检测
     * @param array $operates 当前操作基于配置
     * @param int|string $status 状态
     * @param string $toButton 是否作用于按钮
     * @param array $append 附加配置
     * @return array|boolean
     * 示例: operate(array(10=>['deny'=>array('settlement', 'cancel')]), 10, true, array('allow'=>['cancel']));
     */
    public function operate($operates, $status, $toButton = false, $append = array()){
        
        $is_array = is_array($operates)? true : false;
        $values = !empty($toButton)? array('true' => '', 'false' => 'disabled') : array('true' => 1, 'false' => 0);
        
        if(!is_array($operates)) $operates = [$operates];
        foreach($operates as $operate){
            
            if(isset($this->operate[$status])){
                
                $rule = $this->operate[$status];
                if(isset($rule['allow'])){
                    $return[$operate] = in_array($operate, $rule['allow'])? $values['true'] :  $values['false'];
                }else if(isset($rule['deny'])){
                    $return[$operate]= in_array($operate, $rule['deny'])?  $values['false'] : $values['true'];
                    
                }else{
                    $return[$operate] = $values['false'];
                }
            }else if($status == null){
                if($operate == 'save'){
                    $return[$operate] = $values['true'];
                }else{
                    $return[$operate] = $values['false'];
                }
                
            }else{
                $return[$operate] = $values['true'];
            }
            
        }
        
        foreach($append as  $access => $operates){
            foreach($operates as $operate){
                if(!isset($return[$operate])) continue;
                $return[$operate] = ($access == 'allow')? $values['true'] : $values['false'];
            }
        }
        
        return $is_array? $return : current($return);
    }


    
    /**
     * 设置/获取取表别名
     * @param string $alias
     * @return $this|string
     */
    public function getAlias($table = ''){
        
        $alias = $this->getQuery()->getOptions('alias');
        $table || $table = $this->getTable();
        
        if(isset($alias[$table]) && !empty($alias[$table])){
            return $alias[$table];
        }else{
            return '';
        }
        
    }



    
}