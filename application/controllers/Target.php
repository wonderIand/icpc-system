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
	 * 增加标签
	 */
	public function register()
	{
		//config
		$members = array('Utoken', 'Tfather', 'Tname', 'Ttype');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('target_register'))
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

			//check Tfather
			$Tfather = $post['Tfather'];
			if ( ! $this->db->get_where('target', array('Tid' => $Tfather))->result_array())
			{
				throw new Exception('父标签不存在！');
			}

			//check repeat
			$Tname = $post['Tname'];
			$repeat = $this->db->select('Tname')->get_where('target', array('Tfather' => $Tfather))->result_array();
			foreach($repeat as $r)
			{
				if ($Tname == $r['Tname'])
				{
					throw new Exception("父标签下已存在同名标签！");
				}
			}


			//过滤 && insert
			$this->load->model('Target_model', 'my_target');
			$this->my_target->register(filter($post, $members));

		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '增加成功', array());

	}

}