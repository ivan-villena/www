-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `app_cab` WHERE `esq`='hol'; INSERT INTO `app_cab` 

			( `esq`, `ide`, `pos`, `nom`, `des`, `ico`, `ocu`, `url`, `nav`, `usu` )
			
		VALUES
			('hol','bib', 1, 'Bibliografía', 	'', 'tex_lib', 	0, 0, 1, 0 ),
			('hol','art', 2, 'Artículos', 		'', 'tex_inf', 	0, 0, 1, 0 ),
			('hol','dat', 3, 'Códigos', 			'', 'num_cod', 	0, 0, 1, 0 ),
			('hol','ope', 4, 'Módulos',				'', 'tab', 			0, 0, 0, 0 ),			
			('hol','usu', 6, 'Usuario', 			'', 'usu', 		 	1, 0, 1, 0 )
		
		;