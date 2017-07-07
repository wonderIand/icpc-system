<?php


$config = array(


	//登录表单
	'user_login' => array(
		array(
			'field' => 'Uusername',
			'label' => '用户名',
			'rules' => 'required'
			),
		array(
			'field' => 'Upassword',
			'label' => '密码',
			'rules' => 'required'
			)
		),


	//用户注册表单
	'user_post' => array(
		array(
			'field' => 'Uusername',
			'label' => '用户名',
			'rules' => 'required|min_length[6]|max_length[16]|alpha_dash'
			),
		array(
			'field' => 'Upassword',
			'label' => '密码',
			'rules' => 'required|min_length[6]|max_length[16]'
			),
		array(
			'field' => 'Urealname',
			'label' => '真实姓名',
			'rules' => 'required|min_length[1]|max_length[10]'
			),
		array(
			'field' => 'Unickname',
			'label' => '昵称',
			'rules' => 'required|min_length[1]|max_length[15]'
			)
		),


	//获取用户信息表单
	'user_get_one' => array(
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'Uusername',
			'label' => '用户名',
			'rules' => 'required'
			)
		),


	//获取用户信息表单
	'user_get' => array(
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'search_key',
			'label' => '检索项',
			'rules' => 'required'
			),
		array(
			'field' => 'search_value',
			'label' => '检索值',
			'rules' => 'required'
			),
		array(
			'field' => 'page_size',
			'label' => '每页大小',
			'rules' => 'required|numeric'
			),
		array(
			'field' => 'now_page',
			'label' => '查询页码',
			'rules' => 'required|numeric'
			)
		),

	//队伍注册表单
	'team_post' => array(
		array(
			'field' => 'Tteamname',
			'label' => '队伍名',
			'rules' => 'required|min_length[1]|max_length[16]|alpha_dash'
			),
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'Uusername_2',
			'label' => '成员2',
			'rules' => 'required'
			),
		array(
			'field' => 'Uusername_3',
			'label' => '成员3',
			'rules' => 'required'
			)
		),


	//获取队伍信息表单
	'team_get_one' => array(
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'Tteamname',
			'label' => '队伍名',
			'rules' => 'required'
			)
		),

	//获取用户信息表单
	'team_get' => array(
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'search_key',
			'label' => '检索项',
			'rules' => 'required'
			),
		array(
			'field' => 'search_value',
			'label' => '检索值',
			'rules' => 'required'
			),
		array(
			'field' => 'page_size',
			'label' => '每页大小',
			'rules' => 'required|numeric'
			),
		array(
			'field' => 'now_page',
			'label' => '查询页码',
			'rules' => 'required|numeric'
			)
		),

	//个人训练记录添加表单
	'user_training_post' => array(
		array(
			'field' => 'Utoken',
			'label' => '登陆凭据',
			'rules' => 'required'
			),
		array(
			'field' => 'UTtitle',
			'label' => '训练标题',
			'rules' => 'required|min_length[1]|max_length[50]'
			),
		array(
			'field' => 'UTplace',
			'label' => '排名',
			'rules' => 'required|numeric'
			),
		array(
			'field' => 'UTaddress',
			'label' => '训练地址',
			'rules' => 'required|min_length[1]|max_length[150]'
			),
		array(
			'field' => 'UTproblemset',
			'label' => '题集',
			'rules' => 'required'
			)
		)

	);