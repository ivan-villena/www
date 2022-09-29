-- Active: 1663730672989@@127.0.0.1@3306@_api

DELETE FROM `_api`.`app_var` WHERE `esq`='doc';
--
  -- Contenedores
  DELETE FROM `_api`.`app_var` WHERE `esq`='doc' AND `dat`='val';
  INSERT INTO `_api`.`app_var` VALUES 

    -- navegacion
      ('doc','val','nav', 1, 'ini', '{    
        "nom":"",
        "tit":""
      }'),
      ('doc','val','nav', 2, 'fin', '{    
        "nom":"",
        "tit":""
      }'),
      ('doc','val','nav', 3, 'pre', '{    
        "nom":"",
        "tit":""
      }'),
      ('doc','val','nav', 4, 'pos', '{    
        "nom":"",
        "tit":""
      }'),
    --    
    -- filtros: valores ( todos ) + datos ( estructura > valor ) + listados ( posicion + fecha )

      -- por Valores
      ('doc','val','ver', 1, 'tod', '{
        "nom":"¿Todos?",
        "tit":"Mostrar todos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','val','ver', 2, 'nad', '{
        "nom":"¿Nada?",
        "tit":"Ocultar todos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','val','ver', 3, 'uni', '{
        "nom":"¿Único?",
        "tit":"Mostrar un único valor...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','val','ver', 4, 'inc', '{
        "nom":"¿Cada?",
        "tit":"Indica un valor de salto o incremento entre las posiciones...",
        "ope":{ "_tip":"num_int", "val":1, "min":1, "max":999 }
      }'),
      ('doc','val','ver', 5, 'cue', '{
        "nom":"¿Cuántos?",
        "tit":"Indicar la cantidad máxima del resultado...",
        "ope":{ "_tip":"num_int", "min":1, "max":999 }
      }'),
      -- por tipo de datos
      ('doc','val','ver', 11, 'opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar valores por opción...",
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      ('doc','val','ver', 12, 'num', '{
        "nom":"¿Números?",
        "tit":"Mostrar valores numéricos...",
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      ('doc','val','ver', 13, 'tex', '{
        "nom":"¿Textos?",
        "tit":"Mostrar valores textuales...",
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      ('doc','val','ver', 14, 'fec', '{
        "nom":"¿Fechas?",
        "tit":"Mostrar valores de fechas...",
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      ('doc','val','ver', 15, 'obj', '{
        "nom":"¿Objetos?",
        "tit":"Mostrar objetos...",
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      ('doc','val','ver', 16, 'arc', '{
        "nom":"¿Archivos?",
        "tit":"Mostrar archivos...",        
        "ope":{ "_tip":"opc_bin", "val":1 }
      }'),
      -- por Estructuras de Datos
      ('doc','val','ver', 21, 'dat', '{
        "ico":"dat_ver",
        "tit":"Seleccionar la Estructura e indicar el Valor Buscado..."
      }'),
      ('doc','val','ver', 22, 'esq', '{
        "nom":"Esquema",
        "tit":"Indicar el Esquema de datos..."
      }'),
      ('doc','val','ver', 23, 'est', '{
        "nom":"Estructura",
        "tit":"Indicar la Estructura de datos..."
      }'),
      ('doc','val','ver', 24, 'atr', '{
        "nom":"Atributo",
        "tit":"Indicar el Atributo de la Estructura..."
      }'),
      ('doc','val','ver', 25, 'val', '{
        "nom":"Valor",
        "tit":"Indicar un Valor para la Estructura Seleccionada..."
      }'),
      -- por rango de valores : posicion / fecha
      ('doc','val','ver', 31, 'pos', '{
        "nom":"Posición",
        "tit":"Indicar el valor de la posición buscada...",
        "ope":{ "_tip":"num_int", "min":1, "max":999 }
      }'),
      ('doc','val','ver', 32, 'ini', '{
        "nom":"Desde",
        "tit":"Indicar el Valor inicial...",
        "ope":{ "_tip":"" }
      }'),
      ('doc','val','ver', 33, 'fin', '{
        "nom":"Hasta",
        "tit":"Indicar el Valor final...",
        "ope":{ "_tip":"" }
      }')
    --
  ;
  -- Datos
  DELETE FROM `_api`.`app_var` WHERE `esq`='doc' AND `dat`='dat';
  INSERT INTO `_api`.`app_var` VALUES 
    -- 
    -- abm : contar, ver, agregar, modificar, eliminar      
      ('doc','dat','val', 1, 'cue', '{
        "nom":"Cantidad",
        "tit":"",
        "ope":{ "_tip":"num", "val":"0" }
      }'),
      ('doc','dat','val', 2, 'ver', '{
        "ico":"dat_ver",
        "tit":"Buscar..."
      }'),    
      ('doc','dat','val', 3, 'agr', '{    
        "ico":"dat_agr",
        "tit":"Agregar..."
      }'),
      ('doc','dat','val', 4, 'mod', '{
        "ico":"dat_mod",
        "tit":"Modificar..."
      }'),
      ('doc','dat','val', 5, 'eli', '{
        "ico":"dat_eli",
        "tit":"Eliminar..."
      }'),    
    --
    -- filtros
      ('doc','dat','ver', 1, 'cue', '{
        "nom":"Cantidad",
        "tit":"",
        "ope":{ "_tip":"num", "val":"0" }
      }'),    
      ('doc','dat','ver', 2, 'tod', '{
        "nom":"¿Todos?",
        "tit":"Mostrar todos...",
        "ope":{ "_tip":"opc_bin" }
      }'),
    --
    -- acumulados : posicion, marcas, seleccion
      ('doc','dat','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),    
      ('doc','dat','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','dat','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }')
    --
  ;
  -- Estructura
  DELETE FROM `_api`.`app_var` WHERE `esq`='doc' AND `dat`='est';
  INSERT INTO `_api`.`app_var` VALUES
    --
    -- filtro por conjuntos
      ('doc','est','ver', 1, 'cic', '{
        "nom":"Secuencias",
        "tit":"Indicar la secuencia..."
      }'),
      ('doc','est','ver', 2, 'gru', '{
        "nom":"Clasificaciones",
        "tit":"Indicar la clasificacion..."
      }'),
      ('doc','est','ver', 3, 'des', '{
        "nom":"Lecturas",
        "tit":"Indicar el atributo descriptivo..."
      }'),
    --
    -- descripciones
      ('doc','est','des', 1, 'tit', '{  
        "nom":"¿Titulos?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','est','des', 2, 'det', '{  
        "nom":"¿Detalles?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','est','des', 3, 'lec', '{  
        "nom":"¿Lecturas?",
        "tit":"",
        "ope":{ "_tip":"opc_bin" }
      }')
    --   
  ;
  -- Tablero
  DELETE FROM `_api`.`app_var` WHERE `esq`='doc' AND `dat`='tab';
  INSERT INTO `_api`.`app_var` VALUES
    --
    -- secciones : borde + imagen + color

      ('doc','tab','sec', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes Exteriores...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab','sec', 2, 'ima', '{ 
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "_tip":"arc_ima", "class":"dis-ocu" }
      }'),
      ('doc','tab','sec', 3, 'col', '{
        "nom":"¿Fondo?",
        "tit":"Pintar los Fondos del Tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),

    --
    -- posiciones : borde + color + imagen + numero + texto + fecha [ + variable ? ]
      ('doc','tab', 'pos', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 11, 'bor_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Borde exterior de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 12, 'bor_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Borde exterior de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 13, 'bor_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Borde exterior de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 21, 'col_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Color de Fondo de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 22, 'col_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Color de Fondo de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 23, 'col_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Color de Fondo de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 30, 'ima', '{
        "ide":"ima",
        "ico":"arc_ima",
        "nom":"Ficha",
        "tit":"Seleccionar un tipo de Ficha para las Posiciones..."
      }'),
      ('doc','tab', 'pos', 31, 'ima_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar la Ficha de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 32, 'ima_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar las Fichas de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 33, 'ima_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar las Fichas de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 40, 'num', '{        
        "ide":"num",
        "ico":"num_cod",
        "nom":"Número",
        "tit":"Seleccionar un valor numérico para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 41, 'num_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor numérico de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 42, 'num_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar  el valor numérico de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 43, 'num_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar  el valor numérico de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 50, 'tex', '{
        "ide":"tex",
        "ico":"tex_cod",
        "nom":"Texto",
        "tit":"Seleccionar un valor textual para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 51, 'tex_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor textual de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 52, 'tex_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar  el valor textual de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 53, 'tex_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar  el valor textual de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 60, 'fec', '{
        "ide":"fec",
        "ico":"fec",
        "nom":"Fecha",
        "tit":"Seleccionar un valor temporal para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 61, 'fec_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor temporal de la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 62, 'fec_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar  el valor temporal de las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 63, 'fec_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar  el valor temporal de las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }')
  ;