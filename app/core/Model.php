<?php
// app/core/Model.php
class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
        
        if (!$this->table) {
            $className = get_class($this);
            $className = substr($className, strrpos($className, '\\') + 1);
            $this->table = strtolower($className) . 's';
        }
    }
    
    public function all($columns = ['*']) {
        $columns = implode(', ', $columns);
        $sql = "SELECT {$columns} FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    public function find($id, $columns = ['*']) {
        $columns = implode(', ', $columns);
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, array('id' => $id));
    }
    
    public function where($conditions, $params = [], $columns = ['*']) {
        $columns = implode(', ', $columns);
        $whereParts = array();
        foreach (array_keys($conditions) as $col) {
            $whereParts[] = "{$col} = :{$col}";
        }
        $whereClause = implode(' AND ', $whereParts);
        
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$whereClause}";
        return $this->db->fetchAll($sql, $conditions);
    }
    
    public function create($data) {
        $filteredData = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $filteredData[$key] = $value;
            }
        }
        return $this->db->insert($this->table, $filteredData);
    }
    
    public function updateModel($id, $data) {
        $filteredData = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $filteredData[$key] = $value;
            }
        }
        return $this->db->update($this->table, $filteredData, "{$this->primaryKey} = :id", array('id' => $id));
    }
    
    public function delete($id) {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", array('id' => $id));
    }
    
    public function paginate($page = 1, $perPage = 15, $columns = ['*']) {
        $offset = ($page - 1) * $perPage;
        $columns = implode(', ', $columns);
        
        $sql = "SELECT {$columns} FROM {$this->table} LIMIT {$perPage} OFFSET {$offset}";
        return $this->db->fetchAll($sql);
    }
}