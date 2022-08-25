
DELETE FROM `_api`.`var` WHERE `esq`='hol';
--
  -- Valores : acumulados + sumatorias + atributos
  DELETE FROM `_api`.`var` WHERE `esq`='hol' AND `dat` = 'val';
  INSERT INTO `_api`.`var` VALUES 
    --
    -- sumatoria
      ('hol','val','sum', 1, 'kin', '{ 
        "atr":"ide",
        "nom":"KIN",    
        "ope":{ "_tip":"num", "val":0, "max":260 },
        "var_fic":"hol.kin"
      }'),
      ('hol','val','sum', 2, 'psi', '{ 
        "atr":"tzo",
        "nom":"PSI",    
        "ope":{ "_tip":"num", "val":0, "max":365 },
        "var_fic":"hol.psi"
      }'),  
      ('hol','val','sum', 3, 'umb', '{ 
        "atr":"ide",
        "nom":"UMB",    
        "ope":{ "_tip":"num", "val":0, "max":441 }
      }'),
    --
    -- listado de atributos por posicion
      ('hol','val','pos', 1, 'par', '{
        "nom":"Parejas del Oráculo"
      }'),
      ('hol','val','pos', 2, 'pul', '{
        "nom":"Pulsares de la Onda Encantada"
      }')  

  ;
  --
  -- tableros : seccion + posicion + valores + seleccion
  DELETE FROM `_api`.`var` WHERE `esq`='hol' AND `dat` = 'tab';
  INSERT INTO `_api`.`var` VALUES
    --
    -- secciones

      ('hol','tab','sec', 1, 'par', '{
        "nom":"¿Oráculo?",
        "tit":"Activa el Oráculo...",
        "ope":{ "_tip":"opc_bin" }
      }'),        
      ('hol','tab','sec', 11, 'arm', '{
        "nom":"¿Armónica?",
        "tit":"Mostrar las Armónicas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 21, 'cro', '{
        "nom":"¿Cromática?",
        "tit":"Mostrar las Cromáticas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 31, 'rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar Plasmas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 41, 'ton', '{
        "nom":"¿Tonos?",
        "tit":"Mostrar los Tonos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 51, 'sel', '{
        "nom":"¿Sellos?",
        "tit":"Mostrar los Sellos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 61, 'ond', '{
        "nom":"¿Onda?",
        "tit":"Mostrar la Onda Encantada...",
        "ope":{ "_tip":"opc_bin" }
      }'),  
      ('hol','tab','sec', 71, 'cas', '{
        "nom":"¿Castillo?",
        "tit":"Mostrar posiciones del Castillo...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 81, 'orb', '{
        "nom":"¿Orbitales?",
        "tit":"Activar Orbitales...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 91, 'lun', '{
        "nom":"¿Luna?",
        "tit":"Mostrar Posiciones de la Luna...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('hol','tab','sec', 101, 'hep', '{
        "nom":"¿Heptada?",
        "tit":"Mostrar Posiciones de la Héptada...",
        "ope":{ "_tip":"opc_bin" }
      }')
    --
    -- posiciones
    --  
  ;
  --
  -- Posición : oraculo + pulsares
  DELETE FROM `_api`.`var` WHERE `esq`='hol' AND `dat` = 'pos';
  INSERT INTO `_api`.`var` VALUES

    --
    -- parejas del oráculo ( kin + sel )

      ('hol','pos','par', 1, 'cue', '{
        "nom":"Total",
        "tit":"Cantidad total seleccionada...",
        "ope":{ "_tip":"num", "val":0 }
      }'),  
      ('hol','pos','par', 2, 'ana', '{
        "nom":"¿Análogo?",
        "tit":"Activa la Pareja Análoga que Refuerza al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','pos','par', 3, 'gui', '{
        "nom":"¿Guía?",
        "tit":"Activa la Pareja Guía que Orienta al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','pos','par', 4, 'ant', '{
        "nom":"¿Antípoda?",
        "tit":"Activa la Pareja Antípoda que se Opone al Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','pos','par', 5, 'ocu', '{
        "nom":"¿Oculto?",
        "tit":"Activa la Pareja Oculta que Revela el Destino...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','pos','par', 6, 'ext', '{
        "nom":"¿Extender Patrones?",
        "tit":"Extender las parejas seleccionadas para la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),

    --
    -- pulsares de la o.e. ( ton + cas )

      ('hol','pos','pul', 1, 'dim', '{
        "nom":"¿Dimensional?",
        "tit":"Activar pulsares dimensionales de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),
      ('hol','pos','pul', 2, 'mat', '{
        "nom":"¿Matiz?",
        "tit":"Activar pulsares matiz de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }'),  
      ('hol','pos','pul', 3, 'sim', '{
        "nom":"¿Simetría Inversa?",
        "tit":"Activar pulsares por simetría inversa correspondiente a la posición principal...",
        "ope":{ "_tip":"opc_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "_let":"(" }, { "_tip":"num", "val":"0" }, { "_let":")" } ] }
      }')
      
    --
  ;