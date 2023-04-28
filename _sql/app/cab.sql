-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Cabecera: 1° nivel : Opcion del Menú
	--
	-- Sincronario
		DELETE FROM `app_cab` WHERE `esq`= 1; 
		INSERT INTO `app_cab` (`esq`,`key`,`ide`,`nom`,`des`,`ico`,`ocu`,`url`,`nav`,`tit`,`usu`) VALUES
		
			( 1, 1, 'bibliografia', 'Bibliografía',
				'Aquí podrás encontrar la Mayoría de los Libros de José Arguelles con teoría sobre la Ley del Tiempo, un Glosario global y un Tutorial del Sincronario.', 
				'tex_lib', 	0, 0, 1, 1, 0 
			),
			( 1, 2, 'codigo', 'Códigos y Cuentas',
				'', 
				'num', 			0, 0, 1, 1, 0
			),
			( 1, 3, 'tablero', 'Tableros',
				'', 
				'tab', 			0, 0, 0, 0, 0
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