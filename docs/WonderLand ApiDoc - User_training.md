# WonderLand ApiDoc - User_training

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/07/08** | 新增接口 · 【添加一条个人训练记录】
| **17/07/09** | 新增接口 · 【查询一条个人训练记录】
| **17/07/09** | 新增接口 · 【修改一条个人训练记录】
| **17/07/09** | 新增接口 · 【删除一条个人训练记录】
| **17/07/09** | 新增接口 · 【获取某用户所有训练列表】
| **17/07/09** | **【WonderLand Beta 1.0 Compeleted】**


---

## **属性**

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
| **UTarticle**   | 文章   | -        | 2000     | char(200) | -


---

## **接口 · 增加记录**
- **备注：未实现高并发**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/post**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTtitle**     | O      | 1        | 50       | -    
| **UTplace**     | O      | 1        | 3        | -    
| **UTaddress**   | O      | 1        | 150      | -
| **UTproblemset**| O      | -        | -        | **字符数组**


- **请求示例**
```
{
	"UTtitle" : "a title",
	"UTplace" : 1,
	"UTaddress" : "www.baidu.com",
	"UTproblemset" : [
			"O",
			"O",
			"X"
		]
}
```

- **成功返回**
```
{
	"type": 1,
	"message": "注册成功",
	"data": []
}
```

---

## **接口 · 查询某条记录**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User_training/get?:UTid**

| 返回的记录信息  | 备注
| --------------- | --------
| **editable**    | 当前用户是否有可编辑权限
| **UTid**        | 记录编号
| **Uusername**   | 作者
| **UTtitle**     | 标题 
| **UTdate**      | 记录添加日期
| **UTplace**     | 排名
| **UTaddress**   | 训练地址
| **UTproblemset**| 题集
| **UTarticle**   | 文章


- **成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"UTid": "20",
		"Uusername": "aaaau2",
		"UTtitle": "a title",
		"UTdate": "2017-07-08 17:06:31",
		"UTplace": "1",
		"UTaddress": "www.baidu.com",
		"UTproblemset": [
			"O",
			"O",
			"X"
		],
		"UTarticle": " ",
		"editable": true
	}
}
```

---

## **接口 · 修改某条记录**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/update**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTid**        | O      | -        | -        | -
| **UTtitle**     | O      | 1        | 50       | -    
| **UTplace**     | O      | 1        | 3        | -    
| **UTaddress**   | O      | 1        | 150      | -
| **UTproblemset**| O      | -        | -        | **字符数组**
| **UTarticle**   | O      | 1        | 2000     | -


- **成功返回**
```
{
	"type": 1,
	"message": "修改成功",
	"data": []
}
```

---

## **接口 · 删除某条记录**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/delete**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTid**        | O      | -        | -        | -


- **成功返回**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

---

## **接口 · 获取某用户所有训练列表**
- **【！备注！】：此接口返回的记录信息中不包含文章，获取文章使用上面的查询单条记录接口 · GET**
- **请求方法：GET**
- **按记录添加时间排序**
- **接口网址：http://icpc-system.and-who.cn/User_training/get_list?:Uusername**

| **`GET` 字段可选项** | 备注
| --------------- | --------
| **page_size**   | 设置分页大小，和 **page** 成对存在
| **page**        | 设置查询页，和 **page_size** 成对存在


| 返回的记录信息  | 备注
| --------------- | --------
| **UTid**        | 记录编号
| **Uusername**   | 作者
| **UTtitle**     | 标题 
| **UTdate**      | 记录添加日期
| **UTplace**     | 排名
| **UTaddress**   | 训练地址
| **UTproblemset**| 题集


- **查询示例：http://icpc-system.and-who.cn/User_training/get_list?Uusername=aaaau41&page_size=3&page=1**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"page_size": "3",
		"page": "1",
		"page_max": 1,
		"data": [
			{
				"UTid": "22",
				"Uusername": "aaaau41",
				"UTtitle": "a title",
				"UTdate": "2017-07-08 23:54:32",
				"UTplace": "1",
				"UTaddress": "www.baidu.com1111",
				"UTproblemset": [
					"O",
					"O",
					"X"
				]
			},
			{
				"UTid": "23",
				"Uusername": "aaaau41",
				"UTtitle": "a title",
				"UTdate": "2017-07-08 23:54:50",
				"UTplace": "1",
				"UTaddress": "www.baidu.com1111",
				"UTproblemset": [
					"O",
					"O",
					"X"
				]
			}
		]
	}
}
```