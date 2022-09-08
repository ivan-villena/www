-- Holon-Sincronario
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol';
	-- bibliografía 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='bib';
	INSERT INTO `_api`.`doc_art` VALUES
		
		('hol','bib','tut',	1, 		'Tutorial del Sincronario de 13 Lunas',	'', 'hol/bib/tut' ),
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 			  	'', 'hol/bib/asc' ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',               	'', 'hol/bib/fac' ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',   	'', 'hol/bib/enc' ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento', 		'', 'hol/bib/lun' ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 			  	'', 'hol/bib/arc' ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 				'', 'hol/bib/tie' ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							  '', 'hol/bib/tel' ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					  '', 'hol/bib/rin' ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 			  	'', 'hol/bib/din' ),
		('hol','bib','tab',	1997, '1997 - Las Tablas del Tiempo', 			  '', 'hol/bib/tab' ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				  '', 'hol/bib/ato' ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						  '', 'hol/bib/cro' )
	;
	-- Cuentas :  tablero + listado + informe 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='tab';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','tab','kin-tzo', 1, 'Los 260 kin del Tzolkin',									'', 'hol/tab/kin-tzo' ),
		('hol','tab','kin-nav', 2, 'Los 5 Castillos de la Nave del Tiempo', 	'', 'hol/tab/kin-nav' ),
		('hol','tab','kin-arm', 3, 'Las 13 Trayectorias del Giro Galáctico', 	'', 'hol/tab/kin-arm' ),
		('hol','tab','kin-cro', 4, 'Las 4 Estaciones del Giro Espectral', 		'', 'hol/tab/kin-cro' ),
		('hol','tab','psi-ban', 5, 'Los 365 días del Giro Solar', 						'', 'hol/tab/psi-ban' ),
		('hol','tab','psi-est', 6, 'Las 4 Estaciones del Anillo Solar', 			'', 'hol/tab/psi-est' ),
		('hol','tab','psi-lun', 7, 'Los 28 Días del Giro Lunar', 							'', 'hol/tab/psi-lun' ),
		('hol','tab','psi-tzo', 8, 'Las 2.080 unidades del Banco-Psi', 				'', 'hol/tab/psi-tzo' )
	;
	-- Informes : indice + texto 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='inf';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','inf','dat', 1, 'Códigos y Cuentas',															'', 'hol/inf/dat' ),
		('hol','inf','val', 2, 'Ciclos del Tiempo',															'', 'hol/inf/val' ),
		('hol','inf','hum', 3, 'Firma Galáctica', 															'', 'hol/inf/hum' )
	;