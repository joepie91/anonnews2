CREATE TABLE IF NOT EXISTS `blacklist` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Type` tinyint(4) NOT NULL,
  `Value` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `comments` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Section` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `Body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ItemId` bigint(20) NOT NULL,
  `ParentId` bigint(20) NOT NULL,
  `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Author` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `Visible` tinyint(4) NOT NULL,
  `LineCount` bigint(20) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33481 ;

CREATE TABLE IF NOT EXISTS `ext` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(350) COLLATE utf8_unicode_ci NOT NULL,
  `Url` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `CommentCount` bigint(20) NOT NULL,
  `Rank` bigint(20) NOT NULL,
  `Deleted` tinyint(4) NOT NULL,
  `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Approved` tinyint(4) NOT NULL,
  `Mod` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `Visible` tinyint(1) NOT NULL,
  `Language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3279 ;

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Threads` int(11) NOT NULL,
  `Posts` int(11) NOT NULL,
  `LastPostTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LastPostTopic` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `UrlName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `CategoryId` bigint(20) NOT NULL,
  `ParentId` bigint(20) NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Topic` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `Replies` int(11) NOT NULL,
  `LastReplyUser` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `LastReplyTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `mods` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Hash` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `AccessLevel` smallint(6) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `press` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(350) COLLATE utf8_unicode_ci NOT NULL,
  `Body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CommentCount` bigint(20) NOT NULL,
  `Deleted` tinyint(4) NOT NULL,
  `Approved` tinyint(11) NOT NULL,
  `Attachment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Upvotes` int(11) NOT NULL,
  `Mod` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `ExternalAttachment` tinyint(1) NOT NULL,
  `Language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1334 ;

CREATE TABLE IF NOT EXISTS `sites` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(350) COLLATE utf8_unicode_ci NOT NULL,
  `Url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CommentCount` bigint(20) NOT NULL,
  `Deleted` tinyint(4) NOT NULL,
  `Approved` tinyint(4) NOT NULL,
  `Mod` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `Language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=283 ;

CREATE TABLE IF NOT EXISTS `tags` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Table` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `ItemId` bigint(20) NOT NULL,
  `TagName` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `votes` (
  `Id` bigint(20) NOT NULL,
  `Ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Section` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `EntryId` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=60698 ;
