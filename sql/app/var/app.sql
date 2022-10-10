-- Active: 1663730672989@@127.0.0.1@3306@_api

DELETE FROM `api`.`app_var` WHERE `esq`='app';
--
  -- Valores
  DELETE FROM `api`.`app_var` WHERE `esq`='app' AND `dat`='val';
  INSERT INTO `api`.`app_var` VALUES 
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
  DELETE FROM `api`.`app_var` WHERE `esq`='app' AND `dat`='est';
  INSERT INTO `api`.`app_var` VALUES
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
  DELETE FROM `api`.`app_var` WHERE `esq`='app' AND `dat`='tab';
  INSERT INTO `api`.`app_var` VALUES
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
        "ico":"arc_ima",
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