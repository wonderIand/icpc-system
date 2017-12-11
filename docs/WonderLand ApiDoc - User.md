# WonderLand ApiDoc - User

标签（空格分隔）： WonderLand

---

## **日志**

| 日期           | 备注                                       |
| ------------ | ---------------------------------------- |
| **17/11/29** | 添加接口 · **【获取用户头像url】**                   |
| **17/11/29** | 新增属性 · **Uiconpath**                     |
| **17/11/24** | 添加接口 · **【上传用户头像 · upload_icon】**        |
| **17/07/23** | 添加属性 · **`Utype`**，【get】【get_list】增加返回该字段 |
| **17/07/09** | **【WonderLand Beta 1.0 Compeleted】**     |
| **17/07/--** | 添加接口 · **【获取用户列表 · get_list】**           |
| **17/07/--** | 添加接口 · **【获取某用户信息 · get】**               |
| **17/07/--** | 添加接口 · **【用户登陆 · login】**                |
| **17/07/--** | 添加接口 · **【用户注册 · register】**             |

---

## **属性**

- **【user 表】**

| 属性名             | 中文   | 最小长度 | 最大长度 | 类型        | 特殊要求          |
| --------------- | ---- | ---- | ---- | --------- | ------------- |
| **Uusername**   | 用户名  | 6    | 16   | char(20)  | 字母/数字/下划线/破折号 |
| **Upassword**   | 密码   | 6    | 16   | char(20)  | -             |
| **Utype**       | 类型   | -    | -    | -         | -             |
| **Utoken**      | 凭据   | 30   | 30   | char(30)  | -             |
| **Ulast_visit** | 上次访问 | -    | -    | TIMESTAMP | -             |


- **【user_info 表】**

| 属性名           | 中文    | 最小长度 | 最大长度 | 类型        | 特殊要求          |
| ------------- | ----- | ---- | ---- | --------- | ------------- |
|               |       |      |      |           |               |
| **Uusername** | 用户名   | 6    | 16   | char(20)  | 字母/数字/下划线/破折号 |
| **Unickname** | 昵称    | 1    | 15   | char(20)  | -             |
| **Urealname** | 真名    | 1    | 10   | char(20)  | -             |
| **Uiconpath** | 头像url | -    | 100  | char(100) | -             |

---

## **接口 · 注册**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User/register**

- **表单要求**

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求          |
| ------------- | ---- | ---- | ---- | ------------- |
| **Uusername** | O    | 6    | 16   | 字母/数字/下划线/破折号 |
| **Upassword** | O    | 6    | 16   | -             |
| **Urealname** | O    | 1    | 10   | -             |
| **Unickname** | O    | 1    | 15   | -             |


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

| 属性名           | 必要性  | 最小长度 | 最大长度 | 特殊要求 |
| ------------- | ---- | ---- | ---- | ---- |
| **Uusername** | O    | -    | -    | -    |
| **Upassword** | O    | -    | -    | -    |


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


| **返回的个人信息包含**   | 备注     |
| --------------- | ------ |
| **Uusername**   | 用户名    |
| **Urealname**   | 真实姓名   |
| **Utype**       | 用户类型   |
| **Unickname**   | 昵称     |
| **Ulast_visit** | 上次访问时间 |

- **测试示例：http://icpc-system.and-who.cn/User/get?Uusername=aaaau1**

- **成功返回**

```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Uusername": "aaaau1",
		"Utype": "user",
		"Ulast_visit": "2017-10-22 14:29:29",
		"Unickname": "a",
		"Urealname": "a 真实姓名真实姓名"
	}
}
```

---

## **接口 · 获取用户列表**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User/get_list**

| **`GET` 字段可选项** | 备注                         |
| --------------- | -------------------------- |
| **page_size**   | 设置分页大小，和 **page** 成对存在     |
| **page**        | 设置查询页，和 **page_size** 成对存在 |


| **返回的个人信息包含**   | 备注     |
| --------------- | ------ |
| **Uusername**   | 用户名    |
| **Urealname**   | 真实姓名   |
| **Utype**       | 用户类型   |
| **Unickname**   | 昵称     |
| **Ulast_visit** | 上次访问时间 |


- **查询示例：http://icpc-system.and-who.cn/User/get_list?page_size=2&page=2**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"page_size": "3",
		"page": "1",
		"page_max": 2,
		"data": [
			{
				"Uusername": "aaaau1",
				"Utype": "user",
				"Ulast_visit": "2017-10-22 14:29:29",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			},
			{
				"Uusername": "aaaau3",
				"Utype": "user",
				"Ulast_visit": "2017-07-10 08:14:32",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			},
			{
				"Uusername": "aaaau41",
				"Utype": "user",
				"Ulast_visit": "2017-07-10 05:08:33",
				"Urealname": "a 真实姓名真实姓名",
				"Unickname": "a"
			}
		]
	}
}
```

---

## **接口 · 上传用户头像**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User/upload_icon**
- **存储路径**：**./uploads/user_icon/:Uusername**（自动按用户名作为文件名存储）
- **图片要求**

| **项目**      | **要求**      |
| ----------- | ----------- |
| **html标签中name字段要求** | 必须叫 **`userfile`** |
| **类型**      | **`gif|jpg|png`** |
| **文件大小最大值** | **10000KB**     |
| **图片最大宽度**  | **1024**      |
| **图片最大高度**  | **768**       |


- **成功返回**

```
{
	"type":1,
	"message":"上传成功",
	"data": {
		"upload_data": {
			"file_name": "zhengshuhao.JPG",
			"file_type": "image\/jpeg",
			"file_path": "D:\/Demo\/icpc-system\/uploads\/user_icon\/",
			"full_path": "D:\/Demo\/icpc-system\/uploads\/user_icon\/zhengshuhao.JPG",
			"raw_name": "zhengshuhao",
			"orig_name": "zhengshuhao.JPG",
			"client_name": "IMG_4562.JPG",
			"file_ext": ".JPG",
			"file_size": 59.47,
			"is_image": true,
			"image_width": 700,
			"image_height": 700,
			"image_type": "jpeg",
			"image_size_str": "width=\"700\" height=\"700\""
			},
		"icon_path": "http:\/\/icpc-system.and-who.cn\/uploads\/user_icon\/zhengshuhao.JPG"
	}
}
```

- **失败返回**

```
{
	"type": 0,
	"message": "上传失败",
	"data": {
		"error": "上传文件超出PHP配置文件中允许的最大长度."
	}
}
```

---

## **接口 · 获取用户头像**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User/get_icon?:Uusername**

| **请求Get字段** | **备注** |
| - | - |
| Uusername | 必须给定Uusername


| **返回的标签信息包含** | 备注 |
| ---------------------- | ---- |
| **icon_path**				 | 用户头像url |


- **请求示例：http://icpc-system.and-who.cn/User/get_icon?Uusername=zhengshuhao**

- **成功返回**

```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Uiconpath": "http:\/\/icpc-system.and-who.cn\/uploads\/user_icon\/zhengshuhao.JPG"
	}
}
```

- **失败返回**

```
{
	"type": 0,
	"message": "该用户未上传头像",
	"data": []
}
```
