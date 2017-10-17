# Github Standard

标签：WonderLand

## **主要部分目录**

- **Part 0 · 文 档 相 关**

- **Part 1 · 使 用 教 程**

- **Part 2 · 基 础 教 程**

- **Part 3 · 多 人 协 作**

- **Part 4 · Pull Request**

- **Part 5 · Commit Message 规范**

## **Part 0 · 文 档 相 关**

### **日 志**：

| Date       | Tags |
| ---------- | ---- |
| 2017/10/17 | 增加 **`Commit Message 规范`** |
| 2017/10/17 | 增加 **`pull request`** |
| 2017/10/16 | 初稿 |


### **声 明**：

**适 用 背 景**：该项目的 **【后台开发】** 部分。

**文 档 负 责**：**郑浩晖**



## **Part 1 · 规 范 相 关**

### **文 件 编 辑**

**文 件 路 径：** 确保文件路径无中文，无论是本地还是 **Github** ！

**文 件 操 作：** 不要使用 Windows **`Notepad`** 编辑任何文本文件！

**文 件 操 作：** 使用 **`Notepad++`** 等替代，默认编码设为 **`UTF-8 without BOM`**

### **项 目 开 发**

**注 意 事 项 · 1：** 可以先写好 **`commit`** 的时候的备注信息，和为什么要写 **`单元测试`** 一样的思想。

**注 意 事 项 · 2：** 如果发现做了超出预期备注信息太多的事，应先把当前的变动 **`stash`** 起来，新建一条分支先去解决预期外的问题。


### **文 件 上 传**

**上 传 说 明：** 备注信息，一律使用 **`英文`**。

**上 传 说 明：** 备注信息，如 **`git commit -m ":sparkles: Class(TagSystem) > Function(addTag)"`**

**文 件 冲 突：** 不要强制合并！不要强制合并！**汉犇犇**：谁敢强制合并砍谁！

### **分 支 相 关**

**主 体 分 支**：**`master` 分支**，总是保证该分支是稳定的可用的版本。

**开 发 分 支**：**`dev` 分支**，内测版本，稳定可用后才考虑更新到 **`master`**。 分支，该分支一般来说超前 **`master`** 分支若干提交版本。

**分 支 合 并**：所有的分支合并一律要加 **`--no-ff`** ！所有的分支合并一律要加 **`--no-ff`** ！

## **Part 2 · 基 础 教 程**

### **备 查 链 接**

**链 接 一**：**[《廖雪峰 · Git教程》][1]**

**备 注 一**：配合视频看。

### **准 备 相 关**

**安 装**：**[《廖雪峰 · Git教程》 · 安装Git][2]**

**克 隆**：**`git clone git@github.com:xxxxxx.git`**

**验 证**：

- **背 景**：如果 **本地 Git 仓库** 和 **GitHub 仓库**之间是第一次传输，需要添加许可；

- **链 接**：**[《远程仓库与本地仓库设置 SSH Key》][3]**


### **分 支 相 关 `Branch`**

**备 注 · 1**：分支这块比较重要，要求都去看一下教程理解一下是怎样的一个工作方式。

**备 注 · 2**：链接以下的内容只是列出了我们开发场景中常用的指令以备查阅。

**教 程 链 接**：**[《廖雪峰 · Git教程》 · 创建与合并][4]**

**切 换 分 支**：**`git checkout 分支名`**

**切 换 分 支**：**`git checkout -b 分支名`**，强制切换，不存在的话自动创建分支；

**删 除 分 支**：**`git branch -D 分支名`**

**合 并 分 支**：

- **背 景**：假设要把 **`dev`** 分支的内容更新到 **`master`** 分支。

- **指 令**：切换到 **`master`**，执行 **`git merge --no-ff dev`** 。

**分 支 冲 突**：**[《廖雪峰 · Git教程》 · 解决冲突][5]**

## **Part 3 · 多 人 协 作**

### **推 荐 工 具**

