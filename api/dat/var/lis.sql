--
-- Listado
  DELETE FROM `dat_var` WHERE `esq`='lia'
  ;
  -- Operador : ABM + Acumulados + Filtros
  DELETE FROM `dat_var` WHERE `esq`='lis' AND `dat`='ope'; INSERT INTO `dat_var` VALUES 
    -- 
    -- abm : contar, ver, agregar, modificar, eliminar      
      ('lis','ope','abm', 1, 'act', '{
        "ico":"dat_act",
        "tit":"Actualizar..."        
      }'),
      ('lis','ope','abm', 2, 'ver', '{
        "ico":"dat_ver",
        "tit":"Buscar..."
      }'),    
      ('lis','ope','abm', 3, 'agr', '{
        "ico":"dat_agr",
        "tit":"Agregar..."
      }'),
      ('lis','ope','abm', 4, 'mod', '{
        "ico":"dat_mod",
        "tit":"Modificar..."
      }'),
      ('lis','ope','abm', 5, 'eli', '{
        "ico":"dat_eli",
        "tit":"Eliminar..."
      }'),    
    --
    -- Acumulados : posicion, marcas, seleccion
      ('lis','ope','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "tip":"opc_bin" }
      }'),    
      ('lis','ope','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','ope','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','ope','acu', 4, 'opc', '{
        "nom":"¿Opciones?",
        "tit":"Contar las posiciones marcadas por opciones del tablero...",
        "ope":{ "tip":"opc_bin" }
      }'),
    --
    -- Filtro: valores ( todos ) + datos ( estructura > valor ) + listados ( posicion + fecha )
      -- por Valores
        ('lis','ope','ver', 1, 'tot', '{
          "nom":"Cantidad",
          "tit":"",
          "ope":{ "tip":"num", "val":"0" }
        }'),         
        ('lis','ope','ver', 2, 'tod', '{
          "nom":"¿Todos?",
          "tit":"Mostrar todos...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('lis','ope','ver', 3, 'nad', '{
          "nom":"¿Nada?",
          "tit":"Ocultar todos...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('lis','ope','ver', 4, 'uni', '{
          "nom":"¿Único?",
          "tit":"Mostrar un único valor...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('lis','ope','ver', 5, 'inc', '{
          "nom":"¿Cada?",
          "tit":"Indica un valor de salto o incremento entre las posiciones...",
          "ope":{ "tip":"num_int", "val":1, "min":1, "max":999 }
        }'),
        ('lis','ope','ver', 6, 'lim', '{
          "nom":"¿Cuántos?",
          "tit":"Indicar la cantidad máxima de resultados...",
          "ope":{ "tip":"num_int", "min":1, "max":999 }
        }'),
      -- por tipo de datos
        ('lis','ope','ver', 11, 'opc', '{
          "nom":"¿Opciones?",
          "tit":"Mostrar valores por opción...",
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
        ('lis','ope','ver', 12, 'num', '{
          "nom":"¿Números?",
          "tit":"Mostrar valores numéricos...",
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
        ('lis','ope','ver', 13, 'tex', '{
          "nom":"¿Textos?",
          "tit":"Mostrar valores textuales...",
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
        ('lis','ope','ver', 14, 'fec', '{
          "nom":"¿Fechas?",
          "tit":"Mostrar valores de fechas...",
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
        ('lis','ope','ver', 15, 'obj', '{
          "nom":"¿Objetos?",
          "tit":"Mostrar objetos...",
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
        ('lis','ope','ver', 16, 'arc', '{
          "nom":"¿Archivos?",
          "tit":"Mostrar archivos...",        
          "ope":{ "tip":"opc_bin", "val":1 }
        }'),
      -- por Estructuras de Datos
        ('lis','ope','ver', 21, 'dat', '{
          "ico":"dat_ver",
          "tit":"Seleccionar la Estructura e indicar el Valor Buscado..."
        }'),
        ('lis','ope','ver', 22, 'esq', '{
          "nom":"Esquema",
          "tit":"Indicar el Esquema de datos..."
        }'),
        ('lis','ope','ver', 23, 'est', '{
          "nom":"Estructura",
          "tit":"Indicar la Estructura de datos..."
        }'),
        ('lis','ope','ver', 24, 'atr', '{
          "nom":"Atributo",
          "tit":"Indicar el Atributo de la Estructura..."
        }'),
      -- por rango de valores : posicion / fecha
        ('lis','ope','ver', 31, 'pos', '{
          "nom":"Posición",
          "tit":"Indicar el valor de la posición buscada...",
          "ope":{ "tip":"num_int", "min":1, "max":999 }
        }'),
        ('lis','ope','ver', 32, 'ini', '{
          "nom":"Desde",
          "tit":"Indicar el Valor inicial...",
          "ope":{ "tip":"" }
        }'),
        ('lis','ope','ver', 33, 'fin', '{
          "nom":"Hasta",
          "tit":"Indicar el Valor final...",
          "ope":{ "tip":"" }
        }')
    --    
  ;
  -- Estructura : Titulos + Descripciones
  DELETE FROM `dat_var` WHERE `esq`='lis' AND `dat`='est'; INSERT INTO `dat_var` VALUES
    --
    -- filtro por conjuntos
      ('lis','est','ver', 1, 'cic', '{
        "nom":"Secuencias",
        "tit":"Indicar la secuencia..."
      }'),
      ('lis','est','ver', 2, 'gru', '{
        "nom":"Clasificaciones",
        "tit":"Indicar la clasificacion..."
      }'),
      ('lis','est','ver', 3, 'des', '{
        "nom":"Lecturas",
        "tit":"Indicar el atributo descriptivo..."
      }'),
    --
    -- descripciones
      ('lis','est','des', 1, 'tit', '{  
        "nom":"¿Titulos?",
        "tit":"",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','est','des', 2, 'det', '{  
        "nom":"¿Detalles?",
        "tit":"",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','est','des', 3, 'lec', '{  
        "nom":"¿Lecturas?",
        "tit":"",
        "ope":{ "tip":"opc_bin" }
      }')
    --   
  ;
  -- Tablero : Secciones + Posiciones
  DELETE FROM `dat_var` WHERE `esq`='lis' AND `dat`='tab'; INSERT INTO `dat_var` VALUES
    --
    -- secciones : borde + imagen + color

      ('lis','tab','sec', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes Exteriores...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab','sec', 2, 'ima', '{ 
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "tip":"arc_ima", "class":"dis-ocu" }
      }'),
      ('lis','tab','sec', 3, 'col', '{
        "nom":"¿Fondo?",
        "tit":"Pintar los Fondos del Tablero...",
        "ope":{ "tip":"opc_bin" }
      }'),

    --
    -- posiciones : borde + color + imagen + numero + texto + fecha [ + variable ? ]
      ('lis','tab', 'pos', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 11, 'bor_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Borde exterior de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 12, 'bor_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Borde exterior de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 13, 'bor_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Borde exterior de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 14, 'bor_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el borde extearior en las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
      ('lis','tab', 'pos', 21, 'col_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Color de Fondo de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 22, 'col_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Color de Fondo de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 23, 'col_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Color de Fondo de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 24, 'col_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el Color de Fondo para las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 30, 'ima', '{
        "ide":"ima",
        "ico":"fig_ima",
        "nom":"Ficha",
        "tit":"Seleccionar un tipo de Ficha para las Posiciones..."
      }'),
      ('lis','tab', 'pos', 31, 'ima_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar la Ficha de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 32, 'ima_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar las Fichas de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 33, 'ima_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar las Fichas de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 34, 'ima_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar las Fichas de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 40, 'num', '{        
        "ide":"num",
        "ico":"num_cod",
        "nom":"Número",
        "tit":"Seleccionar un valor numérico para las Posiciones..."        
      }'),
      ('lis','tab', 'pos', 41, 'num_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor numérico de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 42, 'num_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor numérico de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 43, 'num_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor numérico de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 44, 'num_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor numérico de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 50, 'tex', '{
        "ide":"tex",
        "ico":"tex_cod",
        "nom":"Texto",
        "tit":"Seleccionar un valor textual para las Posiciones..."        
      }'),
      ('lis','tab', 'pos', 51, 'tex_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor textual de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 52, 'tex_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor textual de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 53, 'tex_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor textual de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 54, 'tex_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor textual de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 60, 'fec', '{
        "ide":"fec",
        "ico":"fec",
        "nom":"Fecha",
        "tit":"Seleccionar un valor temporal para las Posiciones..."        
      }'),
      ('lis','tab', 'pos', 61, 'fec_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor temporal de la posición principal...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 62, 'fec_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor temporal de las posiciones marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 63, 'fec_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor temporal de las posiciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('lis','tab', 'pos', 64, 'fec_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor temporal de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }')
  ;
--