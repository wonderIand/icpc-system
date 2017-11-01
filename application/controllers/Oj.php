<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');

class Oj extends CI_Controller {

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
	
	//添加hdu关联账号
	public function add_hdu_account()
	{
		//config
		$members = array('Utoken', 'Uusername', 'OJname', 'OJusername', 'OJpassword');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();
			
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);

			if ( ! $this->form_validation->run('oj_account'))
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

			$this->load->model("Oj_model","oj");
			$this->oj->add_hdu_account(filter($post,$members));

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "添加成功", array());
	}
}