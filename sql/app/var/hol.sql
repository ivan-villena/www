-- Active: 1663730672989@@127.0.0.1@3306@_api

DELETE FROM `_api`.`app_var` WHERE `esq`='hol';
--
  -- Valores
  DELETE FROM `_api`.`app_var` WHERE `esq`='hol' AND `dat` = 'val';
  INSERT INTO `_api`.`app_var` VALUES

    -- sumatoria 
      ('hol','val','sum', 1, 'kin', '{ 
        "atr":"ide",
        "nom":"KIN",
        "ope":{ "_tip":"num", "val":0, "max":260 },
        "var_fic":"api.hol_kin"
      }'),
      ('hol','val','sum', 2, 'psi', '{ 
        "atr":"tzo",
        "nom":"PSI",    
        "ope":{ "_tip":"num", "val":0, "max":365 },
        "var_fic":"api.hol_psi"
      }'),  
      ('hol','val','sum', 3, 'umb', '{ 
        "atr":"ide",
        "nom":"UMB",    
        "ope":{ "_tip":"num", "val":0, "max":441 }
      }')
    --
  ;
  --
  -- Atributos : oraculo + pulsares
  DELETE FROM `_api`.`app_var` WHERE `esq`='hol' AND `dat` = 'atr';
  INSERT INTO `_api`.`app_var` VALUES

    -- Portales de Activación
      ('hol','atr','pag', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los portales de activación seleccionados...",
        "ope":{ "_tip":"num", "val":0 }
      }'),    
      ('hol','atr','pag', 2, 'kin', '{
        "nom":"¿Giro Galáctico?",
        "tit":"Activar los Portales de Activación correspondientes al Giro Galáctico de 260 kines...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','pag', 3, 'psi', '{
        "nom":"¿Giro Solar?",
        "tit":"Activar los Portales de Activación correspondientes al Giro Solar de 364 + 1 días...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
    --
    -- parejas del oráculo ( kin + sel )

      ('hol','atr','par', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a las distintas parejas del oráculo...",
        "ope":{ "_tip":"num", "val":0 }
      }'),
      ('hol','atr','par', 2, 'ana', '{
        "nom":"¿Análogo?",
        "tit":"Activa la Pareja Análoga que Refuerza al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','par', 3, 'gui', '{
        "nom":"¿Guía?",
        "tit":"Activa la Pareja Guía que Orienta al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','par', 4, 'ant', '{
        "nom":"¿Antípoda?",
        "tit":"Activa la Pareja Antípoda que se Opone al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','par', 5, 'ocu', '{
        "nom":"¿Oculto?",
        "tit":"Activa la Pareja Oculta que Revela el Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','par', 6, 'ext', '{
        "nom":"¿Extender Patrones?",
        "tit":"Extender las parejas seleccionadas para la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
    --
    -- pulsares de la o.e. ( ton + cas )

      ('hol','atr','pul', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total de elementos pertenecientes a los distintos pulsares seleccionados...",
        "ope":{ "_tip":"num", "val":0 }
      }'),
      ('hol','atr','pul', 2, 'dim', '{
        "nom":"¿Dimensional?",
        "tit":"Activar pulsares dimensionales de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','atr','pul', 3, 'mat', '{
        "nom":"¿Matiz?",
        "tit":"Activar pulsares matiz de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),  
      ('hol','atr','pul', 4, 'sim', '{
        "nom":"¿Simetría Inversa?",
        "tit":"Activar pulsares por simetría inversa correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }')
    --
  ;
  --
  -- Tablero : seccion + posicion + valores + seleccion
  DELETE FROM `_api`.`app_var` WHERE `esq`='hol' AND `dat` = 'tab'
  ;
  -- por secciones
  DELETE FROM `_api`.`app_var` WHERE `esq`='hol' AND `dat` = 'tab' AND `val` = 'sec';
  INSERT INTO `_api`.`app_var` VALUES
    
    ('hol','tab','sec', 50, 'par', '{
      "nom":"¿Oráculo?",
      "tit":"Activa el Oráculo...",
      "ope":{ "_tip":"opc_bin" }
      }')
    ,
    ('hol','tab','sec', 70, 'rad', '{
      "nom":"¿Plasma?",
      "tit":"Mostrar Plasmas...",
      "ope":{ "_tip":"opc_bin" }
      }')
    ,
    ('hol','tab','sec', 130, 'ton', '{
        "nom":"¿Tonos Galácticos?",
        "tit":"Mostrar los Tonos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 131, 'ton-ond', '{
        "nom":"¿Posiciones de O.E.?",
        "tit":"Mostrar las posiciones de la Onda Encantada...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 132, 'ton-col', '{
        "nom":"¿Fondo de O.E.?",
        "tit":"Colorear el Fondo de las Ondas Encantadas...",
        "ope":{ "_tip":"opc_bin" }
      }')
    ,
    ('hol','tab','sec', 200, 'sel', '{
        "nom":"¿Sellos Solares?",
        "tit":"Mostrar los Sellos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 210, 'sel-arm', '{
          "nom":"¿Armónica?",
          "tit":"",
          "ope":{}
        }'),
        ('hol','tab','sec', 211, 'sel-arm_tra-pos', '{
          "nom":"¿Posición de T.A.?",
          "tit":"Mostrar las posiciones de las 13 Trayectorias Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 212, 'sel-arm_tra-bor', '{
          "nom":"¿Borde de T.A.?",
          "tit":"Remarcar las bordes de las 13 Trayectorias Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 213, 'sel-arm_tra-col', '{
          "nom":"Fondo de T.A.?",
          "tit":"Colorear las fondos de las 13 Trayectorias Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 214, 'sel-arm_cel-pos', '{
          "nom":"¿Posición de C.A.?",
          "tit":"Mostrar las posiciones de las 65 Células Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 215, 'sel-arm_cel-bor', '{
          "nom":"¿Borde de C.A.?",
          "tit":"Remarcar las 65 Células Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 216, 'sel-arm_cel-col', '{
          "nom":"¿Fondo de C.A.?",
          "tit":"Colorear el fondo de las 65 Células Armónicas...",
          "ope":{ "_tip":"opc_bin" }
        }')
      ,      
      ('hol','tab','sec', 220, 'sel-cro', '{
          "nom":"¿Cromática?",
          "tit":"",
          "ope":{}
        }'),
        ('hol','tab','sec', 221, 'sel-cro_est-pos', '{
          "nom":"¿Posición del E.E.?",
          "tit":"Mostrar las posiciones de las 4 Estaciones Espectrales...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 222, 'sel-cro_est-bor', '{
          "nom":"¿Borde de E.E.?",
          "tit":"Remarcar las bordes de las 4 Estaciones Espectrales...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 223, 'sel-cro_est-col', '{
          "nom":"Fondo de E.E.?",
          "tit":"Colorear las fondos de las 4 Estaciones Espectrales...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 224, 'sel-cro_ele-pos', '{
          "nom":"¿Posición de E.C.?",
          "tit":"Mostrar las posiciones de los 52 Elementos Cromáticos...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 225, 'sel-cro_ele-bor', '{
          "nom":"¿Borde de E.C.?",
          "tit":"Remarcar los 52 Elementos Cromáticos...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('hol','tab','sec', 226, 'sel-cro_ele-col', '{
          "nom":"¿Fondo de E.C.?",
          "tit":"Colorear el fondo de los 52 Elementos Cromáticos...",
          "ope":{ "_tip":"opc_bin" }
        }')
    ,
    ('hol','tab','sec', 280, 'lun', '{
        "nom":"¿Luna?",
        "tit":"Mostrar Posiciones de la Luna...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 282, 'lun-cab', '{
        "nom":"¿Giro Lunar?",
        "tit":"Mostrar los datos de cabecera para el Giro Luna...",
        "ope":{ "_tip":"opc_bin" }
      }'),      
      ('hol','tab','sec', 283, 'lun-hep', '{
        "nom":"¿Héptada?",
        "tit":"Mostrar las posiciones de las Héptadas Lunares...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 284, 'lun-rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar las columnas de los Plasmas Radiales...",
        "ope":{ "_tip":"opc_bin" }
      }')
    ,
    ('hol','tab','sec', 520, 'cas', '{
        "nom":"¿Castillo?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 521, 'cas-pos', '{
        "nom":"¿Posición del C.D.?",
        "tit":"Mostrar las posiciones del Castillo del Destino...",
        "ope":{ "_tip":"opc_bin" }
      }'),      
      ('hol','tab','sec', 522, 'cas-bor', '{
        "nom":"¿Borde del C.D.?",
        "tit":"Remarcar el Borde del Castillo del Destino...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 523, 'cas-col', '{
        "nom":"¿Fondo del C.D.?",
        "tit":"Colorear el Fondo del Castillo del Destino...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 524, 'cas-orb', '{
        "nom":"¿Orbitales del C.D.?",
        "tit":"Activar los Orbitales del Castillo del Destino...",
        "ope":{ "_tip":"opc_bin" }
      }')
    ,
    ('hol','tab','sec', 2600, 'kin', '{
        "nom":"¿?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 2610, 'kin-sel', '{
        "nom":"¿Sellos Solares?",
        "tit":"Mostrar Columna de Sellos Solares como los 20 katunes del Baktún",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 2620, 'kin-ton', '{
        "nom":"¿Tonos Galácticos?",
        "tit":"Mostrar Cabecera de Tonos Galácticos como las 13 Trayectorias Armónicas - Baktunes",
        "ope":{ "_tip":"opc_bin" }
      }')
  ;
  -- por opciones
  DELETE FROM `_api`.`app_var` WHERE `esq`='hol' AND `dat` = 'tab' AND `val` = 'atr';
  INSERT INTO `_api`.`app_var` VALUES

    ('hol','tab','atr', 1, 'pag', '{
      "nom":"Portales de Activación"
    }'),
    ('hol','tab','atr', 2, 'par', '{
      "nom":"Parejas del Oráculo"
    }'),
    ('hol','tab','atr', 3, 'pul', '{
      "nom":"Pulsares de la Onda Encantada"
    }')
  ;