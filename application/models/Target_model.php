<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');


class Target_model extends CI_Model {


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
	public function get($form)
	{
		//config
		$members = array('Tid', 'Tname', 'Ttype');

		//check token
		$this->load->model('User_model', 'user');
		if (isset($form['Utoken']))
		{
		 	$this->user->check_token($form['Utoken']);
		}

		//get target
		$where = array('Tid' => $form['Tid']);
		$result = $this->db->where($where)
			->get('target')
			->result_array();
		if ( ! $result)
		{
			throw new Exception('标签不存在');
		}

		$data = $result[0];
		$data = filter($data, $members);

		//check TFLAG
		if ( current($form['TFLAG']) )
		{
			$where = array('Tfather' => $data['Tid']);
			$data['Tson'] = $this->db->select('Tid,Tname,Ttype')
					->where($where)
					->get('target')
					->result_array();
			return $data;
		}

		return $data;
	}

	/**
	 * 添加标签
	 */
	public function register($form)
	{

		//config
		$members = array('Tfather', 'Tname', 'Ttype');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//check Tfather
		$Tfather = $this->db->get_where('target', array('Tid' => $form['Tfather']))
			->result_array();
		if ( ! $Tfather)
		{
			throw new Exception('父标签不存在！');
		}
		$FatherType = $this->db->select('Ttype')
			->get_where('target', array('Tid' => $form['Tfather']))
			->result_array();
		if ($FatherType[0]['Ttype'] != 1)
		{
			throw new Exception('父标签类型必须为1！');
		}

		//check repeat
		$Tname = $form['Tname'];
		$repeat = $this->db->select('Tname')
			->get_where('target', array('Tfather' => $form['Tfather'], 'Tname' => $Tname))
			->result_array();
		if ($repeat)
		{
			throw new Exception('父标签下已存在同名标签！');
		}

		//insert
		$this->db->insert('target', filter($form, $members));

	}


	/**
	 * 修改标签
	 */
	public function update($form)
	{
		//config
		$members = array('Tid', 'Tname');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//check repeat
		$Tid = $form['Tid'];
		$Tfather = $this->db->select('Tfather')
			->get_where('target', array('Tid' => $Tid))
			->result_array();
		$Tname = $form['Tname'];
		$repeat = $this->db->select('Tname')
			->get_where('target', array('Tfather' => $Tfather[0]['Tfather'], 'Tname' => $Tname))
			->result_array();
		if ($repeat)
		{
			throw new Exception('父标签下已存在同名标签！');
		}

		//update
		$where = array('Tid' => $form['Tid']);
		$this->db->update('target', filter($form, $members), $where);
	}
}
