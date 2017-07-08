# WonderLand Api文档 - Team

标签（空格分隔）： WonderLand

---

[TOC]

---

## **属性**

- **`【team 表】`**
| 属性名          | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| --------------- | ------ | -------- | -------- | --------- | --------
| **Tteamname**   | 队伍名 | 1        | 16       | char(20)  | **字母/数字/下划线/破折号** 
| **Uusername_1** | 成员1  | -        | -        | char(20)  | -                       
| **Uusername_2** | 成员2  | -        | -        | char(20)  | - 
| **Uusername_3** | 成员3  | -        | -        | char(20)  | -          
| **Tplan_1**     | 成员1近期计划 | - | 200      | char(200) | -           
| **Tplan_2**     | 成员2近期计划 | - | 200      | char(200) | -            
| **Tplan_3**     | 成员3近期计划 | - | 200      | char(200) | -          


---

## **接口 · 注册**

- **接口网址：http://icpc-system.and-who.cn/Team/post**

- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Tusername**   | O      | 1        | 16       | **字母/数字/下划线/破折号**
| **Utoken**      | O      | -        | -        | **持有token的用户即作为成员1**
| **Uusername_2** | O      | -        | -        | -    
| **Uusername_3** | O      | -        | -        | -    


- **成功返回**
```
{
	"type": 1,
	"message": "注册成功",
	"data": []
}
```

---

## **接口 · 获取单队伍信息**

- **接口网址：http://icpc-system.and-who.cn/Team/get/xxx**

- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Tteamname**   | O      | -        | -        | 该字段取代接口url中的xxx
| **Utoken**      | O      | -        | -        | 


- **成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Tteamname": "a teamname",
		"Uusername_1": "aaaau2",
		"Uusername_2": "aaaau2",
		"Uusername_3": "aaaau2",
		"Tplan_1": "",
		"Tplan_2": "",
		"Tplan_3": ""
	}
}
```

---

## **接口 · 获取队伍信息**

- **接口网址：http://icpc-system.and-who.cn/Team/get**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Utoken**    | O      | -        | -        | -
| **search_key** | O      | -        | -        | 检索项，所有就设成字符串"null"
| **search_value** | O      | -        | -      | 检索值，模糊搜索
| **page_size** | O      | 1        | 3        | 每页大小
| **now_page**  | O      | -        | -        | 查询页码


| 允许检索键      | 备注
| --------------- | --------
| **Tteamname**   | 模糊搜索
| **Uusername_1** | 成员1
| **Uusername_2** | 成员2
| **Uusername_3** | 成员3


- **查询示例**
```
{
	"search_key" : "Tteamname",
	"search_value" : "a",
	"page_size" : 2,
	"now_page" : 1
}
```
- **返回结果**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"max_page": 2,
		"page_size": 2,
		"now_page": 1,
		"data": [
			{
				"Tteamname": "ateamname",
				"Uusername_1": "aaaau1",
				"Uusername_2": "aaaau2",
				"Uusername_3": "aaaau2",
				"Tplan_1": "",
				"Tplan_2": "",
				"Tplan_3": ""
			},
			{
				"Tteamname": "ateamname1",
				"Uusername_1": "aaaau1",
				"Uusername_2": "aaaau2",
				"Uusername_3": "aaaau3",
				"Tplan_1": "",
				"Tplan_2": "",
				"Tplan_3": ""
			}
		]
	}
}
```


