# WonderLand ApiDoc - Oj

��ǩ���ո�ָ����� WonderLand

---

## **��־**

| ����         | ��ע  
| ------------ | ------
| **17/11/07** | ���ӽӿ� �� **������oj�����˺� add_oj_account��**
| **17/11/07** | ���ӽӿ� �� **����ȡoj��������Ϣ get_oj_acproblems��**


---

## **����**

- **��oj_account ����**

| ������        | ����    | ��С���� | ��󳤶� | ����      | ����Ҫ��
| ------------- | ------  | -------- | -------- | --------- | --------
| **Uusername** | �û���  | 6        | 16       | char(20)  | ��ĸ/����/�»���/���ۺ�
| **OJname** 	| oj����  | -        | -        | char(20)  | Ϊ"hdu"��"foj"��"cf"                     
| **OJpassword**| oj�û���| -        | -        | char(20)  | - 
| **OJusername**| oj����  | -        | -        | char(20)  | -                

---

## **�ӿ� �� ����oj�����˺�**

- **���󷽷���POST**
- **�ӿ���ַ��http://icpc-system.and-who.cn/Oj/add_oj_account**

- **����Ҫ��**

| ������         | ��Ҫ�� | ��С���� | ��󳤶� | ����Ҫ��
| -------------  | ------ | -------- | -------- | --------
| **Uusername**  | O      | 6        | 16       | ��ĸ/����/�»���/���ۺ�
| **OJname**     | O      | -        | -        | Ϊ"hdu"��"foj"��"cf" �ֱ��ʾ���Ӷ�Ӧoj���˺�                     
| **OJpassword** | O      | -        | -        | -                     
| **OJusername** | O      | -        | -        | -                     


- **�ɹ���������**

```
{
	"type": 1,
	"message": "���ӳɹ�",
	"data": []
}
```

---

## **�ӿ� �� ��ȡoj��������Ϣ**

- **���󷽷���GET**
- **�ӿ���ַ��http://icpc-system.and-who.cn/Oj/get_oj_acproblems?Uusername=&OJname=**

- **����Ҫ��**

| ������         | ��Ҫ�� | ��С���� | ��󳤶� | ����Ҫ��
| -------------  | ------ | -------- | -------- | --------
| **Uusername**  | O      | 6        | 16       | ��ĸ/����/�»���/���ۺ�
| **OJname**     | O      | -        | -        | Ϊ"hdu"��"foj"��"cf" �ֱ��ʾ��ѯ��Ӧoj�Ĺ�����  


- **�ɹ���������**

```
{
	"type": 1,
	"message": "��ѯ�ɹ�",
	"data": "22" 
}
```