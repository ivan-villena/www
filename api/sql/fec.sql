
-- fecha
DROP TABLE IF EXISTS `fec`;
CREATE TABLE `fec` ( 
  `año` SMALLINT(4) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Año',
  `mes` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Mes',
  `dia` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Dia',
  `sem` TINYINT(1) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Semana',
  `hor` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Hora',
  `min` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Minuto',
  `seg` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Segundo',
  `kin` SMALLINT(3) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Kin',
  `psi` SMALLINT(3) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Psi',
  `ani` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT 'Anillo',
  `sir` SMALLINT(2) NULL DEFAULT NULL COMMENT 'Nuevo Sirio' 
) ENGINE = InnoDB COMMENT = 'Fecha'
;
DELETE FROM `fec`
;

-- años
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

-- meses
DROP TABLE IF EXISTS `fec_mes`;
CREATE TABLE `fec_mes` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes', 
  `nom` VARCHAR(15) NOT NULL COMMENT 'Nombre', 
  `dia` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Cantidad de Días', 
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Mes del Calendario'
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

-- semana
DROP TABLE IF EXISTS `fec_sem`;
CREATE TABLE `fec_sem` ( 
  `ide` TINYINT(1) UNSIGNED NOT NULL COMMENT 'Semana', 
  `cod` CHAR(1) NOT NULL COMMENT 'Código',
  `nom` VARCHAR(15) NOT NULL COMMENT 'Nombre',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Día de la Semana'
;

DELETE FROM `fec_sem`;
INSERT INTO `fec_sem` VALUES
  (0, 'D', 'Domingo' ),
  (1, 'L', 'Lunes' ),
  (2, 'M', 'Martes' ),
  (3, 'X', 'Miércoles' ),
  (4, 'J', 'Jueves' ),
  (5, 'V', 'Viernes' ),
  (6, 'S', 'Sábado' )  
;

-- días
DROP TABLE IF EXISTS `fec_dia`;
CREATE TABLE `fec_dia` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Día',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Día del Mes'
;

DELETE FROM `fec_dia`;
INSERT INTO `fec_dia` VALUES
  (1), (2), (3), (4), (5), (6), (7), (8), (9), 
  (10), (11), (12), (13), (14), (15), (16), (17), (18), (19), 
  (20), (21), (22), (23), (24), (25), (26), (27), (28), (29), 
  (30), (31)
;

-- horas
DROP TABLE IF EXISTS `fec_hor`;
CREATE TABLE `fec_hor` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Hora',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Hora'
;
DELETE FROM `fec_hor`;
INSERT INTO `fec_hor` VALUES 
  (1), (2), (3), (4), (5), (6), (7), (8), (9), 
  (10), (11), (12), (13), (14), (15), (16), (17), (18), (19), 
  (20), (21), (22), (23), (24)
;

-- minutos
CREATE TABLE `fec_min` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Minuto',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Minuto'
;
DELETE FROM `fec_min`;
INSERT INTO `fec_min` VALUES 
  (1), (2), (3), (4), (5), (6), (7), (8), (9),
  (10), (11), (12), (13), (14), (15), (16), (17), (18), (19), 
  (20), (21), (22), (23), (24), (25), (26), (27), (28), (29),
  (30), (31), (32), (33), (34), (35), (36), (37), (38), (39), 
  (40), (41), (42), (43), (44), (45), (46), (47), (48), (49), 
  (50), (51), (52), (53), (54), (55), (56), (57), (58), (59), 
  (60)
;

-- segundos
DROP TABLE IF EXISTS `fec_seg`;
CREATE TABLE `fec_seg` ( 
  `ide` TINYINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Segundo',
  PRIMARY KEY (`ide`)
) ENGINE = InnoDB COMMENT = 'Segundo'
;

DELETE FROM `fec_seg`;
INSERT INTO `fec_seg` VALUES 
  (1), (2), (3), (4), (5), (6), (7), (8), (9),
  (10), (11), (12), (13), (14), (15), (16), (17), (18), (19), 
  (20), (21), (22), (23), (24), (25), (26), (27), (28), (29),
  (30), (31), (32), (33), (34), (35), (36), (37), (38), (39), 
  (40), (41), (42), (43), (44), (45), (46), (47), (48), (49), 
  (50), (51), (52), (53), (54), (55), (56), (57), (58), (59), 
  (60)
;