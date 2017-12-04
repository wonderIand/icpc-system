# WonderLand TestingDoc - OJ

标签（空格分隔）： WonderLand

---

## **接口 · 获取题量排行**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/OJ/get_list**
- **测试用例**

### **Test 1** · 获取foj题量排行升序
```
{
	"OJname":"foj",
	"Sort":"insc"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"Uusername": "Kirito",
			"ACproblem": "2"
		},
		{
			"Uusername": "Distance",
			"ACproblem": "14"
		}
	]
}
```

**Test 2** · 获取foj题量排行降序
```
{
	"OJname":"foj",
	"Sort":"desc"
}
```

**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"Uusername": "Distance",
			"ACproblem": "14"
		},
		{
			"Uusername": "Kirito",
			"ACproblem": "2"
		}
	]
}
```


**Test 3** · 获取cf题量排行降序
```
{
	"OJname":"cf",
	"Sort":"desc"
}
```

**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"Uusername": "Kirito",
			"ACproblem": "266"
		},
		{
			"Uusername": "Distance",
			"ACproblem": "1"
		}
	]
}
```

**Test 4** · 获取cf题量排行升序
```
{
	"OJname":"cf",
	"Sort":"insc"
}
```

**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"Uusername": "Distance",
			"ACproblem": "1"
		},
		{
			"Uusername": "Kirito",
			"ACproblem": "266"
		}
	]
}
```

---

