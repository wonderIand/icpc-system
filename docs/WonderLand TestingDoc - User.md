# WonderLand TestingDoc - User

标签（空格分隔）： WonderLand

---

## **接口 · 上传用户头像**

- **请求方法：POST**
- **接口网址：http://icpc-system.and-who.cn/User/upload_icon**
- **测试用例**

### **Test 1** · 上传图片满足要求
**返回信息**
```
{
	"type":1,
	"message":"上传成功",
	"data": {
		"upload_data": {
			"file_name": "zhengshuhao.JPG",
			"file_type": "image\/jpeg",
			"file_path": "D:\/Demo\/icpc-system\/uploads\/user_icon\/",
			"full_path": "D:\/Demo\/icpc-system\/uploads\/user_icon\/zhengshuhao.JPG",
			"raw_name": "zhengshuhao",
			"orig_name": "zhengshuhao.JPG",
			"client_name": "IMG_4562.JPG",
			"file_ext": ".JPG",
			"file_size": 59.47,
			"is_image": true,
			"image_width": 700,
			"image_height": 700,
			"image_type": "jpeg",
			"image_size_str": "width=\"700\" height=\"700\""
			},
		"icon_path": "http:\/\/icpc-system.and-who.cn\/uploads\/user_icon\/zhengshuhao.JPG"
	}
}
```

### **Test 2** · 上传图片不满足要求
**返回信息**
```
{
	"type": 0,
	"message": "上传失败",
	"data": {
		"error": "上传文件超出PHP配置文件中允许的最大长度."
	}
}
```