
DROP TABLE IF EXISTS `var-fec_año`;
CREATE TABLE `var-fec_año` ( 
  `ide` SMALLINT(4) NOT NULL COMMENT 'Año',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Año'
;

DELETE FROM `var-fec_año`;
INSERT INTO `var-fec_año` VALUES
  (1970)
;