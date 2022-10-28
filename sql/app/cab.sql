-- Active: 1665550796793@@127.0.0.1@3306@api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `app_cab` WHERE `esq`='hol'; INSERT INTO `app_cab` 

			( `esq`, `ide`, `pos`, `nom`, `des`, `ico`, `ocu`, `url`, `nav`, `usu` )
			
		VALUES
			('hol','bib', 1, 'Bibliografía', 					'', 'tex_lib', 	0, 0, 1, 0 ),
			('hol','art', 2, 'Artículos', 						'', 'tex_inf', 	0, 0, 1, 0 ),
			('hol','dat', 3, 'Códigos', 							'', 'num_cod', 	0, 0, 1, 0 ),
			('hol','tab', 4, 'Tableros',							'', 'tab', 			0, 0, 0, 0 ),
			('hol','dia', 5, 'Diario',								'', 'fec', 			1, 0, 1, 0 ),
			('hol','usu', 6, 'Kin Planetario', 				'', 'usu', 		 	1, 0, 1, 0 )
		;