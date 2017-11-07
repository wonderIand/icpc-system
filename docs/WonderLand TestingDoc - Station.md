# WonderLand TestingDoc - Station

标签（空格分隔）： WonderLand

---

## **接口 · 获取近期比赛**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Station/recent_contests**

- **测试用例**

### **Test 1** 带GET参数测试
**查询示例**：http://icpc-system.and-who.cn/Station/recent_contests?page_size=2&page=2
```
{
 	
}
```

**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"contests": {
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
			}
		],
			
		"page_size": "2",
		"page": "2",
		"page_max": 1
	}
}
```

**Test 2** · 缺乏GET参数测试

**查询示例：**http://icpc-system.and-who.cn/Station/recent_contests

```
{
 	
}
```
**返回信息**

```
{
	"type": 1,
	"message": "获取成功",
	"contests": {
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
			}
		]
}
```

