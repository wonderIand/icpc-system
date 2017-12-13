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
		$now = date("y-m-d H:i:s");
		$dis = strtotime($now) - strtotime($pre);
		return   $dis > 7200;
	}


	/**
	 * 更新总体量
	 */
	private function update_total_ac($form)
	{
		$member = array('Uusername', 'TotalAC');
		$where = array('Uusername' => $form['Uusername']);
		$data = array('Uusername' => $form['Uusername'], 'Total' => 0);
		$data['TotalAC'] = 0;
		$ojs = array('cf', 'foj', 'hdu');
		foreach ($ojs as $oj) {
			$where['OJname'] = $oj;
			if ( $temp = $this->db->select('ACproblem')
							->where($where)
							->get('oj_last_visit')
							->result_array())
			{
				$data['TotalAC'] += $temp[0]['ACproblem'];
			}

		}
		$where = array('Uusername' => $form['Uusername']);
		if ( ! $this->db->select('Uusername')
			->where($where)
			->get('user')
			->result_array())
		{
			return;
		}
		if ( ! $this->db->select('Uusername')
						->where($where)
						->get('oj_total_ac')
						->result_array())
		{
			$this->db->insert('oj_total_ac', filter($data, $member), $where);
		}
		else
		{
			$this->db->update('oj_total_ac', filter($data, $member), $where);
		}
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
			$data['Last_visit'] = date("y-m-d H:i:s");
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

			//update totalac
			$this->update_total_ac($data);
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

		//check OJ
		$where = array('OJname' => $form['OJname'],'Uusername' => $form['Uusername']);
		if ( $result = $this->db->select('Uusername')
			->where($where)
			->get('oj_account')
			->result_array())
		{
			throw new Exception("已关联hdu账号");
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
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
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
		$this->db->delete('oj_last_visit',$where);
		if ($this->db->select('Uusername')
					->where(array('Uusername' => $form['Uusername']))
					->get('oj_last_visit')
					->result_array())
		{
			$this->update_total_ac($form);
		}
		else
		{
			$this->db->delete('oj_total_ac',array('Uusername' => $form['Uusername']));
		}
	}
	/**
	 * 查看cf近期两周的提交ac记录
	 */
	public function get_cf_acinfo($form)
	{
		//config
		$members = array('Uusername', 'OJname');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
		}

		//check Uusername
		$where = array('Uusername' => $form['Uusername']);
		if ( ! $result = $this->db->select('Uusername')
			->where($where)
			->get('user')
			->result_array())
		{
			throw new Exception('该用户不存在');
		}

		//get OJusername & OJpassword
		$where['OJname'] = 'cf';
		$OJuser = $this->db->select(array('OJusername', 'OJpassword'))
						->where($where)
						->get('oj_account')
						->result_array();
		if ( ! $OJuser)
		{
			return array('ac_count' => 0, 'ac_info' => array());
		}

		//last_visit is not timeout
		$where = array('Uusername'=>$form['Uusername']);
		$last_visit = $this->db->get_where('oj_recent_ac_last_visit', $where)
						->result_array();
		$last_visit = ! $last_visit 
			?  "2017-12-07 23:18:10" 
			: $last_visit[0]['Last_visit'];
		if ( ! $this->is_timeout($last_visit))
		{
			$mem = array('OJname', 'time', 'name', 'url');
			$acinfo = $this->db->select($mem)
								->order_by('time', 'DESC')
								->get_where('oj_recent_acinfo', $where)
								->result_array();
			return array('ac_count' => count($acinfo), 'ac_info' => $acinfo);
		}

		//重新抓取
		$from = 1;
		$count = 1000;
		$num = 0;
		date_default_timezone_set("Asia/Shanghai");
		$tow_week_ago = strtotime("-2 week");
		$data = array();
		$map = array();
		while (True)
		{
			$url = "http://codeforces.com/api/user.status?handle=".$OJuser[0]['OJusername'].
					"&from=".$from."&count=".$count;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			$content = json_decode($content, true);
			
			if (! $content)
			{
				throw new Exception('用户名错误');
			}
				
			if ($content['status'] != 'OK')
			{
				break;
			}
			$flag = false;
			$text = $content['result'];
			foreach($text as $value)
			{
				if ($value['creationTimeSeconds'] < $tow_week_ago)
				{
					$flag = true;
					break;
				}
				if ($value['verdict'] != 'OK')
				{
					continue;
				}
				$problem = $value['problem'];
				if (isset($map[$problem['contestId'].$problem['index']." - ".$problem['name']]))
				{
					continue;
				}
				$data[$num]['OJname'] = 'cf';
				$data[$num]['time'] = date("Y-m-d H:i:s", $value['creationTimeSeconds']);
				$data[$num]['name'] = $problem['contestId'].$problem['index']." - ".$problem['name'];
				$map[$problem['contestId'].$problem['index']." - ".$problem['name']] = 1;
				if (sizeof($value['author']['members']) > 1)
				{
					$data[$num]['url'] = 'http://codeforces.com/problemset/'.'gymProblem/'
											.$problem['contestId'].'/'.$problem['index'];
				}
				else
				{
					$data[$num]['url'] = 'http://codeforces.com/problemset/'.'problem/'
											.$problem['contestId'].'/'.$problem['index'];	
				}
				$num = $num + 1;
			}
			if ($flag) 
			{
				break;
			}
			$form = $form + $count;
		}
		$res['ac_count'] = $num;
		$res['ac_info'] = $data;

		//缓存添加的代码

		//删除旧的数据
		$where = array('Uusername' => $form['Uusername']);
		$this->db->delete('oj_recent_acinfo', $where);
		$this->db->delete('oj_recent_ac_last_visit', $where);

		//添加新的数据
		$this->load->helper('date');
		$where['Last_visit'] = date("y-m-d H:i:s");
		$this->db->insert('oj_recent_ac_last_visit', $where);
		foreach ($res['ac_info'] as $value) {
			$data = array('OJname' => $value['OJname'],
						'time' => $value['time'],
						'name' => $value['name'],
						'url' => $value['url'],
						'Uusername' => $form['Uusername'],
					 );
			$this->db->insert('oj_recent_acinfo',$data);
		}
		return $res;
	}

	/**
	 * 查看hdu近期两周的提交ac记录
	 */
	public function get_hdu_acinfo($form)
	{
		//config
		$members = array('Uusername', 'OJname');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
			$this->user->check_token($form['Utoken']);
		}

		//check Uusername
		$where = array('Uusername' => $form['Uusername']);
		if (! $result = $this->db->select('Uusername')
			->where($where)
			->get('user')
			->result_array())
		{
			throw new Exception('该用户不存在');
		}
		 
		//get OJusername & OJpassword
		$where['OJname'] = 'hdu';
		$OJuser = $this->db->select(array('OJusername', 'OJpassword'))
						->where($where)
						->get('oj_account')
						->result_array();
		if ( ! $OJuser)
		{
			return array('ac_count' => 0, 'ac_info' => array());
		}

		//http://acm.hdu.edu.cn/status.php?first=0&user=starsets&pid=0&lang=0&status=5
		$first = 0;
		$num = 0;
		date_default_timezone_set("Asia/Shanghai");
		$tow_week_ago = strtotime("-2 week");
		$data = array();
		$map = array();
		while (True)
		{
			$url = "http://acm.hdu.edu.cn/status.php?"."first=".$first."&user=".$OJuser[0]['OJusername'].
					"&pid=0&lang=0&status=5";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			
			if (! $content)
			{
				throw new Exception('用户名错误');
			}
			
			$re = "/(\d+)<\/td><td>(\d+-\d+-\d+ \d+:\d+:\d+)<\/td><td><font color=red>Accepted<\/font><\/td><td><a href=\"\/showproblem.php\?pid=(\d+)\"/";
			$content= file_get_contents($url);
			preg_match_all($re,$content,$match);
			
			if (! $match)
			{
				throw new Exception('用户名错误');
			}

			$flag = false;
			$len = count($match[0]);
			for ($i = 0; $i < $len; $i++)
			{
				if (strtotime($match[2][$i]) < $tow_week_ago)
				{
					$flag = true;
					break;
				}
				if (isset($map['hdu'.$match[3][$i]]))
				{
					continue;
				}
				$data[$num]['OJname'] = 'hdu';
				$data[$num]['time'] = $match[2][$i];
				$data[$num]['name'] = 'hdu'.$match[3][$i];
				$map[$data[$num]['name']] = 1;
				$data[$num]['url'] = 'http://acm.hdu.edu.cn/showproblem.php?pid='.$match[3][$i];
				
				$first = $match[1][$i];
				$num = $num + 1;
			}
			if ($flag) 
			{
				break;
			}
		}
		return array('ac_count' => $num, 'ac_info' => $data);
	}
	/**
	 * 获取题量排行
	 */
	public function get_list()
	{
		$result_list = array();
		if ( $data = $this->db->select()
						->order_by('TotalAC','DESC')
						->get('oj_total_ac')
						->result_array() )
		{
			foreach ($data as $key => $value) 
			{
				$where = array('Uusername' => $value['Uusername']);
				$temp = array('username' => $value['Uusername']);
				$temp['realname'] = $this->db->select('Urealname')
					->where($where)
					->get('user_info')
					->result_array()[0]['Urealname'];
				$temp['total'] = $value['TotalAC'];
				$oj_list = array('cf', 'hdu', 'foj');
				foreach ($oj_list as $oj) {
					$where['OJname'] = $oj;
					$temp[$oj] = "0";
					$result = $this->db->select('ACproblem')
								->where($where)
								->get('oj_last_visit')
								->result_array();
					if ($result)
					{
						$temp[$oj] = $result[0]['ACproblem'];
					}
				}

				//get cf recent ac
				$where = array('Uusername' => $value['Uusername']);
				$temp['last2week'] = $this->db->select('Uusername')
				  			->where($where)
							->count_all_results('oj_recent_acinfo');

				array_push($result_list, $temp);
			}
		}
		else
		{
			throw new Exception("请关联相关账户");
			
		}
		return $result_list;
	}


	/**
	 * 手动刷新题量
	 */
	public function refresh($form)
	{
		//congig
		$member = array('Uusername', 'OJname', 'Last_visit', 'ACproblem');
  		$members = array('Uusername', 'OJname');

  		//check token
  		$this->load->model('User_model', 'my_user');
		if (isset($form['Utoken'])) 
		{
			$this->my_user->check_token($form['Utoken']);
		}
		
		//refresh
		$where = array('Uusername' => $form['Uusername']);
		$ojs = array('cf', 'hdu', 'foj');
		$result = array('username' => $form['Uusername'], 'total' => 0);
		foreach ($ojs as $oj) {
			$result[$oj] = "0";
			$where['OJname'] = $oj;
			if ($this->db->select('Uusername')
				->where($where)
				->get('oj_last_visit')
				->result_array())
			{
				switch ($oj) {
					case 'cf':
						$result[$oj] = $this->get_cf_acproblems($where);
						break;
					case 'foj':
						$result[$oj] = $this->get_foj_acproblems($where);
						break;
					case 'hdu':
						$result[$oj] = $this->get_hdu_acproblems($where);
					default:
						break;
				}
			}
			$result['total'] += $result[$oj];
  			$data = array(
  				'Last_visit' => date("y-m-d H:i:s"),
  				'ACproblem' => $result[$oj]
  				);
			$this->db->update('oj_last_visit', filter($data, $member), $where);
		}

		$where = array('Uusername' => $form['Uusername']);
		$this->update_total_ac($where);

		return $result;
	}

	/**
	 * 手动刷新近期做题数
	 */
	public function refresh_recent_ac($form)
	{
		//congig
		$member = array('Uusername');
		$where = array('Uusername' => $form['Uusername'], 'Last_visit' => "2017-12-07 23:18:10");
		$this->db->replace('oj_recent_ac_last_visit', $where);
		$datacf = $this->get_cf_acinfo(filter($form, $member));
		$datahdu = $this->get_hdu_acinfo(filter($form, $member));
		$data['username'] = $form['Uusername'];
		$data['ac_count'] = $datacf['ac_count'] + $datahdu['ac_count'];
		$data['ac_info'] = null;
		$now = 0;
		$i = 0;
		while ($i < $data['ac_count'])
		{
			if ($now < $datacf['ac_count'])
			{
				$data['ac_info'][$i] = $datacf['ac_info'][$now];
				$i = $i + 1;
			}
			if ($now < $datahdu['ac_count'])
			{
				$data['ac_info'][$i] = $datahdu['ac_info'][$now];
				$i = $i + 1;
			}
			$now = $now + 1;
		}
		if ($data['ac_count'] != 0)
		{
			array_multisort(array_column($data['ac_info'], 'time'), SORT_DESC, $data['ac_info']);
		}

		return $data;

	}

}