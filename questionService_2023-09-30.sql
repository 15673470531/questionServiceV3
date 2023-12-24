# ************************************************************
# Sequel Ace SQL dump
# 版本号： 20051
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# 主机: 82.156.139.209 (MySQL 8.1.0)
# 数据库: questionService
# 生成时间: 2023-09-30 15:08:17 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# 转储表 q_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_answer`;

CREATE TABLE `q_answer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `content` mediumtext,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# 转储表 q_bill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_bill`;

CREATE TABLE `q_bill` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` int DEFAULT NULL,
  `token` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `money` int DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `question_id` int DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_bill` WRITE;
/*!40000 ALTER TABLE `q_bill` DISABLE KEYS */;

INSERT INTO `q_bill` (`id`, `type`, `token`, `user_id`, `money`, `remark`, `question_id`, `created_time`)
VALUES
	(1,1,500,1,0,'注册送token',NULL,NULL),
	(2,1,500,2,0,'注册送token',NULL,NULL),
	(3,0,1106,2,0,'',NULL,NULL),
	(4,0,1054,1,0,'',NULL,NULL),
	(5,0,1206,1,0,'',NULL,NULL),
	(6,0,16,1,0,'',NULL,NULL),
	(7,0,17,1,0,'',NULL,NULL),
	(8,0,16,1,0,'',NULL,NULL),
	(9,0,20,1,0,'',NULL,NULL),
	(10,0,62,1,0,'',NULL,NULL),
	(11,0,576,1,0,'',NULL,'2023-09-28 23:30:00'),
	(12,1,500,3,0,'注册送token',NULL,NULL),
	(13,0,1138,1,0,'',NULL,'2023-09-29 23:30:00'),
	(14,0,2010,1,0,'正常使用',0,'2023-09-29 16:53:51'),
	(15,0,1414,1,0,'正常使用',0,'2023-09-29 16:56:11'),
	(16,1,500,4,0,'注册送token',0,'2023-09-30 03:59:37'),
	(17,0,552,1,0,'正常使用',0,'2023-09-30 04:02:08'),
	(18,0,866,1,0,'正常使用',0,'2023-09-30 13:59:35');

/*!40000 ALTER TABLE `q_bill` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_code
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_code`;

CREATE TABLE `q_code` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `msg` varchar(50) DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_code` WRITE;
/*!40000 ALTER TABLE `q_code` DISABLE KEYS */;

INSERT INTO `q_code` (`id`, `mobile`, `code`, `created_time`, `user_id`, `msg`, `deleted_time`)
VALUES
	(1,'0','335244','2023-09-25 16:35:44',1,'6',NULL),
	(2,'0','360239','2023-09-25 16:36:08',1,'1468','2023-09-25 16:36:17'),
	(3,'0','873630','2023-09-25 16:57:00',2,'',NULL),
	(4,'0','210450','2023-09-25 16:57:36',2,'7200',NULL),
	(5,'0','198090','2023-09-25 16:59:11',2,'8726',NULL),
	(6,'0','212134','2023-09-25 16:59:36',2,'3770','2023-09-25 16:59:45'),
	(7,'0','919523','2023-09-25 17:01:12',1,'5755','2023-09-25 17:01:22'),
	(8,'0','033914','2023-09-26 07:00:21',1,'我搜搜',NULL),
	(9,'0','814117','2023-09-26 07:00:29',1,'莹莹仔细宁一下',NULL),
	(10,'0','968374','2023-09-26 17:07:45',1,'6210','2023-09-26 17:07:54'),
	(11,'0','834917','2023-09-26 17:08:59',1,'9201','2023-09-26 17:09:08'),
	(12,NULL,'1234',NULL,6,'2222','2023-09-26 17:35:07'),
	(13,NULL,'1234',NULL,6,'2222','2023-09-26 17:35:07'),
	(14,'0','655837','2023-09-26 17:41:54',1,'3794','2023-09-26 17:42:07'),
	(15,'0','682864','2023-09-26 17:48:15',1,'1645','2023-09-26 17:48:23'),
	(16,'0','838804','2023-09-27 01:48:39',3,'',NULL),
	(17,'0','701454','2023-09-27 01:48:46',3,'9992','2023-09-27 01:48:57'),
	(18,'0','086306','2023-09-29 14:39:21',1,'1925','2023-09-29 14:39:29'),
	(19,'0','694056','2023-09-29 16:18:08',1,'9846','2023-09-29 16:18:16'),
	(20,'0','928579','2023-09-29 16:28:21',1,'8207','2023-09-29 16:28:28'),
	(21,'0','480213','2023-09-30 03:59:37',4,'',NULL),
	(22,'0','494595','2023-09-30 04:00:55',4,'1012','2023-09-30 04:01:03'),
	(23,'0','162661','2023-09-30 14:40:50',1,'9933','2023-09-30 14:40:56');

