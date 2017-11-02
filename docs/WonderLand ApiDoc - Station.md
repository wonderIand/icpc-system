# WonderLand ApiDoc - Station

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/11/02** | **【recent_contest】 新增元素返回字段 page 、page_size**
| **17/07/12** | 添加接口 · **【获取近期比赛 · recent_contest】**
 
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
	"page_size": "2",
	"page": "2",
	"data": [
		{
			"id": "88904",
			"oj": "UOJ",
			"link": "http:\/\/uoj.ac\/contest\/39",
			"name": "UOJ NOI Round #2 Day1",
			"start_time": "2017-07-13 08:30:00",
			"week": "THU",
			"access": ""
		},
		{
			"id": "88794",
			"oj": "AtCoder",
			"link": "https:\/\/agc019.contest.atcoder.jp",
			"name": "AtCoder Grand Contest 019",
			"start_time": "2017-08-26 20:00:00",
			"week": "SAT",
			"access": ""
		}
	]
}
```




