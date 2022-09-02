-- Cabecera
	-- 1° nivel : Opcion del Menú
		DELETE FROM `_api`.`doc_cab` WHERE `esq`='hol';
		INSERT INTO `_api`.`doc_cab` VALUES	

			('hol','bib', 1, 'Bibliografía', 	1, '{
				
				"htm_ini":[
					{ "eti":"h1", "htm":"" }
				]
			}'),
			('hol','tab', 3, 'Tableros', 1,	'{

				"htm_ini":[
					{ "eti":"h1", "htm":"" }
				]
			}'),
			('hol','inf', 4, 'Informes', 1,	'{
				
				"htm_ini":[
					{ "eti":"h1", "htm":"" }
				]
			}')
		;