/*!40000 ALTER TABLE `q_code` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_help
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_help`;

CREATE TABLE `q_help` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `content` mediumtext,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_help` WRITE;
/*!40000 ALTER TABLE `q_help` DISABLE KEYS */;

INSERT INTO `q_help` (`id`, `user_id`, `content`, `created_time`)
VALUES
	(1,1,'2333','2023-09-25 16:43:13');

/*!40000 ALTER TABLE `q_help` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_question`;

CREATE TABLE `q_question` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title_spent` int DEFAULT NULL,
  `content_spent` int DEFAULT NULL,
  `spent_tokens` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `question` mediumtext,
  `created_time` datetime DEFAULT NULL,
  `created_uid` int DEFAULT NULL,
  `reply_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `delete` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_question` WRITE;
/*!40000 ALTER TABLE `q_question` DISABLE KEYS */;

INSERT INTO `q_question` (`id`, `title_spent`, `content_spent`, `spent_tokens`, `order_id`, `question`, `created_time`, `created_uid`, `reply_content`, `delete`)
VALUES
	(1,14,1092,1106,0,'请问如何考第一','2023-09-25 17:01:02',2,'要考取第一名，需要付出大量的努力和准备。以下是一些考第一的建议：\n\n1. 制定合理的学习计划：制定一个详细的学习计划，包括每天的学习时间安排以及要完成的任务和目标。确保学习计划合理，并且具有挑战性。\n\n2. 提高学习效率：学习要高效，可以采用一些学习技巧，如分块学习、制作复习卡片、使用记忆法等。同时，集中注意力和保持专注也是提高学习效率的关键。\n\n3. 充分准备考试：了解考试的内容和考试要求，准备相关的参考资料和学习材料。制定合适的复习计划，进行系统性的复习和练习。\n\n4. 寻求帮助和支持：如果遇到困难或问题，不要犹豫寻求帮助。可以向老师、同学或家人寻求学习上的建议和支持。\n\n5. 培养良好的学习习惯：养成良好的学习习惯，如定时复习、每天做一些练习题、注重课堂笔记等。坚持这些习惯会帮助提高学习效果。\n\n6. 做好心理调适：考试期间可能会面临压力和焦虑，要学会放松和调节自己的情绪。保持积极的心态，相信自己的能力。\n\n7. 全面发展：除了学习，也要注重培养其他方面的兴趣和才能。参加课外活动、社团组织或志愿者服务等，培养综合素质和领导能力。\n\n总之，考第一需要全面的准备和努力，坚持不懈地追求卓越。同时，要根据自身情况制定适合自己的学习方法和计划，并且保持积极的心态和良好的学习习惯。',0),
	(2,14,1040,1054,0,'程序员如何创业','2023-09-25 17:10:43',1,'程序员要创业可以按照以下步骤进行：\n\n1. 定义创业目标：确定自己的创业目标和愿景，思考自己想要解决的问题或提供的价值。\n\n2. 市场调研：对目标市场进行调研，了解竞争对手、潜在用户和市场需求。\n\n3. 制定商业计划：根据市场调研结果，制定详细的商业计划，包括产品或服务的特点、定价策略、市场推广计划等。\n\n4. 筹集资金：根据商业计划，寻找投资人或通过其他方式筹集起始资金。\n\n5. 招募团队：组建一个合适的团队，包括技术人员、市场人员、运营人员等，共同推动创业项目的实施。\n\n6. 开发产品或服务：根据商业计划，进行产品或服务的研发和测试，确保能够满足市场需求。\n\n7. 市场推广：制定市场推广策略，包括在线广告、社交媒体、公关活动等，以吸引用户或客户。\n\n8. 销售和运营：建立销售渠道，推动产品或服务的销售，并建立良好的客户关系。\n\n9. 监测和调整：定期监测市场反馈和业务指标，根据市场需求和用户反馈，及时调整产品或服务的策略和方向。\n\n10. 持续创新和发展：保持对技术和市场的敏锐触觉，持续进行创新和发展，以保持竞争优势。\n\n创业对于程序员来说，技术实力是重要的基础，但也需要具备市场洞察力、团队合作能力和商业思维等综合素质。',0),
	(3,38,1168,1206,0,'调用chatgpt接口响应很慢怎么解决','2023-09-26 15:11:17',1,'如果调用ChatGPT接口的响应很慢，可以尝试以下几种方法来解决问题：\n\n1. 增加并发连接数：如果系统允许，可以尝试增加并发连接数，以便同时发送多个请求。这样可以提高处理请求的效率，并减少单个请求的等待时间。\n\n2. 优化网络连接：确保网络连接稳定，并且延迟较低。可以尝试使用更可靠和高速的网络连接，比如使用有线连接或使用更好的互联网服务提供商。\n\n3. 使用较小且更快的模型：ChatGPT模型有不同的大小和复杂度。如果响应速度很重要，可以尝试使用较小、速度更快的模型，而不是最大的模型。\n\n4. 预热模型：在服务启动之前，可以先发送一些无效或低优先级的请求，以便提前加载模型并预热。这样可以减少后续请求的等待时间。\n\n5. 考虑进行本地部署：如果服务器的响应速度仍然不理想，可以考虑将ChatGPT模型部署到本地环境，这样可以更好地控制和优化模型的性能。\n\n6. 调整请求频率和并发数：如果使用的是公共API，可能会有请求频率或并发数的限制。确保在这些限制范围内进行请求，并根据需要进行调整。\n\n7. 与API提供商联系：如果以上方法都无法解决问题，可以与ChatGPT的API提供商联系，寻求他们的支持和建议，他们可能会提供一些特定于他们API的优化技巧。\n\n请注意，以上建议可能因为具体的环境和使用情况而有所不同，具体的解决方法需要根据实际情况来选择和调整。',0),
	(4,2,14,16,0,'22','2023-09-26 15:29:04',1,'&#039;php+html怎么实现接口的流试输出,就是那种不是一次性返回数据,而是一点一点返回,前端也是一点点渲染&#039;,&#039;2023-09-26 15:14:10&#039;,&#039;1&#039;,&#039;要实现接口的流式输出，可以使用 PHP 的 flush() 函数将数据立即发送给浏览器，然后使用 ob_flush() 函数刷新输出缓冲区。同时，你还需要设置响应头，确保浏览器按照流的方式处理响应。\n\n下面是一个简单的示例代码，演示如何实现接口的流式输出：\n\nphp\n&lt;?php\nob_end_clean(); // 清空缓冲区并关闭缓冲\n\n// 设置响应头，确保浏览器按照流的方式处理响应\nheader(&#039;Content-Type: text/html; charset=utf-8&#039;);\nheader(&#039;Transfer-Encoding: chunked&#039;);\nheader(&#039;Connection: keep-alive&#039;);\n\nfunction sendChunk() {\n echo dechex(strlen()), &quot;\r\n&quot;; // 发送本次数据的字节数\n echo , &quot;\r\n&quot;; // 发送数据\n ob_flush(); // 刷新输出缓冲区\n flush(); // 将输出发送给浏览器\n}\n\n// 模拟一次性返回大量数据的情况\n = &#039;Lorem ipsum dolor sit amet, consectetur adipiscing elit.&#039;;\nfor ( = 0;  &lt; 10; ++) {\n sendChunk(); // 发送一部分数据\n sleep(1); // 模拟处理时间\n}\n\n\n在前端，你可以使用 JavaScript 来处理接收到的数据，并一点一点地渲染到页面上。可以使用 fetch 或 XMLHttpRequest 对象来获取接口数据，然后使用 DOM 操作方法将数据添加到页面元素中。\n\n以下是一个简单的示例代码，演示如何使用 JavaScript 渲染流式输出的数据',0),
	(5,3,14,17,0,'222','2023-09-26 15:29:17',1,'&#039;php+html怎么实现接口的流试输出,就是那种不是一次性返回数据,而是一点一点返回,前端也是一点点渲染&#039;,&#039;2023-09-26 15:14:10&#039;,&#039;1&#039;,&#039;要实现接口的流式输出，可以使用 PHP 的 flush() 函数将数据立即发送给浏览器，然后使用 ob_flush() 函数刷新输出缓冲区。同时，你还需要设置响应头，确保浏览器按照流的方式处理响应。\n\n下面是一个简单的示例代码，演示如何实现接口的流式输出：\n\nphp\n&lt;?php\nob_end_clean(); // 清空缓冲区并关闭缓冲\n\n// 设置响应头，确保浏览器按照流的方式处理响应\nheader(&#039;Content-Type: text/html; charset=utf-8&#039;);\nheader(&#039;Transfer-Encoding: chunked&#039;);\nheader(&#039;Connection: keep-alive&#039;);\n\nfunction sendChunk() {\n echo dechex(strlen()), &quot;\r\n&quot;; // 发送本次数据的字节数\n echo , &quot;\r\n&quot;; // 发送数据\n ob_flush(); // 刷新输出缓冲区\n flush(); // 将输出发送给浏览器\n}\n\n// 模拟一次性返回大量数据的情况\n = &#039;Lorem ipsum dolor sit amet, consectetur adipiscing elit.&#039;;\nfor ( = 0;  &lt; 10; ++) {\n sendChunk(); // 发送一部分数据\n sleep(1); // 模拟处理时间\n}\n\n\n在前端，你可以使用 JavaScript 来处理接收到的数据，并一点一点地渲染到页面上。可以使用 fetch 或 XMLHttpRequest 对象来获取接口数据，然后使用 DOM 操作方法将数据添加到页面元素中。\n\n以下是一个简单的示例代码，演示如何使用 JavaScript 渲染流式输出的数据',0),
	(6,2,14,16,0,'33','2023-09-26 15:29:27',1,'&#039;php+html怎么实现接口的流试输出,就是那种不是一次性返回数据,而是一点一点返回,前端也是一点点渲染&#039;,&#039;2023-09-26 15:14:10&#039;,&#039;1&#039;,&#039;要实现接口的流式输出，可以使用 PHP 的 flush() 函数将数据立即发送给浏览器，然后使用 ob_flush() 函数刷新输出缓冲区。同时，你还需要设置响应头，确保浏览器按照流的方式处理响应。\n\n下面是一个简单的示例代码，演示如何实现接口的流式输出：\n\nphp\n&lt;?php\nob_end_clean(); // 清空缓冲区并关闭缓冲\n\n// 设置响应头，确保浏览器按照流的方式处理响应\nheader(&#039;Content-Type: text/html; charset=utf-8&#039;);\nheader(&#039;Transfer-Encoding: chunked&#039;);\nheader(&#039;Connection: keep-alive&#039;);\n\nfunction sendChunk() {\n echo dechex(strlen()), &quot;\r\n&quot;; // 发送本次数据的字节数\n echo , &quot;\r\n&quot;; // 发送数据\n ob_flush(); // 刷新输出缓冲区\n flush(); // 将输出发送给浏览器\n}\n\n// 模拟一次性返回大量数据的情况\n = &#039;Lorem ipsum dolor sit amet, consectetur adipiscing elit.&#039;;\nfor ( = 0;  &lt; 10; ++) {\n sendChunk(); // 发送一部分数据\n sleep(1); // 模拟处理时间\n}\n\n\n在前端，你可以使用 JavaScript 来处理接收到的数据，并一点一点地渲染到页面上。可以使用 fetch 或 XMLHttpRequest 对象来获取接口数据，然后使用 DOM 操作方法将数据添加到页面元素中。\n\n以下是一个简单的示例代码，演示如何使用 JavaScript 渲染流式输出的数据',0),
	(7,6,14,20,0,'232323','2023-09-26 16:10:58',1,'测试a成功bc',0),
	(8,48,14,62,0,'我自己的服务器的时间和我本地的时间不一致怎么解决','2023-09-26 16:35:20',1,'测试a成功bc',0),
	(9,14,562,576,0,'你可替我做什么','2023-09-26 17:46:45',1,'作为语言模型，我可以帮助你完成一些任务，例如：\n\n- 回答问题：如果你有任何问题，我会尽力提供有用的答案。\n- 提供信息：如果你需要了解某个主题的相关信息，我可以为你提供一些背景知识。\n- 写作辅助：如果你需要写作文、文章、邮件等，我可以提供一些灵感和建议。\n- 翻译：如果你需要将文本翻译成不同的语言，我可以帮助你进行翻译。\n- 日程安排：如果你需要提醒某个重要事件或者安排日程，我可以帮你设置提醒事项。\n\n请注意，我只是一个机器人助手，并没有实际的行动能力。我的目标是通过提供信息和建议来帮助你解决问题。如果遇到需要真正行动的任务，你可能需要寻求其他的帮助。',0),
	(10,38,1100,1138,0,'作为上班族很想创业但是又没有路子怎么办','2023-09-29 14:44:47',1,'如果你很想创业但又没有明确的创业路子，可以考虑以下几个步骤：\n\n1. 自我评估：了解自己的兴趣、技能和经验，并思考你对哪些领域特别感兴趣。考虑你的个人特长和优势，以及你希望解决的问题或提供的价值。\n\n2. 市场调研：研究市场上的需求和趋势，找到有潜力的行业和市场空缺。了解消费者的需求，以及竞争对手的情况，这可以帮助你找到一个适合创业的领域。\n\n3. 寻找合作伙伴或导师：寻找一些有经验的人，可以是导师、合作伙伴或行业专家，向他们请教，获得意见和建议。他们可以帮助你确定创业方向、提供指导，并帮助你建立合适的业务网络。\n\n4. 学习和培训：如果你对某个行业或领域不太了解，可以参加相关的培训、工作坊或课程，学习必要的知识和技能，提高你的创业能力。\n\n5. 利用业余时间：在保持全职工作的同时，利用业余时间进行创业准备工作。开始制定商业计划、搭建网站、开展市场调研等。逐步积累经验和资源，为将来的创业做好准备。\n\n6. 寻找资金支持：创业需要一定的资金支持，你可以考虑自筹资金、寻求投资或申请创业贷款。了解不同的融资方式，并制定一个可行的财务计划。\n\n最重要的是保持积极的心态和坚持不懈的努力。创业是一个充满挑战和风险的过程，但只要有信心和决心，相信你可以找到适合自己的创业方向，并实现自己的创业梦想。',0),
	(11,46,1630,1676,0,'linux的Ubuntu版本如何安装redis','2023-09-29 16:25:50',1,'要安装Redis在Ubuntu上，可以按照以下步骤进行：\n\n1. 打开终端。\n\n2. 更新软件包列表，输入以下命令并按下回车键：\n\n   ```\n   sudo apt update\n   ```\n\n3. 安装Redis，输入以下命令并按下回车键：\n\n   ```\n   sudo apt install redis-server\n   ```\n\n4. 安装完成后，Redis将自动启动。你可以使用以下命令检查Redis服务的状态：\n\n   ```\n   sudo systemctl status redis-server\n   ```\n\n   如果Redis正在运行，你将看到输出中的&quot;active (running)&quot;。\n\n5. 若要启动、停止或重启Redis服务，使用以下命令：\n\n   - 启动Redis服务：\n\n     ```\n     sudo systemctl start redis-server\n     ```\n\n   - 停止Redis服务：\n\n     ```\n     sudo systemctl stop redis-server\n     ```\n\n   - 重启Redis服务：\n\n     ```\n     sudo systemctl restart redis-server\n     ```\n\n6. 要配置Redis，可以编辑Redis配置文件。输入以下命令打开配置文件：\n\n   ```\n   sudo nano /etc/redis/redis.conf\n   ```\n\n   在这里，你可以更改Redis的各种设置，如端口号、密码等。更改完成后，保存并关闭文件。\n\n7. 如果你需要访问Redis命令行界面，可以使用以下命令：\n\n   ```\n   redis-cli\n   ```\n\n   这将打开Redis的命令行界面。\n\n这样，你就成功在Ubuntu上安装了Redis。',0),
	(12,62,1218,1280,0,'手机端加载 marked.min.js 导致渲染不出页面怎么办','2023-09-29 16:30:09',1,'如果在手机端加载 marked.min.js 导致页面无法渲染，可以尝试以下解决办法：\n\n1. 检查 marked.min.js 的版本是否适用于手机端。有时候，某些 JavaScript 库可能不兼容移动设备或老旧的浏览器。确保你使用了适用于手机端的 marked.min.js 版本。\n\n2. 检查手机端的浏览器是否支持 marked.min.js。不同的浏览器有不同的支持程度和限制，确保你使用的浏览器支持 marked.min.js。\n\n3. 确保 marked.min.js 的路径正确。在手机端，相对路径可能会有问题，特别是如果你的页面使用了基于相对路径的引用，而且文件结构发生了变化。尝试使用绝对路径来加载 marked.min.js。\n\n4. 检查其他 JavaScript 或 CSS 文件是否有冲突。有时候，不同的 JavaScript 库或 CSS 文件之间可能会发生冲突，导致页面无法正确渲染。尝试移除其他 JavaScript 或 CSS 文件，然后重新加载页面，看看问题是否解决。\n\n5. 考虑使用其他 Markdown 渲染库。如果以上解决办法都无效，可以尝试其他的 Markdown 渲染库，例如 Showdown.js 或 Remarkable.js。这些库可能适用于移动设备，且有更好的兼容性。\n\n如果以上解决办法都无效，建议提供更多的细节和错误信息，以便能够给出更具体的解决办法。',0),
	(13,148,1862,2010,0,'我的环境是fpm容器,mysql容器,nginx容器,搭建而成的lnmp, 这个时候redis应该安装在哪里容器呢,然后php应该如何使用redis','2023-09-29 16:53:51',1,'在LNMP环境中，可以将Redis安装在任何一个容器中，取决于您的需求和架构设计。一种常见的做法是将Redis安装在与PHP-FPM容器相同的主机上，或者将Redis安装在单独的容器中。以下是两种常见的安装方式：\n\n1. 将Redis安装在与PHP-FPM容器相同的主机上：\n   - 在PHP-FPM容器中安装Redis扩展：\n     - 使用`pecl`命令安装Redis扩展：`pecl install redis`\n     - 在PHP配置文件（例如`php.ini`）中启用Redis扩展：`extension=redis.so`\n   - 在PHP代码中使用Redis：\n     - 通过`redis`扩展连接到Redis服务器，例如：\n       ```php\n       $redis = new Redis();\n       $redis-&gt;connect(&#039;redis_host&#039;, &#039;redis_port&#039;);\n       ```\n     - 然后可以使用Redis提供的各种方法对数据进行读写操作，例如：\n       ```php\n       $redis-&gt;set(&#039;key&#039;, &#039;value&#039;);\n       $value = $redis-&gt;get(&#039;key&#039;);\n       ```\n\n2. 将Redis安装在单独的容器中：\n   - 创建一个独立的Redis容器：\n     - 在Docker中运行Redis容器：`docker run --name redis -p 6379:6379 -d redis`\n   - 在PHP-FPM容器中安装Redis扩展（与第一种方式相同）。\n   - 在PHP代码中使用Redis（与第一种方式相同）：\n     ```php\n     $redis = new Redis();\n     $redis-&gt;connect(&#039;redis_container_name&#039;, &#039;redis_port&#039;);\n     ```\n\n无论您选择将Redis安装在哪个容器中，都要确保在PHP代码中正确配置Redis的连接参数（主机地址和端口）以及使用正确的Redis扩展。',0),
	(14,56,1358,1414,0,'如何下载redis容器并且进行安装运行和宿主机器进行关联','2023-09-29 16:56:11',1,'要下载并安装运行Redis容器，并将其与宿主机器关联，可以按照以下步骤进行操作：\n\n1. 首先，确保您的机器上已经安装了Docker。如果尚未安装Docker，请参考Docker官方文档进行安装。\n\n2. 打开终端或命令提示符，并执行以下命令来从Docker Hub上下载Redis容器：\n\n   ```\n   docker pull redis\n   ```\n\n   这将下载最新版的Redis容器镜像到您的本地机器。\n\n3. 下载完成后，可以使用以下命令来创建并运行Redis容器：\n\n   ```\n   docker run --name my-redis -p 6379:6379 -d redis\n   ```\n\n   这将创建一个名为&quot;my-redis&quot;的容器，并将容器的6379端口映射到宿主机器的6379端口。您可以根据需要修改容器和端口名称。\n\n4. 运行以上命令后，Redis容器将在后台运行。您可以使用以下命令来查看容器的运行状态：\n\n   ```\n   docker ps\n   ```\n\n   如果Redis容器正在运行，您应该能够看到容器的相关信息。\n\n5. 现在，您可以使用Redis客户端与Redis容器进行交互。您可以使用以下命令来连接到Redis容器：\n\n   ```\n   redis-cli\n   ```\n\n   连接成功后，您可以在Redis容器中执行Redis命令。\n\n通过以上步骤，您已经成功下载、安装并运行了Redis容器，并将其与宿主机器进行了关联。您可以根据需要在宿主机器和Redis容器之间进行数据交互。',0),
	(15,12,540,552,0,'关于俄乌冲突','2023-09-30 04:02:08',4,'俄乌冲突是指俄罗斯联邦与乌克兰之间的冲突，始于2014年。该冲突源于乌克兰政府决定与欧洲联盟建立更紧密关系的举措，引发了一系列争议和分歧。俄罗斯对乌克兰的政策和行动引发了乌克兰东部地区亲俄派和亲乌派之间的冲突和冲突升级。\n\n冲突开始后，俄罗斯占领了乌克兰的克里米亚地区，并支持了乌克兰东部地区亲俄派的武装起义。双方冲突导致了大量的人员伤亡和财产损失，也引发了国际社会的关注和谴责。\n\n俄乌冲突进一步加剧了乌克兰和俄罗斯之间的紧张关系，并导致了乌克兰政治和社会结构的严重动荡。国际社会对于该冲突的解决努力一直在进行中，但至今尚未取得重大突破。',0),
	(16,78,788,866,0,'我有一个苍蝇被苍蝇贴黏住，使劲挣扎的视频，请为这段视频配一个让人深刻一点的文案','2023-09-30 13:59:35',1,'《命运的囚徒》\n\n这只苍蝇，被命运的黏黏纠缠，被囚禁在透明的囚笼里。它拼尽全力挣扎，展示出一种无法被束缚的坚韧与不屈。在绝望的困境中，它没有放弃，没有停下脚步。它的羽翼被束缚，但它的意志却飞翔于天际。\n\n苍蝇是如此微小，但它的奋斗却是如此伟大。它的坚持告诉我们，即使在生活中的困境中，我们也能够找到力量，战胜困难。无论是命运的捉弄还是苦难的磨砺，我们都能够通过努力和坚持，突破束缚，寻找到自由的道路。\n\n当你感到迷茫和困顿时，想起这只苍蝇。它是命运的囚徒，但它也是梦想的战士。不论生活如何困难，我们都可以像这只苍蝇一样，迎难而上，挣脱束缚，创造属于自己的奇迹。无论你身处何时何地，内心的自由和坚韧才是最真实的力量。\n\n这段视频让我们思考，我们是否也是命运的囚徒？我们是否也需要像这只苍蝇一样，努力挣脱束缚，追求属于自己的自由？让我们从苍蝇身上汲取力量，勇敢面对困境，坚定地走向我们的梦想！',0);

