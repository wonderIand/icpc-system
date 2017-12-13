# WonderLand ApiDoc - Station

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/12/13   | 添加接口 · **【刷新近期比赛列表 · refresh_contests】**
| **17/11/04** | **新增station_recent_contests 表、station_last_vist 表**
| **17/11/02** | **【recent_contest】 新增元素返回字段 page 、page_size、page_max**
| **17/07/12** | 添加接口 · **【获取近期比赛 · recent_contests】**
 
---


## **属性**

- **【station_recent_contests 表】**

| 属性名        | 中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| ------------- | ------ | -------- | -------- | --------- | --------
| **id**        | id     | -        | -        | char(20)  | -
| **oj**        | oj     | -        | -        | char(20)  | -                     
| **link**      | 链接   | -        | -        | char(150) | - 
| **name**      |比赛名称| -        | -        | char(20)  | -
| **start_time**|开始时间| -        | -        | char(20) | - 
| **week**      | 星期   | -        | -        | char(10)  | -
| **access**    |比赛类型| -        | -        | char(10)  | - 



- **【station_last_visit 表】**

| 属性名        |     中文   | 最小长度 | 最大长度 | 类型      | 特殊要求
| ------------- | ---------- | -------- | -------- | --------- | --------
| **Last_visit** |上次缓存时间| -        | -        | TIMESTAMP | -

---


## **接口 · 获取近期比赛**

> 来源：http://contests.acmicpc.info/contests.json

- **接口网址：http://icpc-system.and-who.cn/Station/recent_contests**

| **`GET` 字段可选项** | 备注
| --------------- | --------
| **page_size**   | 设置分页大小，和 **page** 成对存在
| **page**        | 设置查询页，和 **page_size** 成对存在

- **查询示例：http://icpc-system.and-who.cn/Station/recent_contests?page_size=2&page=2**
- **成功返回例子**


```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"contests": [
			{
				"id": "164404",
				"oj": "Codeforces",
				"link": "http:\/\/codeforces.com\/contests",
				"name": "Codeforces Round #444 (Div. 2)",
				"start_time": "2017-11-04 00:05:00",
				"week": "SAT",
				"access": ""
			},
			{
				"id": "166411",
				"oj": "HDU",
				"link": "http:\/\/acm.hdu.edu.cn\/contests\/contest_show.php?cid=783",
				"name": "2017ACM\/ICPC亚洲区沈阳站-重现赛（感谢东北大学）",
				"start_time": "2017-11-04 12:00:00",
				"week": "SAT",
				"access": "Public"
			}
		],
		"page_size": "2",
		"page": "2",
		"page_max": 1
	}
}
```


---


## **接口 · 刷新近期比赛列表**

> 来源：http://contests.acmicpc.info/contests.json

- **接口网址：http://icpc-system.and-who.cn/Station/refresh_contests**

- **成功返回**

```
{
	"type": 1,
	"message": "刷新成功",
	"data": {
		"contests": [
			{
				"id": "183524",
				"oj": "HackerRank",
				"link": "http:\/\/www.hackerrank.com\/contests",
				"name": "World CodeSprint 12",
				"start_time": "2017-12-15 00:00:00",
				"week": "FRI",
				"access": ""
			},
			{
				"id": "198328",
				"oj": "Codeforces",
				"link": "http:\/\/codeforces.com\/contests",
				"name": "Codeforces Round #451 (Div. 2)",
				"start_time": "2017-12-16 19:35:00",
				"week": "SAT",
				"access": ""
			},
			{
				"id": "192944",
				"oj": "AtCoder",
				"link": "https:\/\/abc082.contest.atcoder.jp",
				"name": "AtCoder Beginner Contest 082",
				"start_time": "2017-12-16 20:00:00",
				"week": "SAT",
				"access": ""
			},
			{
				"id": "192945",
				"oj": "AtCoder",
				"link": "https:\/\/arc087.contest.atcoder.jp",
				"name": "AtCoder Regular Contest 087",
				"start_time": "2017-12-16 20:00:00",
				"week": "SAT",
				"access": ""
			}
		]
	}
}
```




