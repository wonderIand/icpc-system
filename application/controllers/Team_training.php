<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

defined('BASEPATH') OR exit('No direct script access allowed');


class Team_training extends CI_Controller {


	/*****************************************************************************************************
	 * 测试区域
	 *****************************************************************************************************/
	public function test() 
	{
		$order_by = "233";
		foreach ($order_by as $value) {
			echo "233";
		}	
	}


	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/

	/**
	 * 获取post
	 */
	private function get_post() 
	{
		$input = file_get_contents('php://input'); 
		$post = (array)json_decode($input);
		if ( ! isset(apache_request_headers()['Utoken']))
		{
			throw new Exception("没有登陆凭据，请登录");	
		}
		return $post;
	}



	/**
	 * 输出数据
	 */
	private function output_data($type, $message, $data) 
	{
		$data = array(
			'type' => $type,
			'message' => $message,
			'data' => $data
			);
		print_r(json_encode_unicode($data));
	}


	/*****************************************************************************************************
	 * 主接口
	 *****************************************************************************************************/

	/**
	 * 注册
	 */
	public function register() 
	{

		//config
		$members = array('Utoken', 'Tteamname', 'TTtitle', 'TTaddress', 'TTproblem_num');

		//get post
		$post = $this->get_post();
		
		//register
		try
		{
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_training_register')) 
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
			$this->load->model('Team_training_model','my_training');
			$this->my_training->register(filter($post, $members));
			
		}
		catch (Exception $e)
		{
			$this->output_data(0, $e->getMessage(), array());
			return;
		}

		//return
		$this->output_data(1, '添加成功', array());

	}


	/**
	 * 获取列表
	 */
	public function get_list()
	{

		//config
		$members = array('Utoken', 'Tteamname');

		//get post
		$post = $this->get_post();

		//get info
		try
		{
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('team_training_get_list')) 
			{
				foreach ($members as $member) 
				{
					if (form_error($member))
					{
						throw new Exception(strip_tags(form_error($member)));
					}
				}
				
			}

			//过滤 && get_list
			$this->load->model('Team_training_model','my_training');
			$data = $this->my_training->get_list(filter($post, $members));
		}
		catch(Exception $e)
		{
			$this->output_data(0, $e->getMessage(), array());
			return;
		}

		//return
		$this->output_data(1, '获取成功', $data);

	}
	

}