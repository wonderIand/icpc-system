<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 用户管理
 */
class User_model extends CI_Model {

	/*****************************************************************************************************
	 * 私有工具集
	 *****************************************************************************************************/


	/**
	 * 生成一个未被占用的Utoken
	 */
	private function create_token()
	{
		$this->load->helper('string');
		$token=random_string('alnum',30);
		while ($this->db->where(array('Utoken'=>$token))
			->get('user')
			->result_array());
		{
			$token=random_string('alnum',30);
		}
		return $token;
	}


	/**
	 * 检测时间差
	 */
	private function is_timeout($last_visit)
	{
		$this->load->helper('date');
		$pre_unix = human_to_unix($last_visit);
		$now_unix = time();
		return $now_unix - $pre_unix > 600;
	}


	/**********************************************************************************************
	 * 公开工具集
	 **********************************************************************************************/


	/**
	 * 检测凭据
	 */
	public function check_token($token) 
	{

		//不存在
		$where = array('Utoken' => $token);
		if ( ! $result = $this->db->select('Ulast_visit')
			->where(array('Utoken' => $token))
			->get('user')
			->result_array())
		{
			throw new Exception('不存在的凭据', 401);
		}

		//超时
		$user = $result[0];
		if ($this->is_timeout($user['Ulast_visit']))
		{
			throw new Exception('凭据超时', 401);
		}

		//刷新时间
		$new_data = array('Ulast_visit' => date('Y-m-d H:i:s',time()));
		$this->db->update('user', $new_data, $where);
	
	}


	/**********************************************************************************************
	 * 业务接口
	 **********************************************************************************************/


	/**
	 * 注册
	 */
	public function post($form) 
	{
		//config
		$members_user = array('Uusername', 'Utoken', 'Upassword');
		$members_info = array('Uusername', 'Unickname', 'Urealname');

		//check Uusername
		$where = array('Uusername' => $form['Uusername']);
		if ( $result = $this->db->select('Uusername')
			->where(array('Uusername' => $form['Uusername']))
			->get('user')
			->result_array())
		{
			throw new Exception('用户名已存在');
		}
		
		//DO post
		$form['Utoken'] = $this->create_token();
		$this->db->insert('user', filter($form, $members_user));
		$this->db->insert('user_info', filter($form, $members_info));

	}


	/**
	 * 登陆
	 */
	public function login($form)
	{

		//check Uusername && Upassword
		if ( ! $result = $this->db->select(array('Ulast_visit'))
			->where($form)
			->get('user')
			->result_array())
		{
			throw new Exception('用户不存在或密码错误');
		}

		//update token
		$user = $result[0];
		$new_data = array('Ulast_visit' => date('Y-m-d H:i:s',time()));
		if ($this->is_timeout($user['Ulast_visit']))
		{
			$new_data['Utoken'] = $this->create_token();
		}	
		$this->db->update('user', $new_data, array('Uusername' => $form['Uusername']));

		//return 
		$ret = array(
			'Utoken' => $this->db->select('Utoken')
				->where(array('Uusername' => $form['Uusername']))
				->get('user')
				->result_array()[0]['Utoken']);
		return $ret;
		
	}


	/**
	 * 获取单条信息
	 */
	public function get_one($form)
	{

		//config
		$members_user = array('Uusername', 'Ulast_visit');
		$members_info = array('Unickname', 'Urealname');

		//check token
		$this->check_token($form['Utoken']);

		//get user
		$result = $this->db->select($members_user)
				->where(array('Uusername' => $form['Uusername']))
				->get('user')->result_array();
		if ( ! $result)
		{
			throw new Exception('用户名不存在');	
		}
		$user = $result[0];

		//get user info
		$user_info = $this->db->select($members_info)
						->where(array('Uusername' => $form['Uusername']))
						->get('user_info')->result_array()[0];
		foreach ($user_info as $key => $info) {
			$user[$key] = $info;
		}

		//return
		return $user;

	}


	/**
	 * 获取信息
	 */
	public function get($form)
	{

		//config
		$members = array('page_size', 'now_page', 'max_page', 'data');
		$members_user = array('Uusername', 'Ulast_visit');
		$members_info = array('Urealname', 'Unickname');
		$valid_search_user = array('Uusername', 'Ulast_visit');

		//check token
		$this->check_token($form['Utoken']);

		//check search_key
		if ($form['search_key'] !== 'null')
		{
			if ( ! filter(array($form['search_key'] => $form['search_value']), $valid_search_user))
			{
				throw new Exception('[检索键]'.$form['search_key'].'不被允许');
			}
		}

		//get max_page
       	if ($form['search_key'] !== 'null')
        {
        	$this->db->like($form['search_key'], $form['search_value']);
        }
        $ret['max_page'] = (int)(($this->db->count_all_results('user') - 1) / $form['page_size']) + 1;

		//select user
        $this->db->select($members_user);
       	if ( $form['search_key'] !== 'null')
        {
        	$this->db->like($form['search_key'], $form['search_value']);
        }
       	$users = $this->db->limit($form['page_size'], ($form['now_page'] - 1) * $form['page_size'])
        	->get('user')
        	->result_array();

        //select user_info
        if ($users)
		{
			foreach ($users as $key_user => $user) 
			{
				$user_info = $this->db->select($members_info)
					->where(array('Uusername' => $user['Uusername']))
					->get('user_info')
					->result_array()[0];
				foreach ($user_info as $key_info => $info) 
				{
					$users[$key_user][$key_info] = $info;
				}
			}
		}

		//return
		$ret['page_size'] = $form['page_size'];
		$ret['now_page'] = $form['now_page'];
		$ret['data'] = $users;
		return $ret;

	}


}
