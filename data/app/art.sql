-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Holon-Sincronario
	DELETE FROM `app_art` WHERE `esq`=1;
	
	-- Bibliografía
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 1; INSERT INTO `app_art` VALUES
		(1, 1, 1, 'glosario', 'Glosarios',
		'' ),	
		(1, 1, 2, 'tutorial', 'Tutorial del Sincronario de 13 Lunas',
		'' ),	
		(1, 1, 3, 'tierra_en_ascenso','1984 - La Tierra en Ascenso',
		'' ),
		(1, 1, 4, 'factor_maya',	'1987 - El Factor Maya',
		'' ), 
		(1, 1, 5,'encantamiento_del_sueño', '1990 - El Encantamiento del Sueño',
		'' ),
		(1, 1, 6,'lunas_en_movimiento', '1991 - Las 13 lunas en Movimiento',
		'' ),		
		(1, 1, 7, 'sonda_de_arcturus', '1992 - La Sonda de Arcturus',
		'' ),
		(1, 1, 8, 'tratado_del_tiempo', '1993 - Un Tratado del Tiempo',
		'' ),		
		(1, 1, 9, 'telektonon', '1994 - El Telektonon',
		'' ),
		(1, 1, 10, 'proyecto_rinri', '1995 - El Proyecto Rinri',
		'' ),
		(1, 1, 11, 'dinamicas_del_tiempo', '1996 - Dinámicas del Tiempo',
		'' ),
		(1, 1, 12, 'tablas_del_tiempo', '1997 - Las 20 Tablas del Tiempo',
		'' ),
		(1, 1, 13, 'atomo_del_tiempo', '1999 - El Átomo del Tiempo',
		'' ),
		(1, 1, 14, 'sincronotron', '2009 - El Sincronotrón',
		'' )		
	;

	-- Códigos
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 2; INSERT INTO `app_art` VALUES
		(1, 2, 1, 'plasma', 'Los 7 Plasmas Radiales',
		'' ),
		(1, 2, 2, 'tono',  'Los 13 Tonos Galácticos',
		'' ),
		(1, 2, 3, 'sello',  'Los 20 Sellos Solares',
		'' ),
		(1, 2, 4, 'luna',  'Los 28 Días del Giro Lunar',
		'' ),
		(1, 2, 5, 'kin', 'Los 260 Kines del Giro Galáctico',
		'' ),
		(1, 2, 6, 'psi', 'Los 365 Psi-Cronos del Giro Solar',
		'' )
	;

	-- Tableros del Orden Sincrónico
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 3; INSERT INTO `app_art` VALUES
		(1, 3, 1, 'tzolkin', 'El Tzolkin',
		'' ),
		(1, 3, 2, 'nave', 'La Nave del Tiempo',
		'' ),
		(1, 3, 3, 'castillo', 'El Castillo Encantado de 52 días',
		'' ),
		(1, 3, 4, 'onda', 'La Onda Ecantada de 13 días',
		'' ),
		(1, 3, 5, 'armonicas', 'El Giro Galáctico ( Colocación Armónica )',
		'' ),
		(1, 3, 6, 'trayectoria', 'La Trayectoria Armónica de 20 días',
		'' ),
		(1, 3, 7, 'celula', 'La Célula del Tiempo de 4 días',
		'' ),
		(1, 3, 8, 'cromaticas', 'El Giro Espectral ( Colocación Cromática )',
		'' ),
		(1, 3, 9, 'estacion', 'La Estación Galáctica de 65 días',
		'' ),
		(1, 3, 10, 'elemento', 'La Cromática Elemental de 5 días',
		'' )
	;

	-- Tableros del Orden Cíclico
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 4; INSERT INTO `app_art` VALUES
		(1, 4, 1, 'banco-psi', 'El Banco-Psi',
		'' ),
		(1, 4, 2, 'anillo_solar', 'Las 13 Lunas del Anillo Solar',
		'' ),
		(1, 4, 3, 'giro_lunar', 'El Giro Lunar de 28 días',
		'' ),
		(1, 4, 4, 'heptadas', 'Las 52 Héptadas del Giro Solar',
		'' ),
		(1, 4, 5, 'estacion_solar', 'La Estación Solar de 91 días',
		'' ),
		(1, 4, 6, 'heptagono_semanal', 'El Heptágono Semanal de 7 días',
		'' )
	;

	-- Kin Planetario
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 5; INSERT INTO `app_art` VALUES

		(1, 5, 1, 'ficha', 'Ficha Personal',		
		'' ),
		(1, 5, 2, 'relaciones', 'Comparaciones entre Kines', 	
		'' )
	;	