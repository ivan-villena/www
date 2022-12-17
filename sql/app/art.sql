-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Holon-Sincronario
	DELETE FROM `app_art` WHERE `esq`='hol';
	
	-- Bibliografía
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='bib'; INSERT INTO `app_art` VALUES
		('hol','bib','tut',		2, 'Tutorial del Sincronario de 13 Lunas',	'' ),
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 				'' ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',            	'' ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',	'' ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento',	'' ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 				'' ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 			'' ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							'' ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					'' ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 				'' ),
		('hol','bib','tab',	1997, '1997 - Las 20 Tablas del Tiempo', 		'' ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				'' ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						'' )
	;

	-- Artículos
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='dat'; INSERT INTO `app_art` VALUES
		('hol','dat','ide',   1, 'Glosarios', 												'' ),
		('hol','dat','rad',   7, 'Los 7 Plasmas Radiales', 						'' ),
		('hol','dat','ton',  13, 'Los 13 Tonos Galácticos', 					'' ),
		('hol','dat','sel',  20, 'Los 20 Sellos Solares', 						'' ),
		('hol','dat','lun',  28, 'Los 28 Días del Giro Lunar', 				'' ),
		('hol','dat','kin', 260, 'Los 260 Kines del Giro Galáctico', 	'' ),
		('hol','dat','psi', 365, 'Los 365 Psi-Cronos del Giro Solar', '' )
	;

	-- Orden Sincrónico
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='kin'; INSERT INTO `app_art` VALUES
		('hol','kin','tzo', 1, 'El Tzolkin',							'' ),
		('hol','kin','nav', 2, 'La Nave del Tiempo', 			'' ),
		('hol','kin','arm', 3, 'El Giro Galáctico', 			'' ),
		('hol','kin','cro', 4, 'El Giro Espectral', 			'' )
	;
	
	-- Orden Cíclico
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='psi'; INSERT INTO `app_art` VALUES
		('hol','psi','ban', 1, 'El Giro Solar', 					'' ),
		('hol','psi','est', 2, 'Las Estaciones Solares', 	'' ),
		('hol','psi','lun', 3, 'El Giro Lunar', 					'' ),
		('hol','psi','tzo', 4, 'El Banco-Psi', 						'' )
	;
	
	-- Usuario
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='usu'; INSERT INTO `app_art` VALUES		
		('hol','usu','cic', 1, 'Sendero del Destino',					'' ),
		('hol','usu','hum', 2, 'Firma Galáctica',							'' )
	; 