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

			//reject
			throw new Exception("已关闭注册");			

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


	/**
	 * 上传用户头像
 	 */
	public function upload_icon()
	{
		//get username
		$post['Utoken'] = get_token();
		$this->load->model('User_model', 'user');
		$user_info = $this->user->get($post);
		$username = $user_info['Uusername'];

		//upload config
		$config['upload_path'] = './uploads/user_icon/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['file_name'] = $username;
		$config['overwrite'] = TRUE;
		$config['max_size'] = 1000;
		$config['max_width'] = 1024;
		$config['max_height'] = 768;

		//upload
		try
		{
			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('user_icon'))
        	{
            	$error = array('error' => $this->upload->display_errors());
            	output_data(0, '上传失败', $error);
	        }
    		else
        	{	
        		$data = array('upload_data' => $this->upload->data());
				$this->load->helper('url');
            	$data['icon_path'] = base_url() . 'uploads/user_icon/' . $data['upload_data']['file_name'];
            	output_data(1, '上传成功', $data);
        	}
		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}
	}


}
