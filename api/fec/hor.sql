
DROP TABLE IF EXISTS `fec_hor`;
CREATE TABLE `fec_hor` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Hora',
  PRIMARY KEY (`ide`)
) 
  ENGINE = InnoDB 
  COMMENT = 'Hora'
;

DELETE FROM `fec_hor`;
INSERT INTO `fec_hor` VALUES 
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