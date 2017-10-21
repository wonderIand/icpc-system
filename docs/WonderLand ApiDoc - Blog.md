# WonderLand ApiDoc - Blog

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/10/18** | 新增接口 · **【添加一条个人博客记录文章 · register】**
| **17/10/18** | 新增接口 · **【添加一条个人博客删除文章 · delete】**
| **17/10/19** | 新增接口 · **【添加一条个人博客修改文章 · update】**
| **17/10/20** | 新增接口 · **【添加一条个人博客查询文章 · get】**
| **17/10/20** | 新增接口 · **【获取某用户的个人博客文章列表 · get_list】**


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


- **`【blog_article 表】`**

| 属性名          | 中文     | 最小长度 | 最大长度 | 类型           | 特殊要求
| --------------- | ------   | -------- | -------- | ---------      | --------
| **Bid**         | 记录id   | -        | -        | int            | - 
| **BAarticle**   | 正文     | 1        | 21500    | varchar(21500) | - 


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


- **示例**
```
{
	"Btitle": "咸鱼之路",
	"BAarticle": "一位乘客失去了梦想"
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


- **示例**
```
{
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
- **接口网址：http://icpc-system.and-who.cn/Blog/get?Bid**

| **返回的文章信息** | 备注
| -------------------------- | ----
|**editable**                |当前用户是否有可编辑权限
| **Bid**                    | 记录id    
| **Btitle**                 | 标题                     
| **Bauthor**                | 作者         
| **Btime**                  | 发表日期 
| **Blikes**                 | 点赞数    
| **BAarticle**              | 正文
| **upvoteEnable**           |当前用户可否点赞

- **查询示例：http://icpc-system.and-who.cn/Blog/get?Bid=233**
- **成功返回**
```
{
	{
	"type": 1,
	"message": "获取成功",
	"data": {
		"Bid": "233",
		"Btitle": "a Btitle",
		"Bauthor": "a Bauthor",
		"Btime": "2017-07-11 10:55:00",
		"Blikes": "233",
		"BAarticle": "aaaaaaaaaa",
		"editable": true,
		"upvoteEnable": false
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

| 返回的json数组包括  | 备注
| --------------- | --------
| **editable**    | 当前用户是否有编辑权限
| **page_size**   | 分页大小
| **page**        | 当前页
| **page_max**    | 最大页数



| 返回的data包括   | 备注
| ---------------- | --------
| **Bid**          | 记录id
| **Btitle**       | 标题 
| **Bauthor**      | 作者
| **Btime**        | 发表日期
| **Blikes**       | 点赞数
| **upvoteEnable** | 可否点赞 


- **查询示例：http://icpc-system.and-who.cn/Blog/get_list?Bauthor=hbbhbb&&page_size=3&&page=4**
- **查询示例：http://icpc-system.and-who.cn/Blog/get_list?Bauthor=ahhh1**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"editable": true,
		"data": [
			{
        		"Bid": "1",
        		"Btitle": "a Btitle",
        		"Bauthor": "ahhh1",
        		"Btime": "2017-07-11 10:55:00",
        		"Blikes": "233",
        		"editable": true,
			},
			{
        		"Bid": "2",
        		"Btitle": "a Btitle",
        		"Bauthor": "ahhh1",
        		"Btime": "2017-07-11 10:55:00",
        		"Blikes": "233",
        		"editable": true,
			},
			{
        		"Bid": "3",
        		"Btitle": "ahhh1",
        		"Bauthor": "a Bauthor",
        		"Btime": "2017-07-11 10:55:00",
        		"Blikes": "233",
        		"editable": true,
			},
		]
	}
}
```