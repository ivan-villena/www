-- Active: 1623270923336@@127.0.0.1@3306@_api

-- Holon-Sincronario
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol';
	-- bibliografía 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='bib';
	INSERT INTO `_api`.`doc_art` VALUES
	
		('hol','bib','tut',	1, 		'Tutorial del Sincronario de 13 Lunas',			'', NULL, NULL ),
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 			  			'', NULL, NULL ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',               			'', NULL, NULL ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',   			'', NULL, NULL ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento', 				'', NULL, NULL ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 			  			'', NULL, NULL ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 						'', NULL, NULL ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							  		'', NULL, NULL ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					  		'', NULL, NULL ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 			  			'', NULL, NULL ),
		('hol','bib','tab',	1997, '1997 - Las Tablas del Tiempo', 			  		'', NULL, NULL ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				  		'', NULL, NULL ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						  		'', NULL, NULL )
	;
	-- informes
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='inf';
	INSERT INTO `_api`.`doc_art` VALUES	
		('hol','inf','ide', 1, 		'Glosarios',																'', NULL, NULL ),
		('hol','inf','dat', 2, 		'Códigos y Cuentas',												'', NULL, NULL )	
	;		
	-- tableros
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='tab';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','tab','kin-tzo', 1, 'Los 260 kin del Tzolkin',									'', NULL, NULL ),
		('hol','tab','kin-nav', 2, 'Los 5 Castillos de la Nave del Tiempo', 	'', NULL, NULL ),
		('hol','tab','kin-arm', 3, 'Las 13 Trayectorias del Giro Galáctico', 	'', NULL, NULL ),
		('hol','tab','kin-cro', 4, 'Las 4 Estaciones del Giro Espectral', 		'', NULL, NULL ),
		('hol','tab','psi-ban', 5, 'Los 365 días del Giro Solar', 						'', NULL, NULL ),
		('hol','tab','psi-est', 6, 'Las 4 Estaciones del Anillo Solar', 			'', NULL, NULL ),
		('hol','tab','psi-lun', 7, 'Los 28 Días del Giro Lunar', 							'', NULL, NULL ),
		('hol','tab','psi-tzo', 8, 'Las 2.080 unidades del Banco-Psi', 				'', NULL, NULL )
	;
	-- valores diario
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='val';
	INSERT INTO `_api`.`doc_art` VALUES
		
		('hol','val','dia', 1, 'Ciclos del Tiempo',	'', NULL, NULL ),
		('hol','val','hum', 2, 'Firma Galáctica',		'', NULL, NULL )
	;
	-- cuenta de usuario
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='usu';
	INSERT INTO `_api`.`doc_art` VALUES
		
		('hol','usu','cic', 1, 'Sendero del Destino',					'', NULL, NULL ),
		('hol','usu','dat', 2, 'Firma Galáctica',							'', NULL, NULL )
	;