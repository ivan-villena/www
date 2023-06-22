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

	-- Lecturas
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'lectura'; INSERT INTO `sis-app_art` VALUES
	
		('sincronario', 'lectura', 1, 'glosario', 'Glosarios de Términos',
		'', 
		'tex_ord', '' ),
		('sincronario', 'lectura', 2, 'sincronario', 'Tutorial del Sincronario de 13 Lunas',
		'', 
		'fec_tie', '' )
	;

	-- Informes
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'informe'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'informe', 1, 'ciclo', 'Ciclos de Tiempo',
		'', 
		'fec_tie', '' ),	
		('sincronario', 'informe', 2, 'firma', 'Firma Galáctica',
		'', 
		'usu_gru', '' )
	;

	-- Codigos y Cuentas
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
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'orden_sincronico'; INSERT INTO `sis-app_art` VALUES
		
		('sincronario', 'orden_sincronico', 1, 'tzolkin', 'El Tzolkin',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 2, 'nave', 'La Nave del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 3, 'castillo', 'Los 52 días del Castillo',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 4, 'onda', 'Los 13 días de la Onda Ecantada',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 5, 'giro_galactico', 'El Giro Galáctico ( Colocación Armónica )',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 6, 'trayectoria', 'Los 20 días de la Trayectoria Armónica',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 7, 'celula', 'Los 4 días de la Célula del Tiempo',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 8, 'giro_espectral', 'El Giro Espectral ( Colocación Cromática )',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 9, 'estacion', 'Los 65 días de la Estación Galáctica',
		'', 
		'', '' ),
		('sincronario', 'orden_sincronico', 10, 'elemento', 'Los 5 días de la Cromática Elemental',
		'', 
		'', '' )
	;

	-- Orden Cíclico
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'orden_ciclico'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'orden_ciclico', 1, 'luna', 'Las 13 Lunas del Anillo Solar',
		'', 
		'', '' ),
		('sincronario', 'orden_ciclico', 2, 'luna_diario', 'Los 28 días del Giro Lunar',
		'', 
		'', '' ),
		('sincronario', 'orden_ciclico', 3, 'estacion', 'Las 4 Estaciones del Giro Solar',
		'', 
		'', '' ),
		('sincronario', 'orden_ciclico', 4, 'estacion_diario', 'Los 91 días de la Estación Solar',
		'', 
		'', '' ),
		('sincronario', 'orden_ciclico', 5, 'heptada', 'El Heptágono Semanal de 7 días',
		'', 
		'', '' )
	;	
	
	-- Holones
	DELETE FROM `sis-app_art` WHERE `esq` = 'sincronario' AND `cab` = 'holon'; INSERT INTO `sis-app_art` VALUES

		('sincronario', 'holon', 1, 'solar', 'Solar',
		'', 
		'', '' ),	
		('sincronario', 'holon', 2, 'planetario', 'Planetario',
		'', 
		'', '' ),	
		('sincronario', 'holon', 3, 'humano', 'Humano',
		'', 
		'', '' )				
	;	
-- 

-- Proyecto