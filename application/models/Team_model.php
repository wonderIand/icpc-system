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
	public function post($form) 
	{

		//check token
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
		if ( ! $this->db->insert('team', $form)) 
		{
			throw new Exception('数据库错误');
		}

	}


	/**
	 * 获取信息
	 */
	public function get_one($form)
	{
		//config
		$members = array('Tteamname', 'Uusername_1', 'Uusername_2', 'Uusername_3', 'Tplan_1', 'Tplan_2', 'Tplan_3');

		//check token
		$this->load->model('User_model','my_user');
		$this->my_user->check_token($form['Utoken']);

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
	 * 获取信息
	 */
	public function get($form)
	{
		//config
		$members = array('page_size', 'now_page', 'max_page', 'data');
		$valid_search_team = array('Tteamname', 'Uusername_1', 'Uusername_2', 'Uusername_3');

		//check token
		$this->load->model('User_model', 'my_user');
		$this->my_user->check_token($form['Utoken']);

		//check search_key
		if ($form['search_key'] !== 'null')
		{
			if ( ! filter(array($form['search_key'] => $form['search_value']), $valid_search_team))
			{
				throw new Exception('[检索键]'.$form['search_key'].'不被允许');
			}
		}

		//get max_page
       	if ($form['search_key'] !== 'null')
        {
        	$this->db->like($form['search_key'], $form['search_value']);
        }
        $ret['max_page'] = (int)(($this->db->count_all_results('team') - 1) / $form['page_size']) + 1;

		//select team
       	if ( $form['search_key'] !== 'null')
        {
        	$this->db->like($form['search_key'], $form['search_value']);
        }
       	$teams = $this->db->limit($form['page_size'], ($form['now_page']-1)*$form['page_size'])
        	->get('team')
        	->result_array();

		//return
		$ret['page_size'] = $form['page_size'];
		$ret['now_page'] = $form['now_page'];
		$ret['data'] = $teams;
		return $ret;

	}

}
