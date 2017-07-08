<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 个人训练管理
 */
class User_training_model extends CI_Model {

	/*****************************************************************************************************
	 * 工具集
	 *****************************************************************************************************/


	/**********************************************************************************************
	 * 主接口
	 **********************************************************************************************/


	/**
	 * 添加记录
	 */
	public function post($form) 
	{	
		//config
		$members = array('Uusername', 'UTtitle', 'UTdate', 'UTplace');
		$members_contest = array('UTid', 'UTaddress', 'UTproblemset');
		$members_article = array('UTid', 'UTarticle');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//get user
		$form['Uusername'] = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0]['Uusername'];

		//post
		$this->db->insert('user_training', filter($form, $members));
		$form['UTid'] = $this->db->insert_id();
		$this->db->insert('user_training_contest', filter($form, $members_contest));
		$this->db->insert('user_training_article', filter($form, $members_article));

	}


	/**
	 * 查询记录 by UT_id
	 */
//	public function get


}
