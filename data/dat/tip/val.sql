--
--
DELETE FROM `dat_var_val`;
INSERT INTO `dat_var_val` (`dat`,`ide`,`nom`,`des`,`len`,`ope`) VALUES

  -- números
    ( 'num', 'val', 'Número',         '', '', '{    
    }'),
    ( 'num', 'bin', 'Binario',        '2 Valores posibles: 0/1, si/no, cierto/falso...', 'sql', '{    
    }'),    
    ( 'num', 'bit', 'Bits',           'Bits expresdos en número (único, de 0 a 64) o cadena ( fijo o variable ).', 'sql', '{    
    }'),
    ( 'num', 'int', 'Entero',         'Reales de 0 a 9', 'sql', '{    
    }'),
    ( 'num', 'dec', 'Decimal',        'De coma flotante', 'sql', '{    
    }'),
    ( 'num', 'ran', 'Rango',          'Valores numéricos de un mínimo a un máximo con un salto.', NULL, '{    
    }'),

  -- textos
    ( 'tex', 'val', 'Texto',          '', '', '{    
    }'),
    ( 'tex', 'let', 'Letra',          'Letra o conjunto de letras sin espacios vacíos', 'sql', '{    
    }'),
    ( 'tex', 'pal', 'Palabra',        'Palabras Encadenadas por Espacios Vacíos y separadas por. y ,', 'sql', '{    
    }'),
    ( 'tex', 'ora', 'Oración',        'Conjunto de oraciones separadas por. y saltos de línea', 'sql', '{    
    }'),
    ( 'tex', 'par', 'Párrafo',        'Texto completo con Oraciones y Párrafos.', 'sql', '{    
    }'),

  -- Listado
    ( 'lis', 'uni', 'Único',          'Conjunto de Valores para selección única', 'sql', '{    
    }'),
    ( 'lis', 'mul', 'Múltiple',       'Conjunto de valores para selección múltiple', 'sql', '{    
    }'),    

  -- fechas
    ( 'fec', 'val', 'Número',         '', '', '{    
    }'),
    ( 'fec', 'tie', 'Tiempo',         'Una frecuencia o marca de Tiempo UNIX.', NULL, '{    
    }'),
    ( 'fec', 'dyh', 'Día y Hora',     'Fecha completa, dia/mes/año hora:minutos:segundos', 'sql', '{    
    }'),
    ( 'fec', 'dia', 'Dia',            'Fecha: dia/mes/año', 'sql', '{    
    }'),
    ( 'fec', 'hor', 'Hora',           'Horario: hora:minuto:segundo', 'sql', '{    
    }'),
    ( 'fec', 'sem', 'Semana',         'Días de la Semana', 'htm', '{    
    }'),
    ( 'fec', 'año', 'Año',            'Período de 365 días de 24 hs cada uno.', 'sql', '{    
    }'),

  -- figuras
    ( 'fig', 'col', 'Color',     '', NULL, '{    
    }'),
    ( 'fig', 'pun', 'Punto',     '', 'sql', '{    
    }'),
    ( 'fig', 'lin', 'Línea',     '', 'sql', '{    
    }'),
    ( 'fig', 'pol', 'Polígono',  '', 'sql', '{    
    }'),   

  -- Directorio
    ( 'arc', 'val', 'Archivo',        'Archivos de Contenido Múltiple y Variable', 'sql', '{    
    }'),  
    ( 'arc', 'lis', 'Fichero',        'Carpeta del Directorio o Contenedor de Archivos', NULL, '{    
    }'),
    ( 'arc', 'url', 'Enlace',        'Link o enlace que hace referencia a otro archivo en otro lugar', NULL, '{    
    }'),
    ( 'arc', 'tex', 'Texto',          'Archivo de Texto...', NULL, '{    
    }'),
    ( 'arc', 'ima', 'Imagen',         'Archivo de Imagen .png, .jpg,.gif, ...', NULL, '{    
    }'),
    ( 'arc', 'mus', 'Audio',          'Archivo de Música .mp3, .mp4,  ...', NULL, '{    
    }'),
    ( 'arc', 'vid', 'Video',          'Archivo de Video mp4, vid,...', NULL, '{    
    }'),
    ( 'arc', 'eje', 'Ejecutable',     'Archivo de Programa...', NULL, '{    
    }'),

  -- ejecucion
    ( 'eje', 'fun', 'Función',        'Ejecución del entorno por un código que retorna un determinado tipo de valor', NULL, '{    
    }'),
    ( 'eje', 'cla', 'Clase',          'Constructor de Objetos del Entorno', NULL, '{    
    }'),
    ( 'eje', 'met', 'Método',         'Constructor de Objetos del Entorno', NULL, '{    
    }'),
    ( 'eje', 'pro', 'Procedimiento',  'Ejecución del Entorno por iteraciones que puede no retornar ningun valor', NULL, '{    
    }'),
    ( 'eje', 'eve', 'Evento',         'Ejecución de funciones y procedimientos a partir de una acción determinada por el usuario', NULL, '{    
    }'),
    ( 'eje', 'dis', 'Disparador',     'Procedimientos y funciones ejecutadas posterior a rutinas programadas por el sistema o por una rutina del usuario ante el ingreso y la modificación de', NULL, '{    
    }'),

  -- objeto
    ( 'obj', 'pos', 'Escalar',        'Posiciones de clave numérica', 'sql', '{    
    }'),
    ( 'obj', 'nom', 'Asociativo',     'Posiciones de clave textual', 'sql', '{    
    }'),
    ( 'obj', 'atr', 'Atributo',       'Objeto de clave : valor', 'sql', '{    
    }'),
  
  -- elemento
    ( 'ele', 'val', 'Elemento',       'Elemento único', NULL, '{    
    }'),
    ( 'ele', 'lis', 'Nodos',          'Conjunto de Elementos dependientes de un mismo Nodo en el Documento', NULL, '{    
    }')
;