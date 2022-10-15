-- Active: 1665550796793@@127.0.0.1@3306@api

-- Holon-Sincronario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol';
	
	-- Libros
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='bib';
	INSERT INTO `api`.`app_art` VALUES

		('hol','bib','tut',	2, 		'Tutorial del Sincronario de 13 Lunas',			'
		', NULL, NULL ),
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 			  			'
			Se introduce el concepto de Holonomia y se realiza una aplicación del banco-psi al desarrollo evolutivo en este Planeta.
		', NULL, NULL ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',               			'
			Se presenta a los mayas como agentes de sincronización galáctica, repasando aspectos claves de su historia.
			El Tzolkin se aplica como el Módulo Armónico provisto de un código galáctico.
		', NULL, NULL ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',   			'
		', NULL, NULL ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento', 				'
		', NULL, NULL ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 			  			'
		', NULL, NULL ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 						'
		', NULL, NULL ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							  		'
		', NULL, NULL ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					  		'
		', NULL, NULL ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 			  			'
		', NULL, NULL ),
		('hol','bib','tab',	1997, '1997 - Las Tablas del Tiempo', 			  		'
		', NULL, NULL ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				  		'
		', NULL, NULL ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						  		'
		', NULL, NULL )
	;
	-- Artículos
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='art';	
	INSERT INTO `api`.`app_art` VALUES
		('hol','art','ide', 1, 'Glosarios', '', NULL, NULL )
	;
	-- Cuentas
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='dat';
	INSERT INTO `api`.`app_art` VALUES
		
		('hol','dat','rad',   7, 'Los 7 Plasmas Radiales',						'', NULL, NULL ),
		('hol','dat','ton',  13, 'Los 13 Tonos Galácticos',						'', NULL, NULL ),
		('hol','dat','sel',  20, 'Los 20 Sellos Solares',							'', NULL, NULL ),
		('hol','dat','lun',  28, 'Los 28 Días del Giro Lunar',				'', NULL, NULL ),
		('hol','dat','cas',  52, 'Las 52 Posiciones del Castillo',		'', NULL, NULL ),
		('hol','dat','kin', 260, 'Los 260 kines del Tzolkin',					'', NULL, NULL ),
		('hol','dat','psi', 365, 'Los 365 Psi-cronos del Banco-psi',	'', NULL, NULL )
	;
	-- Diario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='dia';
	INSERT INTO `api`.`app_art` VALUES
		
		('hol','dia','kin', 1, 'Kin',							'', NULL, NULL ),
		('hol','dia','sel', 2, 'Sello Solar',			'', NULL, NULL ),
		('hol','dia','ton', 3, 'Tono Galáctico',	'', NULL, NULL ),
		('hol','dia','psi', 4, 'Psi-Cronos',			'', NULL, NULL ),
		('hol','dia','lun', 5, 'Giro Lunar',			'', NULL, NULL ),
		('hol','dia','rad', 6, 'Plasma Radial',		'', NULL, NULL )
	;	
	-- Tableros
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='tab';
	INSERT INTO `api`.`app_art` VALUES

		('hol','tab','kin-tzo', 1, 'Los 260 kines del Tzolkin',								'', NULL, NULL ),
		('hol','tab','kin-nav', 2, 'Los 5 Castillos de la Nave del Tiempo', 	'', NULL, NULL ),
		('hol','tab','kin-arm', 3, 'Las 13 Trayectorias del Giro Galáctico', 	'', NULL, NULL ),
		('hol','tab','kin-cro', 4, 'Las 4 Estaciones del Giro Espectral', 		'', NULL, NULL ),
		('hol','tab','psi-ban', 5, 'Los 365 días del Giro Solar', 						'', NULL, NULL ),
		('hol','tab','psi-est', 6, 'Las 4 Estaciones del Anillo Solar', 			'', NULL, NULL ),
		('hol','tab','psi-lun', 7, 'Los 28 Días del Giro Lunar', 							'', NULL, NULL ),
		('hol','tab','psi-tzo', 8, 'Las 2.080 unidades del Banco-Psi', 				'', NULL, NULL )
	;	
	-- Usuario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='usu';
	INSERT INTO `api`.`app_art` VALUES
		
		('hol','usu','cic', 1, 'Sendero del Destino',					'', NULL, NULL ),
		('hol','usu','dat', 2, 'Firma Galáctica',							'', NULL, NULL )
	;