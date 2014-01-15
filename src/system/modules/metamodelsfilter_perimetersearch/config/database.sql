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
  `single_attr_id` int(10) unsigned NOT NULL default '0',
  `first_attr_id` int(10) unsigned NOT NULL default '0',
  `second_attr_id` int(10) unsigned NOT NULL default '0',
  `datamode` varchar(255) NOT NULL default '',
  `lookupservice` text NULL,
  `rangemode` varchar(255) NOT NULL default '', 
  `range_preset` int(10) unsigned NOT NULL default '0',
  `range_selection` text NULL,
  `range_label` blob NULL,
  `range_template` varchar(64) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

