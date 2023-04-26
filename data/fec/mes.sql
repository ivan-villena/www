
DROP TABLE IF EXISTS `fec_mes`;
CREATE TABLE `fec_mes` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT '',
  `nom` VARCHAR(15) NOT NULL COMMENT 'Mes',
  `dia` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Cantidad de Días',
  PRIMARY KEY (`ide`)
)
  ENGINE = InnoDB 
  COMMENT = 'Mes del Calendario'
;

DELETE FROM `fec_mes`;
INSERT INTO `fec_mes` VALUES
  (1,   'Enero',      31),
  (2,   'Febrero',    28),
  (3,   'Marzo',      31),
  (4,   'Abril',      31),
  (5,   'Mayo',       31),
  (6,   'Junio',      31),
  (7,   'Julio',      31),
  (8,   'Agosto',     31),
  (9,   'Septiembre', 31),
  (10,  'Octubre',    31),
  (11,  'Noviembre',  31),
  (12,  'Diciembre',  31)
;