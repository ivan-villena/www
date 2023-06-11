
-- Sincronario

    DELETE FROM `sis-app_usu` WHERE `esq`= 1; 
		INSERT INTO `sis-app_usu` (`esq`,`key`,`ide`,`nom`,`des`,`ico`,`ima`,`opc_url`) VALUES
		

      ( 1, 1, 'transito', 'Tránsitos',
				'',
				'', '', 0
			),				
      ( 1, 2, 'firma', 'Firma Galáctica',
				'',
				'', '', 0
			),
			( 1, 3, 'oraculo', 'Oráculo de la Quinta Fuerza',
				'', 
				'', '', 0
			),
      ( 1, 4, 'sendero', 'Sendero del Destino',
				'',
				'', '', 0
			)
		;