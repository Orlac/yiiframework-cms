CREATE TABLE IF NOT EXISTS members
(
	id int(10) NOT NULL auto_increment,
	username varchar(155) NOT NULL default '',
	seoname varchar(250) NOT NULL default '',
	email varchar(155) NOT NULL default '',
	password varchar(40) NOT NULL default '',
	joined int(10) NOT NULL default '0',
	passwordreset char(40) NULL,
	data TEXT NULL,
	role char(30) NOT NULL default '',
	ipaddress char(30) NOT NULL default '',
	fbuid int(10) NOT NULL default '0',
	fbtoken varchar(255) NOT NULL default '',
	PRIMARY KEY (id),
	KEY username (username),
	KEY email (email)
) ENGINE = InnoDB;

CREATE TABLE SourceMessage
(
    id INTEGER PRIMARY KEY auto_increment,
    category VARCHAR(32),
    message TEXT
) ENGINE = InnoDB;
CREATE TABLE Message
(
    id INTEGER auto_increment,
    language VARCHAR(16),
    translation TEXT,
    PRIMARY KEY (id, language),
    CONSTRAINT FK_Message_SourceMessage FOREIGN KEY (id)
         REFERENCES SourceMessage (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB;

create table authitem
(
   name                 varchar(64) not null,
   type                 integer not null,
   description          text,
   bizrule              text,
   data                 text,
   primary key (name)
);

create table authitemchild
(
   parent               varchar(64) not null,
   child                varchar(64) not null,
   primary key (parent,child),
   foreign key (parent) references authitem (name) on delete cascade on update cascade,
   foreign key (child) references authitem (name) on delete cascade on update cascade
);

create table authassignment
(
   itemname             varchar(64) not null,
   userid               varchar(64) not null,
   bizrule              text,
   data                 text,
   primary key (itemname,userid),
   foreign key (itemname) references authitem (name) on delete cascade on update cascade
);

CREATE TABLE IF NOT EXISTS settingscats
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	description varchar(255) NULL,
	groupkey varchar(125) NOT NULL default '',
	PRIMARY KEY (id),
	KEY title (title),
	UNIQUE (groupkey)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS settings
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	description text,
	category int(10) NOT NULL default '0',
	type char(30) NOT NULL default 'text',
	settingkey varchar(125) NOT NULL default '',
	default_value text,
	value text null,
	extra text,
	php text,
	PRIMARY KEY (id),
	KEY title (title),
	UNIQUE (settingkey)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS documentations
(
	id int(10) NOT NULL auto_increment,
	name varchar(125) NOT NULL default '',
	mkey varchar(125) NOT NULL default '',
	description varchar(125) NOT NULL default '',
	content text NOT NULL,
	type char(30) NOT NULL default '',
	language char(30) NOT NULL default '',
	last_updated int(10) NOT NULL default '0',
	last_updated_member int(10) NOT NULL default '0',
	views int(10) NOT NULL default '0',
	rating int(10) NOT NULL default '0',
	totalvotes int(10) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY name (name),
	KEY mkey (mkey),
	KEY language (language),
	KEY type (type)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS documentations_comments
(
	id int(10) NOT NULL auto_increment,
	docid varchar(125) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0', 
	comment text NULL,
	PRIMARY KEY (id),
	KEY docid (docid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS custompages
(
	id int(10) NOT NULL auto_increment,
	title varchar(255) NOT NULL default '',
	alias varchar(250) NOT NULL default '', 
	content text NULL,
	dateposted int(10) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	last_edited_date int(10) NOT NULL default '0',
	last_edited_author int(10) NOT NULL default '0',
	tags varchar(255) NOT NULL default '',
	language varchar(125) NOT NULL default '',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	visible text NULL,
	status tinyint(1) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY title (title),
	UNIQUE (alias, language)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS tutorialscats
(
	id int(10) NOT NULL auto_increment,
	parentid int(10) NULL,
	title varchar(155) NOT NULL default '',
	alias varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	language varchar(125) NOT NULL default '',
	position int(10) NOT NULL default '0',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	readonly tinyint(1) NOT NULL default '0',
	viewperms text NULL,
	addtutorialperms text NULL,
	addcommentsperms text NULL,
	addfilesperms text NULL,
	autoaddperms text NULL,
	PRIMARY KEY (id),
	KEY title (title),
	KEY parentid (parentid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS tutorials
(
	id int(10) NOT NULL auto_increment,
	catid int(10) NOT NULL default '0',
	title varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	content text NULL,
	alias text,
	language varchar(125) NOT NULL default '',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	views int(10) NOT NULL default '0',
	rating int(10) NOT NULL default '0',
	totalvotes int(10) NOT NULL default '0',
	status tinyint(1) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	postdate int(10) NOT NULL default '0',
	last_updated_date int(10) NOT NULL default '0',
	last_updated_author int(10) NOT NULL default '0', 
	PRIMARY KEY (id),
	KEY title (title),
	KEY catid (catid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS tutorialscomments
(
	id int(10) NOT NULL auto_increment,
	tutorialid varchar(125) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0', 
	comment text NULL,
	PRIMARY KEY (id),
	KEY tutorialid (tutorialid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS blogcats
(
	id int(10) NOT NULL auto_increment,
	parentid int(10) NULL,
	title varchar(155) NOT NULL default '',
	alias varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	language varchar(125) NOT NULL default '',
	position int(10) NOT NULL default '0',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	readonly tinyint(1) NOT NULL default '0',
	viewperms text NULL,
	addpostsperms text NULL,
	addcommentsperms text NULL,
	addfilesperms text NULL,
	autoaddperms text NULL,
	PRIMARY KEY (id),
	KEY title (title),
	KEY parentid (parentid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS blogposts
(
	id int(10) NOT NULL auto_increment,
	catid int(10) NOT NULL default '0',
	title varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	content text NULL,
	alias text,
	language varchar(125) NOT NULL default '',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	views int(10) NOT NULL default '0',
	rating int(10) NOT NULL default '0',
	totalvotes int(10) NOT NULL default '0',
	status tinyint(1) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	postdate int(10) NOT NULL default '0',
	last_updated_date int(10) NOT NULL default '0',
	last_updated_author int(10) NOT NULL default '0', 
	PRIMARY KEY (id),
	KEY title (title),
	KEY catid (catid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS blogcomments
(
	id int(10) NOT NULL auto_increment,
	postid varchar(125) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0', 
	comment text NULL,
	PRIMARY KEY (id),
	KEY postid (postid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS contactus
(
	id int(10) NOT NULL auto_increment,
	name varchar(55) NOT NULL default '',
	email varchar(55) NOT NULL default '',
	subject varchar(55) NOT NULL default '',
	content TEXT NULL,
	postdate int(10) NOT NULL default '0',
	sread tinyint(1) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY email (email),
	KEY subject (subject),
	KEY name (name)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS extensionscats
(
	id int(10) NOT NULL auto_increment,
	parentid int(10) NULL,
	title varchar(155) NOT NULL default '',
	alias varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	language varchar(125) NOT NULL default '',
	position int(10) NOT NULL default '0',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	readonly tinyint(1) NOT NULL default '0',
	viewperms text NULL,
	addpostsperms text NULL,
	addcommentsperms text NULL,
	addfilesperms text NULL,
	autoaddperms text NULL,
	PRIMARY KEY (id),
	KEY title (title),
	KEY parentid (parentid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS extensions
(
	id int(10) NOT NULL auto_increment,
	catid int(10) NOT NULL default '0',
	title varchar(255) NOT NULL default '',
	description varchar(255) NOT NULL default '',
	content text NULL,
	alias text,
	language varchar(125) NOT NULL default '',
	metadesc varchar(255) NOT NULL default '',
	metakeys varchar(255) NOT NULL default '',
	views int(10) NOT NULL default '0',
	rating int(10) NOT NULL default '0',
	totalvotes int(10) NOT NULL default '0',
	status tinyint(1) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	postdate int(10) NOT NULL default '0',
	last_updated_date int(10) NOT NULL default '0',
	last_updated_author int(10) NOT NULL default '0', 
	PRIMARY KEY (id),
	KEY title (title),
	KEY catid (catid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS extensionscomments
(
	id int(10) NOT NULL auto_increment,
	postid int(10) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0', 
	comment text NULL,
	PRIMARY KEY (id),
	KEY postid (postid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS usercomments
(
	id int(10) NOT NULL auto_increment,
	userid int(10) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0', 
	comment text NULL,
	PRIMARY KEY (id),
	KEY userid (userid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS extensionsfiles
(
	id int(10) NOT NULL auto_increment,
	extensionid int(10) NOT NULL default '',
	authorid int(10) NOT NULL default '0', 
	postdate int(10) NOT NULL default '0',
	size int(10) NOT NULL default '0',
	type char(10) NOT NULL default '',
	mime varchar(125) NOT NULL default '',
	location varchar(125) NOT NULL default '',
	realname varchar(125) NOT NULL default '',
	alias varchar(125) NOT NULL default '',
	description varchar(125) NOT NULL default '',
	downloads int(10) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY extensionid (extensionid),
	KEY authorid (authorid)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS newsletter
(
	id int(10) NOT NULL auto_increment,
	email varchar(125) NOT NULL default '',
	joined int(10) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY joined (joined),
	KEY email (email)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS forumtopics
(
	id int(10) NOT NULL auto_increment,
	title varchar(125) NOT NULL default '',
	alias text,
	dateposted int(10) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	language char(3) NOT NULL default '',
	views int(10) NOT NULL default '0',
	replies int(10) NOT NULL default '0',
	lastpostdate int(10) NOT NULL default '0',
	lastpostauthorid int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0',
	content TEXT NULL,
	PRIMARY KEY (id),
	KEY title (title),
	KEY alias (alias),
	KEY language (language),
	KEY lastpostdate (lastpostdate),
	KEY dateposted (dateposted)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS forumposts
(
	id int(10) NOT NULL auto_increment,
	topicid int(10) NOT NULL default '0',
	authorid int(10) NOT NULL default '0',
	dateposted int(10) NOT NULL default '0',
	visible tinyint(1) NOT NULL default '0',
	content TEXT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS topic_subscription
(
	id int(10) NOT NULL auto_increment,
	topicid int(10) NOT NULL default '0',
	userid int(10) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY topicid (topicid),
	KEY userid (userid)
) ENGINE = InnoDB;
