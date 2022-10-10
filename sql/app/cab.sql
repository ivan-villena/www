-- Active: 1665286450693@@127.0.0.1@3306@api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `api`.`app_cab` WHERE `esq`='hol';
		INSERT INTO `api`.`app_cab` 
			( `esq`, `ide`, `pos`, `nom`, `des`, `ico`, `ocu`, `url`, `nav`, `usu` )
		VALUES
			('hol','bib', 1, 'Bibliografía', 		'', 'art_bib', 0, 0, 1, 0 ),
			('hol','dat', 2, 'Códigos y Cuentas', 	'', 'num_val', 0, 0, 1, 0 ),
			('hol','tab', 3, 'Operador de Tableros','', 'art_tab', 0, 0, 0, 0 ),
			('hol','inf', 5, 'Informe por Posición','', 'art_inf', 0, 0, 1, 0 ),
			('hol','usu', 6, 'Kin Planetario', 		'', 'ses_usu', 1, 0, 1, 0 )
		;