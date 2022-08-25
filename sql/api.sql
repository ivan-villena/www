-- Active: 1623270923336@@127.0.0.1@3306@_api

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- _tex : tratamiento de textos

  -- let_ora : convierte a mayuscia el primer caracter
  UPDATE esq.est SET atr = CONCAT( UCASE( LEFT(atr, 1) ), LCASE( SUBSTRING(atr, 2) ) );


----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- _dat : estructuras de datos

  -- crear estructuras
  DROP TABLE IF EXISTS `_hol`.``;
  CREATE TABLE `_hol`.`ani_rin` (

    `ide` SMALLINT(1) UNSIGNED ZEROFILL NOT NULL COMMENT 'Año del Proyecto Rinri',
    `nom` VARCHAR(20) NOT NULL COMMENT '',

    PRIMARY KEY (`ide`)
  ) 
    ENGINE  = InnoDB
    COMMENT = ''
  ;

  INSERT INTO `_hol`.`psi_ban_pla` VALUES 
    ( 1, 'Afro-Euroasiática', 'Primavera', 'Otoño' ),
    ( 2, 'Pacífico', 'Verano', 'Invierno' ),
    ( 3, 'Americana', 'Otoño', 'Primavera' ),
    ( 4, 'Atlántica', 'Invierno', 'Sur' )
  ;

  -- copiar estructura y datos
  CREATE TABLE `_hol`.`psi_ban_pla` LIKE `_hol`.`psi_ban`;
  -- 
  INSERT INTO `esq`.`est` SELECT * FROM `esq`.`est_ini`;
  
  -- cambiar estructuras
  RENAME TABLE `_hol`.`ani_tie_sub` TO `_hol`.`tel_sub`;

  -- atributos
  ALTER TABLE `_hol`.`lun_cub`
    CHANGE `nom` `nom` VARCHAR(30) NOT NULL COMMENT "Nombre"
  ;
  
  ALTER TABLE `_hol`.`psi_ban`
    ADD `nom` VARCHAR(25) NOT NULL COMMENT 'Nombre' AFTER `ide`,  
    ADD `lun_fin` SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Final' AFTER `lun_ini`
  ;    
