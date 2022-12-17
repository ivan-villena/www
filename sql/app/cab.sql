-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Cabecera: 1° nivel : Opcion del Menú

	-- Sincronario
		DELETE FROM `app_cab` WHERE `esq`='hol'; INSERT INTO `app_cab` 

			( `esq`, `ide`, `pos`, `nom`, `des`, `ico`, `ocu`, `url`, `nav`, `usu` )
			
		VALUES
			('hol','bib', 1, 'Bibliografía', 				'', 'tex_lib', 	0, 0, 1, 0 ),
			('hol','dat', 2, 'Códigos y Cuentas',		'', 'tex_inf', 	0, 0, 1, 0 ),
			('hol','kin', 3, 'Órden Sincrónico',		'', 'tab', 			0, 0, 0, 0 ),
			('hol','psi', 4, 'Órden Cíclico',				'', 'tab', 			0, 0, 0, 0 )
		;