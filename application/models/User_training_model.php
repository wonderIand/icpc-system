<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 个人训练管理
 */
class User_training_model extends CI_Model {

	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/


	/**
	 * 添加记录
	 */
	public function register($form) 
	{	

		//config
		$members = array('Uusername', 'UTtitle', 'UTdate', 'UTplace');
		$members_contest = array('UTid', 'UTaddress', 'UTproblemset');
		$members_article = array('UTid', 'UTarticle');

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
		$training = $this->db->where($where)
			->get('user_training')
			->result_array()[0];
		$contest = $this->db->where($where)
			->get('user_training_contest')
			->result_array()[0];
		$article = $this->db->where($where)
			->get('user_training_article')
			->result_array()[0];

		//combine
		foreach ($contest as $key => $value) {
			$training[$key] = $value;
		}
		foreach ($article as $key => $value) {
			$training[$key] = $value;
		}
		$training['UTproblemset'] = explode('#', $training['UTproblemset']);

		//check editable
		if (isset($form['Utoken']))
		{		
			$result = $this->db->select('Uusername')
				->where('Utoken', $form['Utoken'])
				->get('user')
				->result_array()[0];
			$training['editable'] = $result['Uusername'] == $training['Uusername'];
		}
		else 
		{
			$training['editable'] = FALSE;
		}


		//get
		return $training;

	}


	/**
	 * 修改记录
	 */
	public function update($form) 
	{	

		//config
		$members = array('UTtitle', 'UTdate');
		$members_contest = array('UTaddress', 'UTproblemset');

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
			throw new Exception('不存在的文章id', 0);
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
		$author = $this->db->select('Uusername')
			->where('UTid', $form['UTid'])
			->get('user_training')
			->result_array()[0]['Uusername'];
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];
		if ($author != $user)
		{
			throw new Exception('只有作者可以删除文章', 402);
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
		$members = array('page_size', 'page', 'page_max', 'editable', 'data');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
			$user = $this->db->where('Utoken', $form['Utoken'])->get('user')->result_array()[0]['Uusername'];
		}

		//select training
       	$where = array('Uusername' => $form['Uusername']);
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
			$count = $this->db->where($where)->count_all_results('user_training');
	        $ret['page_max'] = (int)(($count - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$trainings = $this->db->where($where)->order_by('UTdate')->get('user_training')->result_array();

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

		//return
		$ret['editable'] = isset($user) && $user == $form['Uusername'];
		$ret['data'] = $trainings;
		return filter($ret, $members);

	}

}
