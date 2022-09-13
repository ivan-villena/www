-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `_api`.`doc_cab` WHERE `esq`='hol';
		INSERT INTO `_api`.`doc_cab` VALUES	

			('hol','bib', 1, 'Bibliografía', 	1,  1 ),
			('hol','inf', 2, 'Informes', 			1,  1 ),
			('hol','tab', 3, 'Tableros', 			1,  1 )
		;