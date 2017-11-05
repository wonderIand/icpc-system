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





