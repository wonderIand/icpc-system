<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 队伍管理
 */
class Team_training_model extends CI_Model {

	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/

	/**
	 * 获取数据库表名
	 */
	private function get_tb() 
	{ 
		return 'team_training';
	}


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/

	/**
	 * 搜索
	 */
	public function get_team_training($where, $all = FALSE) 
	{
		$team_trainings = $this->db->where($where)->get($this->get_tb())->result_array();
		return $team_trainings ? $all ? $team_trainings : $team_trainings[0] : NULL;
	}


	/**
	 * 添加记录
	 */
	public function register($form) 
	{

		//check token
		$this->load->model('User_model','my_user');
		$this->my_user->check_token($form['Utoken']);
		$where = array('Utoken' => $form['Utoken']);
		$user = $this->my_user->get_user($where);
		unset($form['Utoken']);

		//check Tteamname
		$where = array('Tteamname' => $form['Tteamname']);
		$this->load->model('Team_model','my_team');
		if ( ! $team = $this->my_team->get_team($where))
		{
			throw new Exception('队伍不存在');	
		}

		//check member
		if ($user['Uusername'] != $team['Uusername_1'] && 
			$user['Uusername'] != $team['Uusername_2'] &&
			$user['Uusername'] != $team['Uusername_3'])
		{
			throw new Exception('只有队伍成员能添加训练记录');
		}

		//set TTproblemset
		for ($i = 0; $i < $form['TTproblem_num']; $i++)
		{
			$form['TTproblemset'][] = '.';
		}
		$form['TTproblemset'] = implode('#', $form['TTproblemset']);
		unset($form['TTproblem_num']);
		
		//DO register
		if ( ! $this->db->insert($this->get_tb(), $form)) 
		{
			throw new Exception('数据库错误');
		}

	}


	/**
	 * 获取队伍训练记录
	 */
	public function get_list($form)
	{
		//config
		$members = array('list');
		$list_members = array('TTtitle', '')

		//check token
		$this->load->model('User_model','my_user');
		$this->my_user->check_token($form['Utoken']);

		//check Tteamname
		$where = array('Tteamname' => $form['Tteamname']);
		if ( ! $team = $this->get_team($where))
		{
			throw new Exception('队伍名不存在');
		}

		//do get_list
		$where = array('Tteamname' => $form['Tteamname']);
		$ret = $this->get_team_training($where);


		//return
		$ret = $team;
		return filter($ret, $members);

	}


}
