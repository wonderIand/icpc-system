# WonderLand ApiDoc - Station

标签（空格分隔）： WonderLand

---

## **日志**

| 日期         | 备注  
| ------------ | ------
| **17/11/02** | **【recent_contest】 新增元素返回字段 page 、page_size、page_max**
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
	"data": {
		"data": [
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
			},
			
		"page_size": "2",
		"page": "2",
		"page_max": 1
	}
}
```




