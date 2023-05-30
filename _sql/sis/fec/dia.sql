
DROP TABLE IF EXISTS `sis-fec_dia`;
CREATE TABLE `sis-fec_dia` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Día',
  PRIMARY KEY (`ide`)
) 
  ENGINE = InnoDB 
  COMMENT = 'Día del Mes'
;

DELETE FROM `sis-fec_dia`;
INSERT INTO `sis-fec_dia` VALUES
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
  (24), 
  (25), 
  (26), 
  (27), 
  (28), 
  (29),   
  (30), 
  (31)
;