<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 个人训练管理
 */
class User_training_model extends CI_Model {

	/**********************************************************************************************
	 * 私有工具集
	 **********************************************************************************************/


	/**
	 * 增加访问量
	 */
	private function add_view($where) 
	{
		//where = $Uusername + $Utid
		if ( ! $this->db->where($where)
				->get('user_training_view')
				->result_array())
		{
			$this->db->insert('user_training_view',$where);
			$view = $this->db->select('UTview')
				->where(array('UTid' => $utid))
				->get('user_training')
				->result_array()[0]['UTview'];
			$this->db->update('user_training', array('UTview' => $view + 1), array('UTid' => $utid));	
			return true;
		}
		return false;
	}


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/


	/**
	 * 点赞
	 */
	public function upvote($form)
	{
		//check token
		$this->load->model('User_model','user');
		$this->user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		//check UTid
		$result = $this->db->select('UTup')
			->where(array('UTid' => $form['UTid']))
			->get('user_training')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('文章不存在');
		}
		$up = $result[0]['UTup'];

		//check user_training_up
		$where = array('Uusername' => $username, 'UTid' => $form['UTid']);
		if ($this->db->where($where)->get('user_training_up')->result_array())
		{
			throw new Exception('不能重复点赞', 402);
		}

		//DO upvote
		$this->db->insert('user_training_up', $where);
		$this->db->update('user_training', array('UTup' => $up + 1), array('UTid' => $form['UTid']));

	} 


	/**
	 * 添加记录
	 */
	public function register($form) 
	{	

		//config
		$members = array('Uusername', 'UTdate', 'UTtitle', 'UTregister', 'UTplace');
		$members_contest = array('UTid', 'UTaddress', 'UTproblemset');
		$members_article = array('UTid', 'UTarticle');

		//add 8:00
		$form['UTdate'] = date('Y-m-d H:i:s', strtotime($form['UTdate']));

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//get user
		$form['Uusername'] = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//register
		$this->db->insert('user_training', filter($form, $members));
		$form['UTid'] = $this->db->insert_id();
		$this->db->insert('user_training_contest', filter($form, $members_contest));
		$this->db->insert('user_training_article', filter($form, $members_article));

	}


	/**
	 * 获取个人训练记录
	 */
	public function get($form)
	{

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);			
		}

		//get training
		$where = array('UTid' => $form['UTid']);
		$result = $this->db->where($where)
			->get('user_training')
			->result_array();
		if ( ! $result) 
		{
			throw new Exception('记录不存在');
		}
		$training = $result[0];
		$contest = $this->db->where($where)
			->get('user_training_contest')
			->result_array()[0];

		//combine
		foreach ($contest as $key => $value) {
			$training[$key] = $value;
		}
		$training['UTproblemset'] = explode('#', $training['UTproblemset']);

		//check editable
		$training['editable'] = FALSE;
		if (isset($form['Utoken']))
		{		
			$result = $this->db->select('Uusername')
				->where('Utoken', $form['Utoken'])
				->get('user')
				->result_array()[0];
			$username = $result['Uusername'];
			$training['editable'] = $username == $training['Uusername'];
		}

		//view & get
		if (isset($username) && $this->add_view($username, $form['UTid']))
		{
			$training['UTview'] = (string)($training['UTview'] + 1);
		}

		//check upvoteEnable
		$training['upvoteEnable'] = FALSE;
		if (isset($form['Utoken']))
		{
			$result = $this->db->where(array('Uusername' => $username, 'UTid' => $form['UTid']))
				->get('user_training_up')
				->result_array();
			if ( ! $result) 
			{				
				$training['upvoteEnable'] = TRUE;
			}
		}


		//get
		return $training;

	}


	/**
	 * 获取个人训练记录-文章
	 */
	public function get_article($form)
	{

		//config
		$members_more = array('UTup', 'UTview');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);			
		}

		//get training
		$where = array('UTid' => $form['UTid']);
		$result = $this->db->select('Uusername')
			->where($where)
			->get('user_training')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('文章不存在');
		}
		$author = $result[0]['Uusername'];
		$article = $this->db->where($where)
			->get('user_training_article')
			->result_array()[0];
		$more_info = $this->db->select($members_more)
			->where($where)
			->get('user_training')
			->result_array()[0];

		//combine
		foreach ($members_more as $member) {
			$article[$member] = $more_info[$member];
		}

		//check editable
		$article['editable'] = FALSE;
		if (isset($form['Utoken']))
		{		
			$result = $this->db->select('Uusername')
				->where('Utoken', $form['Utoken'])
				->get('user')
				->result_array()[0];
			$username = $result['Uusername'];
			$article['editable'] = $username == $author;
		}

		//check upvoteEnable
		$article['upvoteEnable'] = FALSE;
		if (isset($form['Utoken']))
		{
			$result = $this->db->where(array('Uusername' => $username, 'UTid' => $form['UTid']))
				->get('user_training_up')
				->result_array();
			if ( ! $result) 
			{				
				$article['upvoteEnable'] = TRUE;
			}
		}



		//add_view	
		if (isset($form['Utoken']))
		{
			$data = array('Uusername' => $username, 'UTid' => $form['UTid']);
			$this->add_view($data);	
		}
		//return article
		return $article;

	}


	/**
	 * 修改记录
	 */
	public function update($form) 
	{	

		//config
		$members = array('UTdate', 'UTtitle');
		$members_contest = array('UTaddress', 'UTproblemset');

		//add 8:00
		$form['UTdate'] = date('Y-m-d H:i:s', strtotime($form['UTdate']));

		//check token & get user
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//check UTid & get author
		$result = $this->db->select('Uusername')
			->where('UTid', $form['UTid'])
			->get('user_training')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('不存在的训练id', 0);
		}
		$author = $result ? $result[0]['Uusername'] : NULL;

		//check editable
		if ($author != $user)
		{
			throw new Exception('只有作者可以修改训练记录', 402);
		}

		//update
		$where = array('UTid' => $form['UTid']);
		$this->db->update('user_training', filter($form, $members), $where);
		$this->db->update('user_training_contest', filter($form, $members_contest), $where);

	}


	/**
	 * 修改记录-文章
	 */
	public function update_article($form) 
	{	

		//config
		$members = array('UTarticle');

		//check token & get user
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//check UTid & get author
		$result = $this->db->select('Uusername')
			->where('UTid', $form['UTid'])
			->get('user_training')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('不存在的文章id');
		}
		$author = $result ? $result[0]['Uusername'] : NULL;
		
		//check editable
		if ($author != $user)
		{
			throw new Exception('只有作者可以修改文章', 402);
		}

		//update
		$where = array('UTid' => $form['UTid']);
		$this->db->update('user_training_article', filter($form, $members), $where);

	}


	/**
	 * 删除记录
	 */
	public function delete($form)
	{

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//check editable
		$result = $this->db->select('Uusername')
			->where('UTid', $form['UTid'])
			->get('user_training')
			->result_array();
		if ( ! $result) 
		{
			throw new Exception('记录不存在');
		}
		$author = $result[0]['Uusername'];
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];
		if ($author != $user)
		{
			throw new Exception('只有作者可以删除记录', 402);
		}	

		//delete
		$where = array('UTid' => $form['UTid']);
		$this->db->delete('user_training', $where);
		$this->db->delete('user_training_contest', $where);
		$this->db->delete('user_training_article', $where);

	}


	/**
	 * 获取个人训练列表
	 */
	public function get_list($form)
	{

		//config
		$members = array('total', 'page_size', 'page', 'page_max', 'editable', 'data');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
			$user = $this->db->where('Utoken', $form['Utoken'])->get('user')->result_array()[0]['Uusername'];
		}

		//select training
       	$where = array('Uusername' => $form['Uusername']);
       	$ret['total'] = $this->db->where($where)->count_all_results('user_training');
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
	        $ret['page_max'] = (int)(($ret['total'] - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$trainings = $this->db->where($where)->order_by('UTdate','DESC')->get('user_training')->result_array();

        if ($trainings)
		{
			foreach ($trainings as $key_trainings => $training) 
			{
		        //select training_contest
				$contest = $this->db->where('UTid', $training['UTid'])
					->get('user_training_contest')
					->result_array()[0];
				foreach ($contest as $key => $value) 
				{
					$trainings[$key_trainings][$key] = $value;
				}
				//explode problemset
				$trainings[$key_trainings]['UTproblemset'] = explode('#', $trainings[$key_trainings]['UTproblemset']);
			}
		}

		//check upvoteEnable
		foreach ($trainings as $key => $training) {
			$trainings[$key]['upvoteEnable'] = FALSE;
			if (isset($form['Utoken']))
			{
				$result = $this->db->where(array('Uusername' => $user, 'UTid' => $training['UTid']))
					->get('user_training_up')
					->result_array();
				if ( ! $result)
				{
					$trainings[$key]['upvoteEnable'] = TRUE;
				}
			}
		}

		//return
		$ret['editable'] = isset($user) && $user == $form['Uusername'];
		$ret['data'] = $trainings;
		return filter($ret, $members);

	}

}
