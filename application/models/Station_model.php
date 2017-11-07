<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 队伍管理
 */
class Station_model extends CI_Model {

	/*****************************************************************************************************
	 * 私有工具集
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
	 * 获取最近比赛列表
	 */
	public function recent_contests()
	{
		//config
		$members_contests = array('id','oj','link','name','start_Time','week','access');

		$last_visit = $this->db->select()->from('station_last_visit')->get()->result_array()[0]['Last_visit'];

		if(!$last_visit)
		{
			$last_visit = "2017-11-03 22:07:00";
		}

		//缓存
		if($this->is_timeout($last_visit) == TRUE)
		{
			$this->db->empty_table('station_last_visit');			
			$this->db->insert('station_last_visit',array('Last_visit' => date("y-m-d h:i:s")));

			$url = "http://contests.acmicpc.info/contests.json";
			$content = file_get_contents($url); 
			$data = (array)json_decode($content);
			
			$this->db->empty_table('station_recent_contests');	
			foreach ($data as $contest) {
				$this->db->insert('station_recent_contests',filter((array)$contest, $members_contests));
			}
		}
		else
		{
			$data = $this->db->get('station_recent_contests')->result_array();
		}

		return $data;
	}
}
