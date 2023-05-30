
DROP TABLE IF EXISTS `sis-fec_año`;
CREATE TABLE `sis-fec_año` ( 
  `ide` SMALLINT(4) NOT NULL COMMENT 'Año',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Año'
;

DELETE FROM `sis-fec_año`;
INSERT INTO `sis-fec_año` VALUES
  (1970)
;