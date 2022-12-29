-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api

UPDATE `hol_kin_cro_est_dia` SET `enc` = CONCAT( '"', `enc`, '"' );

UPDATE `hol_kin` SET `des` = REPLACE(`des`,'. "','."');

RENAME TABLE `hol_tel_cam` TO `hol_uni_tel_cam`;

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
  DROP TABLE IF EXISTS `_hol`.`ani_rin`;

  -- crear estructuras
  CREATE TABLE `_hol`.`ani_rin` (
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
  INSERT INTO `_hol`.`psi_ban_pla` VALUES 
    ( 1, '', '', '' )
  ;

  ----------------------------------------------------------------
  ----------------------------------------------------------------

  -- vaciar registros
    TRUNCATE TABLE IF EXISTS `_hol`.`ani_rin`;

  -- copiar estructura 
    CREATE TABLE `esq_1`.`est_1` LIKE `esq_2`.`est_2`;

  -- copiar datos
    INSERT INTO `esq_1`.`est_1` SELECT * FROM `esq_2`.`est_2`;
  
  -- cambiar estructuras
    RENAME TABLE `esq_1`.`est_1` TO `esq_2`.`est_2`;    

  ----------------------------------------------------------------
  ----------------------------------------------------------------

  -- agregar columna
  ALTER TABLE `esq`.`est`
    ADD `nom` VARCHAR(25) NOT NULL COMMENT 'Nombre' AFTER `ide`,  
    ADD `des` SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Descripción' AFTER `nom`
  ;

  -- cambiar columna
  ALTER TABLE `esq`.`est`
    CHANGE `nom` `nom_2` VARCHAR(30) NOT NULL COMMENT 'Nombre'
  ;

  -- eliminar columna
  ALTER TABLE `esq`.`est`
    DROP COLUMN `atr_1`, 
    DROP COLUMN `atr_2`
  ;

  -- agregar indices	
  ALTER TABLE `esq`.`est`
    ADD PRIMARY KEY         ( `atr_1`, `atr_2` ),
    ADD UNIQUE    `ind_uni` ( `atr_1`, `atr_2` ),
    ADD INDEX     `ind_mul` ( `atr_1`, `atr_2` ),
    ADD FULLTEXT  `ind_tex` ( `atr_1`, `atr_2` )
  ;

  
