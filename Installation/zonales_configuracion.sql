-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 29-07-2010 a las 17:59:12
-- Versión del servidor: 5.0.77
-- Versión de PHP: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `zonales_dev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jos_components`
--

CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `menuid` int(11) unsigned NOT NULL default '0',
  `parent` int(11) unsigned NOT NULL default '0',
  `admin_menu_link` varchar(255) NOT NULL default '',
  `admin_menu_alt` varchar(255) NOT NULL default '',
  `option` varchar(50) NOT NULL default '',
  `ordering` int(11) NOT NULL default '0',
  `admin_menu_img` varchar(255) NOT NULL default '',
  `iscore` tinyint(4) NOT NULL default '0',
  `params` text NOT NULL,
  `enabled` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `parent_option` (`parent`,`option`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Volcar la base de datos para la tabla `jos_components`
--

INSERT INTO `jos_components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
(1, 'Banners', '', 0, 0, '', 'Banner Management', 'com_banners', 0, 'js/ThemeOffice/component.png', 0, 'track_impressions=0\ntrack_clicks=0\ntag_prefix=\n\n', 1),
(2, 'Banners', '', 0, 1, 'option=com_banners', 'Active Banners', 'com_banners', 1, 'js/ThemeOffice/edit.png', 0, '', 1),
(3, 'Clients', '', 0, 1, 'option=com_banners&c=client', 'Manage Clients', 'com_banners', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(4, 'Web Links', 'option=com_weblinks', 0, 0, '', 'Manage Weblinks', 'com_weblinks', 0, 'js/ThemeOffice/component.png', 0, 'show_comp_description=1\ncomp_description=\nshow_link_hits=1\nshow_link_description=1\nshow_other_cats=1\nshow_headings=1\nshow_page_title=1\nlink_target=0\nlink_icons=\n\n', 1),
(5, 'Links', '', 0, 4, 'option=com_weblinks', 'View existing weblinks', 'com_weblinks', 1, 'js/ThemeOffice/edit.png', 0, '', 1),
(6, 'Categories', '', 0, 4, 'option=com_categories&section=com_weblinks', 'Manage weblink categories', '', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(7, 'Contacts', 'option=com_contact', 0, 0, '', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/component.png', 1, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1),
(8, 'Contacts', '', 0, 7, 'option=com_contact', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/edit.png', 1, '', 1),
(9, 'Categories', '', 0, 7, 'option=com_categories&section=com_contact_details', 'Manage contact categories', '', 2, 'js/ThemeOffice/categories.png', 1, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1),
(10, 'Polls', 'option=com_poll', 0, 0, 'option=com_poll', 'Manage Polls', 'com_poll', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(11, 'News Feeds', 'option=com_newsfeeds', 0, 0, '', 'News Feeds Management', 'com_newsfeeds', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(12, 'Feeds', '', 0, 11, 'option=com_newsfeeds', 'Manage News Feeds', 'com_newsfeeds', 1, 'js/ThemeOffice/edit.png', 0, 'show_headings=1\nshow_name=1\nshow_articles=1\nshow_link=1\nshow_cat_description=1\nshow_cat_items=1\nshow_feed_image=1\nshow_feed_description=1\nshow_item_description=1\nfeed_word_count=0\n\n', 1),
(13, 'Categories', '', 0, 11, 'option=com_categories&section=com_newsfeeds', 'Manage Categories', '', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(14, 'User', 'option=com_user', 0, 0, '', '', 'com_user', 0, '', 1, '', 1),
(15, 'Search', 'option=com_search', 0, 0, 'option=com_search', 'Search Statistics', 'com_search', 0, 'js/ThemeOffice/component.png', 1, 'enabled=0\n\n', 1),
(16, 'Categories', '', 0, 1, 'option=com_categories&section=com_banner', 'Categories', '', 3, '', 1, '', 1),
(17, 'Wrapper', 'option=com_wrapper', 0, 0, '', 'Wrapper', 'com_wrapper', 0, '', 1, '', 1),
(18, 'Mail To', '', 0, 0, '', '', 'com_mailto', 0, '', 1, '', 1),
(19, 'Media Manager', '', 0, 0, 'option=com_media', 'Media Manager', 'com_media', 0, '', 1, 'upload_extensions=bmp,csv,doc,epg,gif,ico,jpg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,EPG,GIF,ICO,JPG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\nupload_maxsize=10000000\nfile_path=images\nimage_path=images/stories\nrestrict_uploads=1\ncheck_mime=1\nimage_extensions=bmp,gif,jpg,png\nignore_extensions=\nupload_mime=image/jpeg,image/gif,image/png,image/bmp,application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip\nupload_mime_illegal=text/html\nenable_flash=0\n\n', 1),
(20, 'Articles', 'option=com_content', 0, 0, '', '', 'com_content', 0, '', 1, 'show_noauth=0\nshow_title=1\nlink_titles=1\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=0\nshow_item_navigation=0\nshow_readmore=1\nshow_vote=0\nshow_icons=0\nshow_pdf_icon=0\nshow_print_icon=0\nshow_email_icon=0\nshow_hits=0\nfeed_summary=0\nfilter_tags=\nfilter_attritbutes=\n\n', 1),
(21, 'Configuration Manager', '', 0, 0, '', 'Configuration', 'com_config', 0, '', 1, '', 1),
(22, 'Installation Manager', '', 0, 0, '', 'Installer', 'com_installer', 0, '', 1, '', 1),
(23, 'Language Manager', '', 0, 0, '', 'Languages', 'com_languages', 0, '', 1, 'site=es-ES\nadministrator=es-ES\n\n', 1),
(24, 'Mass mail', '', 0, 0, '', 'Mass Mail', 'com_massmail', 0, '', 1, 'mailSubjectPrefix=\nmailBodySuffix=\n\n', 1),
(25, 'Menu Editor', '', 0, 0, '', 'Menu Editor', 'com_menus', 0, '', 1, '', 1),
(27, 'Messaging', '', 0, 0, '', 'Messages', 'com_messages', 0, '', 1, '', 1),
(28, 'Modules Manager', '', 0, 0, '', 'Modules', 'com_modules', 0, '', 1, '', 1),
(29, 'Plugin Manager', '', 0, 0, '', 'Plugins', 'com_plugins', 0, '', 1, '', 1),
(30, 'Template Manager', '', 0, 0, '', 'Templates', 'com_templates', 0, '', 1, '', 1),
(31, 'User Manager', '', 0, 0, '', 'Users', 'com_users', 0, '', 1, 'allowUserRegistration=1\nnew_usertype=Registered\nuseractivation=1\nfrontend_userparams=1\n\n', 1),
(32, 'Cache Manager', '', 0, 0, '', 'Cache', 'com_cache', 0, '', 1, '', 1),
(33, 'Control Panel', '', 0, 0, '', 'Control Panel', 'com_cpanel', 0, '', 1, '', 1),
(34, '.Mighty Permissions', 'option=com_jcacl', 0, 0, 'option=com_jcacl', '.Mighty Permissions', 'com_jcacl', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(35, 'Permissions', '', 0, 34, 'option=com_jcacl', 'Permissions', 'com_jcacl', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(36, 'User Groups', '', 0, 34, 'option=com_jcacl&controller=usrgroups', 'User Groups', 'com_jcacl', 1, 'js/ThemeOffice/component.png', 0, '', 1),
(37, 'Comments', 'option=com_comments', 0, 0, 'option=com_comments', 'Comments', 'com_comments', 0, 'components/com_comments/media/images/icon-16-jx.png', 0, '', 1),
(38, 'joomlaXplorer', 'option=com_joomlaxplorer', 0, 0, 'option=com_joomlaxplorer', 'joomlaXplorer', 'com_joomlaxplorer', 0, '../administrator/components/com_joomlaxplorer/images/joomla_x_icon.png', 0, '', 1),
(39, 'Easy SQL', 'option=com_easysql', 0, 0, 'option=com_easysql', 'Easy SQL', 'com_easysql', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(73, '\n				Utilidades \n			', '', 0, 69, 'option=com_customproperties&controller=utilities', '\n				Utilidades \n			', 'com_customproperties', 3, 'components/com_customproperties/images/controlpanel.png', 0, '', 1),
(74, '\n				Configuración \n			', '', 0, 69, 'option=com_customproperties&task=configure&controller=cpanel', '\n				Configuración \n			', 'com_customproperties', 4, 'components/com_customproperties/images/config.png', 0, '', 1),
(75, '\n				Acerca de \n			', '', 0, 69, 'option=com_customproperties&task=about&controller=cpanel', '\n				Acerca de \n			', 'com_customproperties', 5, 'components/com_customproperties/images/customproperties.png', 0, '', 1),
(76, 'Zonales', 'option=com_zonales', 0, 0, 'option=com_zonales', 'Zonales', 'com_zonales', 0, 'js/ThemeOffice/component.png', 0, 'menu_tag_prefix=menu_\nzonal_tag_prefix=zonal_\nwidth_mapa_flash=800\nheight_mapa_flash=600\nflash_file=mapa.swf\nrecaptcha_publickey=6Lc8fwcAAAAAAP8aw0ojn_bac-OpAiPv3NnAg9lN\nrecaptcha_privatekey=6Lc8fwcAAAAAAMT7Q1z7Zy2xGWX5W6BVtBKB3foJ\nroot_value=88\n\n', 1),
(77, 'Asociar Menús', '', 0, 76, 'option=com_zonales&task=listMenu', 'Asociar Menús', 'com_zonales', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(78, 'Asociar Tipos', '', 0, 76, 'option=com_zonales&task=listCp2TipoTag', 'Asociar Tipos', 'com_zonales', 1, 'js/ThemeOffice/component.png', 0, '', 1),
(79, 'Tipos de Tag', '', 0, 76, 'option=com_zonales&task=listTipoTag', 'Tipos de Tag', 'com_zonales', 2, 'js/ThemeOffice/component.png', 0, '', 1),
(81, 'Administracion de alias', 'option=com_alias', 0, 0, 'option=com_alias', 'Administracion de alias', 'com_alias', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(59, '\n            Adm. Avanzada de Perfiles de Usuarios', 'option=com_aapu', 0, 0, 'option=com_aapu', '\n            Adm. Avanzada de Perfiles de Usuarios\n        ', 'com_aapu', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(60, '\n                Gestión de Usuarios\n            ', '', 0, 59, 'option=com_aapu&task=listUsers&controller=controller', '\n                Gestión de Usuarios\n            ', 'com_aapu', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(61, '\n                Gestión de Pestañas\n            ', '', 0, 59, 'option=com_aapu&task=listTabs&controller=controller', '\n                Gestión de Pestañas\n            ', 'com_aapu', 1, 'js/ThemeOffice/component.png', 0, '', 1),
(62, '\n                Gestión de Atributos\n            ', '', 0, 59, 'option=com_aapu&task=listAttributes&controller=controller', '\n                Gestión de Atributos\n            ', 'com_aapu', 2, 'js/ThemeOffice/component.png', 0, '', 1),
(63, '\n                Gestión de Tipos de datos\n       ', '', 0, 59, 'option=com_aapu&task=listDataTypes&controller=controller', '\n                Gestión de Tipos de datos\n            ', 'com_aapu', 3, 'js/ThemeOffice/component.png', 0, '', 1),
(64, '\n                Configuración\n            ', '', 0, 59, 'option=com_aapu&task=configure&controller=controller', '\n                Configuración\n            ', 'com_aapu', 4, 'js/ThemeOffice/component.png', 0, '', 1),
(69, '\n			Propiedades Personalizadas \n		', 'option=com_customproperties', 0, 0, 'option=com_customproperties', '\n			Propiedades Personalizadas \n		', 'com_customproperties', 0, 'components/com_customproperties/images/customproperties.png', 0, '', 1),
(54, 'Ecualizador Zonales', 'option=com_eqzonales', 0, 0, 'option=com_eqzonales', 'Ecualizador Zonales', 'com_eqzonales', 0, 'js/ThemeOffice/component.png', 0, 'solr_url=192.168.0.2\nsolr_port=30080\nsolr_webapp=apache-solr-1.4.0\nsolr_querytype=zonalesContent\nsolr_dataimport=dataimporter\nsolr_fullimport=full-import\nsolr_deltaimport=delta-import\nnoticias_field=15\npeso_default=50\neq_anon_id=1\neq_anon_name=Ecualizador Anónimo\neq_order_by=band_label\neq_order_dir=asc\n\n', 1),
(72, '\n				Asignar Propiedades Personalizadas \n			', '', 0, 69, 'option=com_customproperties&controller=assign', '\n				Asignar Propiedades Personalizadas \n			', 'com_customproperties', 2, 'components/com_customproperties/images/assign.png', 0, '', 1),
(71, '\n				Gestionar etiquetas jerárquicas \n			', '', 0, 69, 'option=com_customproperties&controller=values', '\n				Gestionar etiquetas jerárquicas \n			', 'com_customproperties', 1, 'components/com_customproperties/images/editcp.png', 0, '', 1),
(70, '\n				Gestionar Propiedades Personalizadas \n			', '', 0, 69, 'option=com_customproperties&controller=fields', '\n				Gestionar Propiedades Personalizadas \n			', 'com_customproperties', 0, 'components/com_customproperties/images/editcp.png', 0, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jos_modules`
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `position` varchar(50) default NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `module` varchar(50) default NULL,
  `numnews` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `showtitle` tinyint(3) unsigned NOT NULL default '1',
  `params` text NOT NULL,
  `iscore` tinyint(4) NOT NULL default '0',
  `client_id` tinyint(4) NOT NULL default '0',
  `control` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Volcar la base de datos para la tabla `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `control`) VALUES
(1, 'Main Menu', '', 1, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 1, 'menutype=mainmenu\nmoduleclass_sfx=_menu\n', 1, 0, ''),
(2, 'Login', '', 1, 'login', 0, '0000-00-00 00:00:00', 1, 'mod_login', 0, 0, 1, '', 1, 1, ''),
(3, 'Popular', '', 3, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_popular', 0, 2, 1, '', 0, 1, ''),
(4, 'Recent added Articles', '', 4, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_latest', 0, 2, 1, 'ordering=c_dsc\nuser_id=0\ncache=0\n\n', 0, 1, ''),
(5, 'Menu Stats', '', 5, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_stats', 0, 2, 1, '', 0, 1, ''),
(6, 'Unread Messages', '', 1, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_unread', 0, 2, 1, '', 1, 1, ''),
(7, 'Online Users', '', 2, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_online', 0, 2, 1, '', 1, 1, ''),
(8, 'Toolbar', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 2, 1, '', 1, 1, ''),
(9, 'Quick Icons', '', 1, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicon', 0, 2, 1, '', 1, 1, ''),
(10, 'Logged in Users', '', 2, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_logged', 0, 2, 1, '', 0, 1, ''),
(11, 'Footer', '', 0, 'footer', 0, '0000-00-00 00:00:00', 1, 'mod_footer', 0, 0, 1, '', 1, 1, ''),
(12, 'Admin Menu', '', 1, 'menu', 0, '0000-00-00 00:00:00', 1, 'mod_menu', 0, 2, 1, '', 0, 1, ''),
(13, 'Admin SubMenu', '', 1, 'submenu', 0, '0000-00-00 00:00:00', 1, 'mod_submenu', 0, 2, 1, '', 0, 1, ''),
(14, 'User Status', '', 1, 'status', 0, '0000-00-00 00:00:00', 1, 'mod_status', 0, 2, 1, '', 0, 1, ''),
(15, 'Title', '', 1, 'title', 0, '0000-00-00 00:00:00', 1, 'mod_title', 0, 2, 1, '', 0, 1, ''),
(16, 'Permission Menu Module', '', 2, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_jscacl_menu', 0, 0, 1, 'menu_style=list\n', 0, 0, ''),
(17, 'YOOsearch', '', 3, 'topsearch', 0, '0000-00-00 00:00:00', 1, 'mod_yoo_search', 0, 0, 0, 'style=blank\nbox_width=400\nchar_limit=130\nres_limit=6\ncat_limit=6\ncategories=\nmoduleclass_sfx=_yooSearch\ncache=0\ncache_time=900\n\n', 0, 0, ''),
(18, 'JComments Latest', '', 4, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_jcomments', 0, 0, 1, 'object_group=com_content\ncount=5\nlength=50\navatar_size=32\nlimit_object_title=30\nlabel4more=More...\nlabel4author=By\ndateformat=%d.%m.%y %H:%M\nlabel4rss=RSS\n', 0, 0, ''),
(49, 'Alias Admin', '', 20, 'user5', 0, '0000-00-00 00:00:00', 1, 'mod_alias', 0, 0, 1, '', 0, 0, ''),
(20, 'Elegí tu Zonal', '', 5, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_combozona', 0, 0, 0, '', 0, 0, ''),
(21, 'Modulo Ecualizador', '', 0, 'other', 0, '0000-00-00 00:00:00', 1, 'mod_eq', 0, 0, 0, 'use_module_title=0\ntitle=Ecualizador de Interés\ndescription=El Ecualizador de Interés le permite personalizar los contenidos que el portal le ofrece.\nerror_no_eq=No se ha podido recuperar el ecualizador.\n\n', 0, 0, ''),
(23, 'Menu Zonales', '', 0, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_menuzonales', 0, 0, 0, 'menu_joomla=2\n\n', 0, 0, ''),
(24, 'Alias alert', '', 10, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_pending', 0, 0, 0, 'days=5\n\n', 0, 0, ''),
(25, 'Información de Sesión', '', 3, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_sessioninfo', 0, 1, 1, 'zonales_session_close=Salir\nprofilelink=index.php?option=com_aapu\n\n', 0, 0, ''),
(37, 'La Voz del Vecino', '', 2, 'other', 0, '0000-00-00 00:00:00', 1, 'mod_lavozdelvecino', 0, 0, 0, 'cant_articles=5\n\n', 0, 0, ''),
(42, 'Message Presenter', '', 0, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_message', 0, 0, 0, 'icondir=images/message/\n\n', 0, 0, ''),
(40, 'Zonales Login', '', 0, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_zlogin', 0, 0, 0, '', 0, 0, ''),
(29, 'Zonal Actual', '', 4, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_zonalactual', 0, 0, 0, 'show_label=0\nlabel_text=Zonal actual:\nnozonal_text=Zonal no seleccionado\ncache=0\n\n', 0, 0, ''),
(38, 'Zonales Menú Editor', '', 0, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_menueditor', 0, 1, 0, '', 0, 0, ''),
(31, 'banner_Publicite', '', 6, 'right', 0, '0000-00-00 00:00:00', 1, 'mod_banners', 0, 0, 0, 'target=1\ncount=1\ncid=8\ncatid=20\ntag_search=0\nordering=random\nheader_text=\nfooter_text=\nmoduleclass_sfx=\ncache=1\ncache_time=900\n\n', 0, 0, ''),
(48, 'Soy Corresponsal', '', 0, 'other', 0, '0000-00-00 00:00:00', 1, 'mod_soycorresponsal', 0, 0, 0, 'show_colapsed=1\nshow_email=1\nshow_phone=0\ncategory=2\nuser=63\nconfirmacion=¡Gracias!  A la brevedad nos comunicaremos con usted.\nerror=Un error ha ocurrido mientras se procesaba su envio.\ncaptchaTextNew=Nueva Imagen\ncaptchaTextSnd=Escuchar\ncaptchaTextImg=Ver\ncaptchaTextHelp=Ayuda\nroot_value=88\n\n', 0, 0, ''),
(41, 'Registro de usuarios', '', 19, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_userregister', 0, 0, 1, 'show_colapsed=1\n', 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jos_plugins`
--

CREATE TABLE IF NOT EXISTS `jos_plugins` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `element` varchar(100) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `iscore` tinyint(3) NOT NULL default '0',
  `client_id` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_folder` (`published`,`client_id`,`access`,`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Volcar la base de datos para la tabla `jos_plugins`
--

INSERT INTO `jos_plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Authentication - Joomla', 'joomla', 'authentication', 0, 1, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(2, 'Authentication - LDAP', 'ldap', 'authentication', 0, 2, 0, 1, 0, 0, '0000-00-00 00:00:00', 'host=\nport=389\nuse_ldapV3=0\nnegotiate_tls=0\nno_referrals=0\nauth_method=bind\nbase_dn=\nsearch_string=\nusers_dn=\nusername=\npassword=\nldap_fullname=fullName\nldap_email=mail\nldap_uid=uid\n\n'),
(3, 'Authentication - GMail', 'gmail', 'authentication', 0, 4, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(4, 'Authentication - OpenID', 'openid', 'authentication', 0, 3, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(5, 'User - Joomla!', 'joomla', 'user', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'autoregister=1\n\n'),
(6, 'Search - Content', 'content', 'search', 0, 1, 0, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\nsearch_content=1\nsearch_uncategorised=1\nsearch_archived=1\n\n'),
(7, 'Search - Contacts', 'contacts', 'search', 0, 3, 1, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(8, 'Search - Categories', 'categories', 'search', 0, 4, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(9, 'Search - Sections', 'sections', 'search', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(10, 'Search - Newsfeeds', 'newsfeeds', 'search', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(11, 'Search - Weblinks', 'weblinks', 'search', 0, 2, 1, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(12, 'Content - Pagebreak', 'pagebreak', 'content', 0, 10000, 1, 1, 0, 0, '0000-00-00 00:00:00', 'enabled=1\ntitle=1\nmultipage_toc=1\nshowall=1\n\n'),
(13, 'Content - Rating', 'vote', 'content', 0, 4, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(14, 'Content - Email Cloaking', 'emailcloak', 'content', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'mode=1\n\n'),
(15, 'Content - Code Hightlighter (GeSHi)', 'geshi', 'content', 0, 5, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(16, 'Content - Load Module', 'loadmodule', 'content', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', 'enabled=1\nstyle=0\n\n'),
(17, 'Content - Page Navigation', 'pagenavigation', 'content', 0, 2, 1, 1, 0, 0, '0000-00-00 00:00:00', 'position=1\n\n'),
(18, 'Editor - No Editor', 'none', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(19, 'Editor - TinyMCE 2.0', 'tinymce', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', 'theme=advanced\ncleanup=1\ncleanup_startup=0\nautosave=0\ncompressed=0\nrelative_urls=1\ntext_direction=ltr\nlang_mode=0\nlang_code=en\ninvalid_elements=applet\ncontent_css=1\ncontent_css_custom=\nnewlines=0\ntoolbar=top\nhr=1\nsmilies=1\ntable=1\nstyle=1\nlayer=1\nxhtmlxtras=0\ntemplate=0\ndirectionality=1\nfullscreen=1\nhtml_height=550\nhtml_width=750\npreview=1\ninsertdate=1\nformat_date=%Y-%m-%d\ninserttime=1\nformat_time=%H:%M:%S\n\n'),
(20, 'Editor - XStandard Lite 2.0', 'xstandard', 'editors', 0, 0, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(21, 'Editor Button - Image', 'image', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(22, 'Editor Button - Pagebreak', 'pagebreak', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(23, 'Editor Button - Readmore', 'readmore', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(24, 'XML-RPC - Joomla', 'joomla', 'xmlrpc', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(25, 'XML-RPC - Blogger API', 'blogger', 'xmlrpc', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', 'catid=1\nsectionid=0\n\n'),
(27, 'System - SEF', 'sef', 'system', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(28, 'System - Debug', 'debug', 'system', 0, 2, 1, 0, 0, 0, '0000-00-00 00:00:00', 'queries=1\nmemory=1\nlangauge=1\n\n'),
(29, 'System - Legacy', 'legacy', 'system', 0, 3, 1, 1, 0, 0, '0000-00-00 00:00:00', 'route=0\n\n'),
(30, 'System - Cache', 'cache', 'system', 0, 4, 0, 1, 0, 0, '0000-00-00 00:00:00', 'browsercache=0\ncachetime=15\n\n'),
(31, 'System - Log', 'log', 'system', 0, 5, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(32, 'System - Remember Me', 'remember', 'system', 0, 6, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(33, 'System - Backlink', 'backlink', 'system', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(34, 'Content - Comments', 'comments', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(35, 'System - JXtended Libraries', 'jxtended', 'system', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(36, 'Joomla Suit Access Control Layer Plugin', 'jcacl', 'system', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(37, 'YOOeffects', 'yoo_effects', 'system', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'lightbox=1\nreflection=1\nspotlight=1\ngzip=1\n\n'),
(38, 'Content - Like', 'like', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'likedisplay=onBeforeDisplayContent\nshowthumb=yes\ntextsize=12\ntextfloat=none\ntextclear=none\nlinkcolor=blue\ntextcolor=blue\nshowfrontpage=yes\nnotice_tags=100\n\n'),
(39, 'Content - Denunciar', 'denunciar', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'denunciardisplay=onBeforeDisplayContent\ntextsize=12\nrecipient=juanmanuelcortez@gmail.com\ntextfloat=none\ntextclear=none\nlinkcolor=blue\ntextcolor=blue\nshowfrontpage=yes\nnotice_tags=100\n\n'),
(40, 'Custom Properties Tags Plugin', 'cptags', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'tags_position=0\ntag_in_meta=1\n\n'),
(41, 'Button - Custom Properties Tags', 'cptags', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(42, 'User - Zonales - Ecualizador de Interés', 'eqevents', 'user', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(44, 'Content - Solr Events', 'solrevents', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(45, 'Search - Zonales Solr Content Search', 'solrsearch', 'search', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(46, 'Content - Zonales - Autotagging', 'autotagging', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(62, 'Editor - CKEditor', 'ckeditor', 'editors', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'language=es\ntoolbar=Default\ntoolbar_ft=Creative\nCreative_ToolBar=[''Styles'',''Format''],[''Font'',''FontSize''],[''Source''],[''Bold'',''Italic'',''Underline'',''Find'',''JustifyLeft'',''JustifyCenter'',''JustifyRight'',''JustifyBlock'',''NumberedList'',''BulletedList''],[''TextColor'',''BGColor'',''Outdent'',''Indent'',''Link'',''Anchor'',''Image'',''Flash'']\nBlog_ToolBar=[ ''Bold'', ''Italic'', ''-'', ''NumberedList'', ''BulletedList'', ''-'', ''Link'', ''Unlink'' ]\nskin=v2\nColor=#6B6868\nentermode=1\nshiftentermode=1\nckfinder=1\nskinfm=light\nusergroup_access=29|18|19|20|21|30|23|24|25\n\n'),
(47, 'Authentication Proxy', 'authproxy', 'system', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'loginurl=index.php?option=com_user&task=login\n'),
(67, 'External Login', 'externallogin', 'authentication', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'aliasregisterurl=index.php?option=com_user&task=aliasregister\ngetstatusurl=index.php?option=com_user&view=userstatusrequest\n');
