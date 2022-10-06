-- Active: 1663730672989@@127.0.0.1@3306@_api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `_api`.`app_cab` WHERE `esq`='hol';
		INSERT INTO `_api`.`app_cab` 
			( `esq`, `ide`, `pos`, `nom`, `des`, `ico`, `ocu`, `url`, `nav`, `usu` )
		VALUES
			('hol','bib', 1, 'Bibliografía', 				'', 'art_bib', 0, 0, 1, 0 ),
			('hol','dat', 2, 'Cuentas', 						'', 'lis_val', 0, 0, 1, 0 ),
			('hol','val', 3, 'Códigos', 						'', '', 			 1, 0, 1, 0 ),
			('hol','tab', 3, 'Tablero', 						'', 'art_tab', 0, 0, 0, 0 ),
			('hol','inf', 5, 'Informe', 						'', 'art_val', 0, 0, 1, 0 ),
			('hol','usu', 6, 'Kin Planetario', 			'', 'ses_usu', 0, 0, 1, 0 )
		;