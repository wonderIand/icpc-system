# WonderLand TestingDoc - Blog

标签（空格分隔）： WonderLand

---

## **接口 · 查询某条记录**
- **请求方法：GET**
- **接口网址：http://icpc-system.and-who.cn/User_training/get?:UTid**

| 返回的记录信息  | 备注
| --------------- | --------
| **editable**    | 当前用户是否有可编辑权限
| **UTid**        | 记录编号
| **Uusername**   | 作者
| **UTtitle**     | 标题 
| **UTplace**     | 排名
| **UTaddress**   | 训练地址
| **UTproblemset**| 题集
| **UTview**      | 阅读量
| **UTup**        | 点赞数
| **upvoteEnable**| 当前用户可否点赞

**测试样例**

**Test 1** · 查询UTid=3训练记录阅读量

**接口网址：http://icpc-system.and-who.cn/User_training/get?UTid=3**

**成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"UTid": "3",
		"Uusername": "aaaau1",
		"UTdate": "2017-07-11 10:55:00",
		"UTtitle": "a title6",
		"UTplace": "1",
		"UTup": "3",
		"UTview": "44",
		"UTaddress": "https:\/\/www.baidu.com",
		"UTproblemset": [
			"D",
			"D",
			"D"
		],
		"editable": true,
		"upvoteEnable": false
	}
}
```

**Test 2** · 同一用户重复查询UTid=3训练记录阅读量

**接口网址：http://icpc-system.and-who.cn/User_training/get?UTid=3**

**成功返回**
```
{
	"type": 1,
	"message": "获取成功",
	"data": {
		"UTid": "3",
		"Uusername": "aaaau1",
		"UTdate": "2017-07-11 10:55:00",
		"UTtitle": "a title6",
		"UTplace": "1",
		"UTup": "3",
		"UTview": "44",
		"UTaddress": "https:\/\/www.baidu.com",
		"UTproblemset": [
			"D",
			"D",
			"D"
		],
		"editable": true,
		"upvoteEnable": false
	}
}
```

---

## **接口 · 点赞**
- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User_training/upvote**
- **表单要求**

| 属性名          | 必要性 | 最小长度 | 最大长度 | 特殊要求
| --------------- | ------ | -------- | -------- | --------
| **Utoken**      | O      | -        | -        | -
| **UTid**        | O      | -        | -        | -

**Test 1** · 点赞UTid=1的文章

```
{
	"UTid": "1"
}
```

**成功返回**

```
{
	"type": 1,
	"message": "点赞成功",
	"data": []
}
```

**Test 2** · 重复点赞测试

```
{
	"UTid": "1"
}
```

**成功返回**

```
{
	"type": 402,
	"message": "不能重复点赞",
	"data": []
}
```

---





