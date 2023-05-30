
-- Sincronario

    DELETE FROM `sis-app_usu` WHERE `esq`= 1; 
		INSERT INTO `sis-app_usu` (`esq`,`key`,`ide`,`nom`,`des`,`ico`,`ima`,`opc_url`) VALUES
		
			( 1, 1, 'oraculo', 'Oráculo de la Quinta Fuerza',
				'', 
				'', '', 0
			),
			( 1, 2, 'onda', 'Onda Encantada',
				'', 
				'', '', 0
			),
			( 1, 3, 'familia', 'Familia Terrestre',
				'', 
				'', '', 0
			),
			( 1, 4, 'raza', 'Raza Raíz Cósmica',
				'', 
				'', '', 0
			),			
      ( 1, 5, 'sendero', 'Sendero del Destino',
				'',
				'', '', 0
			)
		;