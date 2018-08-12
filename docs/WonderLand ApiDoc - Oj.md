# WonderLand ApiDoc - Oj

标签（空格分隔）： WonderLand

---

## **日志**

| 日期           | 备注                                       |
| ------------ | ---------------------------------------- |
| **17/12/12** | 添加接口 · **【手动刷新个人近期做题记录】**                |
| **17/12/3**  | 添加接口 · **【获取题量排行】**                      |
| **17/11/29** | 修改接口 · **【修改get_oj_account 返回值:增加Account、删除OJpassword】** |
| **17/11/07** | 添加接口 · **【添加oj关联账号 add_oj_account】**     |
| **17/11/07** | 添加接口 · **【获取oj过题数信息 get_oj_acproblems】** |
| **17/11/08** | 添加接口 · **【查询用户所有oj关联账号信息 get_oj_account】** |
| **17/11/08** | 添加接口 · **【删除用户oj关联账号信息 del_oj_account】** |
| **17/12/01** | 添加接口 · **【查询用户oj近期(两周)过题详细信息 get_oj_acinfo】** |

---

## **属性**

- **【oj_account 表】**

| 属性名            | 中文    | 最小长度 | 最大长度 | 类型       | 特殊要求              |
| -------------- | ----- | ---- | ---- | -------- | ----------------- |
| **Uusername**  | 用户名   | 6    | 16   | char(20) | 字母/数字/下划线/破折号     |
| **OJname**     | oj名称  | -    | -    | char(20) | 为"hdu"或"foj"或"cf" |
| **OJpassword** | oj密码  | -    | -    | char(20) | -                 |
| **OJusername** | oj用户名 | -    | -    | char(20) | -                 |

---

## **接口 · 添加oj关联账号**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/add_oj_account**

- **表单要求**

| 属性名            | 必要性  | 最小长度 | 最大长度 | 特殊要求                            |
| -------------- | ---- | ---- | ---- | ------------------------------- |
| **Uusername**  | O    | 6    | 16   | 字母/数字/下划线/破折号                   |
| **OJname**     | O    | -    | -    | 为"hdu"或"foj"或"cf" 分别表示添加对应oj的账号 |
| **OJpassword** | O    | -    | -    | -                               |
| **OJusername** | O    | -    | -    | -                               |


- **成功返回例子**

```
{
	"type": 1,
	"message": "添加成功",
	"data": []
}
```

---

## **接口 · 获取oj过题数信息**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_acproblems?Uusername=&OJname=**

| **返回的信息包含** | 备注   |
| ----------- | ---- |
| **data**    | 过题数量 |


- **成功返回例子**

```
{
	"type": 1,
	"message": "查询成功",
	"data": "22" 
}
```

---

## **接口 · 查询用户所有oj关联账号信息**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_account**

- **表单要求**

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求          |
| ------------- | ---- | ---- | ---- | ------------- |
| **Uusername** | O    | 6    | 16   | 字母/数字/下划线/破折号 |


- **成功返回例子**

```
{
	"type": 1,
	"message": "查询成功",
	"data": [
		{
			"OJname": "cf",
			"OJusername": "xxxxxxx",
			"Account": "xx"
		},
		{
			"OJname": "foj",
			"OJusername": "xxxxxxx",
			"Account": "xx"
		},
		{
			"OJname": "hdu",
			"OJusername": "xxxxxxx",
			"Account": "xx"
		}
	]
}
```

---

## **接口 · 删除oj关联账号信息**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/del_oj_account**

- **表单要求**

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求                              |
| ------------- | ---- | ---- | ---- | --------------------------------- |
| **Uusername** | O    | 6    | 16   | 字母/数字/下划线/破折号                     |
| **OJname**    | O    | -    | -    | 为"hdu"或"foj"或"cf" 分别表示删除对应oj的关联账号 |


- **成功返回例子**

