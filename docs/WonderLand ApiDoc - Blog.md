# WonderLand ApiDoc - Blog

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/11/06** | **【get_list】**增加可选字段
| **17/10/23** | 新增接口 · **【获取可选博文类型列表 · get_type_list】**
| **17/10/23** | 新增属性 · **`Bproblemid`**, **【register】【update】**增加必要字段，**【get】【get_list】**增加返回字段
| **17/10/23** | 新增属性 · **`Bproblemid`**, **【register】【update】**增加可选字段，**【get】【get_list】**增加返回字段
| **17/10/23** | 新增接口 · **【博客标签删除 · delete_target】**
| **17/10/23** | 新增接口 · **【博客点赞 · like】**
| **17/10/23** | **【get_list】** 新增元素返回字段 **`total`**
| **17/10/23** | **【get_list】** 新增元素返回字段 **`Btargets`**
| **17/10/23** | **【get】** 新增返回值 **`Btargets`**
| **17/10/22** | 新增属性 · **`Bviews`**，**【get】【get_list】** 新增返回 **`Bviews`**
| **17/10/22** | 新增接口 · **【添加一条博客标签增加 · register_target】**
| **17/10/20** | 新增接口 · **【获取某用户的个人博客文章列表 · get_list】**
| **17/10/20** | 新增接口 · **【添加一条个人博客查询文章 · get】**
| **17/10/19** | 新增接口 · **【添加一条个人博客修改文章 · update】**
| **17/10/18** | 新增接口 · **【添加一条个人博客删除文章 · delete】**
| **17/10/18** | 新增接口 · **【添加一条个人博客记录文章 · register】**



---

## **属性**
- **`【blog 表】`**

| 属性名          | 中文     | 最小长度 | 最大长度 | 类型           | 特殊要求
| --------------- | ------   | -------- | -------- | ---------      | --------
| **Bid**         | 记录id   | -        | -        | int            | - 
| **Btitle**      | 标题     | 1        | 50       | char(50)       | -                       
| **Bauthor**     | 作者     | -        | -        | char(20)       | -           
| **Btime**       | 发表日期 | -        | -        | TIMESTAMP      | -
| **Blikes**      | 点赞数   | -        | -        | int            | -
| **Bviews**      | 浏览数   | -        | -        | int            | -
| **Bproblemid**  | 题号     | 1        | 10       | char(5)        | 仅包含字母数字
| **Btype**       | 类型     | 1        | 15       | char(10)       | -


- **`【blog_article 表】`**

| 属性名          | 中文     | 最小长度 | 最大长度 | 类型           | 特殊要求
| --------------- | ------   | -------- | -------- | ---------      | --------
| **Bid**         | 记录id   | -        | -        | int            | - 
| **BAarticle**   | 正文     | 1        | 21500    | varchar(21500) | - 


- **`【blog_target 表】`**

| 属性名          | 中文     | 最小长度 | 最大长度 | 类型           | 特殊要求
| --------------- | ------   | -------- | -------- | ---------      | --------
| **BTid**        | 记录id | -        | -        | int            | -
| **Bid**         | 博客id    | -        | -        | int            | -
| **Tid**         | 标签id    | -        | -        | int            | 标签类型为2才能作为文章标签


- **`【blog_like 表】`**

| 属性名          | 中文     | 最小长度 | 最大长度 | 类型           | 特殊要求
| --------------- | ------   | -------- | -------- | ---------      | --------
| **BLid**        | 记录id   | -        | -        | int            | -
| **Bid**         | 博客id   | -        | -        | int            | -
| **Uusername**   | 用户名   | -        | -        | char(20)       | -


---

## **接口 · 增加文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/register**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Btitle**      | O      | 1        | 50       | -    
| **BAarticle**   | O      | 1        | 21500    | - 
| **Bproblemid**  | X      | -        | 5        | 仅包含字母和数字
| **Btype**       | O      | 1        | 15       | -


- **示例**
```
{
	"Btitle": "咸鱼之路",
	"BAarticle": "一位乘客失去了梦想",
	"Btype": "算 法 学 习"
}
```

- **成功返回**
```
{
    "type": 1,
    "message": "增加成功",
    "data": []
}
```

---

## **接口 · 删除文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/delete**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Bid**         | O      | -        | -        | -


- **成功返回**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

---

## **接口 · 修改文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/update**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Bid**         | O      | -        | -        | -
| **Btitle**      | O      | 1        | 50       | -
| **BAarticle**   | O      | 1        | 21500    | -
| **Bproblemid**  | X      | -        | 5        | 仅包含字母或数字


