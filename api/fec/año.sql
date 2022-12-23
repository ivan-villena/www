
DROP TABLE IF EXISTS `fec_año`;
CREATE TABLE `fec_año` ( 
  `ide` SMALLINT(4) NOT NULL COMMENT 'Año',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Año'
;

DELETE FROM `fec_año`;
INSERT INTO `fec_año` VALUES
  (1970)
;