```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

---

## **接口 · 查询用户oj近期(两周)过题详细信息**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_acinfo?Uusername=**

| **返回的信息包含**    | 备注   |
| -------------- | ---- |
| **username**    | 用户名 |
| **ac_account** | 题数   |
| **OJname**     | OJ名称 |
| **time**       | 过题时间 |
| **name**       | 过题名称 |
| **url**        | 过题链接 |


- **查询示例：http://icpc-system.and-who.cn/Oj/get_oj_acinfo?Uusername=aaaau1**
- **成功返回例子**

```
{
	"type": 1,
	"message": "查询成功",
	"data": {
		"username": "aaaau1",
		"ac_count": 6,
		"ac_info": [
			{
				"OJname": "cf",
				"time": "2017-12-12 01:40:55",
				"name": "900E - Maximum Questions",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/900\/E"
			},
			{
				"OJname": "cf",
				"time": "2017-12-12 01:04:59",
				"name": "900D - Unusual Sequences",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/900\/D"
			},
			{
				"OJname": "cf",
				"time": "2017-12-12 00:38:37",
				"name": "900C - Remove Extra One",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/900\/C"
			},
			{
				"OJname": "cf",
				"time": "2017-12-12 00:20:26",
				"name": "900B - Position in Fraction",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/900\/B"
			},
			{
				"OJname": "cf",
				"time": "2017-12-12 00:07:48",
				"name": "900A - Find Extra One",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/900\/A"
			},
			{
				"OJname": "cf",
				"time": "2017-12-07 00:22:34",
				"name": "121E - Lucky Array",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/121\/E"
			}
		]
	}
}
```

---

## **接口 · 获取题量排行**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_list**

  | **返回的信息包含**  | 备注     |
  | ------------ | ------ |
  | **username** | 用户名    |
  | **realname** | 真实姓名   |
  | **total**    | 总过题数   |
  | **cf**       | cf过题数量 |
  | **hdu**      | cf过题数量 |
  | **foj**      | cf过题数量 |
  | **last2week** | 最近两周过题数 |


- **成功返回例子**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"username": "iaojnh",
			"realname": "尹泽锋",
			"total": "804",
			"cf": "161",
			"hdu": "643",
			"foj": "0",
			"last2week": 19
		},
		{
			"username": "359084415",
			"realname": "郑浩晖",
			"total": "585",
			"cf": "372",
			"hdu": "146",
			"foj": "67",
			"last2week": 12
		},
		{
			"username": "omoshiroi",
			"realname": "omoshiroi",
			"total": "269",
			"cf": "269",
			"hdu": "0",
			"foj": "0",
			"last2week": 3
		},
		{
			"username": "994495jj",
			"realname": "吴媛媛",
			"total": "227",
			"cf": "135",
			"hdu": "78",
			"foj": "14",
			"last2week": 8
		},
		{
			"username": "hbbhbb",
			"realname": "陈汉森",
			"total": "124",
			"cf": "93",
			"hdu": "31",
			"foj": "0",
			"last2week": 0
		},
		{
			"username": "hongzhiyin",
			"realname": "蔡鸿毅",
			"total": "59",
			"cf": "53",
			"hdu": "6",
			"foj": "0",
			"last2week": 0
		}
	]
}
```

---


## **接口 · 刷新个人题量缓存**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/refresh**


- **表单要求**

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求          |
| ------------- | ---- | ---- | ---- | ------------- |
| **Uusername** | O    | 6    | 16   | 字母/数字/下划线/破折号 |


- **成功返回例子**
```
{
	"type": 1,
	"message": "刷新成功",
	"data": {
		"username": "aaaau2",
		"total": 146,
		"cf": "0",
		"hdu": "146",
		"foj": "0"
	}
}
```

---
## **接口 · 手动刷新个人近期做题记录**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/refresh_recent_ac**


- **表单要求**

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求          |
| ------------- | ---- | ---- | ---- | ------------- |
| **Uusername** | O    | 6    | 16   | 字母/数字/下划线/破折号 |


- **成功返回例子**
```
{
	"type": 1,
	"message": "刷新成功",
	"data": {
		"username": "aaaau2",
		"ac_count": 2,
		"ac_info": [
			{
				"OJname": "hdu",
				"time": "2017-12-12 19:55:40",
				"name": "hdu6252",
				"url": "http:\/\/acm.hdu.edu.cn\/showproblem.php?pid=6252"
			},
			{
				"OJname": "hdu",
				"time": "2017-12-12 19:08:03",
				"name": "hdu2544",
				"url": "http:\/\/acm.hdu.edu.cn\/showproblem.php?pid=2544"
			}
		]
	}
}
```

---


## **接口 · 手动刷新个人近期做题记录**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Oj/refresh_all**

- **成功返回例子**
```
{
	"type": 1,
	"message": "请求已成功,正在刷新",
	"data": []
}
```