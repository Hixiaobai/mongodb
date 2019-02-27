<?php

class MyMongo{

	private $manager;
	private $bulk;
	private static $mongo = null;
	private $dbname = '';

	/**
	 * 初始化数据
	 * @param array $info 
	 */
	private function __construct($info)
	{
		$host = $info['host'] ?? '127.0.0.1';      // 地址
		$port = $info['port'] ??  '27017';         // 端口号
		$dbname = $info['dbname'] ?? 'test';       // 数据库名
		$set = $info['set'] ?? 'new';              // 集合名
		$this->dbname = $dbname . '.' . $set;      
		$this->manager = new MongoDB\Driver\Manager("mongodb://$host:$port"); 
		$this->bulk = new MongoDB\Driver\BulkWrite;
	}
	/**
	 * 实例化对象
	 * @param  array  $info 一位数组列：$array = ['host'=>'127.0.0.1','port'=>27017];
	 * @return object       返回实例化对象
	 */
	public static function get_init($info=[])
	{
		if(!self::$mongo instanceof self)
		{
			self::$mongo = new MyMongo($info);
		}
		return self::$mongo;
	}

	/**
	 * 插入数据
	 * @param  Array $data    一维数组或二位数组  
	 * @return object         返回对象
	 */
	public function insert($data)
	{	

		if(count($data) == count($data, 1)){
			$this->bulk->insert($data);
		}else{
			foreach ($data as $value) {
				$this->bulk->insert($value);
			}
		}
		return $this->manager->executeBulkWrite($this->dbname, $this->bulk);
	}

	/**
	 * 查询数据
	 * @param  array  $where   
	 * @param  array  $options 
	 * @return object          
	 */
	public function query($where=[],$options=[])
	{
		$query = new MongoDB\Driver\Query($where, $options);
		return $cursor = $this->manager->executeQuery($this->dbname, $query)->toArray();
	}

	/**
	 * 更新数据
	 * @param  array   $where  条件
	 * @param  array   $data   内容
	 * @param  boolean $multi  不存在是否插入
	 * @param  boolean $upsert 是否更新全部
	 * @return object          对象
	 */
	public function update($where=[],$data=[],$multi=false,$upsert=false)
	{
		$this->bulk->update(
		    $where,
		    ['$set' => $data],
		    ['multi' => $multi, 'upsert' => $upsert]
		);

		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		return  $this->manager->executeBulkWrite($this->dbname, $this->bulk, $writeConcern);
	}

	/**
	 * 删除数据
	 * @param  array   $where 条件
	 * @param  integer $limit=1删除一条，$Limit=0删除全部
	 * @return object        
	 */
	public function delete($where=[],$limit=1)
	{

		$this->bulk->delete($where, ['limit' => $limit]);
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		return $this->manager->executeBulkWrite($this->dbname, $this->bulk, $writeConcern);
	}

	// 私有化克隆方法
	private function __clone(){}
}

