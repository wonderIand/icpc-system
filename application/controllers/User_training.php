<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

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
	 * post
	 */
	public function post()
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
			if ( ! $this->form_validation->run('user_training_post'))
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

			//post
			$this->load->model('User_training_model','user_training');
			$this->user_training->post(filter($post, $members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "注册成功", array());

	}
	

}