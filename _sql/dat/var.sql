-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------

-- Datos
  DELETE FROM `dat_var` WHERE `esq`='dat'
  ;
  -- Operador : ABM + Acumulados + Filtros
  DELETE FROM `dat_var` WHERE `esq`='dat' AND `dat`='ope'; INSERT INTO `dat_var` VALUES 
    -- 
    -- abm : contar, ver, agregar, modificar, eliminar
      ('dat','ope','abm', 1, 'act', '{
        "ico":"dat_act",
        "tit":"Actualizar..."        
      }'),
      ('dat','ope','abm', 2, 'ver', '{
        "ico":"dat_ver",
        "tit":"Buscar..."
      }'),    
      ('dat','ope','abm', 3, 'agr', '{
        "ico":"dat_agr",
        "tit":"Agregar..."
      }'),
      ('dat','ope','abm', 4, 'mod', '{
        "ico":"dat_mod",
        "tit":"Modificar..."
      }'),
      ('dat','ope','abm', 5, 'eli', '{
        "ico":"dat_eli",
        "tit":"Eliminar..."
      }'),    
    --
    -- Acumulados : posicion, marcas, seleccion
      ('dat','ope','acu', 1, 'pos', '{
        "nom":"¿Posición?",
        "tit":"Activar la Posición Principal...",
        "ope":{ "tip":"num_bin" }
      }'),    
      ('dat','ope','acu', 2, 'mar', '{
        "nom":"¿Marcas?",
        "tit":"Activar las Posiciones Marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','ope','acu', 3, 'ver', '{
        "nom":"¿Selección?",
        "tit":"Activar las Posiciones Seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','ope','acu', 4, 'opc', '{
        "nom":"¿Opciones?",
        "tit":"Contar las posiciones marcadas por opciones del tablero...",
        "ope":{ "tip":"num_bin" }
      }'),
    --
    -- Filtro: valores ( todos ) + datos ( estructura > valor ) + listados ( posicion + fecha )
      -- por Valores
        ('dat','ope','ver', 1, 'tot', '{
          "nom":"Cantidad",
          "tit":"",
          "ope":{ "tip":"num", "val":"0" }
        }'),         
        ('dat','ope','ver', 2, 'tod', '{
          "nom":"¿Todos?",
          "tit":"Mostrar todos...",
          "ope":{ "tip":"num_bin" }
        }'),
        ('dat','ope','ver', 3, 'nad', '{
          "nom":"¿Nada?",
          "tit":"Ocultar todos...",
          "ope":{ "tip":"num_bin" }
        }'),
        ('dat','ope','ver', 4, 'uni', '{
          "nom":"¿Único?",
          "tit":"Mostrar un único valor...",
          "ope":{ "tip":"num_bin" }
        }'),
        ('dat','ope','ver', 5, 'inc', '{
          "nom":"¿Cada?",
          "tit":"Indica un valor de salto o incremento entre las posiciones...",
          "ope":{ "tip":"num_int", "val":1, "min":1, "max":999 }
        }'),
        ('dat','ope','ver', 6, 'lim', '{
          "nom":"¿Cuántos?",
          "tit":"Indicar la cantidad máxima de resultados...",
          "ope":{ "tip":"num_int", "min":1, "max":999 }
        }'),
      -- por tipo de datos
        ('dat','ope','ver', 11, 'opc', '{
          "nom":"¿Opciones?",
          "tit":"Mostrar valores por opción...",
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
        ('dat','ope','ver', 12, 'num', '{
          "nom":"¿Números?",
          "tit":"Mostrar valores numéricos...",
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
        ('dat','ope','ver', 13, 'tex', '{
          "nom":"¿Textos?",
          "tit":"Mostrar valores textuales...",
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
        ('dat','ope','ver', 14, 'fec', '{
          "nom":"¿Fechas?",
          "tit":"Mostrar valores de fechas...",
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
        ('dat','ope','ver', 15, 'obj', '{
          "nom":"¿Objetos?",
          "tit":"Mostrar objetos...",
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
        ('dat','ope','ver', 16, 'arc', '{
          "nom":"¿Archivos?",
          "tit":"Mostrar archivos...",        
          "ope":{ "tip":"num_bin", "val":1 }
        }'),
      -- por Estructuras de Datos
        ('dat','ope','ver', 21, 'dat', '{
          "ico":"dat_ver",
          "tit":"Seleccionar la Estructura e indicar el Valor Buscado..."
        }'),
        ('dat','ope','ver', 22, 'esq', '{
          "nom":"Esquema",
          "tit":"Indicar el Esquema de datos..."
        }'),
        ('dat','ope','ver', 23, 'dat', '{
          "nom":"Estructura",
          "tit":"Indicar la Estructura de datos..."
        }'),
        ('dat','ope','ver', 24, 'atr', '{
          "nom":"Atributo",
          "tit":"Indicar el Atributo de la Estructura..."
        }'),
      -- por rango de valores : posicion / fecha
        ('dat','ope','ver', 31, 'pos', '{
          "nom":"Posición",
          "tit":"Indicar el valor de la posición buscada...",
          "ope":{ "tip":"num_int", "min":1, "max":999 }
        }'),
        ('dat','ope','ver', 32, 'ini', '{
          "nom":"Desde",
          "tit":"Indicar el Valor inicial...",
          "ope":{ "tip":"" }
        }'),
        ('dat','ope','ver', 33, 'fin', '{
          "nom":"Hasta",
          "tit":"Indicar el Valor final...",
          "ope":{ "tip":"" }
        }')
    --    
  ;
  -- Estructura : Titulos + Descripciones
  DELETE FROM `dat_var` WHERE `esq`='dat' AND `dat`='lis'; INSERT INTO `dat_var` VALUES
    --
    -- filtro por conjuntos
      ('dat','lis','ver', 1, 'cic', '{
        "nom":"Secuencias",
        "tit":"Indicar la secuencia..."
      }'),
      ('dat','lis','ver', 2, 'gru', '{
        "nom":"Clasificaciones",
        "tit":"Indicar la clasificacion..."
      }'),
      ('dat','lis','ver', 3, 'des', '{
        "nom":"Lecturas",
        "tit":"Indicar el atributo descriptivo..."
      }'),
    --
    -- descripciones
      ('dat','lis','des', 1, 'tit', '{  
        "nom":"¿Titulos?",
        "tit":"",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','lis','des', 2, 'det', '{  
        "nom":"¿Detalles?",
        "tit":"",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','lis','des', 3, 'lec', '{  
        "nom":"¿Lecturas?",
        "tit":"",
        "ope":{ "tip":"num_bin" }
      }')
    --   
  ;
  -- Tablero : Secciones + Posiciones
  DELETE FROM `dat_var` WHERE `esq`='dat' AND `dat`='tab'; INSERT INTO `dat_var` VALUES
    --
    -- secciones : borde + imagen + color

      ('dat','tab','sec', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marcar los Bordes Exteriores...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab','sec', 2, 'ima', '{ 
        "nom":"¿Imagen?",
        "tit":"Seleccionar una imagen de fondo...",    
        "ope":{ "tip":"arc_ima", "class":"dis-ocu" }
      }'),
      ('dat','tab','sec', 3, 'col', '{
        "nom":"¿Fondo?",
        "tit":"Pintar los Fondos del Tablero...",
        "ope":{ "tip":"num_bin" }
      }'),

    --
    -- posiciones : borde + color + imagen + numero + texto + fecha [ + variable ? ]
      ('dat','tab', 'pos', 1, 'bor', '{
        "nom":"¿Bordes?",
        "tit":"Marca y Desmarca los Bordes de las Posiciones en el Tablero...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 11, 'bor_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Borde exterior de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 12, 'bor_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Borde exterior de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 13, 'bor_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Borde exterior de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 14, 'bor_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el borde extearior en las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 20, 'col', '{
        "ide":"col",
        "ico":"fig_col",
        "nom":"Color",
        "tit":"Seleccionar Colores de Fondo para las Posiciones..."        
      }'),
      ('dat','tab', 'pos', 21, 'col_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el Color de Fondo de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 22, 'col_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el Color de Fondo de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 23, 'col_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el Color de Fondo de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 24, 'col_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el Color de Fondo para las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 30, 'ima', '{
        "ide":"ima",
        "ico":"fig_ima",
        "nom":"Ficha",
        "tit":"Seleccionar un tipo de Ficha para las Posiciones..."
      }'),
      ('dat','tab', 'pos', 31, 'ima_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar la Ficha de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 32, 'ima_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar las Fichas de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 33, 'ima_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar las Fichas de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 34, 'ima_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar las Fichas de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 40, 'num', '{        
        "ide":"num",
        "ico":"num",
        "nom":"Número",
        "tit":"Seleccionar un valor numérico para las Posiciones..."        
      }'),
      ('dat','tab', 'pos', 41, 'num_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor numérico de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 42, 'num_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor numérico de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 43, 'num_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor numérico de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 44, 'num_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor numérico de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 50, 'tex', '{
        "ide":"tex",
        "ico":"tex_cod",
        "nom":"Texto",
        "tit":"Seleccionar un valor textual para las Posiciones..."        
      }'),
      ('dat','tab', 'pos', 51, 'tex_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor textual de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 52, 'tex_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor textual de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 53, 'tex_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor textual de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 54, 'tex_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor textual de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 60, 'fec', '{
        "ide":"fec",
        "ico":"fec",
        "nom":"Fecha",
        "tit":"Seleccionar un valor temporal para las Posiciones..."        
      }'),
      ('dat','tab', 'pos', 61, 'fec_pos', '{
        "nom":"¿Posición?",
        "tit":"Mostrar el valor temporal de la posición principal...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 62, 'fec_mar', '{
        "nom":"¿Marcas?",
        "tit":"Mostrar el valor temporal de las posiciones marcadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 63, 'fec_ver', '{
        "nom":"¿Selección?",
        "tit":"Mostrar el valor temporal de las posiciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('dat','tab', 'pos', 64, 'fec_opc', '{
        "nom":"¿Opciones?",
        "tit":"Mostrar el valor temporal de las posiciones por las opciones seleccionadas...",
        "ope":{ "tip":"num_bin" }
      }')
  ;
--

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
      "ope":{ "tip":"num_bin" }
      }'),
    --
    -- Plasmas
      ('hol','tab','sec', 70, 'rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar Plasmas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 71, 'rad-hep', '{
        "nom":"¿Heptágono?",
        "tit":"Mostrar Heptágono...",
        "ope":{ "tip":"num_bin" }
      }'),      
    --
    -- Tonos
      ('hol','tab','sec', 130, 'ton', '{
        "nom":"¿Tonos Galácticos?",
        "tit":"Mostrar los Tonos...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 131, 'ton-ond', '{
        "nom":"¿Posiciones de O.E.?",
        "tit":"Mostrar las posiciones de la Onda Encantada...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 132, 'ton-col', '{
        "nom":"¿Fondo de O.E.?",
        "tit":"Colorear el Fondo de las Ondas Encantadas...",
        "ope":{ "tip":"num_bin" }
      }'),
    --
    -- Sellos
      ('hol','tab','sec', 200, 'sel', '{
        "nom":"¿Sellos Solares?",
        "tit":"Mostrar los Sellos...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 210, 'sel-arm', '{
        "nom":"¿Armónica?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 211, 'sel-arm_tra-pos', '{
        "nom":"¿Posición de T.A.?",
        "tit":"Mostrar las posiciones de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 212, 'sel-arm_tra-bor', '{
        "nom":"¿Borde de T.A.?",
        "tit":"Remarcar las bordes de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 213, 'sel-arm_tra-col', '{
        "nom":"Fondo de T.A.?",
        "tit":"Colorear las fondos de las 13 Trayectorias Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 214, 'sel-arm_cel-pos', '{
        "nom":"¿Posición de C.A.?",
        "tit":"Mostrar las posiciones de las 65 Células Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 215, 'sel-arm_cel-bor', '{
        "nom":"¿Borde de C.A.?",
        "tit":"Remarcar las 65 Células Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 216, 'sel-arm_cel-col', '{
        "nom":"¿Fondo de C.A.?",
        "tit":"Colorear el fondo de las 65 Células Armónicas...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 220, 'sel-cro', '{
        "nom":"¿Cromática?",
        "tit":"",
        "ope":{}
      }'),
      ('hol','tab','sec', 221, 'sel-cro_est-pos', '{
        "nom":"¿Posición del E.E.?",
        "tit":"Mostrar las posiciones de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 222, 'sel-cro_est-bor', '{
        "nom":"¿Borde de E.E.?",
        "tit":"Remarcar las bordes de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 223, 'sel-cro_est-col', '{
        "nom":"Fondo de E.E.?",
        "tit":"Colorear las fondos de las 4 Estaciones Espectrales...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 224, 'sel-cro_ele-pos', '{
        "nom":"¿Posición de E.C.?",
        "tit":"Mostrar las posiciones de los 52 Elementos Cromáticos...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 225, 'sel-cro_ele-bor', '{
        "nom":"¿Borde de E.C.?",
        "tit":"Remarcar los 52 Elementos Cromáticos...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 226, 'sel-cro_ele-col', '{
        "nom":"¿Fondo de E.C.?",
        "tit":"Colorear el fondo de los 52 Elementos Cromáticos...",
        "ope":{ "tip":"num_bin" }
      }'),
    --
    -- Luna
      ('hol','tab','sec', 280, 'lun', '{
        "nom":"¿Luna?",
        "tit":"Mostrar Posiciones de la Luna...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 282, 'lun-cab', '{
        "nom":"¿Giro Lunar?",
        "tit":"Mostrar los datos de cabecera para el Giro Luna...",
        "ope":{ "tip":"num_bin" }
      }'),      
      ('hol','tab','sec', 283, 'lun-hep', '{
        "nom":"¿Héptada?",
        "tit":"Mostrar las posiciones de las Héptadas Lunares...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 284, 'lun-rad', '{
        "nom":"¿Plasma?",
        "tit":"Mostrar las columnas de los Plasmas Radiales...",
        "ope":{ "tip":"num_bin" }
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
        "ope":{ "tip":"num_bin" }
      }'),      
      ('hol','tab','sec', 522, 'cas-bor', '{
        "nom":"¿Borde del C.D.?",
        "tit":"Remarcar el Borde del Castillo del Destino...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 523, 'cas-col', '{
        "nom":"¿Fondo del C.D.?",
        "tit":"Colorear el Fondo del Castillo del Destino...",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 524, 'cas-orb', '{
        "nom":"¿Orbitales del C.D.?",
        "tit":"Activar los Orbitales del Castillo del Destino...",
        "ope":{ "tip":"num_bin" }
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
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 2620, 'kin-ton', '{
        "nom":"¿Trayectorias?",
        "tit":"Mostrar Cabecera de Tonos Galácticos como las 13 Trayectorias Armónicas - Baktunes",
        "ope":{ "tip":"num_bin" }
      }'),
      ('hol','tab','sec', 2621, 'kin-arm_cel', '{
        "nom":"¿Células?",
        "tit":"Mostrar Columna de Células del Tiempo",
        "ope":{ "tip":"num_bin" }
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
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-pag', 3, 'psi', '{
        "nom":"¿Giro Solar?",
        "tit":"Activar los Portales de Activación correspondientes al Giro Solar de 364 + 1 días...",
        "ope":{ "tip":"num_bin" },
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
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 3, 'gui', '{
        "nom":"¿Guía?",
        "tit":"Activa la Pareja Guía que Orienta al Destino...",
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 4, 'ant', '{
        "nom":"¿Antípoda?",
        "tit":"Activa la Pareja Antípoda que se Opone al Destino...",
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 5, 'ocu', '{
        "nom":"¿Oculto?",
        "tit":"Activa la Pareja Oculta que Revela el Destino...",
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-par', 6, 'ext', '{
        "nom":"¿Extender Patrones?",
        "tit":"Extender las parejas seleccionadas para la posición principal...",
        "ope":{ "tip":"num_bin" }
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
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-pul', 3, 'mat', '{
        "nom":"¿Matiz?",
        "tit":"Activar pulsares matiz de la Onda Encantada correspondiente a la posición principal...",
        "ope":{ "tip":"num_bin" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),  
      ('hol','tab','opc-pul', 4, 'sim', '{
        "nom":"¿Simetría Inversa?",
        "tit":"Activar pulsares por simetría inversa correspondiente a la posición principal...",
        "ope":{ "tip":"num_bin" },
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
        "ope":{ "tip":"num_bin", "name":"tie", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 3, '2', '{
        "nom":"¿Vida?",
        "tit":"Activa el Pulsar Dimensional de la Vida...",
        "ope":{ "tip":"num_bin", "name":"vid", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 4, '3', '{
        "nom":"¿Sentir?",
        "tit":"Activa el Pulsar Dimensional del Sentir...",
        "ope":{ "tip":"num_bin", "name":"sen", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-dim', 5, '4', '{
        "nom":"¿Mente?",
        "tit":"Activa el Pulsar Dimensional de la Mente...",
        "ope":{ "tip":"num_bin", "name":"men", "value":"3" },
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
        "ope":{ "tip":"num_bin", "name":"uno", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 3, '2', '{
        "nom":"¿2 Puntos?",
        "tit":"Activa el Pulsar Matiz Lunar...",
        "ope":{ "tip":"num_bin", "name":"dos", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 4, '3', '{
        "nom":"¿3 Puntos?",
        "tit":"Activa el Pulsar Matiz Eléctrico...",
        "ope":{ "tip":"num_bin", "name":"tre", "value":"3" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 5, '4', '{
        "nom":"¿4 Puntos?",
        "tit":"Activa el Pulsar Matiz Mente-Tiempo...",
        "ope":{ "tip":"num_bin", "name":"cua", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-mat', 6, '5', '{
        "nom":"¿Raya/s?",
        "tit":"Activa el Pulsar Matiz Tiempo-Vida...",
        "ope":{ "tip":"num_bin", "name":"sin", "value":"5" },
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
        "ope":{ "tip":"num_bin", "name":"_1", "value":"1" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 3, '2', '{
        "nom":"¿Tonos 2-12?",
        "tit":"Activa el Pulsar Especular de la Polaridad y la Estabilidad Compleja...",
        "ope":{ "tip":"num_bin", "name":"_2", "value":"2" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 4, '3', '{
        "nom":"¿Tonos 3-11?",
        "tit":"Activa el Pulsar Especular del Ritmo y la Estructura Disonante...",
        "ope":{ "tip":"num_bin", "name":"_3", "value":"3" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 5, '4', '{
        "nom":"¿Tonos 4-10?",
        "tit":"Activa el Pulsar Especular de la Medida y la Manifestación...",
        "ope":{ "tip":"num_bin", "name":"_4", "value":"4" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 6, '5', '{
        "nom":"¿Tonos 5-9?",
        "tit":"Activa el Pulsar Especular del Centro y la Periodicidad Cíclica...",
        "ope":{ "tip":"num_bin", "name":"_5", "value":"5" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 7, '6', '{
        "nom":"¿Tonos 6-8?",
        "tit":"Activa el Pulsar Especular del Equilibrio Orgánico y la Resonancia Armónica...",
        "ope":{ "tip":"num_bin", "name":"_6", "value":"6" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }'),
      ('hol','tab','opc-sim', 8, '7', '{
        "nom":"¿Tonos 7-7?",
        "tit":"Activa el Pulsar Especular del Poder Místico Doble...",
        "ope":{ "tip":"num_bin", "name":"_7", "value":"7" },
        "htm_fin":{ "eti":"span", "htm":[ { "let":"(" }, { "tip":"num", "val":"0" }, { "let":")" } ] }
      }')
  ;
--

---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------