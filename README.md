# WonderLand Beta1.0

标签（空格分隔）： Fzuacm集训队2017

---

[TOC]

---

## **开发环境**
- **wamp server 2.5**
- **CI框架 3.1.4**

---

## **Part 0 · 一些约定**

### **【一】接口返回格式**
- 返回类型为一个 **`json数组`**
| 成员        | 中文   | 类型       | 备注
| ----------- | ------ | ---------- | ----
| **type**    | 结果   | 数字       | 1成功，0失败 
| **message** | 消息   | 字符串     | 失败时：存一条错误信息
| **data**    | 数据   | 字符串数组 | 返回数据


- **成功例子**
```
{
	"type": 0,
	"message": "用户名  至少包含 6  个字",
	"data": []
}
```

- **失败例子**
```
{
	"type": 1,
	"message": "",
	"data": []
}
```

---

### **【二】接口名设计约定**

---

#### **（一）新增一条记录：`post`**
- 例，**新增用户：`.../User/post`**
- 例：**新增队伍训练记录：`.../Team_training/post`**
- 目前版本不对前台提供添加多条资源的接口；

---

#### **（二）获取记录：`get`**

---

**【`url字段` 带参为按主键查询】**

- 例如：**`.../User/get/hs97`**
- 代表查询用户名为 **hs97** 的信息
  
---

**【`url字段` 不带参为查询批量资源】**
 - 例如：**`.../User/get`**
 - 需要支持 **`关键字检索` 和 `分页`**

- **最基本接口输入值格式**：
| 成员          | 中文   | 类型       | 备注
| ------------- | ------ | ---------- | ----
| **page_size** | 页大小 | 数字       | -
| **now_page**  | 当前页 | 数字       | -
| **where**     | 检索表 | 数组       | -


- **最基本接口返回值格式（无发生错误时）**：
| 成员          | 中文   | 类型       | 备注
| ------------- | ------ | ---------- | ----
| **page_size** | 页大小 | 数字       | -
| **now_page**  | 当前页 | 数字       | -
| **max_page**  | 最大页 | 数字       | -
| **data**      | 当前页 | 数组       | 即便没有资源也要返回一个空数组data


- 例：**获取某个队伍训练记录：`.../Team_training/get`**

---

#### **（四）修改记录-部分项：`putch`**
- 只允许提供 **主键** 修改
- 且都需要判定持有 **`Utoken`** 的用户是否有修改的权限
- 例：**修改某条训练记录：`.../Team_training/putch`**

---

#### **（四）删除记录：`delete`**
- 只允许提供 **主键** 删除
- 且都需要判定持有 **`Utoken`** 的用户是否有删除的权限
- 例：**删除某条训练记录：`.../Team_training/delete`**

---


## **Part 1 · 思维导图**

![此处输入图片的描述][1]

![此处输入图片的描述][2]

---

## **Part 2 · User**

### **属性**

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

### **接口 · 注册**

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

### **接口 · 登陆**

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

### **接口 · 获取单用户信息**

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

### **接口 · 获取用户信息**

- **接口网址：http://icpc-system.and-who.cn/User/get**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Utoken**    | O      | -        | -        | -
| **search_key** | O      | -        | -        | 检索项，所有就设成字符串"null"
| **search_value** | O      | -        | -      | 检索值，模糊搜索
| **page_size** | O      | 1        | 3        | 每页大小
| **now_page**  | O      | -        | -        | 查询页码


| 允许检索键  | 备注
| ----------- | --------
| Uusername   | 模糊搜索
| Ulast_visit | 模糊搜索


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


---

## **Part 3 · Team**

### **属性**

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

### **接口 · 注册**

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

### **接口 · 获取单队伍信息**

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

### **接口 · 获取队伍信息**

- **接口网址：http://icpc-system.and-who.cn/Team/get**

| 属性名        | 必要性 | 最小长度 | 最大长度 | 特殊要求
| ------------- | ------ | -------- | -------- | --------
| **Utoken**    | O      | -        | -        | -
| **search_key** | O      | -        | -        | 检索项，所有就设成字符串"null"
| **search_value** | O      | -        | -      | 检索值，模糊搜索
| **page_size** | O      | 1        | 3        | 每页大小
| **now_page**  | O      | -        | -        | 查询页码


| 允许检索键  | 备注
| ----------- | --------
| Tteamname   | 模糊搜索
| Uusername_1 | 成员1
| Uusername_2 | 成员2
| Uusername_3 | 成员3


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

---

## **Part 4 · User_training**

### **属性**

- **`【user_training 表】`**
| 属性名          | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| --------------- | ------ | -------- | -------- | --------- | --------
| **UTid**        | 记录id | -        | -        | int       | - 
| **Uusername**   | 拥有者 | -        | -        | char(20)  | -                       
| **UTtitle**     | 标题   | 1        | 50       | char(50)  | - 
| **UTdate**      | 日期   | -        | -        | TIMESTAMP | -          
| **UTplace**     | 排名   | -        | -        | int       | -           


- **`【user_training_contest 表】`**
| 属性名          | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| --------------- | ------ | -------- | -------- | --------- | --------
| **UTid**        | 记录id | -        | -        | int       | -
| **UTaddress**   | 地址   | 1        | 150      | char(150) | -
| **UTproblemset**| 题集   | 1        | -        | char(50)  | -


- **`【user_training_article】`**
| 属性名          | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| --------------- | ------ | -------- | -------- | --------- | --------
| **UTid**        | 记录id | -        | -        | int       | -
| **UTarticle**   | 文章   | -        | -        | char(200) | -


---

### **接口 · 增加记录**

- **接口网址：http://icpc-system.and-who.cn/User_training/post**

- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | **持有token的用户即作为成员1**
| **UTtitle**     | O      | 1        | 50       | -    
| **UTplace**     | O      | -        | -        | -    
| **UTaddress**   | O      | 1        | 150      | -
| **UTproblemset**| O      | -        | -        | **用#分隔的字符数组，如O#O#.#O#.**


---
  [1]: http://od690gqhu.bkt.clouddn.com/20177413159.png
  [2]: http://od690gqhu.bkt.clouddn.com/201776223736.png