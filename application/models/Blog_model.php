<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 个人博客管理
 */
class Blog_model extends CI_Model {

	/**********************************************************************************************
	 * 私有工具集
	 **********************************************************************************************/

	/**
	 * 获取文章标签
	 */
	private function get_targets($bid)
	{
		$targets = $this->db->select('Tid')
			->where('Bid', $bid)
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
		return $targets;
	}

	/**
	 * 文章阅读量，新阅读量返回true
	 */
	private function add_view($username, $bid)
	{
		$where = array('Uusername' => $username, 'Bid' => $bid);
		if ( ! $this->db->where($where)
			->get('blog_view')
			->result_array())
		{
			$this->db->insert('blog_view', $where);
			$views = $this->db->select('Bviews')
				->where(array('Bid' => $bid))
				->get('blog')
				->result_array()[0]['Bviews'];
			$this->db->update('blog', array('Bviews' => $views + 1), array('Bid' => $bid));
			return true;
		}
		return false;
	}	
	

	/**
	 *	获取一个标签的所有叶子节点
	 */
	private function get_leaves($tid)
	{
		//config
		$type_father = 1;
		$type_son = 2;
		$ret = array();

		//check type && get leaves
		$ttype = $this->db->select('Ttype')
			->where('Tid', $tid)
			->get('target')
			->result_array()[0]['Ttype'];
		if ($ttype == $type_son)
		{
			return array($tid);
		}
		else if ($ttype == $type_father)
		{
			$sons = $this->db->select('Tid')
				->where('Tfather', $tid)
				->get('target')
				->result_array();
			foreach ($sons as $son)
			{
				$leaves = $this->get_leaves($son['Tid']);
				foreach ($leaves as $leaf)
				{
					array_push($ret, $leaf);
				}
			}
		}

		//return
		return $ret;
	}
	

	/**********************************************************************************************
	 * 对外接口
	 **********************************************************************************************/

	/**
	 * 点赞
	 */
	public function like($form)
	{
		//check token
		$this->load->model('User_model','user');
		$this->user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		//check Bid
		$result = $this->db->select('Blikes')
			->where(array('Bid' => $form['Bid']))
			->get('blog')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('文章不存在');
		}
		$likes = $result[0]['Blikes'];

		//check blog_like
		$where = array('Uusername' => $username, 'Bid' => $form['Bid']);
		if ($this->db->where($where)->get('blog_like')->result_array())
		{
			throw new Exception('不能重复点赞', 402);
		}

