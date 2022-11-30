--
-- Aplicacion
  DELETE FROM `dat_var` WHERE `esq`='app';
  -- Valores
  DELETE FROM `dat_var` WHERE `esq`='app' AND `dat`='val';
  INSERT INTO `dat_var` VALUES 
    -- 
    -- abm : contar, ver, agregar, modificar, eliminar      
      ('app','val','abm', 1, 'act', '{
        "ico":"dat_act",
        "tit":"Actualizar..."        
      }'),
      ('app','val','abm', 2, 'ver', '{
        "ico":"dat_ver",
        "tit":"Buscar..."
      }'),    
      ('app','val','abm', 3, 'agr', '{
        "ico":"dat_agr",
        "tit":"Agregar..."
      }'),
      ('app','val','abm', 4, 'mod', '{
        "ico":"dat_mod",
        "tit":"Modificar..."
      }'),
      ('app','val','abm', 5, 'eli', '{
        "ico":"dat_eli",
        "tit":"Eliminar..."
      }'),    
    --
    -- navegacion : 
      ('app','val','nav', 1, 'ini', '{    
        "nom":"",
        "tit":""
      }'),
      ('app','val','nav', 2, 'fin', '{    
        "nom":"",
        "tit":""
      }'),
      ('app','val','nav', 3, 'pre', '{    
        "nom":"",
        "tit":""
      }'),
      ('app','val','nav', 4, 'pos', '{    
        "nom":"",
        "tit":""
      }'),
    --
    -- acumulados : posicion, marcas, seleccion
      ('app','val','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),    
      ('app','val','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','val','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','val','acu', 4, 'opc', '{
        "nom":"¿Opciones?",
        "tit":"Contar las posiciones marcadas por opciones del tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),
    --
    -- filtro: valores ( todos ) + datos ( estructura > valor ) + listados ( posicion + fecha )

      -- por Valores
        ('app','val','ver', 1, 'tot', '{
          "nom":"Cantidad",
          "tit":"",
          "ope":{ "_tip":"num", "val":"0" }
        }'),         
        ('app','val','ver', 2, 'tod', '{
          "nom":"¿Todos?",
          "tit":"Mostrar todos...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('app','val','ver', 3, 'nad', '{
          "nom":"¿Nada?",
          "tit":"Ocultar todos...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('app','val','ver', 4, 'uni', '{
          "nom":"¿Único?",
          "tit":"Mostrar un único valor...",
          "ope":{ "_tip":"opc_bin" }
        }'),
        ('app','val','ver', 5, 'inc', '{
          "nom":"¿Cada?",
          "tit":"Indica un valor de salto o incremento entre las posiciones...",
          "ope":{ "_tip":"num_int", "val":1, "min":1, "max":999 }
        }'),
        ('app','val','ver', 6, 'lim', '{
          "nom":"¿Cuántos?",
          "tit":"Indicar la cantidad máxima de resultados...",
          "ope":{ "_tip":"num_int", "min":1, "max":999 }
        }'),
      -- por tipo de datos
        ('app','val','ver', 11, 'opc', '{
          "nom":"¿Opciones?",
          "tit":"Mostrar valores por opción...",
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
        ('app','val','ver', 12, 'num', '{
          "nom":"¿Números?",
          "tit":"Mostrar valores numéricos...",
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
        ('app','val','ver', 13, 'tex', '{
          "nom":"¿Textos?",
          "tit":"Mostrar valores textuales...",
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
        ('app','val','ver', 14, 'fec', '{
          "nom":"¿Fechas?",
          "tit":"Mostrar valores de fechas...",
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
        ('app','val','ver', 15, 'obj', '{
          "nom":"¿Objetos?",
          "tit":"Mostrar objetos...",
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
        ('app','val','ver', 16, 'arc', '{
          "nom":"¿Archivos?",
          "tit":"Mostrar archivos...",        
          "ope":{ "_tip":"opc_bin", "val":1 }
        }'),
      -- por Estructuras de Datos
        ('app','val','ver', 21, 'dat', '{
          "ico":"dat_ver",
          "tit":"Seleccionar la Estructura e indicar el Valor Buscado..."
        }'),
        ('app','val','ver', 22, 'esq', '{
          "nom":"Esquema",
          "tit":"Indicar el Esquema de datos..."
        }'),
        ('app','val','ver', 23, 'est', '{
          "nom":"Estructura",
          "tit":"Indicar la Estructura de datos..."
        }'),
        ('app','val','ver', 24, 'atr', '{
          "nom":"Atributo",
          "tit":"Indicar el Atributo de la Estructura..."
        }'),
      -- por rango de valores : posicion / fecha
        ('app','val','ver', 31, 'pos', '{
          "nom":"Posición",
          "tit":"Indicar el valor de la posición buscada...",
          "ope":{ "_tip":"num_int", "min":1, "max":999 }
        }'),
        ('app','val','ver', 32, 'ini', '{
          "nom":"Desde",
          "tit":"Indicar el Valor inicial...",
          "ope":{ "_tip":"" }
        }'),
        ('app','val','ver', 33, 'fin', '{
          "nom":"Hasta",
          "tit":"Indicar el Valor final...",
          "ope":{ "_tip":"" }
        }')
    --
  ;
  -- Estructura
  DELETE FROM `dat_var` WHERE `esq`='app' AND `dat`='est';
  INSERT INTO `dat_var` VALUES
    --
    -- filtro por conjuntos
      ('app','est','ver', 1, 'cic', '{
        "nom":"Secuencias",
        "tit":"Indicar la secuencia..."
      }'),
      ('app','est','ver', 2, 'gru', '{
        "nom":"Clasificaciones",
        "tit":"Indicar la clasificacion..."
      }'),
      ('app','est','ver', 3, 'des', '{
        "nom":"Lecturas",
        "tit":"Indicar el atributo descriptivo..."
      }'),
    --
    -- descripciones
      ('app','est','des', 1, 'tit', '{  
        "nom":"¿Titulos?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','est','des', 2, 'det', '{  
        "nom":"¿Detalles?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','est','des', 3, 'lec', '{  
        "nom":"¿Lecturas?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }')
    --   
  ;
  -- Tablero
  DELETE FROM `dat_var` WHERE `esq`='app' AND `dat`='tab';
  INSERT INTO `dat_var` VALUES
    --
    -- secciones : borde + imagen + color

      ('app','tab','sec', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes Exteriores...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab','sec', 2, 'ima', '{ 
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "_tip":"arc_ima", "class":"dis-ocu" }
      }'),
      ('app','tab','sec', 3, 'col', '{
        "nom":"¿Fondo?",
        "tit":"Pintar los Fondos del Tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),

    --
    -- posiciones : borde + color + imagen + numero + texto + fecha [ + variable ? ]
      ('app','tab', 'pos', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 11, 'bor_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Borde exterior de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 12, 'bor_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Borde exterior de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 13, 'bor_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Borde exterior de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 14, 'bor_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el borde extearior en las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
      ('app','tab', 'pos', 21, 'col_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Color de Fondo de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 22, 'col_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Color de Fondo de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 23, 'col_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Color de Fondo de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 24, 'col_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el Color de Fondo para las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 30, 'ima', '{
        "ide":"ima",
        "ico":"fig_ima",
        "nom":"Ficha",
        "tit":"Seleccionar un tipo de Ficha para las Posiciones..."
      }'),
      ('app','tab', 'pos', 31, 'ima_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar la Ficha de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 32, 'ima_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar las Fichas de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 33, 'ima_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar las Fichas de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 34, 'ima_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar las Fichas de las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 40, 'num', '{        
        "ide":"num",
        "ico":"num_cod",
        "nom":"Número",
        "tit":"Seleccionar un valor numérico para las Posiciones..."        
      }'),
      ('app','tab', 'pos', 41, 'num_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor numérico de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 42, 'num_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor numérico de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 43, 'num_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor numérico de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 44, 'num_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor numérico de las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 50, 'tex', '{
        "ide":"tex",
        "ico":"tex_cod",
        "nom":"Texto",
        "tit":"Seleccionar un valor textual para las Posiciones..."        
      }'),
      ('app','tab', 'pos', 51, 'tex_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor textual de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 52, 'tex_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor textual de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 53, 'tex_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor textual de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 54, 'tex_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor textual de las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 60, 'fec', '{
        "ide":"fec",
        "ico":"fec",
        "nom":"Fecha",
        "tit":"Seleccionar un valor temporal para las Posiciones..."        
      }'),
      ('app','tab', 'pos', 61, 'fec_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor temporal de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 62, 'fec_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor temporal de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 63, 'fec_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor temporal de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('app','tab', 'pos', 64, 'fec_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor temporal de las posiciones por las opciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }')
  ;
--
-- Holon
  DELETE FROM `dat_var` WHERE `esq`='hol';
  -- Valores
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat` = 'val';
  INSERT INTO `dat_var` VALUES

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
      }')
    --
  ;
  -- Atributos : oraculo + pulsares
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat` = 'atr';
  INSERT INTO `dat_var` VALUES

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
  -- Tablero : seccion + posicion + valores + seleccion
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat` = 'tab'
  ;-- por secciones
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat` = 'tab' AND `val` = 'sec';
  INSERT INTO `dat_var` VALUES
    
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
  ;-- por opciones
  DELETE FROM `dat_var` WHERE `esq`='hol' AND `dat` = 'tab' AND `val` = 'atr';
  INSERT INTO `dat_var` VALUES

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
-- 