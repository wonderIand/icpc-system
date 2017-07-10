<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 队伍管理
 */
class Team_model extends CI_Model {

	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/
	

	/**
	 * 注册
	 */
	public function register($form) 
	{

		//check token|Uusername_1
		$this->load->model('User_model','my_user');
		$this->my_user->check_token($form['Utoken']);
		$user = $this->db->select('Uusername') 
			->where(array("Utoken" => $form['Utoken']))
			->get('user')
			->result_array()[0];
		unset($form['Utoken']);
		$form['Uusername_1'] = $user['Uusername'];

		//check Tteamname
		$where = array('Tteamname' => $form['Tteamname']);
		if ($this->db->select('Tteamname')
			->where(array('Tteamname' => $form['Tteamname']))
			->get('team')
			->result_array())
		{
			throw new Exception('队伍名已存在');	
		}

		//check Uusername_2
		if ( ! $this->db->select('Uusername')
			->where(array('Uusername' => $form['Uusername_2']))
			->get('user')
			->result_array())
		{
			throw new Exception('成员2 不存在');
		}

		//check Uusername_3
		if ( ! $this->db->select('Uusername')
			->where(array('Uusername' => $form['Uusername_3']))
			->get('user')
			->result_array())
		{
			throw new Exception('成员3 不存在');
			
		}
		
		//DO register
		$this->db->insert('team', $form); 
	}


	/**
	 * 获取队伍信息
	 */
	public function get($form)
	{
		//config
		$members = array('Tteamname', 'Uusername_1', 'Uusername_2', 'Uusername_3', 'Tplan_1', 'Tplan_2', 'Tplan_3');

		//check token
		$this->load->model('User_model','my_user');
		if (isset($form['Utoken']))
		{
			$this->my_user->check_token($form['Utoken']);
		}

		//select members
		$result = $this->db->select($members)
			->where(array('Tteamname' => $form['Tteamname']))
			->get('team')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('队伍名不存在');
		}
		$team = $result[0];

		//return
		return $team;

	}


	/**
	 * 获取队伍列表
	 */
	public function get_all($form)
	{

		//config
		$members = array('page_size', 'page', 'page_max', 'data');
		$members_team = array('Tteamname', 'Uusername_1', 'Uusername_2', 'Uusername_3');

		//check token
		$this->load->model('User_model','my_user');
		if (isset($form['Utoken']))
		{
			$this->my_user->check_token($form['Utoken']);			
		}

		//select team
        $this->db->select($members_team);
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
	        $ret['page_max'] = (int)(($this->db->count_all_results('team') - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$teams = $this->db->get('team')->result_array();

		//return
		$ret['data'] = $teams;
		return filter($ret, $members);

	}


	/**
	 * 获取用户队伍列表
	 */
	public function get_list($form)
	{

		//config
		$ret = array();
		$members = array('page_size', 'page', 'page_max', 'data');
		$members_team = array('Tteamname', 'Uusername_1', 'Uusername_2', 'Uusername_3');

		//check token
		$this->load->model('User_model','my_user');
		if (isset($form['Utoken']))
		{
			$this->my_user->check_token($form['Utoken']);
		}

		//check Uusername
		if ( ! isset($form['Uusername']))
		{
			$form['Uusername'] = $this->db->select('Uusername')
				->where('Utoken', $form['Utoken'])
				->get('user')
				->result_array()[0]['Uusername'];
		}

		//select team
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
	        $ret['page_max'] = (int)(($this->db->count_all_results('team') - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$teams = $this->db->select($members_team)
       		->where('Uusername_1', $form['Uusername'])
       		->get('team')
       		->result_array();
       	$teams_part_2 = $this->db->select($members_team)
       		->where('Uusername_2', $form['Uusername'])
       		->get('team')
       		->result_array();
       	$teams_part_3 = $this->db->select($members_team)
       	    ->where('Uusername_3', $form['Uusername'])
       	    ->get('team')
       	    ->result_array();

       	//combine
       	foreach ($teams as $key => $team) 
       	{
       		$exist[$team['Tteamname']] = TRUE; 
       	}
       	foreach ($teams_part_2 as $team) 
       	{
       		if ( ! isset($exist[$team['Tteamname']]))
       		{
       			$exist[$team['Tteamname']] = TRUE;
       			$teams[] = $team;
       		}
       	}
       	foreach ($teams_part_3 as $team) 
       	{
       		if ( ! isset($exist[$team['Tteamname']]))
       		{
       			$exist[$team['Tteamname']] = TRUE;
       			$teams[] = $team;
       		}
       	}

       	//get this page
        if (isset($form['page_size']))
        {
        	$offset = $form['page_size'] * ($form['page'] - 1);
        	for ($i = 0; $i < $form['page_size'] && isset($teams[$offset + $i]); $i += 1)
        	{
        		$ret['data'][]=$teams[$i];
        	}
        }
        else 
        {
        	$ret['data'] = $teams;
        }

		//return
		return filter($ret, $members);

	}


}
