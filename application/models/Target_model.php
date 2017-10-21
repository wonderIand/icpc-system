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
			throw new Exception('记录不存在');
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
}

