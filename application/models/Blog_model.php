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

	/**
	 * 获取某条文章记录
	 */
	public function get($form)
	{

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);			
		}

		//get article
		$where = array('Bid' => $form['Bid']);
		$result = $this->db->where($where)
			->get('blog')
			->result_array();
		if ( ! $result) 
		{
			throw new Exception('记录不存在');
		}
		$article = $result[0];
		$blog_article = $this->db->where($where)
			->get('blog_article')
			->result_array()[0];

		//combine
		foreach ($blog_article as $key => $value) {
			$article[$key] = $value;
		}
		
		//check editable
		$article['editable'] = FALSE;
		if (isset($form['Utoken']))
		{		
			$result = $this->db->select('Uusername')
				->where('Utoken', $form['Utoken'])
				->get('user')
				->result_array()[0];
			$username = $result['Uusername'];
			$article['editable'] = $username == $article['Bauthor'];
		}

		$article['upvoteEnable'] = FALSE;

		//get blog_target
		$targets = $this->db->select('Tid')
			->where('Bid', $form['Bid'])
			->get('blog_target')
			->result_array();
		if ($targets)
		foreach ($targets as $key => $target) {
			$info = $this->db->select(array('Tid', 'Tfather', 'Tname'))
				->where('Tid', $target['Tid'])
				->get('target')
				->result_array()[0];
			$info['Tfather'] = $this->db->select('Tname')
				->where('Tid', $info['Tfather'])
				->get('target')
				->result_array()[0]['Tname'];
			$targets[$key] = $info;
		}
		$article['Btargets'] = $targets;

		//get
		return $article;

		//check upvoteEnable 
		/*if (isset($form['Utoken']))
		{
			$result = $this->db->where(array('Uusername' => $username, 'UTid' => $form['UTid']))
				->get('blog_likes')
				->result_array();
			if ( ! $result) 
			{				
				$article['upvoteEnable'] = TRUE;
			}
		}
		return $article;*/

	}

	/**
	 * 获取个人文章列表
	 */
	public function get_list($form)
	{

		//config
		$members = array('page_size', 'page', 'page_max', 'editable', 'data');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
			$user = $this->db->where('Utoken', $form['Utoken'])->get('user')->result_array()[0]['Uusername'];
		}

		//select articles
       	$where = array('Bauthor' => $form['Bauthor']);
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
			$count = $this->db->where($where)->count_all_results('blog');
	        $ret['page_max'] = (int)(($count - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }
       	$articles = $this->db->where($where)->order_by('Btime','DESC')->get('blog')->result_array();

       	//set upvoteEnable 
       	foreach ($articles as $key => $article) {
			$articles[$key]['upvoteEnable'] = FALSE;}

		//return
		$ret['editable'] = isset($user) && $user == $form['Bauthor'];
		$ret['data'] = $articles;
		return filter($ret, $members);


		//check upvoteEnable 目前没有实现该功能
		foreach ($articles as $key => $article) {
			$articles[$key]['upvoteEnable'] = FALSE;
			if (isset($form['Utoken']))
			{
				$result = $this->db->where(array('Uusername' => $user, 'Bid' => $article['Bid']))
					->get('blog_likes')
					->result_array();
				if ( ! $result)
				{
					$articles[$key]['upvoteEnable'] = TRUE;
				}
			}
		}

		//return
		$ret['editable'] = isset($user) && $user == $form['Uusername'];
		$ret['data'] = $articles;
		return filter($ret, $members);

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