**文 档 制 作**：**`作业部落`**，**`Typora / 作业部落`**



### **备 查 资 料**

**链 接 一**：**[《github团队合作管理代码》][6]**

**链 接 二**：**[《Git工作流指南：Pull Request工作流》][7]**

### **签 入 管 理**

**流 程 一**：把团队项目 **`folk`** 到自己仓库。

**流 程 二**：在自己的仓库上编写相关代码，要求有递进可查的 **`commit`** 记录。

**流 程 三**：成熟后（往往在本地若干条commit记录后）往团队项目中发起 **`pull request`**

**流 程 四**：将相关的 **md文档**（具体见下一个版块）提交给管理员。

**流 程 四**：等待管理员 **`审核`**、**`签入`** 代码；**注意!**：在这之后还是可以 **commit** 的，对 **`pull request`** 的理解请查阅 **【备查资料】**

### **签 入 原 则**

**原 则 一**：具有 **【接口描述文档】**，**`commit 记录`**。

**原 则 二**：符合代码规范且通过测试。

## **Part 4 · Pull Request**

**教 程 零**：可以看西瓜学长的教程；

**教 程 一**：**[《GitHub——Pull Request》][8]**

**教 程 二**：**[《GitHub 之 pull request 流程简介》][9]**

## **Part 5 · Commit Message 规范**

**规 定 一 · 不会用 emoji 的后台不是好前端**

- **备 注 一**：commit信息首部必须为一个代表commi类型的 **emoji**，**之后隔一个空格！！！**

- **资 料 一**：**[程序员 git commit 时 emoji 使用指南][10]**

- **本 项 目 实 用 表**：

| emoji 代码 | commit 说明 |
| - | - |
| 施工 **`:construction:`** | 工作进行中 |
| 火花 **`:sparkles:`** | 引入新功能/接口 |
| 备忘录 **`:memo:`** | 撰写文档 |
| 赛马 **`:racehorse:`** | 提升性能 |
| 调色板 **`:art:`** | 改进代码结构/代码格式 |
| 扳手 **`:wrench:`** | 修改配置文件 |
| 火箭 **`:rocket:`** | 部署功能 |
| 书签 **`:bookmark:`** | 发行/版本标签 |
| 复选框 **`:white_check_mark:`** | 增加测试 |
| 火焰 **`:fire:`** | 移除代码或文件 |
| BUG **`:bug:`**   | 修复bug |
| 锁 **`:lock:`** | 修复安全问题 |
| 急救车 **`:ambulance:`** | 重要补丁 |
| 锤子 **`:hammer:`** | 重大重构 |
| 鲸鱼 **`:whale: ()`** | Docker 相关工作 |


**规 定 二 · commit信息描述清晰**

**示 例 一 · 给 User.php 新增了一个修改密码的接口 edit_passwd**

```c++
git commit -m ":sparkles: Controller(User) add Api(edit_passwd)."
```

**示 例 二 · 为 User.php 的新接口 edit_passwd 增加/完善接口文档**

```c++
git commit -m ":memo:  add/update ApiDoc(User)."
```

**示 例 三 · 修复了修改密码接口，旧密码错误也能通过的bug**

```C++
git commit -m ":bug:  Controller(User) Fix Bug(unexpected old passwd)."
```

**end.**


  [1]: https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000
  [2]: https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000/00137396287703354d8c6c01c904c7d9ff056ae23da865a000
  [3]: http://blog.csdn.net/oliver__lau/article/details/51242267
  [4]: https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000/001375840038939c291467cc7c747b1810aab2fb8863508000
  [5]: https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000/001375840202368c74be33fbd884e71b570f2cc3c0d1dcf000
  [6]: http://blog.csdn.net/napoay/article/details/50453480
  [7]: http://blog.jobbole.com/76854/
  [8]: http://blog.csdn.net/u012325167/article/details/50635522
  [9]: http://blog.csdn.net/lw_power/article/details/46583419
  [10]: http://blog.csdn.net/simple_the_best/article/details/53320275