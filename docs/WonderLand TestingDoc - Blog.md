# WonderLand TestingDoc - Blog

标签（空格分隔）： WonderLand

---

## **接口 · 增加文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/register**
- **测试用例**

### **Test 1** · 表单齐全测试
```
{
 	"Btitle": "咸鱼之路",
 	"BAarticle": "一位乘客失去了梦想",
 	"Btype": "算 法 学 习"
}
```
**返回信息**
```
{
    "type": 1,
    "message": "增加成功",
    "data": []
}
```

**Test 2** · 缺乏标题测试
```
{
	"Btitle": "",
 	"BAarticle": "一位乘客失去了梦想",
 	"Btype": "算 法 学 习"
}
```

**返回信息**
```
{
	"type": 0,
	"message": "标题  必须填写",
	"data": []
}
```


**Test 3** · 缺乏正文测试
```
{
	"Btitle": "咸鱼之路",
 	"BAarticle": "",
 	"Btype": "算 法 学 习"
}
```

**返回信息**
```
{
	"type": 0,
	"message": "正文  必须填写",
	"data": []
}
```

**Test 4** · 缺乏类型测试
```
{
	"Btitle": "咸鱼之路",
 	"BAarticle": "一位乘客失去了梦想",
 	"Btype": ""
}
```

**返回信息**
```
{
	"type": 0,
	"message": "类型  必须填写",
	"data": []
}
```

---

## **接口 · 删除文章**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/Blog/delete**
- **测试用例：**

**Test 1** · 删除文章测试
```
{
	"Bid": "1"
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

**Test 2** · 删除不存在的记录测试
```
{
	"Bid": "1"
}
```

**返回信息**
```
{
	"type": 0,
	"message": "记录不存在",
	"data": []
}
```

---

## **函数 · 获取标签叶子结点**
- **函数名：**get_leaves()
- **作用域：**Blog_model
- **传入数据：**标签标号
- **返回数据：**该标签的所有叶子结点标号
- **测试用例：**

**Test 1** · 传入叶子结点标签标号

**传入数据：**
```
	//标签2为叶子结点
	$tid = 2;
```

**返回数据：**
```
	$ret = array(2);
```


**Test 2** · 传入父结点标签标号

**传入数据：**
```
	//标签3为父结点，该标签下所有叶子结点标号为4、5
	$tid = 3;
```

**返回数据：**
```
	$ret = array(4, 5);
```


---

## **接口 · 点赞排行**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/Blog/ranking_like**
- **测试用例：**

**Test 1** · 获取点赞排行
```
{
}
```
**返回信息**
```
{
	"type": 1,
	"message": "获取成功",
	"data": [
		{
			"Bid": "2",
			"Btype": "算 法 学",
			"Bproblemid": "无",
			"Btitle": "咸鱼之路",
			"Bauthor": "hbbhbb",
			"Btime": "2017-11-21 15:45:10",
			"Blikes": "2",
			"Bviews": "0"
		},
		{
			"Bid": "1",
			"Btype": "算 法 学",
			"Bproblemid": "无",
			"Btitle": "咸鱼之路",
			"Bauthor": "hbbhbb",
			"Btime": "2017-11-21 15:45:04",
			"Blikes": "1",
			"Bviews": "2"
		},
		{
			"Bid": "3",
			"Btype": "算 法 学",
			"Bproblemid": "无",
			"Btitle": "咸鱼之路",
			"Bauthor": "hbbhbb",
			"Btime": "2017-11-21 15:45:10",
			"Blikes": "0",
			"Bviews": "0"
		}
	]
}
```


---