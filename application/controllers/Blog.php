<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');


class Blog extends CI_Controller {


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
	 * 增加文章记录
	 */
	public function register()
	{
		//config
		$members = array('Utoken', 'Btitle', 'BAarticle');

		//post
		try 
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();
			
			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_blog_register'))
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
			$this->load->model('Blog_model','my_blog');
			$this->my_blog->register(filter($post, $members));

		}
		catch (Exception $e) 
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;	
		}

		//return
		output_data(1, "增加成功", array());
	}
	

	/**
	 * 删除文章记录
	 */
	public function delete()
	{
		//config
		$members = array('Utoken', 'Bid');

		//delete
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_blog_delete'))
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

			//DO delete
			$this->load->model('Blog_model', 'my_blog');
			$this->my_blog->delete(filter($post, $members));

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
	 * 修改文章记录
	 */
	public function update()
	{
		//config
		$members = array('Utoken', 'Bid', 'Btitle', 'BAarticle');

		//post
		try
		{
			//get post
			$post = get_post();
			$post['Utoken'] = get_token();

			//check form
			$this->load->library('form_validation');
			$this->form_validation->set_data($post);
			if ( ! $this->form_validation->run('user_blog_update'))
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
			$this->load->model('Blog_model', 'my_blog');
			$this->my_blog->update(filter($post, $members));

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
	 * 查询文章记录
	 */
	public function get()
	{

		//config
		$members = array('Utoken', 'Bid');

		//get
		try
		{
			//get post
			$post['Utoken'] = get_token(FALSE);
			if ( ! $this->input->get('Bid'))
			{
				throw new Exception('必须指定Bid');				
			}
			$post['Bid'] = $this->input->get('Bid');

			//DO get
			$this->load->model('Blog_model', 'Blog');
			$data = $this->Blog->get($post);

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