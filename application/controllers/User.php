<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CI_Controller {


	/*****************************************************************************************************
	 * 测试区域
	 *****************************************************************************************************/
	public function test() 
	{
		echo "test";
	}


	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/


	/*****************************************************************************************************
	 * 重载接口
	 *****************************************************************************************************/

	/**
	 * 获取单用户信息
	 */
	private function get_one($post)
	{

		//config
		$members = array('Utoken', 'Uusername');

		//get info
		try
		{
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_get_one')) 
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
			$this->load->model('User_model','my_user');
			$data = $this->my_user->get_one(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data(0, $e->getMessage(), array());
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
		$members = array('Uusername', 'Upassword', 'Urealname', 'Unickname');

		//post
		try
		{

			//get post
			$post = get_post();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_post')) 
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

			//过滤 && post
			$this->load->model('User_model','my_user');
			$this->my_user->post(filter($post, $members));
			
		}
		catch (Exception $e)
		{
			output_data(0, $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '注册成功', array());

	}


	/**
	 * 登陆
	 */
	public function login() 
	{

		//config
		$members = array('Uusername', 'Upassword');

		//login
		try
		{

			//get post
			$post = get_post();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_login'))
			{
				$this->load->helper('form');
				foreach ($members as  $member) 
				{
					if (form_error($member))
					{
						throw new Exception(strip_tags(form_error($member)));
					}
				}
			}

			//过滤 && login
			$this->load->model('User_model','my_user');
			$data = $this->my_user->login(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data(0, $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '登陆成功', $data);

	}


	/**
	 * 用户信息
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
				$post['Uusername'] = $this->uri->segment(3);
				return $this->get_one($post);
			}

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_get'))
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
			$this->load->model('User_model','my_user');
			$data = $this->my_user->get(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data(0, $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "获取成功", $data);

	}
	

}