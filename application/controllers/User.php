<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CI_Controller {


	/*****************************************************************************************************
	 * 测试区域
	 *****************************************************************************************************/
	public function test() 
	{
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
		$members = array('Uusername', 'Upassword', 'Urealname', 'Unickname');

		//register
		try
		{

			//get post
			$post = get_post();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_register')) 
			{
				$this->load->helper('form');
				foreach ($members as $member) 
				{
					if (form_error($member))
					{
						throw new Exception(strip_tags(form_error($member)));
					}
				}
				return;
			}

			//过滤 && register
			$this->load->model('User_model','my_user');
			$this->my_user->register(filter($post, $members));
			
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
				return;
			}

			//过滤 && login
			$this->load->model('User_model','my_user');
			$data = $this->my_user->login(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '登陆成功', $data);

	}


	/**
	 * 获取用户信息
	 */
	public function get()
	{

		//config
		$members = array('Utoken', 'Uusername');

		//get
		try
		{

			//get post
			$post['Utoken'] = get_token(FALSE);
			if ($this->input->get('Uusername'))
			{
				$post['Uusername'] = $this->input->get('Uusername');
			}

			//过滤 && get_info
			$this->load->model('User_model','my_user');
			$data = $this->my_user->get(filter($post, $members));

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
	 * 获取用户列表
	 */
	public function get_list()
	{
		//config
		$members = array('Utoken', 'page_size', 'page');

		//get
		try
		{

			//get post
			$post['Utoken'] = get_token(FALSE);
			if ($this->input->get('page_size') && $this->input->get('page'))
			{
				$post['page_size'] = $this->input->get('page_size');
				$post['page'] = $this->input->get('page');
			}

			//过滤 && get_list
			$this->load->model('User_model','my_user');
			$data = $this->my_user->get_list(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '获取成功', $data);

	}


}