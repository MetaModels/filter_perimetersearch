-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- --------------------------------------------------------

--
-- Table `tl_metamodel_filtersetting`
--

CREATE TABLE `tl_metamodel_filtersetting` (
  `single_attr_id` varchar(255) NOT NULL default '',
  `first_attr_id` varchar(255) NOT NULL default '',
  `second_attr_id` varchar(255) NOT NULL default '',
  `datamode` varchar(255) NOT NULL default '',
  `placeholder` varchar(255) NOT NULL default '',
  `lookupservice` text NULL,
  `rangemode` varchar(255) NOT NULL default '',
  `range_preset` int(10) unsigned NOT NULL default '0',
  `range_selection` text NULL,
  `range_label` blob NULL,
  `range_placeholder` varchar(255) NOT NULL default '',
  `range_template` varchar(64) NOT NULL default '',
  `countrymode` varchar(255) NOT NULL default '',
  `country_preset` text NULL,
  `country_get` text NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_metamodel_perimetersearch`
--

CREATE TABLE `tl_metamodel_perimetersearch` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `search` text NULL,
  `country` varchar(255) NOT NULL default '',
  `geo_lat` text NULL,
  `geo_long` text NULL
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

