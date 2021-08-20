# Study Note

<img src="https://firebasestorage.googleapis.com/v0/b/watataku-portfolio.appspot.com/o/%E3%83%9E%E3%82%A4%E3%83%98%E3%82%9A%E3%83%BC%E3%82%B7%E3%82%99_%F0%9F%94%8A.png?alt=media&token=ded09f36-4c73-4acd-9bc4-434d3178c5e9">
- php: 7.3.11
- mysql: 5.6.35



```sql
# アカウントテーブル
CREATE TABLE `accounts` (
  `a_no` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `a_name` varchar(255) NOT NULL,
  `a_image` varchar(255) DEFAULT NULL,
  `delete_day` int(1) DEFAULT NULL,
  PRIMARY KEY (`a_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 

# パスワードテーブル
CREATE TABLE `password` (
  `a_no` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `p_pass` varchar(255) NOT NULL,
  `share_pass` varchar(255) NOT NULL,
  PRIMARY KEY (`a_no`),
  CONSTRAINT `password_ibfk_1` FOREIGN KEY (`a_no`) REFERENCES `accounts` (`a_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

# 科目テーブル
CREATE TABLE `subjects` (
  `subject_no` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(20) NOT NULL,
  `a_no` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`subject_no`),
  KEY `a_no` (`a_no`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`a_no`) REFERENCES `accounts` (`a_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

# ノートの画像テーブル
CREATE TABLE `note_images` (
  `image_no` int(5) NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) NOT NULL,
  `a_no` int(5) unsigned NOT NULL,
  PRIMARY KEY (`image_no`),
  KEY `a_no` (`a_no`),
  CONSTRAINT `note_images_ibfk_1` FOREIGN KEY (`a_no`) REFERENCES `accounts` (`a_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

# ノートテーブル
CREATE TABLE `notes` (
  `n_no` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `n_title` varchar(255) NOT NULL,
  `n_body` text NOT NULL,
  `created` datetime NOT NULL,
  `a_no` int(5) unsigned NOT NULL,
  `subject_no` int(3) unsigned DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletes` datetime DEFAULT NULL,
  `share` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`n_no`),
  KEY `a_no` (`a_no`),
  KEY `subject_no` (`subject_no`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`a_no`) REFERENCES `accounts` (`a_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
```

