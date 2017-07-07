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
		$members_team = array('Uusername', 'UTtitle', 'UTdate', 'UTplace');
		$members_contest = array('UTaddress', 'UTproblemset');
		$members_article = array('UTarticle');

		//check token
		$this->load->model('User_model', 'user');
		$this->user->check_token($form['Utoken']);

		//get user
		$form['Uusername'] = $this->db->select('Uusername')
			->where(array('Utoken' => $form['Utoken']))
			->get('user')
			->result_array()[0];

	}


}
