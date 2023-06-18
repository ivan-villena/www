
-- Sincronario
DELETE FROM `sis-dat_var` WHERE `app` = 'hol';

-- Datos:
DELETE FROM `sis-dat_var` WHERE `app` = 'hol' AND `esq` LIKE 'dat_';

  -- Valores -- 

    -- Sumatoria
    DELETE FROM `sis-dat_var` WHERE `app` = 'hol' AND `esq` = 'dat_val'; INSERT INTO `sis-dat_var` VALUES
      
      -- Sumatoria
      ('hol','dat_val','sum', 1, 'kin', '{ 
        "atr":"ide",
        "nom":"KIN",
        "ope":{ "tip":"num", "val":0, "max":260 },
        "dat_ima":"hol.kin"
      }'),
      ('hol','dat_val','sum', 2, 'psi', '{ 
        "atr":"tzo",
        "nom":"PSI",    
        "ope":{ "tip":"num", "val":0, "max":365 },
        "dat_ima":"hol.psi"
      }'),  
      ('hol','dat_val','sum', 3, 'umb', '{ 
        "atr":"ide",
        "nom":"UMB",    
        "ope":{ "tip":"num", "val":0, "max":441 }
      }')
      --
    ;
  --

  -- Tablero --

    -- Estructuras para Opciones
    DELETE FROM `sis-dat_var` WHERE `app` = 'hol' AND `esq` = 'dat_tab' AND `est` LIKE 'est%'; INSERT INTO `sis-dat_var` VALUES

      -- x5: Oráculo del Destino
      ('hol','dat_tab','est', 1, 'par', '{
        "nom":"Oráculo del Destino"
      }'),
        ('hol','dat_tab','est-par', 1, 'bor', '{
          "nom":"¿Bordes?",
          "tit":"Muestra u Oculta los bordes del Oráculo...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- x7: Heptágono
      ('hol','dat_tab','est', 2, 'rad', '{
        "nom":"Plasma Radial"
      }'),    
        ('hol','dat_tab','est-rad', 1, 'pla', '{
          "nom":"¿Posiciones?",
          "tit":"Mostrar Plasmas de las agrupaciones...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-rad', 2, 'hep', '{
          "nom":"¿Fondo del Heptágono?",
          "tit":"Mostrar Heptágono...",
          "ope":{ "tip":"opc_bin" }
        }'),      
      -- x13: Onda Encantada
      ('hol','dat_tab','est', 3, 'ton', '{
        "nom":"Onda Encantada"
      }'),
        ('hol','dat_tab','est-ton', 1, 'pos', '{
          "nom":"¿Posiciones?",
          "tit":"Mostrar las posiciones de la Onda Encantada...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-ton', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Mostrar los bordes de la Onda Encantada...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-ton', 3, 'col', '{
          "nom":"¿Color de Fondo?",
          "tit":"Colorear el Fondo de las Ondas Encantadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- x20: Colocaciones de Sellos Solares
      ('hol','dat_tab','est', 4, 'sel', '{
        "nom":"Sellos Solares"
      }'),
        ('hol','dat_tab','est-sel', 1, 'ima', '{
          "nom":"¿Posiciones?",
          "tit":"Mostrar los Sellos de las agrupaciones...",
          "ope":{ "tip":"opc_bin" }
        }'),
        -- armonicas
        ('hol','dat_tab','est', 21, 'sel_arm_tra', '{
          "nom":"Trayectoria Armónica"
        }'),
        ('hol','dat_tab','est-sel_arm_tra', 1, 'pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar las posiciones de las 13 Trayectorias Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_arm_tra', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Remarcar las bordes de las 13 Trayectorias Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_arm_tra', 3, 'col', '{
          "nom":"¿Color de fondo?",
          "tit":"Colorear las fondos de las 13 Trayectorias Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        -- celulas
        ('hol','dat_tab','est', 22, 'sel_arm_cel', '{
          "nom":"Célula del Tiempo"
        }'),      
        ('hol','dat_tab','est-sel_arm_cel', 1, 'pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar las posiciones de las 65 Células Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_arm_cel', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Remarcar las 65 Células Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_arm_cel', 3, 'col', '{
          "nom":"¿Color de fondo?",
          "tit":"Colorear el fondo de las 65 Células Armónicas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        -- cromaticas
        ('hol','dat_tab','est', 23, 'sel_cro_est', '{
          "nom":"Estación Galáctica"
        }'),      
        ('hol','dat_tab','est-sel_cro_est', 1, 'pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar las posiciones de las 4 Estaciones Espectrales...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_cro_est', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Remarcar las bordes de las 4 Estaciones Espectrales...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_cro_est', 3, 'col', '{
          "nom":"¿Color de fondo?",
          "tit":"Colorear las fondos de las 4 Estaciones Espectrales...",
          "ope":{ "tip":"opc_bin" }
        }'),
        -- elementos
        ('hol','dat_tab','est', 24, 'sel_cro_ele', '{
          "nom":"Elemento Cromático"
        }'),        
        ('hol','dat_tab','est-sel_cro_ele', 1, 'pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar las posiciones de los 52 Elementos Cromáticos...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_cro_ele', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Remarcar los 52 Elementos Cromáticos...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sel_cro_ele', 3, 'col', '{
          "nom":"¿Color de fondo?",
          "tit":"Colorear el fondo de los 52 Elementos Cromáticos...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- x28: Giro Lunar
      ('hol','dat_tab','est', 5, 'lun', '{
        "nom":"Giro Lunar"
      }'),    
        ('hol','dat_tab','est-lun', 1, 'ima', '{
          "nom":"¿Luna?",
          "tit":"Mostrar Posiciones de la Luna...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-lun', 2, 'cab', '{
          "nom":"¿Giro Lunar?",
          "tit":"Mostrar los datos de cabecera para el Giro Luna...",
          "ope":{ "tip":"opc_bin" }
        }'),      
        ('hol','dat_tab','est-lun', 3, 'hep', '{
          "nom":"¿Héptada?",
          "tit":"Mostrar las posiciones de las Héptadas Lunares...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-lun', 4, 'rad', '{
          "nom":"¿Plasma?",
          "tit":"Mostrar las columnas de los Plasmas Radiales...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- x52: Castillo del Destino
      ('hol','dat_tab','est', 6, 'cas', '{
        "nom":"Castillo del Destino"
      }'),
        ('hol','dat_tab','est-cas', 1, 'pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar las posiciones del Castillo del Destino...",
          "ope":{ "tip":"opc_bin" }
        }'),      
        ('hol','dat_tab','est-cas', 2, 'bor', '{
          "nom":"¿Borde?",
          "tit":"Remarcar el Borde del Castillo del Destino...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-cas', 3, 'col', '{
          "nom":"¿Color de fondo?",
          "tit":"Colorear el Fondo del Castillo del Destino...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-cas', 4, 'orb', '{
          "nom":"¿Orbitales?",
          "tit":"Activar los Orbitales del Castillo del Destino...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- x260: Kines del Tzolkin
      ('hol','dat_tab','est', 7, 'kin', '{
        "nom":"Módulo Armónico"
      }'),
        ('hol','dat_tab','est-kin', 1, 'sel', '{
          "nom":"¿Sellos Solares?",
          "tit":"Mostrar los Sellos Solares - Katunes...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-kin', 2, 'ton', '{
          "nom":"¿Trayectorias?",
          "tit":"Mostrar las 13 Trayectorias Armónicas - Baktunes...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- Holon Solar
      ('hol','dat_tab','est', 8, 'sol', '{
        "nom":"Holon Solar"
      }'),
        ('hol','dat_tab','est-sol', 1, 'res', '{
          "nom":"¿Respiración S-G?",
          "tit":"Mostrar los 2 flujos de la Respiración galáctico-solar del holon Solar...",
          "ope":{ "tip":"opc_bin" }
        }'),       
        ('hol','dat_tab','est-sol', 2, 'pla', '{
          "nom":"¿Planetas?",
          "tit":"Mostrar los 10 planetas del Sistema Solar...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sol', 3, 'orb', '{
          "nom":"¿Orbitales?",
          "tit":"Mostrar los 10 orbitales correspondientes a los planetas del Sistema Solar...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sol', 4, 'ele', '{
          "nom":"¿Elementos Galácticos?",
          "tit":"Mostrar los 4 Elementos Galácticos del Código 0-19...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sol', 5, 'cel', '{
          "nom":"¿Células Solares?",
          "tit":"Mostrar las 5 Células Solares del Código 0-19...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-sol', 6, 'cir', '{
          "nom":"¿Circuitos de Telepatía?",
          "tit":"Mostrar los 5 Circuitos de Telepatía del Telektonon...",
          "ope":{ "tip":"opc_bin" }
        }'),
      -- Holon Planetario
      ('hol','dat_tab','est', 9, 'pla', '{
        "nom":"Holon Planetario"
      }'),
        ('hol','dat_tab','est-pla', 1, 'res', '{
          "nom":"¿Respiración S-G?",
          "tit":"Mostrar los 2 flujos de la Respiración galáctico-solar del Holon Planetairo...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-pla', 2, 'ele', '{
          "nom":"¿Elementos Galácticos?",
          "tit":"Mostrar los 4 Elementos Galácticos del Holon Planetario...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-pla', 3, 'hem', '{
          "nom":"¿Hemisferios?",
          "tit":"Mostrar los Sellos correspondientes a los Hemisferios Planetarios...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-pla', 4, 'mer', '{
          "nom":"¿Meridianos?",
          "tit":"Mostrar los Sellos correspondientes a los Meridianos Planetarios...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-pla', 5, 'cen', '{
          "nom":"¿Centros Galácticos?",
          "tit":"Mostrar los 5 Centros Galácticos del Holon Planetario...",
          "ope":{ "tip":"opc_bin" }
        }'),                                              
      -- Holon Humano
      ('hol','dat_tab','est', 10, 'hum', '{
        "nom":"Holon Humano"
      }'),
        ('hol','dat_tab','est-hum', 1, 'res', '{
          "nom":"¿Respiración S-G?",
          "tit":"Mostrar los 2 flujos de la Respiración galáctico-solar del holon Humano...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-hum', 2, 'cen', '{
          "nom":"¿Centros Galácticos?",
          "tit":"Mostrar los Sellos Solares correspondientes a los 5 Centros Galácticos que se encuentran en los Chakras del Holon Humano...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-hum', 3, 'ext', '{
          "nom":"¿Extremidades?",
          "tit":"Mostrar los Sellos Solares correspondientes a los 4 Elementos Galácticos que se encuentran en las Extremidades del Holon Humano...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('hol','dat_tab','est-hum', 4, 'ded', '{
          "nom":"¿Dedos?",
          "tit":"Mostrar los 20 Sellos Solares correspondientes a los Dedos del Holon Humano...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-hum', 5, 'art', '{
          "nom":"¿Articulaciones?",
          "tit":"Mostrar las 13 Articulaciones principales del cuerpo Humano asociadas a los Tonos Galácticos...",
          "ope":{ "tip":"opc_bin" }
        }'), 
        ('hol','dat_tab','est-hum', 6, 'cha', '{
          "nom":"¿Chakras?",
          "tit":"Mostrar los 7 Chakras asociados a los Plasmas Radiales en el Holon Humano...",
          "ope":{ "tip":"opc_bin" }
        }')
    ;
    
    -- Atributos para Seleccion
    DELETE FROM `sis-dat_var` WHERE `app` = 'hol' AND `esq` = 'dat_tab' AND `est` LIKE 'atr%'; INSERT INTO `sis-dat_var` VALUES

      -- Portales de Activación
      ('hol','dat_tab','atr', 0, 'pag', '{
        "nom":"Portales de Activación"
      }'),
        ('hol','dat_tab','atr-pag', 1, 'cue', '{
          "nom":"Total",
          "tit":"Cantidad total de elementos pertenecientes a los portales de activación seleccionados...",
          "ope":{ "tip":"num", "val":0 }
        }'),    
        ('hol','dat_tab','atr-pag', 2, 'kin', '{
          "nom":"¿Giro Galáctico?",
          "tit":"Activar los Portales de Activación correspondientes al Giro Galáctico de 260 kines...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-pag', 3, 'psi', '{
          "nom":"¿Giro Solar?",
          "tit":"Activar los Portales de Activación correspondientes al Giro Solar de 364 + 1 días...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
      -- Parejas del Oráculo por posición
      ('hol','dat_tab','atr', 1, 'par', '{
        "nom":"Parejas del Oráculo"
      }'),
        ('hol','dat_tab','atr-par', 1, 'cue', '{
          "nom":"Total",
          "tit":"Cantidad total de elementos pertenecientes a las distintas parejas del oráculo...",
          "ope":{ "tip":"num", "val":0 }
        }'),      
        ('hol','dat_tab','atr-par', 2, 'ana', '{
          "nom":"¿Análogo?",
          "tit":"Activa la Pareja Análoga que Refuerza al Destino...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-par', 3, 'gui', '{
          "nom":"¿Guía?",
          "tit":"Activa la Pareja Guía que Orienta al Destino...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-par', 4, 'ant', '{
          "nom":"¿Antípoda?",
          "tit":"Activa la Pareja Antípoda que se Opone al Destino...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-par', 5, 'ocu', '{
          "nom":"¿Oculto?",
          "tit":"Activa la Pareja Oculta que Revela el Destino...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-par', 6, 'ext', '{
          "nom":"¿Extender Patrones?",
          "tit":"Extender las parejas seleccionadas para la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),      
      -- Pulsares de la O.E. por posición
      ('hol','dat_tab','atr', 3, 'pul', '{
        "nom":"Pulsares de la Onda Encantada",
        "opc":[ "dim", "mat", "sim" ]
      }'),
        ('hol','dat_tab','atr-pul', 1, 'cue', '{
          "nom":"Total",
          "tit":"Cantidad total de elementos pertenecientes a los distintos pulsares seleccionados...",
          "ope":{ "tip":"num", "val":0 }
        }'),
        ('hol','dat_tab','atr-pul', 2, 'dim', '{
          "nom":"¿Dimensional?",
          "tit":"Activar pulsares dimensionales de la Onda Encantada correspondiente a la posición principal...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-pul', 3, 'mat', '{
          "nom":"¿Matiz?",
          "tit":"Activar pulsares matiz de la Onda Encantada correspondiente a la posición principal...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),  
        ('hol','dat_tab','atr-pul', 4, 'sim', '{
          "nom":"¿Simetría Inversa?",
          "tit":"Activar pulsares por simetría inversa correspondiente a la posición principal...",
          "ope":{ "tip":"opc_bin" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
      -- Dimensional
      ('hol','dat_tab','atr', 4, 'dim', '{
        "nom":"Pulsares Dimensionales"
      }'),
        ('hol','dat_tab','atr-dim', 1, 'cue', '{
          "nom": "Total",
          "tit": "Cantidad total de elementos pertenecientes a los Pulsares Dimensionales...",        
          "ope":{ "tip":"num", "val": 0 }
        }'),
        ('hol','dat_tab','atr-dim', 2, '1', '{
          "nom":"¿Tiempo?",
          "tit":"Activa el Pulsar Dimensional del Tiempo...",
          "ope":{ "tip":"opc_bin", "value":"4" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-dim', 3, '2', '{
          "nom":"¿Vida?",
          "tit":"Activa el Pulsar Dimensional de la Vida...",
          "ope":{ "tip":"opc_bin", "value":"1" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-dim', 4, '3', '{
          "nom":"¿Sentir?",
          "tit":"Activa el Pulsar Dimensional del Sentir...",
          "ope":{ "tip":"opc_bin", "value":"2" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-dim', 5, '4', '{
          "nom":"¿Mente?",
          "tit":"Activa el Pulsar Dimensional de la Mente...",
          "ope":{ "tip":"opc_bin", "value":"3" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
      -- Matiz
      ('hol','dat_tab','atr', 5, 'mat', '{
        "nom":"Pulsares Matices"
      }'),
        ('hol','dat_tab','atr-mat', 1, 'cue', '{
          "nom":"Total",
          "tit":"Cantidad total de elementos pertenecientes a los Pulsares Matices...",
          "ope":{ "tip":"num", "val":0 }
        }'),
        ('hol','dat_tab','atr-mat', 2, '1', '{
          "nom":"¿1 Punto?",
          "tit":"Activa el Pulsar Matiz Magnético...",
          "ope":{ "tip":"opc_bin", "value":"1" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-mat', 3, '2', '{
          "nom":"¿2 Puntos?",
          "tit":"Activa el Pulsar Matiz Lunar...",
          "ope":{ "tip":"opc_bin", "value":"2" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-mat', 4, '3', '{
          "nom":"¿3 Puntos?",
          "tit":"Activa el Pulsar Matiz Eléctrico...",
          "ope":{ "tip":"opc_bin", "value":"3" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-mat', 5, '4', '{
          "nom":"¿4 Puntos?",
          "tit":"Activa el Pulsar Matiz Mente-Tiempo...",
          "ope":{ "tip":"opc_bin", "value":"4" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-mat', 6, '5', '{
          "nom":"¿Raya/s?",
          "tit":"Activa el Pulsar Matiz Tiempo-Vida...",
          "ope":{ "tip":"opc_bin", "value":"5" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
      -- Especular
      ('hol','dat_tab','atr', 6, 'sim', '{
        "nom":"Pulsares Especulares"
      }'),
        ('hol','dat_tab','atr-sim', 1, 'cue', '{
          "nom":"Total",
          "tit":"Cantidad total de elementos pertenecientes a los Pulsares Especulares...",
          "ope":{ "tip":"num", "val":0 }
        }'),
        ('hol','dat_tab','atr-sim', 2, '1', '{
          "nom":"¿Tonos 1-13?",
          "tit":"Activa el Pulsar Especular de la Unidad y el Movimiento...",
          "ope":{ "tip":"opc_bin", "value":"1" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 3, '2', '{
          "nom":"¿Tonos 2-12?",
          "tit":"Activa el Pulsar Especular de la Polaridad y la Estabilidad Compleja...",
          "ope":{ "tip":"opc_bin", "value":"2" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 4, '3', '{
          "nom":"¿Tonos 3-11?",
          "tit":"Activa el Pulsar Especular del Ritmo y la Estructura Disonante...",
          "ope":{ "tip":"opc_bin", "value":"3" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 5, '4', '{
          "nom":"¿Tonos 4-10?",
          "tit":"Activa el Pulsar Especular de la Medida y la Manifestación...",
          "ope":{ "tip":"opc_bin", "value":"4" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 6, '5', '{
          "nom":"¿Tonos 5-9?",
          "tit":"Activa el Pulsar Especular del Centro y la Periodicidad Cíclica...",
          "ope":{ "tip":"opc_bin", "value":"5" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 7, '6', '{
          "nom":"¿Tonos 6-8?",
          "tit":"Activa el Pulsar Especular del Equilibrio Orgánico y la Resonancia Armónica...",
          "ope":{ "tip":"opc_bin", "value":"6" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }'),
        ('hol','dat_tab','atr-sim', 8, '7', '{
          "nom":"¿Tonos 7-7?",
          "tit":"Activa el Pulsar Especular del Poder Místico Doble...",
          "ope":{ "tip":"opc_bin", "value":"7" },
          "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
        }')
      --
    ;
  -- 
--