- **示例**
```
{
	"Bid": 1,
	"Btitle": "翻身之路",
	"BAarticle": "一位乘客重新获得了梦想"
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

## **接口 ·查询某篇文章**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Blog/get?:Bid**

| **返回的文章信息** | 备注
| -------------------------- | ----
|**editable**                |当前用户是否有可编辑权限
| **Bid**                    | 记录id    
| **Btitle**                 | 标题                     
| **Bauthor**                | 作者         
| **Btime**                  | 发表日期 
| **Blikes**                 | 点赞数    
| **Bviews**                 | 浏览数
| **BAarticle**              | 正文
| **upvoteEnable**           | 当前用户可否点赞
| **Btargets**               | 博客标签列表
| **Bproblemid**             | 题目编号


- **查询示例：http://icpc-system.and-who.cn/Blog/get?Bid=233**
- **成功返回**

```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Bid": "11",
		"Btype": "博 文",
		"Btitle": "翻身之路",
		"Bauthor": "aaaau1",
		"Btime": "2017-10-23 11:58:21",
		"Blikes": "0",
		"Bviews": "1",
		"Bproblemid": "foj2333",
		"BAarticle": "一位乘客重新获得了梦想",
		"editable": true,
		"upvoteEnable": true,
		"Btargets": []
	}
}
```

---

## **接口 · 获取某用户的个人博客文章列表**
- **【！备注！】：此接口返回的记录信息中不包含文章，获取文章使用上面的查询单条记录接口 · GET**
- **请求方法：GET**
- **按记录添加时间排序**
- **接口网址：http://icpc-system.and-who.cn/Blog/get_list?Bauthor**

| **`GET` 字段可选项** | 备注
| --------------- | --------
| **page_size**   | 设置分页大小，和 **page** 成对存在
| **page**        | 设置查询页，和 **page_size** 成对存在
| **orderby**     | 设置排序关键字，可选：**`Btime` `Bviews` `Blikes`**
| **tid**         | 设置检索标签，内容为一个标签标号


| 返回的json数组包括  | 备注
| --------------- | --------
| **editable**    | 当前用户是否有编辑权限
| **page_size**   | 分页大小
| **page**        | 当前页
| **page_max**    | 最大页数
| **total**       | 总条目
| **tid**         | 检索标签标号



| 返回的data包括   | 备注
| ---------------- | --------
| **Bid**          | 记录id
| **Btitle**       | 标题 
| **Bauthor**      | 作者
| **Btime**        | 发表日期
| **Blikes**       | 点赞数
| **Bviews**       | 浏览数
| **upvoteEnable** | 可否点赞 
| **Btargets**     | 博客标签列表，其中 **Tfather** 为父标签，**Tname** 为标签名，**Tid** 为标签ID.
| **Bproblemid**   | 题目标号
| **Btype**        | 文章类型

- **查询示例：http://icpc-system.and-who.cn/Blog/get_list?Bauthor=ahhh1**
- **查询示例：http://icpc-system.and-who.cn/Blog/get_list?Bauthor=aaaau1&&page_size=3&&page=3&&tid=12**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"total": 11,
		"page_size": "3",
		"page": "1",
		"page_max": 4,
		"editable": true,
		"tid": "12",
		"data": [
			{
				"Bid": "11",
				"Btype": "博 文",
				"Btitle": "翻身之路",
				"Bauthor": "aaaau1",
				"Btime": "2017-10-23 11:58:21",
				"Blikes": "0",
				"Bviews": "1",
				"Bproblemid": "foj2333",
				"Btargets": [
					{
						"Tid": "12",
						"Tfather": "root",
						"Tname": "字符串"
					}
				],
				"upvoteEnable": false
			},
			{
				"Bid": "10",
				"Btype": "博 文",
				"Btitle": "咸鱼之路",
				"Bauthor": "aaaau1",
				"Btime": "2017-10-23 11:57:26",
				"Blikes": "0",
				"Bviews": "1",
				"Bproblemid": "无",
				"Btargets": [
					{
						"Tid": "12",
						"Tfather": "root",
						"Tname": "字符串"
					}
				],
				"upvoteEnable": false
			}
		]
	}
}
```

---
	
## **接口 · 增加博客标签**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/register_target**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Bid**         | O      | -        | -        | -
| **Tid**         | O      | -        | -        | -


- **示例**
```
{
	"Bid": 1,
	"Tid": 1
}
```

- **成功返回**
```
{
	"type": 1,
	"message": "增加成功",
	"data": []
}
```

---

## **接口 · 删除博客标签**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/delete_target**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Bid**         | O      | -        | -        | -
| **Tid**         | O      | -        | -        | -


- **示例**
```
{
	"Bid": 1,
	"Tid": 1
}
```

- **成功返回**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

---

## **接口 · 博客点赞**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Blog/like?:Bid**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **Bid**         | O      | -        | -        | -


- **示例：http://icpc-system.and-who.cn/Blog/like?Bid=1**

- **成功返回**
```
{
	"type": 1,
	"message": "点赞成功",
	"data": []
}
```

---

## **接口 · 获取可选博文类型列表**

- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Blog/get_type_list**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -


- **示例：http://icpc-system.and-who.cn/Blog/get_type_list**

- **成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		"博 文",
		"题 解",
		"算 法 学 习",
		"比 赛 感 悟"
	]
}
```


