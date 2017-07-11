# WonderLand ApiDoc - User

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/07/--** | 添加接口 · **【用户注册 · register】**
| **17/07/--** | 添加接口 · **【用户登陆 · login】**
| **17/07/--** | 添加接口 · **【获取某用户信息 · get】**
| **17/07/--** | 添加接口 · **【获取用户列表 · get_list】**
| **17/07/09** | **【WonderLand Beta 1.0 Compeleted】**


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
| **Unickname** | 昵称   | 1        | 15       | char(20)  | -
| **Urealname** | 真名   | 1        | 10       | char(20)  | -                       


---

## **接口 · 注册**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User/register**

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

- **请求方法：POST**
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

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User/get?:Uusername**
- **接口网址：http://icpc-system.and-who.cn/User/get 获取持有token的用户用户信息**

| **返回的个人信息包含** | 备注
| ---------------------- | ----
| **Uusername**          | 用户名
| **Urealname**          | 真实姓名
| **Unickname**          | 昵称
| **Ulast_visit**        | 上次访问时间


- **成功返回**

```
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

## **接口 · 获取用户列表**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User/get_list**

| **`GET` 字段可选项** | 备注
| --------------- | --------
| **page_size**   | 设置分页大小，和 **page** 成对存在
| **page**        | 设置查询页，和 **page_size** 成对存在


| **返回的个人信息包含** | 备注
| ---------------------- | ----
| **Uusername**          | 用户名
| **Urealname**          | 真实姓名
| **Unickname**          | 昵称
| **Ulast_visit**        | 上次访问时间


- **查询示例：http://icpc-system.and-who.cn/User/get_list?page_size=2&page=2**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"page_size": "2",
		"page": "2",
		"page_max": 3,
		"data": [
			{
				"Uusername": "aaaau3",
				"Ulast_visit": "2017-07-07 08:59:21",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			},
			{
				"Uusername": "aaaau4",
				"Ulast_visit": "2017-07-07 08:59:23",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			}
		]
	}
}
```
