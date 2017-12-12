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

## **接口 · 添加关联账号**
- **请求方法：post**
- **接口网址：http://icpc-system.and-who.cn/Oj/add_oj_account**
- **测试用例**

### **Test 1** · 添加正确hdu关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "hdu",
	"OJusername" : "123123",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "添加成功",
	"data": []
}
```

### **Test 2** · 添加错误hdu关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "hdu",
	"OJusername" : "123abc",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "关联账户用户名或密码错误",
	"data": []
}
```

### **Test 3** · 该账号已添加hdu关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "hdu",
	"OJusername" : "123123",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "已关联hdu账号",
	"data": []
}
```

### **Test 4** · 该hdu账号已被其他账号关联
```
{
	"Uusername" : "starset",
	"OJname" : "hdu",
	"OJusername" : "123123",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "账户已被关联",
	"data": []
}
```

### **Test 5** · 添加foj关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "foj",
	"OJusername" : "test123123",
	"OJpassword" : "test123123"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "添加成功",
	"data": []
}
```

### **Test 6** · 该账号已添加foj关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "foj",
	"OJusername" : "test123124",
	"OJpassword" : "test123124"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "已关联foj账号",
	"data": []
}
```

### **Test 7** · 该foj账号已被其他账号关联
```
{
	"Uusername" : "abcdef",
	"OJname" : "foj",
	"OJusername" : "test123123",
	"OJpassword" : "test123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "账户已被关联",
	"data": []
}
```

### **Test 8** · 添加正确cf关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf",
	"OJusername" : "Mystarset",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "添加成功",
	"data": []
}
```

### **Test 9** · 添加错误cf关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf",
	"OJusername" : "Mystaset",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "关联账户用户名或密码错误",
	"data": []
}
```

### **Test 10** · 该账号已添加cf关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf",
	"OJusername" : "starset",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "已关联cf账号",
	"data": []
}
```

### **Test 11** · 该cf账号已被其他账号关联
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf",
	"OJusername" : "Mystarset",
	"OJpassword" : "123123"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "账户已被关联",
	"data": []
}
```

---

## **接口 · 获取oj过题数信息**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_acproblems?Uusername=&OJname=**
- **测试用例**

### **Test 1** · 获取hdu题量信息
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdef&OJname=hdu
```
{

}
```
**返回信息**
```
{
	"type": 1,
	"message": "查询成功",
	"data": "22"
}
```

### **Test 2** · 获取foj题量信息
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdef&OJname=foj
```
{

}
```
**返回信息**
```
{
	"type": 1,
	"message": "查询成功",
	"data": "0"
}
```

### **Test 3** · 获取cf题量信息
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdef&OJname=cf
```
{

}
```
**返回信息**
```
{
	"type": 1,
	"message": "查询成功",
	"data": "49"
}
```

### **Test 4** · 不带Uusername参数
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername&OJname=cf
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "必须指定Uusername",
	"data": []
}
```

### **Test 5** · 不带OJname参数
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdef&OJname
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "必须指定OJname",
	"data": []
}
```

### **Test 6** · 不带所有参数
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername&OJname
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "必须指定Uusername",
	"data": []
}
```

### **Test 6** · Uusername错误
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdf&OJname=hdu
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "用户名错误",
	"data": []
}
```

### **Test 7** · OJname错误
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acproblems?Uusername=abcdef&OJname=hu
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "OJ名称出错",
	"data": []
}
```
---

## **接口 · 查询用户所有oj关联账号信息**
- **请求方法：post**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_account**
- **测试用例**

### **Test 1** · 提交正确的用户名
```
{
	"Uusername" : "abcdef"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "查询成功",
	"data": [
		{
			"OJname": "cf",
			"OJusername": "starset",
			"Account": "49"
		},
		{
			"OJname": "foj",
			"OJusername": "test123123",
			"Account": "0"
		},
		{
			"OJname": "hdu",
			"OJusername": "123123",
			"Account": "15"
		}
	]
}
```

### **Test 2** · 提交错误的用户名
```
{
	"Uusername" : "abcde"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "未关联oj账户",
	"data": []
}
```

---

## **接口 · 删除用户oj关联账号信息**
- **请求方法：post**
- **接口网址：http://icpc-system.and-who.cn/Oj/del_oj_account**
- **测试用例**

### **Test 1** · 删除账号的hdu关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "hdu"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

### **Test 2** · 该账号没有关联hdu账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "hdu"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "未关联该OJ账号",
	"data": []
}
```

### **Test 3** · 删除账号的foj关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "foj"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

### **Test 4** · 该账号没有关联foj账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "foj"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "未关联该OJ账号",
	"data": []
}
```

### **Test 5** · 删除账号的cf关联账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf"
}
```
**返回信息**
```
{
	"type": 1,
	"message": "删除成功",
	"data": []
}
```

### **Test 6** · 该账号没有关联cf账号
```
{
	"Uusername" : "abcdef",
	"OJname" : "cf"
}
```
**返回信息**
```
{
	"type": 0,
	"message": "未关联该OJ账号",
	"data": []
}
```

---

## **接口 · 查询用户oj近期(两周)过题详细信息**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Oj/get_oj_acinfo?Uusername=**
- **测试用例**

### **Test 1** · 获取近期过题详细信息
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acinfo?Uusername=abcdef
```
{

}
```
**返回信息**
```
{
	"type": 1,
	"message": "查询成功",
	"data": {
		"ac_count": 2,
		"ac_info": [
			{
				"OJname": "hdu",
				"time": "2017-12-07 19:02:06",
				"name": "hdu3966",
				"url": "http:\/\/acm.hdu.edu.cn\/showproblem.php?pid=3966"
			},
			{
				"OJname": "cf",
				"time": "2017-12-02 19:34:14",
				"name": "895B - XK Segments",
				"url": "http:\/\/codeforces.com\/problemset\/problem\/895\/B"
			}
		]
	}
}
```

### **Test 2** · 不带Uusername参数
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acinfo?Uusername
```
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "必须指定Uusername",
	"data": []
}
```

### **Test 3** · Uusername错误
**查询示例：**http://icpc-system.and-who.cn/oj/get_oj_acinfo?Uusername=abcdf
{

}
```
**返回信息**
```
{
	"type": 0,
	"message": "用户名错误",
	"data": []
}
```
