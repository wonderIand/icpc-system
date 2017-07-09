<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

defined('BASEPATH') OR exit('No direct script access allowed');


class Team extends CI_Controller {


	/*****************************************************************************************************
	 * 测试区域
	 *****************************************************************************************************/
	public function test() 
	{
		echo $this->input->get("a");
	}


	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/


	/*****************************************************************************************************
	 * 主接口
	 *****************************************************************************************************/

	/**
	 * 注册
	 */
	public function register() 
	{

		//config
		$members = array('Tteamname', 'Utoken', 'Uusername_2', 'Uusername_3');

		//register
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_register')) 
			{
				$this->load->helper('form');
				foreach ($members as $member) 
				{
					if (form_error($member))
					{
						throw new Exception(strip_tags(form_error($member)));
					}
				}
			}

			//过滤 && register
			$this->load->model('Team_model','my_team');
			$this->my_team->register(filter($post, $members));
			
		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '注册成功', array());

	}


	/**
	 * 获取队伍信息
	 */
	public function get()
	{

		//config
		$members = array('Utoken', 'Tteamname');

		//get
		try
		{

			//get post
			$post['Utoken'] = get_token();
			if ( ! $this->input->get('Tteamname'))
			{
				throw new Exception('必须指定Tteamname');
			}
			$post['Tteamname'] = $this->input->get('Tteamname');
				

			//过滤 && get_info
			$this->load->model('Team_model','my_team');
			$data = $this->my_team->get(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '获取成功', $data);

	}		


	/**
	 * 获取队伍列表
	 */
	public function get_all()
	{

		//config
		$members = array('Utoken', 'page_size', 'page');

		//get
		try
		{

			//get post
			$post['Utoken'] = get_token();

			//check page
			if ($this->input->get('page_size'))
			{
				if ($this->input->get('page'))
				{
					$post['page_size'] = $this->input->get('page_size');
					$post['page'] = $this->input->get('page');
				}
				else
				{
					throw new Exception("请设置页码");
				}
			}
			else
			{
				if ($this->input->get('page'))
				{
					throw new Exception("请设置每页大小", 1);
					
				}
			}			

			//DO get_list
			$this->load->model('Team_model','my_team');
			$data = $this->my_team->get_all(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "获取成功", $data);

	}
	

	/**
	 * 获取用户队伍列表
	 */
	public function get_list()
	{

		//config
		$members = array('Utoken', 'Uusername', 'page_size', 'page');

		//get
		try
		{

			//get post
			$post['Utoken'] = get_token();
			if ($this->input->get('Uusername'))
			{
				$post['Uusername'] = $this->input->get('Uusername');
			}
			if ($this->input->get('page_size') && $this->input->get('page'))
			{
				$post['page_size'] = $this->input->get('page_size');
				$post['page'] = $this->input->get('page');
			}

			//DO get_list
			$this->load->model('Team_model','my_team');
			$data = $this->my_team->get_list(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "获取成功", $data);

	}
}