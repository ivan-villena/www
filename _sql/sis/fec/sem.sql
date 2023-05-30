
DROP TABLE IF EXISTS `sis-fec_sem`;
CREATE TABLE `sis-fec_sem` ( 
  `ide` TINYINT(1) UNSIGNED NOT NULL COMMENT 'Semana',
  `cod` CHAR(1) NOT NULL COMMENT 'Código',
  `nom` VARCHAR(15) NOT NULL COMMENT 'Nombre',
  PRIMARY KEY (`ide`)
) 
  ENGINE = InnoDB 
  COMMENT = 'Día de la Semana'
;

DELETE FROM `sis-fec_sem`;
INSERT INTO `sis-fec_sem` VALUES
  (1, 'D', 'Domingo' ),
  (2, 'L', 'Lunes' ),
  (3, 'M', 'Martes' ),
  (4, 'X', 'Miércoles' ),
  (5, 'J', 'Jueves' ),
  (6, 'V', 'Viernes' ),
  (7, 'S', 'Sábado' )  
;