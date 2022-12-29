--
-- Holon
  DELETE FROM `dat_var` WHERE `esq`='hol';
  -- Operadores : Sumatoria
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat`='ope'; INSERT INTO `dat_var` VALUES
    -- 
    -- Sumatoria
      ('hol','ope','sum', 1, 'kin', '{ 
        "atr":"ide",
        "nom":"KIN",
        "ope":{ "tip":"num", "val":0, "max":260 },
        "var_fic":"hol.kin"
      }'),
      ('hol','ope','sum', 2, 'psi', '{ 
        "atr":"tzo",
        "nom":"PSI",    
        "ope":{ "tip":"num", "val":0, "max":365 },
        "var_fic":"hol.psi"
      }'),  
      ('hol','ope','sum', 3, 'umb', '{ 
        "atr":"ide",
        "nom":"UMB",    
        "ope":{ "tip":"num", "val":0, "max":441 }
      }')
    --
  ;
  -- Tablero
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat`='tab'
  ; -- > Secciones : dat_var
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat`='tab' AND `val` LIKE 'sec%'; INSERT INTO `dat_var` VALUES
    --
    -- Oráculo
      ('hol','tab','sec', 50, 'par', '{
      "nom":"¿Oráculo?",
      "tit":"Muestra u Oculta los bordes del Oráculo...",
      "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Plasmas
      ('hol','tab','sec', 70, 'rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar Plasmas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 71, 'rad-hep', '{
        "nom":"¿Heptágono?",
        "tit":"Mostrar Heptágono...",
        "ope":{ "tip":"opc_bin" }
      }'),      
    --
    -- Tonos
      ('hol','tab','sec', 130, 'ton', '{
        "nom":"¿Tonos Galácticos?",
        "tit":"Mostrar los Tonos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 131, 'ton-ond', '{
        "nom":"¿Posiciones de O.E.?",
        "tit":"Mostrar las posiciones de la Onda Encantada...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 132, 'ton-col', '{
        "nom":"¿Fondo de O.E.?",
        "tit":"Colorear el Fondo de las Ondas Encantadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Sellos
      ('hol','tab','sec', 200, 'sel', '{
        "nom":"¿Sellos Solares?",
        "tit":"Mostrar los Sellos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 210, 'sel-arm', '{
        "nom":"¿Armónica?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 211, 'sel-arm_tra-pos', '{
        "nom":"¿Posición de T.A.?",
        "tit":"Mostrar las posiciones de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 212, 'sel-arm_tra-bor', '{
        "nom":"¿Borde de T.A.?",
        "tit":"Remarcar las bordes de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 213, 'sel-arm_tra-col', '{
        "nom":"Fondo de T.A.?",
        "tit":"Colorear las fondos de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 214, 'sel-arm_cel-pos', '{
        "nom":"¿Posición de C.A.?",
        "tit":"Mostrar las posiciones de las 65 Células Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 215, 'sel-arm_cel-bor', '{
        "nom":"¿Borde de C.A.?",
        "tit":"Remarcar las 65 Células Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 216, 'sel-arm_cel-col', '{
        "nom":"¿Fondo de C.A.?",
        "tit":"Colorear el fondo de las 65 Células Armónicas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 220, 'sel-cro', '{
        "nom":"¿Cromática?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 221, 'sel-cro_est-pos', '{
        "nom":"¿Posición del E.E.?",
        "tit":"Mostrar las posiciones de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 222, 'sel-cro_est-bor', '{
        "nom":"¿Borde de E.E.?",
        "tit":"Remarcar las bordes de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 223, 'sel-cro_est-col', '{
        "nom":"Fondo de E.E.?",
        "tit":"Colorear las fondos de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 224, 'sel-cro_ele-pos', '{
        "nom":"¿Posición de E.C.?",
        "tit":"Mostrar las posiciones de los 52 Elementos Cromáticos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 225, 'sel-cro_ele-bor', '{
        "nom":"¿Borde de E.C.?",
        "tit":"Remarcar los 52 Elementos Cromáticos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 226, 'sel-cro_ele-col', '{
        "nom":"¿Fondo de E.C.?",
        "tit":"Colorear el fondo de los 52 Elementos Cromáticos...",
        "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Luna
      ('hol','tab','sec', 280, 'lun', '{
        "nom":"¿Luna?",
        "tit":"Mostrar Posiciones de la Luna...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 282, 'lun-cab', '{
        "nom":"¿Giro Lunar?",
        "tit":"Mostrar los datos de cabecera para el Giro Luna...",
        "ope":{ "tip":"opc_bin" }
      }'),      
      ('hol','tab','sec', 283, 'lun-hep', '{
        "nom":"¿Héptada?",
        "tit":"Mostrar las posiciones de las Héptadas Lunares...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 284, 'lun-rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar las columnas de los Plasmas Radiales...",
        "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Castillo
      ('hol','tab','sec', 520, 'cas', '{
        "nom":"¿Castillo?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 521, 'cas-pos', '{
        "nom":"¿Posición del C.D.?",
        "tit":"Mostrar las posiciones del Castillo del Destino...",
        "ope":{ "tip":"opc_bin" }
      }'),      
      ('hol','tab','sec', 522, 'cas-bor', '{
        "nom":"¿Borde del C.D.?",
        "tit":"Remarcar el Borde del Castillo del Destino...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 523, 'cas-col', '{
        "nom":"¿Fondo del C.D.?",
        "tit":"Colorear el Fondo del Castillo del Destino...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 524, 'cas-orb', '{
        "nom":"¿Orbitales del C.D.?",
        "tit":"Activar los Orbitales del Castillo del Destino...",
        "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Kines
      ('hol','tab','sec', 2600, 'kin', '{
        "nom":"¿?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 2610, 'kin-sel', '{
        "nom":"¿Sellos Solares?",
        "tit":"Mostrar Columna de Sellos Solares como los 20 katunes del Baktún",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 2620, 'kin-ton', '{
        "nom":"¿Trayectorias?",
        "tit":"Mostrar Cabecera de Tonos Galácticos como las 13 Trayectorias Armónicas - Baktunes",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 2621, 'kin-arm_cel', '{
        "nom":"¿Células?",
        "tit":"Mostrar Columna de Células del Tiempo",
        "ope":{ "tip":"opc_bin" }
      }')
    --
  ; -- > Opciones : form > fieldset > dat_var
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat`='tab' AND `val` LIKE 'opc%'; INSERT INTO `dat_var` VALUES
    --
    -- Portales de Activación
      ('hol','tab','opc', 1, 'pag', '{
        "nom":"Portales de Activación"
      }'),
      ('hol','tab','opc-pag', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los portales de activación seleccionados...",
        "ope":{ "tip":"num", "val":0 }
      }'),    
      ('hol','tab','opc-pag', 2, 'kin', '{
        "nom":"¿Giro Galáctico?",
        "tit":"Activar los Portales de Activación correspondientes al Giro Galáctico de 260 kines...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-pag', 3, 'psi', '{
        "nom":"¿Giro Solar?",
        "tit":"Activar los Portales de Activación correspondientes al Giro Solar de 364 + 1 días...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
    --
    -- Parejas del Oráculo por posición
      ('hol','tab','opc', 2, 'par', '{
        "nom":"Parejas del Oráculo"
      }'),
      ('hol','tab','opc-par', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a las distintas parejas del oráculo...",
        "ope":{ "tip":"num", "val":0 }
      }'),
      ('hol','tab','opc-par', 2, 'ana', '{
        "nom":"¿Análogo?",
        "tit":"Activa la Pareja Análoga que Refuerza al Destino...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 3, 'gui', '{
        "nom":"¿Guía?",
        "tit":"Activa la Pareja Guía que Orienta al Destino...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 4, 'ant', '{
        "nom":"¿Antípoda?",
        "tit":"Activa la Pareja Antípoda que se Opone al Destino...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 5, 'ocu', '{
        "nom":"¿Oculto?",
        "tit":"Activa la Pareja Oculta que Revela el Destino...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 6, 'ext', '{
        "nom":"¿Extender Patrones?",
        "tit":"Extender las parejas seleccionadas para la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),      
    --
    -- Pulsares de la O.E. por posición
      ('hol','tab','opc', 3, 'pul', '{
        "nom":"Pulsares de la Onda Encantada",
        "opc":[ "dim", "mat", "sim" ]
      }'),
      ('hol','tab','opc-pul', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los distintos pulsares seleccionados...",
        "ope":{ "tip":"num", "val":0 }
      }'),
      ('hol','tab','opc-pul', 2, 'dim', '{
        "nom":"¿Dimensional?",
        "tit":"Activar pulsares dimensionales de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-pul', 3, 'mat', '{
        "nom":"¿Matiz?",
        "tit":"Activar pulsares matiz de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),  
      ('hol','tab','opc-pul', 4, 'sim', '{
        "nom":"¿Simetría Inversa?",
        "tit":"Activar pulsares por simetría inversa correspondiente a la posición principal...",
        "ope":{ "tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
    --
    -- Dimensional
      ('hol','tab','opc', 4, 'dim', '{
        "nom":"Pulsares Dimensionales"
      }'),
      ('hol','tab','opc-dim', 1, 'cue', '{
        "nom": "Total",
        "tit": "Cantidad total de elementos pertenecientes a los Pulsares Dimensionales...",        
        "ope":{ "tip":"num", "val": 0 }
      }'),
      ('hol','tab','opc-dim', 2, '1', '{
        "nom":"¿Tiempo?",
        "tit":"Activa el Pulsar Dimensional del Tiempo...",
        "ope":{ "tip":"opc_bin", "name":"dim_tie", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 3, '2', '{
        "nom":"¿Vida?",
        "tit":"Activa el Pulsar Dimensional de la Vida...",
        "ope":{ "tip":"opc_bin", "name":"dim_vid", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 4, '3', '{
        "nom":"¿Sentir?",
        "tit":"Activa el Pulsar Dimensional del Sentir...",
        "ope":{ "tip":"opc_bin", "name":"dim_sen", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 5, '4', '{
        "nom":"¿Mente?",
        "tit":"Activa el Pulsar Dimensional de la Mente...",
        "ope":{ "tip":"opc_bin", "name":"dim_men", "value":"3" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
    --
    -- Matiz
      ('hol','tab','opc', 5, 'mat', '{
        "nom":"Pulsares Matices"
      }'),
      ('hol','tab','opc-mat', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los Pulsares Matices...",
        "ope":{ "tip":"num", "val":0 }
      }'),
      ('hol','tab','opc-mat', 2, '1', '{
        "nom":"¿1 Punto?",
        "tit":"Activa el Pulsar Matiz Magnético...",
        "ope":{ "tip":"opc_bin", "name":"mat_uno", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 3, '2', '{
        "nom":"¿2 Puntos?",
        "tit":"Activa el Pulsar Matiz Lunar...",
        "ope":{ "tip":"opc_bin", "name":"mat_dos", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 4, '3', '{
        "nom":"¿3 Puntos?",
        "tit":"Activa el Pulsar Matiz Eléctrico...",
        "ope":{ "tip":"opc_bin", "name":"mat_tre", "value":"3" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 5, '4', '{
        "nom":"¿4 Puntos?",
        "tit":"Activa el Pulsar Matiz Mente-Tiempo...",
        "ope":{ "tip":"opc_bin", "name":"mat_cua", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 6, '5', '{
        "nom":"¿Raya/s?",
        "tit":"Activa el Pulsar Matiz Tiempo-Vida...",
        "ope":{ "tip":"opc_bin", "name":"mat_sin", "value":"5" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
    --
    -- Especular
      ('hol','tab','opc', 6, 'sim', '{
        "nom":"Pulsares Especulares"
      }'),
      ('hol','tab','opc-sim', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los Pulsares Especulares...",
        "ope":{ "tip":"num", "val":0 }
      }'),
      ('hol','tab','opc-sim', 2, '1', '{
        "nom":"¿Tonos 1-13?",
        "tit":"Activa el Pulsar Especular de la Unidad y el Movimiento...",
        "ope":{ "tip":"opc_bin", "name":"sim_1", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 3, '2', '{
        "nom":"¿Tonos 2-12?",
        "tit":"Activa el Pulsar Especular de la Polaridad y la Estabilidad Compleja...",
        "ope":{ "tip":"opc_bin", "name":"sim_2", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 4, '3', '{
        "nom":"¿Tonos 3-11?",
        "tit":"Activa el Pulsar Especular del Ritmo y la Estructura Disonante...",
        "ope":{ "tip":"opc_bin", "name":"sim_3", "value":"3" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 5, '4', '{
        "nom":"¿Tonos 4-10?",
        "tit":"Activa el Pulsar Especular de la Medida y la Manifestación...",
        "ope":{ "tip":"opc_bin", "name":"sim_4", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 6, '5', '{
        "nom":"¿Tonos 5-9?",
        "tit":"Activa el Pulsar Especular del Centro y la Periodicidad Cíclica...",
        "ope":{ "tip":"opc_bin", "name":"sim_5", "value":"5" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 7, '6', '{
        "nom":"¿Tonos 6-8?",
        "tit":"Activa el Pulsar Especular del Equilibrio Orgánico y la Resonancia Armónica...",
        "ope":{ "tip":"opc_bin", "name":"sim_6", "value":"6" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 8, '7', '{
        "nom":"¿Tonos 7-7?",
        "tit":"Activa el Pulsar Especular del Poder Místico Doble...",
        "ope":{ "tip":"opc_bin", "name":"sim_7", "value":"7" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }')
  ;
--