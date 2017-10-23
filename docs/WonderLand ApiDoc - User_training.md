# WonderLand ApiDoc - User_training

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/10/23** | 接口 **【get_list】** 新增返回值 **`total`**
| **17/07/08** | 新增接口 · **【添加一条个人训练记录 · register】**
| **17/07/09** | 新增接口 · **【查询一条个人训练记录 · get】**
| **17/07/09** | 新增接口 · **【修改一条个人训练记录 · update】**
| **17/07/09** | 新增接口 · **【删除一条个人训练记录 · delete】**
| **17/07/09** | 新增接口 · **【获取某用户所有训练列表 · get_list】**
| **17/07/09** | **-----------------【WonderLand Beta 1.0 Compeleted】**
| **17/07/10** | 新增接口 · **【修改一条个人训练记录文章 · update_article】**
| **17/07/10** | 修改接口 · **【get_list · 增加返回信息editable】**
| **17/07/10** | **-----------------【WonderLand Beta 1.1.5 Compeleted】**
| **17/07/11** | 修改接口 · **【get · 不再返回文章】**
| **17/07/11** | 新增接口 · **【查询一条个人训练记录文章 · get_article】**
| **17/07/11** | **【接口register、update】表单项均加上UTdate**
| **17/07/11** | **-----------------【WonderLand Beta 1.1.8 Compeleted】**
| **17/07/14** | **【新增UTview，UTup】**
| **17/07/14** | **【新增点赞接口 · upvote】**
| **17/07/14** | **-----------------【WonderLand Beta 1.1.10 Compeleted】**


---

## **属性**

- **`【user_training 表】`**

| 属性名          | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| --------------- | ------ | -------- | -------- | --------- | --------
| **UTid**        | 记录id | -        | -        | int       | - 
| **Uusername**   | 拥有者 | -        | -        | char(20)  | -                       
| **UTtitle**     | 标题   | 1        | 50       | char(50)  | - 
| **UTplace**     | 排名   | -        | -        | int       | -           
| **UTdate**      | 训练日期   | -        | -        | TIMESTAMP | -
| **UTup**        | 点赞数 | -        | -        | int       | -
| **UTview**      | 阅读量 | -        | -        | int       | -


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
| **UTarticle**   | 文章   | -        | 21500    | varchar(21500) | -


---

## **接口 · 增加记录**
- **备注：未实现高并发**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/register**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTtitle**     | O      | 1        | 50       | -    
| **UTdate**      | O      | -        | -        | -
| **UTplace**     | O      | 1        | 3        | -    
| **UTaddress**   | O      | 1        | 150      | -
| **UTproblemset**| O      | -        | -        | **字符数组**


- **请求示例**
```
{
	"UTtitle" : "a title6",
	"UTplace" : 1,
	"UTaddress" : "https://www.baidu.com",
	"UTdate" : "2017/07/11",
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
| **UTplace**     | 排名
| **UTaddress**   | 训练地址
| **UTproblemset**| 题集
| **UTview**      | 阅读量
| **UTup**        | 点赞数
| **upvoteEnable**| 当前用户可否点赞


- **成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"UTid": "3",
		"Uusername": "aaaau1",
		"UTdate": "2017-07-11 10:55:00",
		"UTtitle": "a title6",
		"UTplace": "1",
		"UTup": "3",
		"UTview": "44",
		"UTaddress": "https:\/\/www.baidu.com",
		"UTproblemset": [
			"D",
			"D",
			"D"
		],
		"editable": true,
		"upvoteEnable": false
	}
}
```

---

## **接口 · 查询某篇文章**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User_training/get_article?:UTid**

| 返回的记录信息  | 备注
| --------------- | --------
| **editable**    | 当前用户是否有可编辑权限
| **UTid**        | 记录编号
| **UTarticle**   | 文章
| **UTview**      | 访问量
| **UTup**        | 点赞数量
| **upvoteEnable** | 当前可否点赞


- **成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"UTid": "3",
		"UTarticle": "# 不补题怎么变强",
		"UTup": "4",
		"UTview": "58",
		"editable": true,
		"upvoteEnable": false
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
| **UTdate**      | O      | -        | -        | 训练日期
| **UTaddress**   | O      | 1        | 150      | -
| **UTproblemset**| O      | -        | -        | **字符数组**


- **示例**
```
{
	"UTtitle" : "a title",
	"UTplace" : -1,
	"UTid" : 26,
    "UTdate" : "2010/07/12",
	"UTaddress" : "wwwww",
	"UTproblemset" : [
			"A",
			"A",
			"A"
		]
}
```

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

| 返回的json数组包括  | 备注
| --------------- | --------
| **total**       | 总条目
| **editable**    | 当前用户是否有编辑权限
| **page_size**   | 分页大小
| **page**        | 当前页
| **page_max**    | 最大页数



| 返回的data包括  | 备注
| --------------- | --------
| **UTid**        | 记录编号
| **Uusername**   | 作者
| **UTtitle**     | 标题 
| **UTdate**      | 训练时间
| **UTplace**     | 排名
| **UTaddress**   | 训练地址
| **UTproblemset**| 题集
| **UTview**      | 访问量
| **UTup**        | 点赞数
| **UTupvoteEnable** | 可否点赞 


- **查询示例：http://icpc-system.and-who.cn/User_training/get_list?Uusername=aaaau1**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"total": 3,
		"page_size": "5",
		"page": "1",
		"page_max": 1,
		"editable": true,
		"data": [
			{
				"UTid": "1",
				"Uusername": "aaaau1",
				"UTdate": "2017-07-11 10:55:00",
				"UTtitle": "a title6",
				"UTplace": "1",
				"UTup": "1",
				"UTview": "0",
				"UTaddress": "https:\/\/www.baidu.com",
				"UTproblemset": [
					"D",
					"D",
					"D"
				],
				"upvoteEnable": true
			},
			{
				"UTid": "2",
				"Uusername": "aaaau1",
				"UTdate": "2017-07-11 10:55:00",
				"UTtitle": "a title6",
				"UTplace": "1",
				"UTup": "1",
				"UTview": "0",
				"UTaddress": "https:\/\/www.baidu.com",
				"UTproblemset": [
					"D",
					"D",
					"D"
				],
				"upvoteEnable": false
			},
			{
				"UTid": "3",
				"Uusername": "aaaau1",
				"UTdate": "2017-07-11 10:55:00",
				"UTtitle": "a title6",
				"UTplace": "1",
				"UTup": "4",
				"UTview": "59",
				"UTaddress": "https:\/\/www.baidu.com",
				"UTproblemset": [
					"D",
					"D",
					"D"
				],
				"upvoteEnable": false
			}
		]
	}
}
```

---

## **接口 · 修改某条记录的文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/update_article**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTid**        | O      | -        | -        | -
| **UTarticle**   | O      | 1        | 21500    | -


- **成功返回**
```
{
	"type": 1,
	"message": "修改成功",
	"data": []
}
```

---

## **接口 · 点赞**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/upvote**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTid**        | O      | -        | -        | -


- **成功返回**
```
{
	"type": 1,
	"message": "点赞成功",
	"data": []
}
```
