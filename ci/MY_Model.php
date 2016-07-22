<?php

class MY_Model extends CI_Model
{
    /**
     * 表名
     * @var string
     */
    protected $table_name;

    /**
     * 主键
     * @var string
     */
    protected $primary_key;

    /**
     * 主键后缀
     * @var string
     */
    protected $primary_key_suffix = '_id';

    /**
     * 表名前缀
     * @var string
     */
    protected $prefix = 't_';

    /**
     * 表名后缀
     * @var string
     */
    protected $suffix = '_0';

    public function __construct()
    {
        parent::__construct();
        
        $this->conn = $this->load->database('default', TRUE);     
   
        // 设置表名
        $this->table_name = $this->init_table_name();
        // 设置主键
        $this->primary_key = $this->init_primary_key();
    }

    /**
     * 获取模型名字
     * @return string
     */
    public function get_model_name()
    {
        return strtolower(substr(get_class($this), 0, -strlen(config_item('model_suffix'))));
    }

    /**
     * 初始化表名
     * @return string
     */
    public function init_table_name()
    {
        $name = $this->get_model_name();
        $this->table_name = $this->prefix.$name.$this->suffix;
        return $this->table_name;
    }

    /**
     * 设置表名
     * @param $table_name
     */
    public function set_table_name($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * 获取表名
     * @return string
     */
    public function get_table_name()
    {
        return $this->table_name;
    }

    /**
     * 初始化设置表名
     */
    public function init_primary_key()
    {
        $name = $this->get_model_name();
        $this->primary_key = $name.$this->primary_key_suffix;
        return $this->primary_key;
    }

    /**
     * 设置主键字段
     * @param $primary_key
     */
    public function set_primary_key($primary_key)
    {
        $this->primary_key = $primary_key;
    }

    /**
     * 获取主键字段
     * @return string
     */
    public function get_primary_key()
    {
        return $this->primary_key;
    }

    /**
     * 查询多条记录
     * @return mixed
     */
    public function get()
    {
        return $this->conn->get($this->table_name)->result_array();
    }

    /**
     * 查询多条记录，不自动设置表名
     * @return mixed
     */
    public function _get()
    {
        return $this->conn->get()->result_array();
    }

    /**
     * 限制查询结果
     * @param $size
     * @param $offset
     * @return mixed
     */
    public function limit($size, $offset)
    {
        $this->conn->limit($size, $offset);
        return $this;
    }

    /**
     * 统计
     * @return mixed
     */
    public function counts()
    {
        return $this->conn->from($this->table_name)->count_all_results();
    }

    /**
     * 执行原生sql语句
     * @param $sql
     * @return array
     */
    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    /**
     * 获取单条
     * @param $query
     * @return mixed
     */
    public function row_array($query)
    {
        return $query->row_array();
    }

    /**
     * 获取多条
     * @param $query
     * @return mixed
     */
    public function result_array($query)
    {
        return $query->result_array();
    }

    /**
     * 查询单条记录
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if (is_array($id)) {
            $this->conn->where($id);
        } else {
            $this->conn->where($this->primary_key, $id);
        }

        return $this->conn->get($this->table_name)->row_array();
    }

    /**
     * 新增
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        $this->conn->insert($this->table_name, $data);
        return $this->conn->insert_id();
    }

    /**
     * 批量新增
     * @param $data
     * @return mixed
     */
    public function insert_batch($data)
    {
        $this->conn->insert_batch($this->table_name, $data);
        return $this->conn->affected_rows();
    }

    /**
     * 更新
     * @param $data
     * @return mixed
     */
    public function update($data)
    {
        $this->conn->update($this->table_name, $data);
        return $this->conn->affected_rows();
    }

    /**
     * 批量更新
     * @param $data
     * @return mixed
     */
    public function update_batch($data)
    {
        $this->conn->update_batch($this->table_name, $data);
        return $this->conn->affected_rows();
    }

    /**
     * 更新
     * @param $data
     * @return mixed
     */
    public function replace($data)
    {
        $this->conn->replace($this->table_name, $data);
        return $this->conn->affected_rows();
    }

    /**
     * 删除
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $this->conn->delete($this->table_name, $where);
        return $this->conn->affected_rows();
    }

    /**
     * 设置查询表名
     * @param $table_name
     * @return mixed
     */
    public function from($table_name)
    {
        $this->conn->from($table_name);
        return $this;
    }

    /**
     * 关联查询
     * @param $table_name
     * @param $on
     * @param $type 连接类型 left，right，outer，inner，left outer 和 right outer
     * @return $this
     */
    public function join($table_name, $on, $type='inner')
    {
        $this->conn->join($table_name, $on, $type);
        return $this;
    }

    /**
     * 条件
     * @param $where
     * @return mixed
     */
    public function where($where)
    {
        $this->conn->where($where);
        return $this;
    }

    /**
     * in条件查询
     * @param $where
     * @return mixed
     */
    public function where_in(array $where)
    {
        foreach($where as $key=>$val) {
            $this->conn->where_in($key, $val);
        }
        return $this;
    }

    /**
     * 查询字段
     * @param $field string
     * @return mixed
     */
    public function select($field)
    {
        $this->conn->select($field);
        return $this;
    }

    /**
     * 排序
     * @param $order
     * @return minxed
     */
    public function order($order)
    {
        $this->conn->order_by($order);
        return $this;
    }

    /**
     * 字段去重
     * @param $field
     * @return mixed
     */
    public function distinct($field)
    {
        $this->conn->distinct($field);
        return $this;
    }

    /**
     * 分组
     * @param $field
     * @return $this
     */
    public function group($field)
    {
        $this->conn->group_by($field);
        return $this;
    }

    /**
     * 设置新增或更新的数据
     * @param $data
     * @return $this
     */
    public function set($data)
    {
        $this->conn->set($data);
        return $this;
    }

    /**
     * 查看最近一次执行的sql语句
     * @return mixed
     */
    public function last_query()
    {
        return $this->conn->last_query();
    }

    /**
     * 清空表记录
     * @return mixed
     */
    public function truncate()
    {
        return $this->conn->truncate($this->table_name);
    }

    /**
     * 聚合函数sum  select sum(field)
     * @param $field
     * @return $this
     */
    public function sum($field)
    {
        $this->conn->select_sum($field);
        return $this;
    }
}