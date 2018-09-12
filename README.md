#NutApi 


###NutApi 是一个轻量级PHP后台接口开发框架，目的是让接口开发更简单，更快的实现业务。
```
此框架代码开源、产品开源、思想开源 如有疑问请邮件
```

#安装
+ *请从gitbub上拉取稳定的代码*
+ *建议PHP >= 5.3.3*

将代码下载解压到服务器后即可，然后把根目录设置为Public。
项目依赖两个扩展
+ *yar （提供并行，异步接口支持）*
+ *phalcon （项目底层框架，实现业务）*


#文档
##主要目录结构
```
.nutapi
│
├── public          	//对外访问目录，建议隐藏PHP实现
│   └── index.php		//项目服务访问入口
│   └── cli.php		//命令行如口
│
├── apps
	├── Config      //项目接口公共配置，主要有：config.php, services.php, modules.php
	├── library     //项目接口公共类库
	├── logs        //错误日志保存
	├── tasks       //计划任务，命令行运行
	│
	│
	└── Demo					//应用接口服务，名称自取，可多组
	    ├── controllers		//服务提供普通http get请求调用
	    ├── models			//接口数据层
	    ├── services			//接口业务层
	    └── Module.php		//加载服务模块入口

```
