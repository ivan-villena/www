-- Active: 1663730672989@@127.0.0.1@3306@_api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `_api`.`app_cab` WHERE `esq`='hol';
		INSERT INTO `_api`.`app_cab` VALUES	

			('hol','bib', 1, 'Bibliografía', 				'', 'art_bib', 0, 1, 0 ),
			('hol','inf', 2, 'Informes', 						'', 'art_inf', 0, 1, 0 ),
			('hol','tab', 3, 'Tableros', 						'', 'art_tab', 0, 0, 0 ),
			('hol','val', 4, 'Valores Diarios', 		'', 'art_val', 0, 1, 0 ),
			('hol','usu', 5, 'Kin Planetario', 			'', 'ses_usu', 0, 1, 1 )
		;