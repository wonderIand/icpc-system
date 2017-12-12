<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
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
	
	/**
	 * 添加oj关联账号
	 */
	public function add_oj_account()
	{
		//config
		$members = array('Utoken', 'Uusername', 'OJname', 'OJusername', 'OJpassword');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check OJname
			if (isset($post['OJname']))
			{
				if ($post['OJname'] != "hdu" && $post['OJname'] != "foj"
					&& $post['OJname'] != "cf")
				{
					throw new Exception("oj名称错误");
				}
			}
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);

			if ( ! $this->form_validation->run('add_oj_account'))
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
			if ($post['OJname'] == 'hdu')
			{
				$this->oj->add_hdu_account(filter($post, $members));
			}
			else if ($post['OJname'] == 'foj')
			{
				$this->oj->add_foj_account(filter($post, $members));
			}
			else if ($post['OJname'] == 'cf')
			{
				$this->oj->add_cf_account(filter($post, $members));
			}
			else
			{
				throw new Exception("oj名称错误");
			}

		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		//return
		output_data(1, "添加成功", array());
	}

	/**
	 * 获取对应oj过题数
	 */
	public function get_oj_acproblems()
	{
		//config
		$members = array('Utoken', 'Uusername', 'OJname');

		//get
		try
		{
			//get post
			$post['Utoken'] = get_token(FALSE);
			if (! $this->input->get('Uusername'))
			{
				throw new Exception("必须指定Uusername");
			}
			$post['Uusername'] = $this->input->get('Uusername');

			if (! $this->input->get('OJname'))
			{
				throw new Exception("必须指定OJname");
			}
			$post['OJname'] = $this->input->get('OJname');

			// filter && get info
			$this->load->model("Oj_model", 'oj');
			$data = $this->oj->get_cache(filter($post, $members));
		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		output_data(1, "查询成功", $data);
	}

	/**
	 * 查询所有OJ(hdu,foj,cf)关联账号信息
	 */
	public function get_oj_account()
	{
		//config
		$members = array('Utoken', 'Uusername');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token(FALSE);

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);

			if (! $this->form_validation->run('get_oj_account'))
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

			$this->load->model('Oj_model', 'oj');
			$data = $this->oj->get_oj_account($post);

		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		output_data(1, "查询成功", $data);
	}

	/**
	 * 删除oj关联账号信息
	 */
	public function del_oj_account()
	{
		//config
		$members = array('Utoken', 'Uusername', 'OJname');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);

			if (! $this->form_validation->run('del_oj_account'))
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

			$this->load->model('Oj_model', 'oj');

			if ($post['OJname'] == 'hdu' || $post['OJname'] == 'foj' || $post['OJname'] == 'cf')
			{
				$data = $this->oj->del_oj_account($post);
			}
			else
			{
				throw new Exception('OJ名称错误');
			}
		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		output_data(1, "删除成功", array());
	}

	/**
	 * 查看oj近期两周提交过题记录
	 */
	public function get_oj_acinfo()
	{
		//config
		$members = array('Utoken', 'Uusername', 'OJname');
		//get
		try
		{
			//get post
			$post['Utoken'] = get_token(FALSE);
			if (! $this->input->get('Uusername'))
			{
				throw new Exception("必须指定Uusername");
			}
			$post['Uusername'] = $this->input->get('Uusername');
			if (! $this->input->get('OJname'))
			{
				throw new Exception("必须指定OJname");
			}
			$post['OJname'] = $this->input->get('OJname');
			// filter && get info
			$this->load->model("Oj_model", 'oj');
			if ($post['OJname'] == 'cf')
			{
				$data = $this->oj->get_cf_acinfo(filter($post,$members));
			}
			else if ($post['OJname'] == 'foj')
			{
				throw new Exception("暂无此功能");
			}
			else if ($post['OJname'] == 'hdu')
			{
				$data = $this->oj->get_hdu_acinfo(filter($post,$members));
			}
			else
			{
				throw new Exception("OJ名称出错");	
			}
		}
		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}
		output_data(1, "查询成功", $data);
	}
	/**
	 * 获取题量排行
	 */
	public function get_list()
	{
		try
		{
			$this->load->model('Oj_model', 'oj');
			$data = $this->oj->get_list();
		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}
		output_data(1, "获取成功", $data);
	}


	/**
	 * 手动刷新题量
	 */
	public function refresh()
	{
		//config
		$members = array('Uusername');
		
		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token(FALSE);
			//get &&filter
			$this->load->model('Oj_model', 'oj');
			$data = $this->oj->refresh(filter($post, $members));
		}
		catch (Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}
		output_data(1, "刷新成功", $data);
	}
}