-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- _tex : tratamiento de textos

  -- let_cue : Longitud del Campo
  SELECT char_length(`atr`) FROM `est`; 


  -- let_ora : Oración ( Primer Caracter Mayúscula )
  UPDATE `est` SET `atr` = CONCAT( UCASE( LEFT(`atr`,1) ), LCASE( SUBSTRING(`atr`,2) ) ) 
  WHERE 
    `atr_ver` = 0
  ;
  -- let_rep : Reemplaza
  UPDATE `est` SET `atr` = REPLACE(`atr`,'buscar','remplazar')
  WHERE 
    `atr_ver` = 0
  ;
  -- let_agr : Agrega
  UPDATE `est` SET `atr` = CONCAT( '"', `atr`, '"' )
  WHERE
    `atr_ver` = 0
  ;

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- _dat : estructuras de datos

  -- eliminar estructura
  DROP TABLE IF EXISTS `ani_rin`;

  -- crear estructuras
  CREATE TABLE `ani_rin` (
    -- columnas
    `ide` SMALLINT(1) UNSIGNED ZEROFILL NOT NULL COMMENT 'Año',
    `nom` VARCHAR(20) NOT NULL COMMENT 'Nombre',
    -- indices
    PRIMARY KEY (`ide`)
  ) 
    ENGINE  = InnoDB
    COMMENT = ''
  ;

  -- insertar registros
  INSERT INTO `psi_ban_pla` VALUES 
    ( 1, '', '', '' )
  ;
  -- cambiar estructuras
    RENAME TABLE `esq_1`.`est_1` TO `esq_2`.`est_2`;

  -- vaciar registros
    TRUNCATE TABLE IF EXISTS `ani_rin`;

  -- copiar estructura 
    CREATE TABLE `esq_1`.`est_1` LIKE `esq_2`.`est_2`;

  -- copiar datos
    INSERT INTO `esq_1`.`est_1` SELECT * FROM `esq_2`.`est_2`;  

  ----------------------------------------------------------------
  ----------------------------------------------------------------

  -- agregar columna
  ALTER TABLE `est`
    ADD `nom` VARCHAR(25) NOT NULL COMMENT 'Nombre' AFTER `ide`,  
    ADD `des` SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Descripción' AFTER `nom`
  ;

  -- cambiar columna
  ALTER TABLE `est`
    CHANGE `nom` `nom_2` VARCHAR(30) NOT NULL COMMENT 'Nombre'
  ;

  -- eliminar columna
  ALTER TABLE `est`
    DROP COLUMN `atr_1`, 
    DROP COLUMN `atr_2`
  ;

  -- agregar indices	
  ALTER TABLE `est`
    ADD PRIMARY KEY         ( `atr_1`, `atr_2` ),
    ADD UNIQUE    `ind_uni` ( `atr_1`, `atr_2` ),
    ADD INDEX     `ind_mul` ( `atr_1`, `atr_2` ),
    ADD FULLTEXT  `ind_tex` ( `atr_1`, `atr_2` )
  ;

  