/*!40000 ALTER TABLE `q_question` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_token`;

CREATE TABLE `q_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_token` WRITE;
/*!40000 ALTER TABLE `q_token` DISABLE KEYS */;

INSERT INTO `q_token` (`id`, `user_id`, `access_token`, `created_time`)
VALUES
	(1,1,'fe96aca522c699fa7542','2023-09-25 16:36:17'),
	(2,2,'8a18a0ed04b8c39dd863','2023-09-25 16:59:45'),
	(3,1,'e175f863ea5c226fd657','2023-09-25 17:01:22'),
	(4,1,'7053f5de355bf182f2fa','2023-09-25 17:07:54'),
	(5,1,'03188bf46167bb93f192','2023-09-24 17:09:08'),
	(6,6,'ab89d4afde8923c423af','2023-09-24 17:18:03'),
	(7,1,'c06e038eaa5ca69cd993','2023-09-26 17:42:07'),
	(8,1,'8cf8219654d8c1c10dea','2023-09-26 17:48:23'),
	(9,1,'31a51095dde8bdbb8447','2023-09-27 01:48:57'),
	(10,1,'ab4bff2e9672558e38ed','2023-09-29 14:39:29'),
	(11,1,'ac8d4ad5f9e748d1f180','2023-09-29 16:18:16'),
	(12,1,'b329195c5cc57c1ba67f','2023-09-29 16:28:28'),
	(13,1,'4441755d4132c666d015','2023-09-30 04:01:03'),
	(14,1,'91a9591a662bc06b3d8a','2023-09-30 14:40:56');

/*!40000 ALTER TABLE `q_token` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_user`;

CREATE TABLE `q_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `open_id` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `balance_token` int DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_user` WRITE;
/*!40000 ALTER TABLE `q_user` DISABLE KEYS */;

INSERT INTO `q_user` (`id`, `order_id`, `open_id`, `created_time`, `balance_token`, `remark`)
VALUES
	(1,0,'oh0-H6NPz6h9OBeUo8J0NK8iNT0E','2023-09-25 16:35:44',-5687,'我自己'),
	(2,0,'oh0-H6MaujxeZslWzbeFdt3QmW68','2023-09-25 16:57:00',1000,NULL),
	(3,0,'oh0-H6GyeZkWKu2QE7erpySFhkoM','2023-09-27 01:48:39',500,NULL),
	(4,0,'oh0-H6IG9BYm3HUKNBjzDyAY8t_Q','2023-09-30 03:59:37',500,NULL);

/*!40000 ALTER TABLE `q_user` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_wx_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_wx_token`;

CREATE TABLE `q_wx_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
