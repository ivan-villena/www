-- Active: 1623270923336@@127.0.0.1@3306@_api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `_api`.`doc_cab` WHERE `esq`='hol';
		INSERT INTO `_api`.`doc_cab` VALUES	

			('hol','bib', 1, 'Bibliografía', 				'', 'art_bib', 1, 1, 0 ),
			('hol','inf', 2, 'Informes', 						'', 'art_inf', 1, 1, 0 ),
			('hol','tab', 3, 'Tableros', 						'', 'art_tab', 1, 1, 0 ),
			('hol','val', 4, 'Valores Diarios', 		'', 'art_val', 1, 1, 0 ),
			('hol','usu', 5, 'Kin Planetario', 			'', 'ses_usu', 1, 1, 1 )
		;