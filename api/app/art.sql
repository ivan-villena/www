-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

-- Holon-Sincronario
	DELETE FROM `app_art` WHERE `esq`='hol';
	
	-- Bibliografía
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='bib'; INSERT INTO `app_art` VALUES
		('hol','bib','ide',   1, 'Glosarios',
		'' ),	
		('hol','bib','tut',		2, 'Tutorial del Sincronario de 13 Lunas',
		'' ),	
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso',
		'' ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',
		'' ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',
		'' ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento',
		'' ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus',
		'' ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo',
		'' ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon',
		'' ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri',
		'' ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo',
		'' ),
		('hol','bib','tab',	1997, '1997 - Las 20 Tablas del Tiempo',
		'' ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo',
		'' ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón',
		'' )		
	;

	-- Códigos y Cuentas
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='dat'; INSERT INTO `app_art` VALUES
		('hol','dat','rad',   7, 'Los 7 Plasmas Radiales',
		'' ),
		('hol','dat','ton',  13, 'Los 13 Tonos Galácticos',
		'' ),
		('hol','dat','sel',  20, 'Los 20 Sellos Solares',
		'' ),
		('hol','dat','lun',  28, 'Los 28 Días del Giro Lunar',
		'' ),
		('hol','dat','kin', 260, 'Los 260 Kines del Giro Galáctico',
		'' ),
		('hol','dat','psi', 365, 'Los 365 Psi-Cronos del Giro Solar',
		'' )
	;

	-- Informes
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='inf'; INSERT INTO `app_art` VALUES

		('hol','inf','dat', 1, 'Ficha Personal',		
		'' ),
		('hol','inf','rel', 2, 'Comparaciones entre Kines', 	
		'' )
	;

	-- Orden Sincrónico
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='kin'; INSERT INTO `app_art` VALUES
		('hol','kin','tzo', 10, 'El Tzolkin',
		'' ),
		('hol','kin','nav', 20, 'La Nave del Tiempo',
		'' ),
		('hol','kin','nav_cas', 21, 'El Castillo Encantado de 52 días',
		'' ),
		('hol','kin','nav_ond', 22, 'La Onda Ecantada de 13 días',
		'' ),
		('hol','kin','arm', 30, 'El Giro Galáctico ( Colocación Armónica )',
		'' ),
		('hol','kin','arm_tra', 31, 'La Trayectoria Armónica de 20 días',
		'' ),
		('hol','kin','arm_cel', 32, 'La Célula del Tiempo de 4 días',
		'' ),
		('hol','kin','cro', 40, 'El Giro Espectral ( Colocación Cromática )',
		'' ),
		('hol','kin','cro_est', 41, 'La Estación Galáctica de 65 días',
		'' ),
		('hol','kin','cro_ele', 42, 'La Cromática Elemental de 5 días',
		'' )
	;
	
	-- Orden Cíclico
	DELETE FROM `app_art` WHERE `esq`='hol' AND `cab`='psi'; INSERT INTO `app_art` VALUES
		('hol','psi','kin', 10, 'El Banco-Psi',
		'' ),
		('hol','psi','ani', 20, 'Las 13 Lunas del Anillo Solar',
		'' ),
		('hol','psi','ani_lun', 21, 'El Giro Lunar de 28 días',
		'' ),
		('hol','psi','hep', 30, 'Las 52 Héptadas del Giro Solar',
		'' ),
		('hol','psi','hep_est', 31, 'La Estación Solar de 91 días',
		'' ),
		('hol','psi','hep_rad', 32, 'El Heptágono Semanal de 7 días',
		'' )
	;