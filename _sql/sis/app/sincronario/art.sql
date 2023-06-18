-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Sincronario
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario';
	
	-- Libros
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

	-- Tutoriales
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'tutorial'; INSERT INTO `sis-app_art` VALUES
	
		('sincronario', 'tutorial', 1, 'glosario', 'Glosarios de Términos',
		'', 
		'tex_ord', '' ),
		('sincronario', 'tutorial', 21, 'introduccion', 'Introducción al Sincronario de 13 Lunas',
		'', 
		'fec_tie', '' )
	;

	-- Códigos
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

	-- Orden Sincrónico
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'kin'; INSERT INTO `sis-app_art` VALUES
		
		('sincronario', 'kin', 1, 'tzolkin', 'El Tzolkin',
		'', 
		'', '' ),
		('sincronario', 'kin', 2, 'nave', 'La Nave del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'kin', 3, 'castillo', 'Los 52 días del Castillo',
		'', 
		'', '' ),
		('sincronario', 'kin', 4, 'onda', 'Los 13 días de la Onda Ecantada',
		'', 
		'', '' ),
		('sincronario', 'kin', 5, 'giro_galactico', 'El Giro Galáctico ( Colocación Armónica )',
		'', 
		'', '' ),
		('sincronario', 'kin', 6, 'trayectoria', 'Los 20 días de la Trayectoria Armónica',
		'', 
		'', '' ),
		('sincronario', 'kin', 7, 'celula', 'Los 4 días de la Célula del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'kin', 8, 'giro_espectral', 'El Giro Espectral ( Colocación Cromática )',
		'', 
		'', '' ),
		('sincronario', 'kin', 9, 'estacion', 'Los 65 días de la Estación Galáctica',
		'', 
		'', '' ),
		('sincronario', 'kin', 10, 'elemento', 'Los 5 días de la Cromática Elemental',
		'', 
		'', '' )
	;
	-- Orden Cíclico
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'psi'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'psi', 1, 'banco_psi', 'El Banco-Psi',
		'', 
		'', '' ),
		('sincronario', 'psi', 2, 'anillo', 'Los 52 años de Nuevo Sirio',
		'', 
		'', '' ),
		('sincronario', 'psi', 3, 'estacion', 'Las 4 Estaciones del Giro Solar',
		'', 
		'', '' ),
		('sincronario', 'psi', 4, 'estacion_diario', 'Los 91 días de la Estación Solar',
		'', 
		'', '' ),
		('sincronario', 'psi', 5, 'luna', 'Las 13 Lunas del Anillo Solar',
		'', 
		'', '' ),
		('sincronario', 'psi', 6, 'luna_diario', 'Los 28 días del Giro Lunar',
		'', 
		'', '' ),
		('sincronario', 'psi', 7, 'heptada_diario', 'El Heptágono Semanal de 7 días',
		'', 
		'', '' )	
	;	
-- 

-- Proyecto