-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api

-- Holon-Sincronario
	DELETE FROM `app_art` WHERE `esq`='hol';
	
	-- Libros
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
	-- Codigos
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='dat'; INSERT INTO `app_art` VALUES
		('hol','dat','ide',  	0, 'Glosarios', 												'' ),
		('hol','dat','rad',   7, 'Los 7 Plasmas Radiales', 						'' ),
		('hol','dat','ton',  13, 'Los 13 Tonos Galácticos', 					'' ),
		('hol','dat','sel',  20, 'Los 20 Sellos Solares', 						'' ),
		('hol','dat','lun',  28, 'Los 28 Días del Giro Lunar', 				'' ),
		('hol','dat','cas',  52, 'Las 52 Posiciones del Castillo',		'' ),
		('hol','dat','chi',  64, 'Los 64 Hexagramas', 								'' ),
		('hol','dat','kin', 260, 'Los 260 Kines del Giro Galáctico', 	'' ),
		('hol','dat','psi', 365, 'Los 365 Psi-Cronos del Giro Solar',	'' )
	;
	-- Modulos
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='ope'; INSERT INTO `app_art` VALUES

		('hol','ope','kin_tzo', 1, 'El Tzolkin',							'' ),
		('hol','ope','kin_nav', 2, 'La Nave del Tiempo', 			'' ),
		('hol','ope','kin_arm', 3, 'El Giro Galáctico', 			'' ),
		('hol','ope','kin_cro', 4, 'El Giro Espectral', 			'' ),
		('hol','ope','psi_ban', 5, 'El Giro Solar', 					'' ),
		('hol','ope','psi_est', 6, 'Las Estaciones Solares', 	'' ),
		('hol','ope','psi_lun', 7, 'El Giro Lunar', 					'' ),
		('hol','ope','psi_tzo', 8, 'El Banco-Psi', 						'' )
	;	
	-- Usuario
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='usu'; INSERT INTO `app_art` VALUES
		
		('hol','usu','cic', 1, 'Sendero del Destino',					'' ),
		('hol','usu','hum', 2, 'Firma Galáctica',							'' )
	; 