-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Cabecera: 1° nivel : Opcion del Menú
	--
	-- Sincronario
		DELETE FROM `app_cab` WHERE `esq`= 1; 
		INSERT INTO `app_cab` (`esq`,`key`,`ide`,`nom`,`des`,`ico`,`ocu`,`url`,`nav`,`tit`,`usu`) VALUES
		
			( 1, 1, 'bibliografia', 'Bibliografía',
				'Aquí podrás encontrar la Mayoría de los Libros de José Arguelles con teoría sobre la Ley del Tiempo, un Glosario global y un Tutorial del Sincronario.', 
				'doc_lib', 	0, 0, 1, 1, 0 
			),
			( 1, 2, 'apunte', 'Apuntes',
				'En esta sección se encuentran distintos apuntes sobre los códigos y cuentas del Sincronario. En cada artículo se agrupan según sus clasificaciones haciendo referencia a la fuente de la misma, ya sean libros incluidos en la Bibliografía o enlaces a sitios externos.', 
				'doc_inf', 			0, 0, 1, 1, 0
			),
			( 1, 3, 'tablero', 'Tableros',
				'', 
				'dat_tab', 			0, 0, 0, 0, 0
			),
			( 1, 4, 'diario', 'Diario',
				'',
				'fec', 			0, 0, 1, 1, 0
			),
			( 1, 5, 'kin_planetario', 'Kin Planetario',
				'',
				'usu', 			0, 0, 1, 1, 0
			)
		;
	--
	
--