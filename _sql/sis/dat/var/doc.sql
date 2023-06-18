
-- Documento
DELETE FROM `sis-dat_var` WHERE `app` = 'doc'
;

-- Datos:
DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` LIKE 'dat_%';
  
  --
  -- Valores : ABM + Acumulados + Filtros + Cuentas
  DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_val';

    -- ABM : contar, ver, agregar, modificar, eliminar
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_val' AND `est` = 'abm'; INSERT INTO `sis-dat_var` VALUES     
      ('doc','dat_val','abm', 1, 'act', '{
        "ico":"val-act",
        "tit":"Actualizar..."        
      }'),
      ('doc','dat_val','abm', 2, 'ver', '{
        "ico":"val-ver",
        "tit":"Buscar..."
      }'),    
      ('doc','dat_val','abm', 3, 'agr', '{
        "ico":"val-agr",
        "tit":"Agregar..."
      }'),
      ('doc','dat_val','abm', 4, 'mod', '{
        "ico":"val-mod",
        "tit":"Modificar..."
      }'),
      ('doc','dat_val','abm', 5, 'eli', '{
        "ico":"val-eli",
        "tit":"Eliminar..."
      }')  
    ;
    -- Acumulados : posicion, marcas, seleccion
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_val' AND `est` = 'acu'; INSERT INTO `sis-dat_var` VALUES
      ('doc','dat_val','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "tip":"opc_bin" }
      }'),    
      ('doc','dat_val','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_val','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_val','acu', 4, 'atr', '{
        "nom":"¿Atributos?",
        "tit":"Activar las Posiciones por Selección de atributos...",
        "ope":{ "tip":"opc_bin" }
      }')
    ;
    -- Filtro: valores ( todos ) + datos ( estructura > valor ) + listados ( posicion + fecha )
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_val' AND `est` = 'ver'; INSERT INTO `sis-dat_var` VALUES         
      -- Opciones
      ('doc','dat_val','ver', 1, 'tot', '{
        "nom":"Cantidad",
        "tit":"",
        "ope":{ "tip":"num", "val":"0" }
      }'),         
      ('doc','dat_val','ver', 2, 'tod', '{
        "nom":"¿Todos?",
        "tit":"Mostrar todos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_val','ver', 3, 'nad', '{
        "nom":"¿Nada?",
        "tit":"Ocultar todos...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_val','ver', 4, 'uni', '{
        "nom":"¿Único?",
        "tit":"Mostrar un único valor...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_val','ver', 5, 'inc', '{
        "nom":"¿Cada?",
        "tit":"Indica un valor de salto o incremento entre las posiciones...",
        "ope":{ "tip":"num_int", "val":1, "min":1, "max":999 }
      }'),
      ('doc','dat_val','ver', 6, 'lim', '{
        "nom":"¿Cuántos?",
        "tit":"Indicar la cantidad máxima de resultados...",
        "ope":{ "tip":"num_int", "min":1, "max":999 }
      }'),
      -- Valores Variables
      ('doc','dat_val','ver', 11, 'opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar valores por opción...",
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      ('doc','dat_val','ver', 12, 'num', '{
        "nom":"¿Números?",
        "tit":"Mostrar valores numéricos...",
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      ('doc','dat_val','ver', 13, 'tex', '{
        "nom":"¿Textos?",
        "tit":"Mostrar valores textuales...",
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      ('doc','dat_val','ver', 14, 'fec', '{
        "nom":"¿Fechas?",
        "tit":"Mostrar valores de fechas...",
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      ('doc','dat_val','ver', 15, 'obj', '{
        "nom":"¿Objetos?",
        "tit":"Mostrar objetos...",
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      ('doc','dat_val','ver', 16, 'arc', '{
        "nom":"¿Archivos?",
        "tit":"Mostrar archivos...",        
        "ope":{ "tip":"opc_bin", "val":1 }
      }'),
      -- Estructuras de Datos
      ('doc','dat_val','ver', 21, 'dat', '{
        "ico":"val-ver",
        "tit":"Seleccionar la Estructura e indicar el Valor Buscado..."
      }'),
      ('doc','dat_val','ver', 22, 'esq', '{
        "nom":"Esquema",
        "tit":"Indicar el Esquema de datos..."
      }'),
      ('doc','dat_val','ver', 23, 'est', '{
        "nom":"Estructura",
        "tit":"Indicar la Estructura de datos..."
      }'),
      ('doc','dat_val','ver', 24, 'atr', '{
        "nom":"Atributo",
        "tit":"Indicar el Atributo de la Estructura..."
      }'),
      -- Lista de valores ( numerico/fecha ) : desde-hasta, cada, cuantos
      ('doc','dat_val','ver', 31, 'pos', '{
        "nom":"Posición",
        "tit":"Indicar el valor de la posición buscada...",
        "ope":{ "tip":"num_int", "min":1, "max":999 }
      }'),
      ('doc','dat_val','ver', 32, 'ini', '{
        "nom":"Desde",
        "tit":"Indicar el Valor inicial...",
        "ope":{ "tip":"" }
      }'),
      ('doc','dat_val','ver', 33, 'fin', '{
        "nom":"Hasta",
        "tit":"Indicar el Valor final...",
        "ope":{ "tip":"" }
      }')
    ;
  --
  
  --
  -- Listado por Tabla : Titulos + Descripciones
  DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_lis';

    -- filtros: Secuencias, grupos, lecturas
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_lis' AND `est` = 'ver'; INSERT INTO `sis-dat_var` VALUES

      ('doc','dat_lis','ver', 1, 'cic', '{
        "nom":"Secuencias",
        "tit":"Indicar la secuencia..."
      }'),
      ('doc','dat_lis','ver', 2, 'gru', '{
        "nom":"Clasificaciones",
        "tit":"Indicar la clasificacion..."
      }'),
      ('doc','dat_lis','ver', 3, 'des', '{
        "nom":"Lecturas",
        "tit":"Indicar el atributo descriptivo..."
      }')
    ;

    -- Descripciones: Titulo, Detalle, Lecturas
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_lis' AND `est` = 'des'; INSERT INTO `sis-dat_var` VALUES

      ('doc','dat_lis','des', 1, 'tit', '{  
        "nom":"¿Titulos?",
        "tit":"Mostrar los titulos",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_lis','des', 2, 'det', '{  
        "nom":"¿Detalles?",
        "tit":"",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_lis','des', 3, 'lec', '{  
        "nom":"¿Lecturas?",
        "tit":"",
        "ope":{ "tip":"opc_bin" }
      }')  
    ;

  -- 
  -- Tablero : Fondo + Posiciones
  DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_tab';

    -- Secciones : borde + color + imagen
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_tab' AND `est` = 'sec'; INSERT INTO `sis-dat_var` VALUES
      
      ('doc','dat_tab','sec', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes Exteriores...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_tab','sec', 2, 'col', '{
        "nom":"¿Color?",
        "tit":"Pintar los Fondos del Tablero...",
        "ope":{ "tip":"opc_bin" }
      }'),
      ('doc','dat_tab','sec', 3, 'ima', '{
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "tip":"arc_ima", "class":"dis-ocu" }
      }')
    ;
    -- Posiciones : borde + color + imagen + numero + texto + fecha
    DELETE FROM `sis-dat_var` WHERE `app` = 'doc' AND `esq` = 'dat_tab' AND `est` = 'pos'; INSERT INTO `sis-dat_var` VALUES
    
      ('doc','dat_tab', 'pos', 10, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "tip":"opc_bin" }
      }'),
        ('doc','dat_tab', 'pos', 11, 'bor-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar el Borde exterior de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 12, 'bor-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar el Borde exterior de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 13, 'bor-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar el Borde exterior de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 14, 'bor-atr', '{
          "nom":"¿Atributos?",
          "tit":"Mostrar el borde extearior en las posiciones por los Atributos activados...",
          "ope":{ "tip":"opc_bin" }
        }'),
      ('doc','dat_tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
        ('doc','dat_tab', 'pos', 21, 'col-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar el Color de Fondo de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 22, 'col-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar el Color de Fondo de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 23, 'col-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar el Color de Fondo de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 24, 'col-atr', '{
          "nom":"¿Atributos?",
          "tit":"Mostrar el Color de Fondo para las posiciones por los Atributos activados...",
          "ope":{ "tip":"opc_bin" }
        }'),
      ('doc','dat_tab', 'pos', 30, 'ima', '{
        "ide":"ima",
        "ico":"arc_ima",
        "nom":"Ficha",
        "tit":"Seleccionar un tipo de Ficha para las Posiciones..."
      }'),
        ('doc','dat_tab', 'pos', 31, 'ima-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar la Ficha de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 32, 'ima-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar las Fichas de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 33, 'ima-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar las Fichas de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 34, 'ima-atr', '{
          "nom":"¿Atributos?",
          "tit":"Mostrar las Fichas de las posiciones por los Atributos activados...",
          "ope":{ "tip":"opc_bin" }
        }'),
      ('doc','dat_tab', 'pos', 40, 'num', '{
        "ide":"num",
        "ico":"num",
        "nom":"Número",
        "tit":"Seleccionar un valor numérico para las Posiciones..."        
      }'),
        ('doc','dat_tab', 'pos', 41, 'num-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar el valor numérico de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 42, 'num-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar el valor numérico de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 43, 'num-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar el valor numérico de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 44, 'num-atr', '{
          "nom":"¿Atributos?",
          "tit":"Mostrar el valor numérico de las posiciones por los Atributos activados...",
          "ope":{ "tip":"opc_bin" }
        }'),
      ('doc','dat_tab', 'pos', 50, 'tex', '{
        "ide":"tex",
        "ico":"tex",
        "nom":"Texto",
        "tit":"Seleccionar un valor textual para las Posiciones..."        
      }'),
        ('doc','dat_tab', 'pos', 51, 'tex-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar el valor textual de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 52, 'tex-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar el valor textual de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 53, 'tex-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar el valor textual de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 54, 'tex-atr', '{
          "nom":"¿Atributos?",
          "tit":"Mostrar el valor textual de las posiciones por los Atributos activados...",
          "ope":{ "tip":"opc_bin" }
        }'),
      ('doc','dat_tab', 'pos', 60, 'fec', '{
        "ide":"fec",
        "ico":"fec",
        "nom":"Fecha",
        "tit":"Seleccionar un valor temporal para las Posiciones..."        
      }'),
        ('doc','dat_tab', 'pos', 61, 'fec-pos', '{
          "nom":"¿Posición?",
          "tit":"Mostrar el valor temporal de la posición principal...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 62, 'fec-mar', '{
          "nom":"¿Marcas?",
          "tit":"Mostrar el valor temporal de las posiciones marcadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 63, 'fec-ver', '{
          "nom":"¿Selección?",
          "tit":"Mostrar el valor temporal de las posiciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }'),
        ('doc','dat_tab', 'pos', 64, 'fec-atr', '{
          "nom":"¿Opciones?",
          "tit":"Mostrar el valor temporal de las posiciones por las opciones seleccionadas...",
          "ope":{ "tip":"opc_bin" }
        }')
      --
    ;
  --    
--

