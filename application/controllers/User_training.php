<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

defined('BASEPATH') OR exit('No direct script access allowed');


class User_training extends CI_Controller {


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
	 * 添加训练记录
	 */
	public function register()
	{
		//config
		$members = array('Utoken', 'UTtitle', 'UTplace', 'UTaddress', 'UTproblemset');

		//post
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();
			if (isset($post['UTproblemset']))
			{
				$post['UTproblemset'] = implode('#', $post['UTproblemset']);
			}

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_training_register'))
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

			//DO register
			$this->load->model('User_training_model','user_training');
			$this->user_training->register(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "注册成功", array());

	}
	

	/**
	 * 获取个人训练记录
	 */
	public function get()
	{

		//config
		$members = array('Utoken', 'UTid');

		//get
		try
		{
			//get post
			$post['Utoken'] = get_token();
			if ( ! $this->input->get('UTid'))
			{
				throw new Exception('必须指定UTid');				
			}
			$post['UTid'] = $this->input->get('UTid');

			//DO get
			$this->load->model('User_training_model', 'user_training');
			$data = $this->user_training->get($post);

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
	 * 修改训练记录
	 */
	public function update()
	{
		//config
		$members = array('Utoken', 'UTid', 'UTtitle', 'UTplace', 'UTaddress', 'UTproblemset', 'UTarticle');

		//post
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();
			if (isset($post['UTproblemset']))
			{
				$post['UTproblemset'] = implode('#', $post['UTproblemset']);
			}

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_training_update'))
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

			//DO register
			$this->load->model('User_training_model','user_training');
			$this->user_training->update(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "修改成功", array());

	}


	/**
	 * 删除记录
	 */
	public function delete()
	{	
		//config
		$members = array('Utoken', 'UTid');

		//delete
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_training_delete'))
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

			//DO delete
			$this->load->model('User_training_model', 'user_training');
			$this->user_training->delete(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '删除成功', array());

	}


	/**
	 * 获取个人训练列表
	 */
	public function get_list()
	{
		//config
		$members = array('Utoken', 'Uusername', 'page_size', 'page');

		//get_list
		try
		{

			//get post
			$post = get_post();
			$post['Utoken'] = get_token();
			if ( ! $this->input->get('Uusername'))
			{
				throw new Exception('必须制定用户名Uusername');
			}
			$post['Uusername'] = $this->input->get('Uusername');

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
			$this->load->model('User_training_model', 'user_training');
			$data = $this->user_training->get_list(filter($post, $members));

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