<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 标签管理
 */
class Target_model extends CI_Model {

	/**********************************************************************************************
	 * 私有工具集
	 **********************************************************************************************/

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

		//insert
		$this->db->insert('target', filter($form, $members));

	}


}