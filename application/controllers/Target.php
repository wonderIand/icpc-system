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
		$members = array('Tid', 'Bid', 'Utoken', 'getson');

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
			$temp = get_post();
			$post['getson'] = isset($temp['getson']) ? $temp['getson'] : 1;				
			if ($this->input->get('Bid'))
			{
				$post['Bid'] = $this->input->get('Bid');
			}

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


	/**
	 * 修改标签
	 */
	public function update()
	{
		//config
		$members = array('Utoken', 'Tid', 'Tname');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('target_update'))
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

			//DO update
			$this->load->model('Target_model', 'my_target');
			$this->my_target->update(filter($post, $members));

		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, '修改成功', array());
	}

}
