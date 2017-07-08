# WonderLand ApiDoc - User

标签（空格分隔）： WonderLand

---

[TOC]

---

## **属性**

- **【user 表】**

| 属性名        | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| ------------- | ------ | -------- | -------- | --------- | --------
| **Uusername** | 用户名 | 6        | 16       | char(20)  | 字母/数字/下划线/破折号
| **Upassword** | 密码   | 6        | 16       | char(20)  | -                     
| **Utoken**    | 凭据   | 30       | 30       | char(30)  | -
| **Ulast_visit**| 上次访问 | -     | -        | TIMESTAMP | - 


- **【user_info 表】**

| 属性名        | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| ------------- | ------ | -------- | -------- | --------- | --------
| **Uusername** | 用户名 | 6        | 16       | char(20)  | 字母/数字/下划线/破折号
| **Unickname** | 昵称   | 1        | 15       | char(20)  | 字母/数字/下划线/破折号 
| **Urealname** | 真名   | 1        | 10       | char(20)  | -                       


---

## **接口 · 注册**

- **接口网址：http://icpc-system.and-who.cn/User/post**

- **表单要求**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Uusername** | O      | 6        | 16       | 字母/数字/下划线/破折号
| **Upassword** | O      | 6        | 16       | -                      
| **Urealname** | O      | 1        | 10       | -                     
| **Unickname** | O      | 1        | 15       | -                     


- **成功返回例子**

```
{
	"type": 1,
	"message": "注册成功",
	"data": []
}
```

---

## **接口 · 登陆**

- **接口网址：http://icpc-system.and-who.cn/User/login**

- **表单要求**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Uusername** | O      | -        | -        | - 
| **Upassword** | O      | -        | -        | -


- **成功返回**

```
{
	"type": 1,
	"message": "登陆成功",
	"data": {
		"Utoken": "0waigMnljb4QuY9RAqsxEUveTD7OCL"
	}
}
```

---

## **接口 · 获取单用户信息**

- **接口网址：http://icpc-system.and-who.cn/User/get/xxx**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Uusername** | O      | -        | -        | 该字段取代接口url中的xxx
| **Utoken**    | O      | -        | -        | -


- **成功返回**
````
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Uusername": "aaaau6",
		"Urealname": "a 真实姓名真实姓名",
		"Unickname": "a",
		"Ulast_visit": "2017-07-06 17:16:24"
	}
}
```

---

## **接口 · 获取用户信息**

- **接口网址：http://icpc-system.and-who.cn/User/get**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Utoken**    | O      | -        | -        | -
| **search_key** | O      | -        | -        | 检索项，所有就设成字符串"null"
| **search_value** | O      | -        | -      | 检索值，模糊搜索
| **page_size** | O      | 1        | 3        | 每页大小
| **now_page**  | O      | -        | -        | 查询页码


| 允许检索键      | 备注
| --------------- | --------
| **Uusername**   | 模糊搜索
| **Ulast_visit** | 模糊搜索


- **查询示例**
```
{
	"page_size" : 3,
	"now_page" : 1,
	"search_key" : "Uusername",
	"search_value" : "au"
}
```
- **返回结果**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"max_page": 2,
		"page_size": 3,
		"now_page": 1,
		"data": [
			{
				"Uusername": "aaaau2",
				"Ulast_visit": "2017-07-06 23:58:09",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			},
			{
				"Uusername": "aaaau1",
				"Ulast_visit": "2017-07-07 13:58:54",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			},
			{
				"Uusername": "aaaau3",
				"Ulast_visit": "2017-07-07 08:59:21",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			}
		]
	}
}
```
