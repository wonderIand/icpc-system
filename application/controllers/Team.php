<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

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
	 * 重载接口
	 *****************************************************************************************************/
	private function get_one($post)
	{

		//config
		$members = array('Utoken', 'Tteamname');

		//get info
		try
		{
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_get_one')) 
			{
				foreach ($members as $member) 
				{
					if (form_error($member))
					{
						throw new Exception(strip_tags(form_error($member)));
					}
				}
				
			}

			//过滤 && get_info
			$this->load->model('Team_model','my_team');
			$data = $this->my_team->get_one(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '获取成功', $data);

	}	


	/*****************************************************************************************************
	 * 主接口
	 *****************************************************************************************************/

	/**
	 * 注册
	 */
	public function post() 
	{

		//config
		$members = array('Tteamname', 'Utoken', 'Uusername_2', 'Uusername_3');

		//post
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_post')) 
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
			$this->my_team->post(filter($post, $members));
			
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
	 * 队伍信息
	 */
	public function get()
	{

		//config
		$members = array('Utoken', 'search_key', 'search_value', 'page_size', 'now_page');

		//get
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check route
			if ($this->uri->segment(3))
			{
				$post['Tteamname'] = $this->uri->segment(3);
				return $this->get_one($post);				
			}

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_get'))
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

			//get
			$this->load->model('Team_model','my_team');
			$data = $this->my_team->get(filter($post, $members));

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