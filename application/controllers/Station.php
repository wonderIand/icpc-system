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

		$url = "http://contests.acmicpc.info/contests.json";
		$content = file_get_contents($url); 	
		$data = (array)json_decode($content);
		output_data(1, '获取成功', $data);

	}

}