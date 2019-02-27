<?php

include 'MyMongo.php';

class MongApi{
	
	private $mongo; // 链接对象

	/**
	 * 初始化数据
	 */
	public function __construct()
	{
		$info['host'] = '127.0.0.1';
		$info['prot'] = '27017';
		$info['dbname'] = 'test';
		$info['set'] = 'new';
		$this->mongo = MyMongo::get_init($info);
	}

	/**
	 * 添加数据
	 */
	public function add()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$title = trim($_POST['title']);
			$coutent = trim($_POST['coutent']);
			$time = time();
			if(!empty($title) && !empty($coutent)){

				$res = $this->mongo->insert(['title'=>$title, 'coutent'=>$coutent, 'time'=>$time]);

				if(!$res){
					$data = ['code'=>1, 'msg'=>'添加失败'];
				}else{
					$data = ['code'=>0, 'msg'=>'添加成功'];
				} 

				echo json_encode($data);
			}
		}
	}

	/**
	 * 更新数据
	 */
	public function updata()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(($_id = $this->objID('POST')) != false){
				$title = trim($_POST['title']);
		      	$coutent = trim($_POST['coutent']);
		      	$where = ['_id'=> $_id];
		     	$data = ['title'=>$title,'coutent'=>$coutent];
				$res = $this->mongo->update($where,$data);
				$data = ['code' => 0,'msg' => '更新成功'];
			}else{
				$data = ['code' => 1,'msg' => '更新失败'];
			}
		}else{
			$data = ['code' => 1,'msg' => '更新失败'];
		}
		echo json_encode($data);
	}

	/**
	 * 删除数据
	 */
	public function del()
	{
		if(($_id = $this->objID()) != false){
			$where = ['_id' => $_id];
			$res = $this->mongo->delete($where);
			$data = ['code' => 0,'msg' => '删除成功'];
		}else{
			$data = ['code' => 1,'msg' => '删除失败'];
		}

		echo json_encode($data);
	}

	/**
	 * 获取数据
	 */
	public function getData()
	{	
		// 获取参数
		
		$page = (int)trim(@$_GET['page']) ?? 1;
		
		// 验证参数
		if(($_id = $this->objID()) != false){

			$where = ['_id' => $_id];
			$res = $this->mongo->query($where);
		}else{
			$res = $this->mongo->query();
		}
		// 返回数据
		echo $this->dataSplicing($res,$page); 
	}

	/**
	 * 模糊查询
	 */
	public function query()
	{	
		$title = trim(@$_GET['title']);
		$page = (int)trim(@$_GET['page']) ?? 1;
		if(empty($title)){
			die;
		}
		$where = ['title' => ['$regex' =>$title.'+']];
		$res = $this->mongo->query($where);

		echo $this->dataSplicing($res,$page); 
	}

	/**
	 * 数据拼接
	 * @return json
	 */
	private function dataSplicing($res, $page)
	{
		// 获取总条数
		$count = count((array)$res);

		if($count > 0){
			foreach ($res as $key => $value) {
				$data[$key]['id'] = $key+1;
				$data[$key]['_id'] = $value->_id;
				$data[$key]['title'] = $value->title;
				$data[$key]['coutent'] = $value->coutent;
				$data[$key]['time'] = date('Y-m-d H:i:s', $value->time);
			}

			$data = $this->pages($data, $page);
			$data = ['code'=>0, 'mag'=>'', 'count'=>$count ,'data'=>$data];
			return json_encode($data);
		}

		$data = ['code'=>1,'msg'=>'查询失败','count'=>0, 'data'=>[]];
		return json_encode($data);
	}

	/**
	 * 分页
	 * @param  array  $data  
	 * @param  int    $page  
	 * @param  int    $limit 
	 * @return object     
	 */
	private function pages($data, $page, $limit=10)
	{
		return array_slice($data,($page-1)*$limit,$limit);
	}

	/**
	 * _id转换为对象
	 * @param  string $_id 
	 * @return object      
	 */
	private function objID($method = 'GET')
	{	
		 
		if($method == 'POST'){
			$_id = trim(@$_POST['_id']);
		}else{
			$_id = trim(@$_GET['_id']);
		}
		if(strlen($_id) == 24){
			return new MongoDB\BSON\ObjectId($_id);
		}

		return false;
	}

}
$obj = trim($_GET['pram']);
if(empty($obj)){
	echo '请求失败';die;
}
$m = new MongApi();
echo $m->$obj();