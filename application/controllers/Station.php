<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Utoken");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

defined('BASEPATH') OR exit('No direct script access allowed');


class Station extends CI_Controller {


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
	 * 获取比赛列表
	 */
	public function recent_contests()
	{

		//config
		$members = array('page_size', 'page');
		
		$url = "http://contests.acmicpc.info/contests.json";
		$content = file_get_contents($url); 

		$data['data'] = (array)json_decode($content);

		//get page && page_size
		try
		{

			//get post
			if ($this->input->get('page_size') && $this->input->get('page'))
			{
				$data['page_size'] = $this->input->get('page_size');
				$data['page'] = $this->input->get('page');
				$data['page_max'] = (int)(count($data['data']) - 1)/$data['page_size'] + 1;
			}

		}

		catch(Exception $e)
		{
			output_data($e->getCode(), $e->getMessage(), array());
			return;
		}

		output_data(1, '获取成功', $data);

	}

}