		//DO like
		$this->db->insert('blog_like', $where);
		$this->db->update('blog', array('Blikes' => $likes + 1), array('Bid' => $form['Bid']));

	} 


	/**
	 * 添加记录
	 */
	public function register($form)
	{
		//config
		$members = array('Btitle', 'Bauthor', 'Btime', 'Bproblemid', 'Btype');
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
		$members = array('Btitle', 'Bproblemid', 'Btype');
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
		$article['Btargets'] = $this->get_targets($form['Bid']);

		//view & get
		if (isset($username) && $this->add_view($username, $form['Bid']))
		{
			$article['Bviews'] = (string)($article['Bviews'] + 1);
		}

		//check upvoteEnable 
		$article['upvoteEnable'] = true;
		if (isset($form['Utoken']))
		{
			if ($this->db->where(array('Uusername' => $username, 'Bid' => $form['Bid']))
				->get('blog_like')
				->result_array())
			{				
				$article['upvoteEnable'] = false;
			}
		}
		return $article;

	}

	/**
	 * 获取个人文章列表
	 */
	public function get_list($form)
	{

		//config
		$members = array('total', 'page_size', 'page', 'page_max', 'editable', 'tid', 'data');
		$orderby_table = array('Btime' => 1, 'Bviews' => 1, 'Blikes' => 1);

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
			$user = $this->db->where('Utoken', $form['Utoken'])->get('user')->result_array()[0]['Uusername'];
		}

		//target search blogs
		if (isset($form['tid']))
		{
			//get leaves
			$ret['tid'] = $form['tid'];
			$leaves = $this->get_leaves($form['tid']);

			//get bids
			foreach ($leaves as $leaf)
			{
				$where = array('Tid' => $leaf);
				$this->db->or_where($where);
			}
			$this->db->where('Bauthor', $form['Bauthor']);
			$tmp = $this->db->select('blog.Bid')
				->from('blog')
				->join('blog_target', 'blog.Bid=blog_target.Bid')
				->get()
				->result_array();
			foreach ($tmp as $key => $t)
			{
				$bids[$key] = $t['Bid'];
			}
			if ( ! bids)
			{
				throw new Exception("无该标签的博客");
			}
			$bids = array_unique($bids);
		}

		//select articles
		if ( ! isset($form['tid']))
		{
       		$where = array('Bauthor' => $form['Bauthor']);
       		$ret['total'] = $this->db->where($where)->count_all_results('blog');
        }
        else
        {
        	foreach ($bids as $bid)
			{
				$where = array('Bid' => $bid);
				$this->db->or_where($where);
			}
			$ret['total'] = $this->db->count_all_results('blog');
        }
        if (isset($form['page_size']))
        {
			$ret['page_size'] = $form['page_size'];
	        $ret['page_max'] = (int)(($ret['total'] - 1) / $form['page_size']) + 1;
			$ret['page'] = $form['page'];
        	$this->db->limit($form['page_size'], ($form['page'] - 1) * $form['page_size']);
        }

        //set orderby
    	$orderby = isset($form['orderby']) ? $form['orderby'] : 'Btime';
    	if ( ! isset($orderby_table[$orderby]))
    	{
    		throw new Exception("不合法的排序字段");
    	}

    	//get blogs
    	if ( ! isset($form['tid']))
    	{
			$blogs = $this->db->where($where)->order_by($orderby, 'DESC')->get('blog')->result_array(); 
    	}
    	else
    	{
    		foreach ($bids as $bid)
			{
				$where = array('Bid' => $bid);
				$this->db->or_where($where);
			}
			$blogs = $this->db->order_by($orderby, 'DESC')->get('blog')->result_array();
    	}

	    //get targets
       	if ($blogs)
       	{
       		foreach ($blogs as $key => $blog) 
       		{
       			$blogs[$key]['Btargets'] = $this->get_targets($blog['Bid']);
       		}
        }

       	//set upvoteEnable 
       	foreach ($blogs as $key => $blog) 
       	{
			$blogs[$key]['upvoteEnable'] = FALSE;
		}

		//return
		$ret['editable'] = isset($user) && $user == $form['Bauthor'];
		$ret['data'] = $blogs;
		return filter($ret, $members);


		//check upvoteEnable 目前没有实现该功能
		/*foreach ($articles as $key => $article) {
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
		return filter($ret, $members);*/

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

	/*
	 * 删除博客标签
	 */
	public function delete_target($form)
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
			throw new Exception('只有作者可以修改博客', 402);
		}

		//check tag
		$data['Tid'] = $form['Tid'];
		$data['Utoken'] = $form['Utoken'];
		$this->load->model('Target_model', 'target');
		$tag_info = $this->target->get($data);

		//check exist
		$where = array('Bid' => $form['Bid'], 'Tid' => $form['Tid']);
		$repeat = $this->db->get_where('blog_target', $where)
			->result_array();
		if ( ! $repeat)
		{
			throw new Exception('该博客没有该标签');
		}

		//delete
		$this->db->delete('blog_target', $where);

	}

	/**
	 * 获取博客点赞排行
	 */
	public function like_ranking()
	{

		//get like_ranking list
		$data = $this->db->order_by('Blikes', 'DESC')->get('blog')->result_array();
		return $data;
	}

	/**
	 * 获取博客阅读排行
	 */
	public function bviews_ranking()
	{

		//get bviews_ranking list
		$data = $this->db->order_by('Bviews', 'DESC')->get('blog')->result_array();
		return $data;
	}

}