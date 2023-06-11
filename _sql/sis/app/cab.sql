-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- `ocu` : oculto ?			|| no se muestra en el menu
-- `url` : enlace ?			|| si es un enlace directo o contenedor de articulos
-- `usu` : usuario ? 		|| se muestra solo cuando el usuario esta logueado

-- Cabecera: 1° nivel : Opcion del Menú
	--
	-- Sincronario
		DELETE FROM `sis-app_cab` WHERE `esq`= 1; 
		INSERT INTO `sis-app_cab` (`esq`,`key`,`ide`,`nom`,`des`,`ico`,`opc_ocu`,`opc_url`,`opc_usu`) VALUES
		
			( 1, 1, 'libro', 'Los Libros',
				'Aquí podrás encontrar la Mayoría de los Libros de José Arguelles con teoría sobre la Ley del Tiempo, un Glosario global y un Tutorial del Sincronario.', 
				'tex_lib', 			0, 0, 0 
			),
			( 1, 2, 'codigo', 'Los Códigos',
				'', 
				'num', 					0, 0, 0
			),
			( 1, 3, 'ciclo', 'Los Ciclos',
				'', 
				'fec_tie', 					0, 0, 0
			),
			( 1, 4, 'holon', 'Los Holones',
				'', 
				'arc_url', 					0, 0, 0
			),			
			( 1, 5, 'kin_planetario', 'El Kin Planetario',
				'',
				'usu', 					0, 0, 0
			),			
			( 1, 6, 'tablero', 'Los Tableros',
				'',
				'dat_tab', 			0, 0, 0
			),
			( 1, 7, 'usuario', 'Cuenta de Usuario',
				'',
				'app_ses', 			1, 0, 1
			)
		;
	--
	
--