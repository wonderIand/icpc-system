<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Oj_model extends CI_Model {

	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/

	/**
	 * 检测时间差,单位:秒
	 */
	private function is_timeout($pre)
	{
		$this->load->helper('date');
		$now = date("y-m-d h:i:s");
		$dis = strtotime($now) - strtotime($pre);
		return   $dis > 3600;
	}


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/


	/**
	 * 添加缓存
	 */
	public function get_cache($form)
	{
		//config
		$member = array('Uusername', 'OJname', 'Last_visit', 'ACproblem');
		$members = array('Uusername', 'OJname');

		$where = array('Uusername' => $form['Uusername'], 'OJname' => $form['OJname']);
		if ( !$visit = $this->db->select('Last_visit')
			->where($where)
			->get('oj_last_visit')
			->result_array())
		{
			$last_visit = "2017-11-03 22:07:00";
		}
		else
		{
			$last_visit = $visit[0]['Last_visit'];
		}	

		//缓存
		if($this->is_timeout($last_visit) == TRUE)
		{
			$data['Uusername'] = $form['Uusername'];
			$data['OJname'] = $form['OJname'];
			$data['Last_visit'] = date("y-m-d h:i:s");
			if ($form['OJname'] == 'cf')
			{
				$data['ACproblem']= $this->get_cf_acproblems(filter($form, $members));
			}
			else if ($form['OJname'] == 'foj')
			{
				$data['ACproblem'] = $this->get_foj_acproblems(filter($form, $members));
			}
			else if ($form['OJname'] == 'hdu')
			{
				$data['ACproblem'] = $this->get_hdu_acproblems(filter($form, $members));
			}
			else
			{
				throw new Exception("OJ名称出错");	
			}
			
			//update&&insert
			$rel = $data['ACproblem'];
			if ( !$visit )
			{
				$this->db->insert('oj_last_visit', filter($data, $member), $where);
			}
			else
			{
				$this->db->update('oj_last_visit',filter($data, $member), $where);
			}
		}
		else
		{
			$rel = $this->db->select('ACproblem')
							->where($where)
							->get('oj_last_visit')
							->result_array()[0]['ACproblem'];
		}
		return $rel;
	}


	/**
	 * 添加hdu账号
	 */
	public function add_hdu_account($form)
	{
		//config
		$members = array('Uusername', 'OJname', 'OJusername', 'OJpassword');

		
		//check token
		$this->load->model('User_model', 'my_user');
		$this->my_user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		if ($username != $form['Uusername'])
		{
			throw new Exception('请重新登录');
		}

		//check OJusername
		$where = array('OJname' => $form['OJname'],'OJusername' => $form['OJusername']);
		if ( $result = $this->db->select('OJusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("账户已被关联");
		}
		
		//check hdu username and password
		$url ='http://acm.hdu.edu.cn/status.php';
		$ch1 = curl_init();
		$cookie_file = dirname(__FILE__).'/cookie.txt';
		curl_setopt($ch1, CURLOPT_URL, $url);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch1, CURLOPT_COOKIEJAR, $cookie_file);
		if (curl_exec($ch1))
		{
			$content = curl_multi_getcontent($ch1);
		}
		else
		{
			throw new Exception('网页爬取失败',401);
		}
		curl_close($ch1);

		$url = 'http://acm.hdu.edu.cn/userloginex.php?action=login';
		$post_data = array
					(
						'username' => $form['OJusername'],
						'userpass' => $form['OJpassword'],
						'login' => 'Sign In',
					);
		$post_data = http_build_query($post_data);
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_file);
		curl_exec($ch2);
		curl_close($ch2);
		

		$url = "http://acm.hdu.edu.cn/status.php";
		$ch3 = curl_init();
		curl_setopt($ch3, CURLOPT_URL, $url);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie_file);
		$content = curl_exec($ch3);
		curl_close($ch3);

		//删除cookie文件
		unlink($cookie_file);
		
		$re = "/".$form['OJusername']."/";
		if (! preg_match($re,$content,$match))
		{
			throw new Exception("关联账户用户名或密码错误");
		}
		$this->db->insert('oj_account',filter($form, $members));
	}

	/**
	 * 添加foj账号
	 */
	public function add_foj_account($form)	
	{
		//config
		$members = array('Uusername', 'OJname', 'OJusername', 'OJpassword');

		
		//check token
		$this->load->model('User_model', 'my_user');
		$this->my_user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		if ($username != $form['Uusername'])
		{
			throw new Exception('请重新登录');
		}

		//check OJusername
		$where = array('OJname' => $form['OJname'],'OJusername' => $form['OJusername']);
		if ( $result = $this->db->select('OJusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("账户已被关联");
		}
		
		//check OJ
		$where = array('OJname' => $form['OJname'],'Uusername' => $form['Uusername']);
		if ( $result = $this->db->select('Uusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("已关联foj账号");
		}

		$this->db->insert('oj_account',filter($form, $members));
	}
	/**
	 * 添加cf账号
	 */
	public function add_cf_account($form)
	{
		//config
		$members = array('Uusername', 'OJname', 'OJusername', 'OJpassword');

		
		//check token
		$this->load->model('User_model', 'my_user');
		$this->my_user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		if ($username != $form['Uusername'])
		{
			throw new Exception('请重新登录');
		}

		//check OJusername
		$where = array('OJname' => $form['OJname'],'OJusername' => $form['OJusername']);
		if ( $result = $this->db->select('OJusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("账户已被关联");
		}
		
		//check OJ
		$where = array('OJname' => $form['OJname'],'Uusername' => $form['Uusername']);
		if ( $result = $this->db->select('Uusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("已关联cf账号");
		}

		//check hdu username and password
		//获取网页链接
		$url ='http://codeforces.com/enter?back=%2Fproblemset%2Fstandings';

		//爬取网页
		
		//get csrf_token以及cookie
		$ch1 = curl_init();
		$cookie_file = dirname(__FILE__).'/cookie.txt';
		curl_setopt($ch1, CURLOPT_URL, $url);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch1, CURLOPT_COOKIEJAR, $cookie_file);
		if (curl_exec($ch1))
		{
			$text = curl_multi_getcontent($ch1);
		}
		else
		{
			throw new Exception('网页爬取失败',401);
		}
		curl_close($ch1);

		//正则匹配获取csrf_token
		$re = "/<meta name=\"X-Csrf-Token\" content=\"(\w*)\"\/>/";
		if (preg_match($re,$text,$match)){
			$token = $match[1];
		}
		else
		{
			throw new Exception("内容爬取失败",401);
		}
			
	
		//模拟登录
		$post_data = array
					(
						'csrf_token' => $token,
						'action' => 'enter',
						'handle' => $form['OJusername'],
						'password' => $form['OJpassword']
					);
		$post_data = http_build_query($post_data);
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_file);
		curl_exec($ch2);
		curl_close($ch2);

		$url = "http://codeforces.com/problemset/standings";
		$ch3 = curl_init();
		curl_setopt($ch3, CURLOPT_URL, $url);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie_file);
		$content = curl_exec($ch3);
		curl_close($ch3);

		//删除cookie文件
		unlink($cookie_file);
		//正则匹配获取判断是否登录成功
		$re = "/".$form["OJusername"]."/i";
		if (! preg_match($re,$content,$match))
		{
			throw new Exception("关联账户用户名或密码错误");
		}		

		$this->db->insert('oj_account',filter($form, $members));
	}

	/**
	 * 获取hdu过题数
	 */
	public function get_hdu_acproblems($form)
	{
		//config
		$members = array('Uusername', 'OJname');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
		}

		//check OJname
		if (isset($form['OJname']))
		{
			if ($form['OJname'] != 'hdu')
			{
				throw new Exception('oj名称错误');
			}
		}
		else
		{
			throw new Exception('oj名称错误');
		}

		//get OJusername
		$OJusername = $this->db->select('OJusername')
						->where(array('OJname' => $form['OJname'],
							'Uusername' => $form['Uusername']))
						->get('oj_account')->result_array();
		if (! $OJusername)
		{
			throw new Exception('用户名错误');
		}
		//get url
		$url = 'http://acm.hdu.edu.cn/userstatus.php?user='.$OJusername[0]['OJusername'];

		//get html
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if (curl_exec($ch))
		{
			$text = curl_multi_getcontent($ch);
		}
		else
		{
			throw new Exception('用户名错误');
		}

		//正则匹配获取过题数信息
		$re = "/(Problems Solved<\/td><td align=center>)(\d+)/i";
		if (preg_match($re, $text, $match))
		{
			$res = $match[2];
		}
		else
		{
			throw new Exception("用户名错误");
		}
		curl_close($ch);

		return $res;
	}

	/**
	 * 获取foj过题数
	 */
	public function get_foj_acproblems($form)
	{
		//config
		$members = array('Uusername', 'OJname');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
		}

		//check OJname
		if (isset($form['OJname']))
		{
			if ($form['OJname'] != 'foj')
			{
				throw new Exception('oj名称错误');
			}
		}
		else
		{
			throw new Exception('oj名称错误');
		}

		//get OJusername
		$OJusername = $this->db->select('OJusername')
						->where(array('OJname' => $form['OJname'],
								'Uusername' => $form['Uusername']))
						->get('oj_account')->result_array();
		if (! $OJusername)
		{
			throw new Exception('用户名错误');
		}

		//get url
		$url = 'http://acm.fzu.edu.cn/user.php?uname='.$OJusername[0]['OJusername'];

		//get html
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if (curl_exec($ch))
		{
			$text = curl_multi_getcontent($ch);
		}
		else
		{
			throw new Exception('用户名错误');
		}

		//正则匹配获取过题数信息
		$re = "/\t<td>Total Accepted<\/td>\r\n\t<td>(\d+)<\/td>\r\n/i";
		if (preg_match($re, $text, $match))
		{
			$res = $match[1];
		}
		else
		{
			throw new Exception("用户名错误");
		}
		curl_close($ch);

		return $res;
	}

	/**
	 * 获取cf过题数
	 */
	public function get_cf_acproblems($form)
	{
		//config
		$members = array('Uusername', 'OJname');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
		}

		//check OJname
		if (isset($form['OJname']))
		{
			if ($form['OJname'] != 'cf')
			{
				throw new Exception('oj名称错误');
			}
		}
		else
		{
			throw new Exception('oj名称错误');
		}

		//get OJusername & OJpassword
		$OJuser = $this->db->select(array('OJusername', 'OJpassword'))
						->where(array('OJname' => $form['OJname'],
								'Uusername' => $form['Uusername']))
						->get('oj_account')->result_array();
		if (! $OJuser)
		{
			throw new Exception('用户名错误');
		}
		//get url
		$url ='http://codeforces.com/enter?back=%2Fproblemset%2Fstandings';
			
		//get csrf_token and cookie
		$ch1 = curl_init();
		$cookie_file = dirname(__FILE__).'/cookie.txt';
		curl_setopt($ch1, CURLOPT_URL, $url);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch1, CURLOPT_COOKIEJAR, $cookie_file);
		if (curl_exec($ch1))
		{
			$text = curl_multi_getcontent($ch1);
		}
		else
		{
			throw new Exception('用户名错误');
		}
		curl_close($ch1);

		//正则匹配获取csrf_token
		$re = "/<meta name=\"X-Csrf-Token\" content=\"(\w*)\"\/>/";
		if (preg_match($re, $text, $match)){
			$token = $match[1];
		}
		else
		{
			throw new Exception("用户名错误");
		}	

		//模拟登录
		$post_data = array
					(
						'csrf_token' => $token,
						'action' => 'enter',
						'handle' => $OJuser[0]['OJusername'],
						'password' => $OJuser[0]['OJpassword']
					);
		$post_data = http_build_query($post_data);
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_file);
		curl_exec($ch2);
		curl_close($ch2);

		$url = "http://codeforces.com/problemset/standings";
		$ch3 = curl_init();
		curl_setopt($ch3, CURLOPT_URL, $url);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie_file);
		$content = curl_exec($ch3);
		curl_close($ch3);

		//删除cookie文件
		unlink($cookie_file);
		//正则匹配获取过题数信息
		$re = "/".$OJuser[0]["OJusername"]."<\/a>\s+<\/td>\s+<td >\s+(\d+)/i";
		if (preg_match($re, $content, $match))
		{
			$res = $match[1];
		}
		else
		{
			throw new Exception('用户名错误');
		}

		return $res;
	}
	/**
	 * 查询所有oj(hdu,foj,cf)关联账号信息
	 */
	public function get_oj_account($form)
	{
		//config
		$members = array('Uusername');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		if ($username != $form['Uusername'])
		{
			throw new Exception('请重新登录');
		}

		$OJuserinfo = $this->db->select(array('OJname', 'OJusername'))
					->where(array('Uusername' => $form['Uusername']))
					->get('oj_account')->result_array();


		//get oj_account
		$form['Uusername'] = $form['Uusername'];
		foreach ($OJuserinfo as $key => $value) {
			
			$form['OJname'] = $value['OJname'];
			$value['Account'] = $this->oj->get_cache($form);
			$OJuserinfo[$key] = $value;
		}	

		if (! $OJuserinfo)
		{
			throw new Exception('未关联oj账户');
		}

		return $OJuserinfo;
	}	
	/**
	 * 删除用户的oj关联账号信息
	 */
	public function del_oj_account($form)
	{
		//config
		$members = array('Uusername', 'OJname');
		$where = array('Uusername' => $form['Uusername'],
						'OJname' => $form['OJname']);
		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);
		$username = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		if ($username != $form['Uusername'])
		{
			throw new Exception('请重新登录');
		}

		//检查该记录是否存在
		$OJuserinfo = $this->db->select(array('Uusername'))
					->where($where)->get('oj_account')->result_array();

		if (! $OJuserinfo)
		{
			throw new Exception('未关联该OJ账号');
		}

		$this->db->delete('oj_account', $where);
	}


	/**
	 * 获取题量排行
	 */
	public function get_list($form)
	{

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);			
		}

		//get
		$where = array('OJname' => $form['OJname']);
		$data = $this->db->select('Uusername, ACproblem')
				->order_by('ACproblem', $form['Sort'])
				->get_where('oj_last_visit', array('OJname' => $form['OJname']))
				->result_array();


		return $data;
	}

}