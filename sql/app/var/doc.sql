-- Active: 1623270923336@@127.0.0.1@3306@_api

DELETE FROM `_api`.`var` WHERE `esq`='doc';
--
  -- Enlaces
  DELETE FROM `_api`.`var` WHERE `esq`='doc' AND `dat`='nav';
  INSERT INTO `_api`.`var` VALUES 

    ('doc','nav','val', 1, 'ini', '{    
      "nom":"",
      "tit":""
    }'),
    ('doc','nav','val', 2, 'fin', '{    
      "nom":"",
      "tit":""
    }'),
    ('doc','nav','val', 3, 'pre', '{    
      "nom":"",
      "tit":""
    }'),
    ('doc','nav','val', 4, 'pos', '{    
      "nom":"",
      "tit":""
    }')  
  ;
  -- Valores : datos de la base + acumulados por seleccion + filtro de listas ( dato, posicion, fecha )
  DELETE FROM `_api`.`var` WHERE `esq`='doc' AND `dat`='val';
  INSERT INTO `_api`.`var` VALUES 
    -- 
    -- datos : contar, ver, agregar, modificar, eliminar
      ('doc','val','dat', 1, 'cue', '{
        "nom":"Cantidad",
        "tit":"",
        "ope":{ "_tip":"num", "val":"0" }
      }'),
      ('doc','val','dat', 2, 'ver', '{
        "ico":"dat_ver",
        "tit":"Buscar..."
      }'),    
      ('doc','val','dat', 3, 'agr', '{    
        "ico":"dat_agr",
        "tit":"Agregar..."
      }'),
      ('doc','val','dat', 4, 'mod', '{
        "ico":"dat_mod",
        "tit":"Modificar..."
      }'),
      ('doc','val','dat', 5, 'eli', '{
        "ico":"dat_eli",
        "tit":"Eliminar..."
      }'),    
    --
    -- acumulados : posicion, marcas, seleccion
      ('doc','val','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),    
      ('doc','val','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','val','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
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
        "tit":"Indicar el Registro de la Estructura..."
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
  -- estructuras
  DELETE FROM `_api`.`var` WHERE `esq`='doc' AND `dat`='est';
  INSERT INTO `_api`.`var` VALUES

    --
    -- datos
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
  ;
  -- tableros
  DELETE FROM `_api`.`var` WHERE `esq`='doc' AND `dat`='tab';
  INSERT INTO `_api`.`var` VALUES
    --
    -- secciones
      ('doc','tab','sec', 1, 'ima', '{ 
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "_tip":"arc_ima", "class":"dis-ocu" }
      }'),
      ('doc','tab','sec', 2, 'col', '{
        "nom":"¿Color?",
        "tit":"Pintar los Colores de Fondos del Tablero...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab','sec', 3, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes de las Posiciones...",
        "ope":{ "_tip":"opc_bin" }
      }'),

    --
    -- posiciones
      ('doc','tab', 'pos', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "_tip":"opc_bin" }    
      }'),
      ('doc','tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 30, 'num', '{        
        "ide":"num",
        "ico":"num_cod",
        "nom":"Número",
        "tit":"Seleccionar por valor numérico para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 40, 'tex', '{
        "ide":"tex",
        "ico":"tex_cod",
        "nom":"Texto",
        "tit":"Seleccionar por valor textual para las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 50, 'ima', '{
        "ide":"ima",
        "ico":"arc_ima",
        "nom":"Ficha",
        "tit":"Seleccionar Ficha de las Posiciones..."        
      }'),
      ('doc','tab', 'pos', 51, 'ima_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar la posición principal...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 52, 'ima_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar las posiciones marcadas...",
        "ope":{ "_tip":"opc_bin" }
      }'),
      ('doc','tab', 'pos', 53, 'ima_sel', '{
        "nom":"¿Selección?",
        "tit":"Mostrar las posiciones seleccionadas...",
        "ope":{ "_tip":"opc_bin" }
      }')  
  ;