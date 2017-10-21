<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');


class Target extends CI_Controller {


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
	 * 获取标签记录
	 */
	public function get()
	{
		//config
		$members = array('Tid', 'Utoken', 'TFLAG');

		//get
		try 
		{
			//get post
			$post['Utoken'] = get_token(FALSE);
			if ( ! $this->input->get('Tid'))
			{
				throw new Exception('必须指定Tid');
				
			}
			$post['Tid'] = $this->input->get('Tid');
			$post['TFLAG'] = get_post();

			//Do get
			$this->load->model('Target_model', 'my_target');
			$data = $this->my_target->get(filter($post, $members));
		 	
		} 
		catch (Exception $e) 
		{
		 	output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '获取成功', $data);
		 
	}
}

