<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 个人博客管理
 */
class Blog_model extends CI_Model {

	/**********************************************************************************************
	 * 私有工具集
	 **********************************************************************************************/

	/**
	 * 添加记录
	 */
	public function register($form)
	{
		//config
		$members = array('Btitle', 'Bauthor','Btime');
		$members_article = array('Bid', 'BAarticle');

		//add time
		$form['Btime'] = date('Y-m-d H:i:s');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//get author
		$form['Bauthor'] = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//update
		$this->db->insert('blog',filter($form, $members));
		$form['Bid'] = $this->db->insert_id();
		$this->db->insert('blog_article', filter($form, $members_article));
	}
	

	/**
	 * 删除记录
	 */
	public function delete($form)
	{

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//check editable
		$result = $this->db->select('Bauthor')
			->where('Bid', $form['Bid'])
			->get('blog')
			->result_array();
		if ( ! $result) 
		{
			throw new Exception('记录不存在');
		}
		$author = $result[0]['Bauthor'];
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		if ($author != $user)
		{
			throw new Exception('只有作者可以删除记录', 402);
		}	

		//delete
		$where = array('Bid' => $form['Bid']);
		$this->db->delete('blog', $where);
		$this->db->delete('blog_article', $where);
	}
	

	/**
	 * 修改记录
	 */
	public function update($form)
	{
		
		//config
		$members = array('Btitle');
		$members_article = array('BAarticle');

		//check token & get user
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//check Bid & get author
		$result = $this->db->select('Bauthor')
			->where('Bid', $form['Bid'])
			->get('blog')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('不存在的博客id', 0);
		}
		$author = $result ? $result[0]['Bauthor'] : NULL;

		//check editable
		if ($author != $user)
		{
			throw new Exception('只有作者可以修改博客记录', 402);
		}

		//update
		$where = array('Bid' => $form['Bid']);
		$this->db->update('blog', filter($form, $members), $where);
		$this->db->update('blog_article', filter($form, $members_article), $where);

	}


	/*
	 * 添加博客标签
	 */
	public function register_target ($form)
	{
		//config
		$members = array('Bid', 'Tid');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//check token & get user
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$user = $this->db->select('Uusername')
			->where('Utoken', $form['Utoken'])
			->get('user')
			->result_array()[0]['Uusername'];

		//check Bid & get author
		$result = $this->db->select('Bauthor')
			->where('Bid', $form['Bid'])
			->get('blog')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('不存在的博客id', 0);
		}
		$author = $result ? $result[0]['Bauthor'] : NULL;

		//check editable
		if ($author != $user)
		{
			throw new Exception('只有作者可以修改博客记录', 402);
		}

		//check tag
		$data['Tid'] = $form['Tid'];
		$data['Utoken'] = $form['Utoken'];
		$data['TFLAG'] = array(0);
		$this->load->model('Target_model', 'target');
		$tag_info = $this->target->get($data);
		if ($tag_info['Ttype'] != 2)
		{
			throw new Exception("博客标签类型必须为2");
		}

		//check repeat
		$where = array('Bid' => $form['Bid'], 'Tid' => $form['Tid']);
		$repeat = $this->db->get_where('blog_target', $where)
			->result_array();
		if ($repeat)
		{
			throw new Exception('该博客已存在重复标签');
		}

		//insert
		$this->db->insert('blog_target', filter($form, $members));

	}


}