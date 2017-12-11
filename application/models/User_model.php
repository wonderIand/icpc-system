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
		return $now_unix - $pre_unix > 10000;
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
			throw new Exception('会话已过期，请重新登陆', 401);
		}
		else
		{
			$user = $result[0];
			if ($this->is_timeout($user['Ulast_visit']))
			{
				throw new Exception('会话已过期，请重新登陆', 401);
			}
			else 
			{
				//刷新访问时间
				$new_data = array('Ulast_visit' => date('Y-m-d H:i:s',time()));
				$this->db->update('user', $new_data, $where);
			}
		}

	
	}


	/**********************************************************************************************
	 * 业务接口
	 **********************************************************************************************/


	/**
	 * 注册
	 */
	public function register($form) 
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
		
		//DO register
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
	 * 获取用户信息
	 */
	public function get($form)
	{

		//config
		$members_user = array('Uusername', 'Utype', 'Ulast_visit');
		$members_info = array('Unickname', 'Urealname');

		//check token
		if (isset($form['Utoken'])) 
		{
			$this->check_token($form['Utoken']);
		}

		//get user
		$where = isset($form['Uusername'])
			? array('Uusername' => $form['Uusername'])
			: array('Utoken' => $form['Utoken']);
		$result = $this->db->select($members_user)
			->where($where)
			->get('user')->result_array();
		if ( ! $result)
		{
			throw new Exception('用户名不存在');	
		}
		$user = $result[0];

		//get user info
		$user_info = $this->db->select($members_info)
						->where(array('Uusername' => $user['Uusername']))
						->get('user_info')->result_array()[0];
		foreach ($user_info as $key => $info) {
			$user[$key] = $info;
		}

		//return
		return $user;

	}


	/**
	 * 获取用户列表
	 */
	public function get_list($form)
	{

		//config
		$members = array('page_size', 'page', 'page_max', 'data');
		$members_user = array('Uusername', 'Utype', 'Ulast_visit');
		$members_info = array('Urealname', 'Unickname');

		//check token
		if (isset($form['Utoken']))
		{
			$this->check_token($form['Utoken']);
		}

		//select user
        $this->db->select($members_user);
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
	        $ret['page_max'] = (int)(($this->db->count_all_results('user') - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
	        $this->db->select($members_user);
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$users = $this->db->order_by('Ulast_visit','DESC')->get('user')->result_array();

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
		$ret['data'] = $users;
		return filter($ret, $members);

	}


	/**
	 * 上传用户头像
	 **/
	public function upload_icon($form)
	{
		//config
		$members_info = array('Uiconpath');

		//check token
		if (isset($form['Utoken']))
		{
			$this->check_token($form['Utoken']);
		}

		//select user
		$where = array('Uusername' => $form['Uusername']);
		$this->db->update('user_info', filter($form, $members_info), $where);
	}


	/**
	 * 获取用户头像
	 **/
	public function get_icon($form)
	{
		//check user
		$where = array('Uusername' => $form['Uusername']);
		$isExist = $this->db->where($where)->get('user_info')->result_array();
		if(!$isExist)
		{
			throw new Exception("该用户不存在", 0);
		}

		//get iconpath
		$ret = $this->db->select('Uiconpath')->where($where)->get('user_info')->result_array()[0];
		if($ret['Uiconpath'] == '-')
		{
			throw new Exception("该用户未上传头像", 0);
		}

		//return
		return $ret;
	}


}
