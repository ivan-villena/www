-- Holon-Sincronario
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol';
	-- bibliografía 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='bib';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','bib','dat', 1, 		'Códigos y Cuentas', 										'', 'hol/bib/dat', NULL ),
		('hol','bib','tut',	2, 		'Tutorial del Sincronario de 13 Lunas',	'', 'hol/bib/tut', NULL ),
		('hol','bib','asc',	1984, '1984 - La Tierra en Ascenso', 			  	'', 'hol/bib/asc', NULL ),
		('hol','bib','fac',	1987, '1987 - El Factor Maya',               	'', 'hol/bib/fac', NULL ), 
		('hol','bib','enc',	1990, '1990 - El Encantamiento del Sueño',   	'', 'hol/bib/enc', NULL ),
		('hol','bib','lun',	1991, '1991 - Las 13 lunas en Movimiento', 		'', 'hol/bib/lun', NULL ),		
		('hol','bib','arc',	1992, '1992 - La Sonda de Arcturus', 			  	'', 'hol/bib/arc', NULL ),
		('hol','bib','tie',	1993, '1993 - Un Tratado del Tiempo', 				'', 'hol/bib/tie', NULL ),		
		('hol','bib','tel',	1994, '1994 - El Telektonon', 							  '', 'hol/bib/tel', NULL ),
		('hol','bib','rin',	1995, '1995 - El Proyecto Rinri', 					  '', 'hol/bib/rin', NULL ),
		('hol','bib','din',	1996, '1996 - Dinámicas del Tiempo', 			  	'', 'hol/bib/din', NULL ),
		('hol','bib','tab',	1997, '1997 - Las Tablas del Tiempo', 			  '', 'hol/bib/tab', NULL ),
		('hol','bib','ato',	1999, '1999 - El Átomo del Tiempo', 				  '', 'hol/bib/ato', NULL ),
		('hol','bib','cro',	2009, '2009 - El Sincronotrón', 						  '', 'hol/bib/cro', NULL )
	;
	-- Cuentas :  tablero + listado + informe 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='dat';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','dat','kin', 		1, 'Módulo Armónico del Tzolkin', 			'', 'hol/dat/kin', 			'{

			"tex":[

				{ "_tip":"tex", "val":"Módulo Armónico calibrado en 260 días" }
			],
			
			"tab":{

				"opc":[ "par" ],

				"ope":{

					"sec":{ "ima": "", "sel": 1 },

					"pos":{ "ima": "hol.ton.ide", "col": "hol.kin.pag", "num": "hol.kin.ide", "pag_kin":1 }

				}
			} 
		}'),
		('hol','dat','kin_nav', 2, 'Castillos de la Nave del Tiempo', 	'', 'hol/dat/kin_nav', 	'{
			
			"tab":{

				"opc":[ "par", "pul" ],

				"ope":{
					
					"sec":{ "ima": "", "bor": 0, "col": 1, "cas":1 }, 	

					"pos":{ "ima": "hol.kin.ide", "col": "", "num": "" }
				}			
			}
		}'),	
		('hol','dat','kin_arm', 3, 'Trayectorias del Giro Galáctico', 	'', 'hol/dat/kin_arm', 	'{
			
			"tab":{

				"opc":[ "par", "pul" ],
				
				"ope":{											

					"sec":{ "ima": "", "bor": 0, "arm": 1 },

					"pos":{ "ima": "hol.sel.ide", "col": "", "num": "" }
				}			
			}
		}'),
		('hol','dat','kin_cro', 4, 'Estaciones del Giro Espectral', 		'', 'hol/dat/kin_cro', 	'{
					
			"tab":{

				"opc":[ "par", "pul" ],
				
				"ope":{				
					
					"sec":{ "ima": "", "bor": 0, "cas": 1, "orb": 1, "ond": 1, "cro": 1 },
					
					"pos":{ "ima": "hol.sel.ide", "col": "", "num": "" }
				}
			}
		}'),
		('hol','dat','psi', 		5, 'Lunas del Anillo Solar', 						'', 'hol/dat/psi', 			'{
			
			"tab":{

				"opc":[ "par", "pul" ],
				
				"ope":{				

					"sec":{ "ima": "", "bor": 0 },
					
					"pos":{ "ima": "hol.kin.ide", "col": "", "num": "" }
				}		
			}
		}'),			
		('hol','dat','psi_est', 6, 'Estaciones del Anillo Solar', 			'', 'hol/dat/psi_est', 	'{
			
			"tab":{

				"opc":[ "par", "pul" ],			

				"ope":{

					"sec":{ "ima": "", "bor": 0 },

					"pos":{ "ima": "hol.rad.ide", "col": "", "num": "" }
				}
			}
		}'),
		('hol','dat','psi_lun', 7, 'Días del Giro Lunar', 							'', 'hol/dat/psi_lun', 	'{
			
			"tab":{

				"opc":[ "par" ],			

				"ope":{			

					"sec":{ "ima": "", "bor": 0, "lun": 1, "hep": 1, "rad": 1 },

					"pos":{ "ima": "hol.kin.ide", "col": "", "num": "" }
				}, 

				"ele":{

					"lun":{
						"style": "border-collapse: separate;"
					}
				}			
			}
		}')
	;
	-- Informes : indice + texto 
	DELETE FROM `_api`.`doc_art` WHERE `esq`='hol' AND `cab`='inf';
	INSERT INTO `_api`.`doc_art` VALUES

		('hol','inf','gal', 1, 'Giro Galáctico Diario', 								'', 'hol/inf/gal', NULL ),	
		('hol','inf','sol', 2, 'Giro Solar Diario', 										'', 'hol/inf/sol', NULL ),
		('hol','inf','hum', 3, 'Ficha del kin Planetario', 							'', 'hol/inf/hum', NULL )
	;