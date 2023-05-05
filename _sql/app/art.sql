-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Sincronario
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
	-- Apuntes
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 2; INSERT INTO `app_art` VALUES
		(1, 2, 0, '', 'Códigos',
		'' ),	
		(1, 2, 1, 'plasma', 'Los 7 Plasmas Radiales',
		'' ),
		(1, 2, 2, 'tono',  'Los 13 Tonos Galácticos',
		'' ),
		(1, 2, 3, 'sello',  'Los 20 Sellos Solares',
		'' ),
		(1, 2, 10, '', 'Ciclos',
		'' ),			
		(1, 2, 11, 'kin', 'Los 260 Kines del Giro Galáctico',
		'' ),
		(1, 2, 12, 'psi', 'Los 365 Psi-Cronos del Giro Solar',
		'' ),
		(1, 2, 20, '', 'Holones',
		'' ),
		(1, 2, 21, 'solar', 'Solar',
		'' ),
		(1, 2, 22, 'planetario', 'Planetario',
		'' ),
		(1, 2, 23, 'humano', 'Humano',
		'' )
	;
	-- Tableros 
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 3; INSERT INTO `app_art` VALUES
		-- orden sincronico
		(1, 3, 0, '', 'Orden Sincronico',
		'' ),
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
		'' ),
		-- orden ciclico
		(1, 3, 20, '', 'Orden Cíclico',
		'' ),		
		(1, 3, 21, 'banco-psi', 'El Banco-Psi',
		'' ),
		(1, 3, 22, 'anillo_solar', 'Las 13 Lunas del Anillo Solar',
		'' ),
		(1, 3, 23, 'giro_lunar', 'El Giro Lunar de 28 días',
		'' ),
		(1, 3, 24, 'heptadas', 'Las 52 Héptadas del Giro Solar',
		'' ),
		(1, 3, 25, 'estacion_solar', 'La Estación Solar de 91 días',
		'' ),
		(1, 3, 26, 'heptagono_semanal', 'El Heptágono Semanal de 7 días',
		'' )
	;
	-- Diario
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 4; INSERT INTO `app_art` VALUES

		(1, 4, 1, 'sincronico', 'Orden Sincrónico',
		'' ),
		(1, 4, 2, 'ciclico', 'Orden Cíclico',
		'' )
	;
	-- Kin Planetario
	DELETE FROM `app_art` WHERE `esq`=1 AND `cab`= 5; INSERT INTO `app_art` VALUES

		(1, 5, 1, 'ficha', 'Ficha Personal',
		'' ),
		(1, 5, 2, 'sendero', 'Sendero del Destino',
		'' ),
		(1, 5, 3, 'relacion', 'Relaciones entre kines',
		'' )
	;
-- 

-- Proyecto