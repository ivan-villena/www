-- Active: 1665550796793@@127.0.0.1@3306@api

-- Holon-Sincronario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol';
	
	-- Libros
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='bib'; INSERT INTO `api`.`app_art` VALUES
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 				'' ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',            	'' ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',	'' ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento',	'' ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 				'' ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 			'' ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							'' ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					'' ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 				'' ),
		('hol','bib','tab',	1997, '1997 - Las Tablas del Tiempo', 			'' ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				'' ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						'' )
	;
	-- Artículos
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='art'; INSERT INTO `api`.`app_art` VALUES
		('hol','art','ide',  	1, 'Glosarios', 														'' ),
		('hol','art','tut',		2, 'Tutorial del Sincronario de 13 Lunas',	'' )
	;
	-- Codigos y cuentas
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='dat'; INSERT INTO `api`.`app_art` VALUES
		('hol','dat','rad',   7, 'Los 7 Plasmas Radiales', 						'' ),
		('hol','dat','ton',  13, 'Los 13 Tonos Galácticos', 					'' ),
		('hol','dat','sel',  20, 'Los 20 Sellos Solares', 						'' ),
		('hol','dat','lun',  28, 'Los 28 Días del Giro Lunar', 				'' ),
		('hol','dat','cas',  52, 'Las 52 Posiciones del Castillo',		'' ),
		('hol','dat','chi',  64, 'Los 64 Hexagramas', 								'' ),
		('hol','dat','kin', 260, 'Los 260 Kines del Giro Galáctico', 	'' ),
		('hol','dat','psi', 365, 'Los 365 Psi-Cronos del Giro Solar',	'' )
	;
	-- Diario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='dia'; INSERT INTO `api`.`app_art` VALUES
		
		('hol','dia','kin', 				01, 'El Kin',										'' ),
		('hol','dia','sel', 				02, 'Sello Solar',							'' ),
		('hol','dia','ton', 				03, 'Tono Galáctico',						'' ),
		('hol','dia','kin_nav_cas', 11, 'Castillo',									'' ),
		('hol','dia','kin_nav_ond', 12, 'Onda Encantada',						'' ),
		('hol','dia','kin_arm_tra', 13, 'Trayectoria Armónica',			'' ),
		('hol','dia','kin_arm_cel', 14, 'Célula del Tiempo',				'' ),
		('hol','dia','kin_cro_est', 15, 'Estación Espectral',				'' ),
		('hol','dia','kin_cro_ele', 16, 'Elemento Cromático',				'' ),
		('hol','dia','psi', 				21, 'Psi-Cronos',								'' ),
		('hol','dia','lun', 				22, 'Giro Lunar',								'' ),
		('hol','dia','rad', 				23, 'Plasma Radial',						'' )
	;	
	-- Tableros
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='tab'; INSERT INTO `api`.`app_art` VALUES

		('hol','tab','kin-tzo', 1, 'Los 260 kines del Tzolkin',								'' ),
		('hol','tab','kin-nav', 2, 'Los 5 Castillos de la Nave del Tiempo', 	'' ),
		('hol','tab','kin-arm', 3, 'Las 13 Trayectorias del Giro Galáctico', 	'' ),
		('hol','tab','kin-cro', 4, 'Las 4 Estaciones del Giro Espectral', 		'' ),
		('hol','tab','psi-ban', 5, 'Los 365 días del Giro Solar', 						'' ),
		('hol','tab','psi-est', 6, 'Las 4 Estaciones del Anillo Solar', 			'' ),
		('hol','tab','psi-lun', 7, 'Los 28 Días del Giro Lunar', 							'' ),
		('hol','tab','psi-tzo', 8, 'Las 2.080 unidades del Banco-Psi', 				'' )
	;	
	-- Usuario
	DELETE FROM `api`.`app_art` WHERE `esq`='hol' AND `cab`='usu'; INSERT INTO `api`.`app_art` VALUES
		
		('hol','usu','cic', 1, 'Sendero del Destino',					'' ),
		('hol','usu','dat', 2, 'Firma Galáctica',							'' )
	;