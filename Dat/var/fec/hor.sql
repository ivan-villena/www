
DROP TABLE IF EXISTS `var-fec_hor`;
CREATE TABLE `var-fec_hor` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Hora',
  PRIMARY KEY (`ide`)
) 
  ENGINE = InnoDB 
  COMMENT = 'Hora'
;

DELETE FROM `var-fec_hor`;
INSERT INTO `var-fec_hor` VALUES 
  (1), 
  (2), 
  (3), 
  (4), 
  (5), 
  (6), 
  (7), 
  (8), 
  (9),
  (10), 
  (11), 
  (12), 
  (13), 
  (14), 
  (15), 
  (16), 
  (17), 
  (18), 
  (19),
  (20), 
  (21), 
  (22), 
  (23), 
  (24)
;