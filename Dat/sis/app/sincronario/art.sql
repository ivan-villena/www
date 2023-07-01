-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Sincronario
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario';
	
	-- 1-Libros
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'libro'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'libro', 2, 'tierra_en_ascenso','1984 - La Tierra en Ascenso',
		'', 
		'', '' ),
		('sincronario', 'libro', 3, 'factor_maya',	'1987 - El Factor Maya',
		'', 
		'', '' ), 
		('sincronario', 'libro', 4, 'encantamiento_del_sueño', '1990 - El Encantamiento del Sueño',
		'', 
		'', '' ),
		('sincronario', 'libro', 5, 'lunas_en_movimiento', '1991 - Las 13 lunas en Movimiento',
		'', 
		'', '' ),		
		('sincronario', 'libro', 6, 'sonda_de_arcturus', '1992 - La Sonda de Arcturus',
		'', 
		'', '' ),
		('sincronario', 'libro', 7, 'tratado_del_tiempo', '1993 - Un Tratado del Tiempo',
		'', 
		'', '' ),		
		('sincronario', 'libro', 8, 'telektonon', '1994 - El Telektonon',
		'', 
		'', '' ),
		('sincronario', 'libro', 9, 'proyecto_rinri', '1995 - El Proyecto Rinri',
		'', 
		'', '' ),
		('sincronario', 'libro', 10, 'dinamicas_del_tiempo', '1996 - Dinámicas del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'libro', 11, 'tablas_del_tiempo', '1997 - Las 20 Tablas del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'libro', 12, 'atomo_del_tiempo', '1999 - El Átomo del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'libro', 13, 'sincronotron', '2009 - El Sincronotrón',
		'', 
		'', '' )
	;

	-- 2-Tutoriales
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'tutorial'; INSERT INTO `sis-app_art` VALUES
	
		('sincronario', 'tutorial', 1, 'glosario', 'Glosarios de Términos',
		'', 
		'tex_ord', '' ),
		('sincronario', 'tutorial', 2, 'sincronario', 'Tutorial del Sincronario de 13 Lunas',
		'', 
		'fec_tie', '' )
	;

	-- 3-Códigos y Cuentas
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'codigo'; INSERT INTO `sis-app_art` VALUES		

		('sincronario', 'codigo', 1, 'plasma', 'Los 7 Plasmas Radiales',
		'', 
		'', '' ),
		('sincronario', 'codigo', 2, 'tono',  'Los 13 Tonos Galácticos',
		'', 
		'', '' ),
		('sincronario', 'codigo', 3, 'sello',  'Los 20 Sellos Solares',
		'', 
		'', '' ),
		('sincronario', 'codigo', 4, 'luna',  'Los 28 días del Giro Lunar',
		'', 
		'', '' ),		
		('sincronario', 'codigo', 5, 'kin', 'Los 260 Kines del Giro Galáctico',
		'',
		'', '' ),
		('sincronario', 'codigo', 6, 'psi', 'Los 365 Psi-Cronos del Giro Solar',
		'', 
		'', '' )
	;

	-- 4-Informes
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'informe'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'informe', 1, 'ciclico', 'Órden Cíclico del Giro Solar',
		'', 
		'fig_pun', '' ),	
		('sincronario', 'informe', 2, 'sincronico', 'Orden Sincrónico del Giro Galáctico',
		'', 
		'fig', '' ),
		('sincronario', 'informe', 3, 'firma', 'Firma Galáctica Personal',
		'', 
		'usu_gru', '' )
	;

	-- 5-Tableros
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'tablero'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'tablero', 1, '', 'Orden Cíclico',
		'', 
		'', '' ),				
		('sincronario', 'tablero', 11, 'luna', 'Las 13 Lunas del Anillo Solar',
		'', 
		'fig_pun', '' ),
		('sincronario', 'tablero', 12, 'luna_diario', 'Los 28 días del Giro Lunar',
		'', 
		'fig_pun', '' ),
		('sincronario', 'tablero', 13, 'estacion_solar', 'Las 4 Estaciones del Giro Solar',
		'', 
		'fig_pun', '' ),
		('sincronario', 'tablero', 14, 'estacion_solar_diario', 'Los 91 días de la Estación Solar',
		'', 
		'fig_pun', '' ),
		('sincronario', 'tablero', 15, 'heptada', 'El Heptágono Semanal de 7 días',
		'', 
		'fig_pun', '' ),
		('sincronario', 'tablero', 20, '', 'Orden Sincrónico',
		'', 
		'fig_pun', '' ),		
		('sincronario', 'tablero', 21, 'tzolkin', 'El Tzolkin',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 22, 'nave', 'La Nave del Tiempo',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 23, 'castillo', 'Los 52 días del Castillo',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 24, 'onda_encantada', 'Los 13 días de la Onda Ecantada',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 25, 'giro_galactico', 'El Giro Galáctico ( Colocación Armónica )',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 26, 'trayectoria_armonica', 'Los 20 días de la Trayectoria Armónica',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 27, 'celula_armonica', 'Los 4 días de la Célula del Tiempo',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 28, 'giro_espectral', 'El Giro Espectral ( Colocación Cromática )',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 29, 'estacion_galactica', 'Los 65 días de la Estación Galáctica',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 30, 'elemento_galactico', 'Los 5 días de la Cromática Elemental',
		'', 
		'fig', '' ),
		('sincronario', 'tablero', 31, '', 'Holones',
		'', 
		'arc_url', '' ),			
		('sincronario', 'tablero', 32, 'holon_solar', 'Solar',
		'', 
		'arc_url', '' ),	
		('sincronario', 'tablero', 33, 'holon_planetario', 'Planetario',
		'', 
		'arc_url', '' ),	
		('sincronario', 'tablero', 34, 'holon_humano', 'Humano',
		'', 
		'arc_url', '' )
	;
--