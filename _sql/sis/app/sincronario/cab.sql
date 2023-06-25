-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- `ocu` : oculto ?			|| no se muestra en el menu
-- `url` : enlace ?			|| si es un enlace directo o contenedor de articulos
-- `usu` : usuario ? 		|| se muestra solo cuando el usuario esta logueado

-- Cabecera: 1° nivel : Opcion del Menú
	--
	-- Sincronario
		DELETE FROM `sis-app_cab` WHERE `esq` = 'sincronario'; 
		INSERT INTO `sis-app_cab` (`esq`,`pos`,`ide`,`nom`,`des`,`ico`,`ima`,`opc_ocu`,`opc_url`,`opc_usu`) VALUES
		
			('sincronario', 1, 'libro', 'Libros',
				'Aquí podrás encontrar los Libros de José Arguelles con teoría sobre la Ley del Tiempo', 
				'tex_lib', '', 0, 0, 0 
			),
			('sincronario', 2, 'tutorial', 'Tutoriales',
				'', 
				'app_art', '', 0, 0, 0 
			),
			('sincronario', 3, 'codigo', 'Códigos y Cuentas',
				'', 
				'num', '', 0, 0, 0 
			),			
			('sincronario', 4, 'informe', 'Posicionamiento Diario',
				'',
				'fec_dia', '', 0, 0, 0
			),			
			('sincronario', 5, 'orden_sincronico', 'Tableros del Orden Sincrónico',
				'',
				'fig', '', 0, 0, 0
			),
			('sincronario', 6, 'orden_ciclico', 'Tableros del Orden Cíclico',
				'',
				'fig_pun', '', 0, 0, 0
			),
			('sincronario', 6, 'holon', 'Los Holones',
				'',
				'arc_url', '', 0, 0, 0
			)
		;
	--
	
--