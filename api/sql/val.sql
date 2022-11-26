
-- tipo
  DELETE FROM `val_tip`;
  INSERT INTO `val_tip` (`ide`,`dat`,`val`,`nom`,`len`,`des`,`ope`) VALUES

    -- valores
      ( 'null',             'opc', 'vac', 'Nulo',             'php,jso',  'Valor no Definido','{ 
        "tip":"nov" 
      }'),
      ( 'undefined',        'opc', 'vac', 'Indefinido',       'php,jso',  'Tipo de Dato no Definido. Se utiliza en operaciones funcionales internas de los programas de procesamiento','{ 
        "tip":"not" 
      }'),
      ( 'nan',              'opc', 'vac', 'No Numérico',      'php,jso',  'Tipo de dato NaN. Se utiliza en rutinas internas de los programas','{ 
        "tip":"non" 
      }'),
      ( 'bool',             'opc', 'bin', 'Booleano',         'sql',      'BOOL, BOOLEAN: Son sinónimos para TINYINT(1). Un valor de cero se considera falso. Valores distintos a cero se consideran ciertos','{ 
      }'),
      ( 'boolean',          'opc', 'bin', 'Booleano',         'php,jso',  'Un valor de cero se considera falso. Valores distintos a cero se consideran ciertos','{ 
      }'),
      ( 'enum',             'opc', 'uni', 'Único',            'sql',      'ENUM("value1","value2",...): Una enumeración. Un objeto de cadena de caracteres que sólo puede tener un valor, elegido de una lista de valores "value1", "value2", ..., NULL o el valor de error especial ". Una columna ENUM puede tener un máximo de 65,535 valores distintos. Los valores ENUM se representan internamente como enteros','{ 
        "lis_max":65535 
      }'),
      ( 'set',              'opc', 'mul', 'Múltiple',         'sql',      'SET("value1","value2",...): Un conjunto. Un objeto de cadena de caracteres que puede tener cero o más valores que deben pertencer a la lista de valores "value1", "value2", ... Una columna SET puede tener un máximo de 64 miembros. Los valores SET se representan internamente como enteros','{ 
        "lis_max":64 
      }' ),
    
    -- numeros
      ( 'bit',              'num', 'bit', 'Binario',            'sql',      'BIT(M): En un tipo de datos bit. M indica el número de bits por valor, de 1 a 64. El valor por defecto es 1 si se omite M' , '{ 
        "tip":"num"
      }'),
      ( 'binary',           'num', 'bit', 'Cadena Fija',        'sql',      'BINARY(M): El tipo BINARY es similar al tipo CHAR, pero almacena cadenas de datos binarios en lugar de cadenas de caracteres no binarias' , '{ 
        "tip":"fij"
      }'),
      ( 'varbinary',        'num', 'bit', 'Cadena Variable',    'sql',      'VARBINARY(M): El tipo VARBINARY es similar al tipo VARCHAR, pero almacena cadenas de caracteres binarias en lugar de cadenas de caracteres no binarias' , '{ 
        "tip":"var"
      }'),
      ( 'integer',          'num', 'int', 'Entero',           'php,jso',  NULL, '{
        "step":1
      }'),
      ( 'tinyint',          'num', 'int', 'Muy Pequeño',      'sql',      'TINYINT[(M)]: Un entero muy pequeño. El rango con signo es de -128 a 127. El rango sin signo es de 0 a 255', '{ 
        "step":1, "min":-128, "max":127
      }'),
      ( 'smallint',         'num', 'int', 'Entero Pequeño',   'sql',      'SMALLINT[(M)]: Un entero pequeño. El rango con signo es de -32768 a 32767. El rango sin signo es de 0 a 65535', '{ 
        "step":1, "min":-32768, "max":32767
      }'),
      ( 'mediumint',        'num', 'int', 'Entero Medio',     'sql',      'MEDIUMINT[(M)]: Entero de tamaño medio. El rango con signo es de -8388608 a 8388607. El rango sin singo es de 0 a 16777215', '{ 
        "step":1, "min":-8388608, "max":8388607
      }'),
      ( 'int',              'num', 'int', 'Entero',           'sql',      'INT[(M)]: Un entero de tamaño normal. El rango con signo es de . El rango sin signo es de 0 a 4294967295', '{ 
        "step":1, "min":-2147483648, "max":2147483647
      }'),
      ( 'bigint',           'num', 'int', 'Entero Grande',    'sql',      'BIGINT[(M)]: Un entero grande. El rango con signo es de -9223372036854775808 a 9223372036854775807. El rango sin signo es de 0 a 18446744073709551615', '{ 
        "step":1, "min":-9223372036854775808, "max":9223372036854775807
      }'),
      ( 'numeric',          'num', 'dec', 'Numérico',         'php',      NULL, '{
      }'),
      ( 'number',           'num', 'dec', 'Numérico',         'jso',      NULL, '{
      }'),
      ( 'decimal',          'num', 'dec', 'Decimal',          'sql',      'DECIMAL[(M[,D])]: Número de punto fijo exacto y empaquetado. M es el número total de dígitos y D es el número de decimales. El punto decimal y (para números negativos) el signo "-" no se tiene en cuenta en M. Si D es 0, los valores no tienen punto decimal o parte fraccional. El máximo número de dígitos (M) para DECIMAL es 64. El máximo número de decimales soportados (D) es 30. Si UNSIGNED se especifica, no se permiten valores negativos.\r\n\r\nSi se omite D, el valor por defecto es 0. Si se omite M, el valor por defecto es 10.\r\n\r\nTodos los cálculos básicos (+, -, *, /) con columnas DECIMAL se hacen con precisión de 64 dígitos decimales. ', '{ 
        "step": 0.01, "num_dec_fij": 1, "maxlength": 64
      }'),
      ( 'float',            'num', 'dec', 'Coma flotante',    'sql',      'FLOAT[(M,D)]: Un número de coma flotante pequeño (de precisión simple). Los valores permitidos son de -3.402823466E+38 a -1.175494351E-38, 0, y de 1.175494351E-38 a 3.402823466E+38. Si se especifica UNSIGNED, los valores negativos no se permiten. M es la anchura de muestra y D es el número de dígitos significativos. FLOAT sin argumentos o FLOAT(p) (donde p está en el rango de 0 a 24) es un número de coma flotante con precisión simple. ', '{ 
        "step":0.01, "maxlength":24
      }'),
      ( 'double',           'num', 'dec', 'Decimal Grande',   'sql,php',  'DOUBLE[(M,B)]: Número de coma flotante de tamaño normal (precisión doble). Los valores permitidos son de -1.7976931348623157E+308 a -2.2250738585072014E-308, 0, y de 2.2250738585072014E-308 a 1.7976931348623157E+308. Si se especifica UNSIGNED, no se permiten valores negativos. M es la anchura de muestra y B es el número de bits de precisión. DOUBLE sin parámetros o FLOAT(p) (donde p está en el rango de 25 a 53) es un número de coma flotante con doble precisión. Un número de coma flotante con precision sencilla tiene una precisión de 7 decimales aproximadamente; un número con coma flotante de doble precisión tiene una precisión aproximada de 15 decimales.', '{ 
        "step":0.01, "maxlength":53
      }'),

    -- textos
      ( 'char',             'tex', 'pal', 'Letras ',          'sql',      'CHAR(M): Una cadena de caracteres de longitud fija que siempre tiene el número necesario de espacios a la derecha para ajustarla a la longitud especificada al almacenarla. M representa la longitud de la columna. El rango de M en MySQL 5.0 es de 0 a 255 caracteres', '{ 
        "tex_fij":1, "maxlength":255 
      }'),
      ( 'varchar',          'tex', 'ora', 'Palabras',         'sql',      'VARCHAR(M): Cadena de caracteres de longitud variable. M representa la longitud de columna máxima. En MySQL 5.0, el rango de M es de 0 a 255 antes de MySQL 5.0.3, y de 0 a 65,535 en MySQL 5.0.3 y posterior. (La longitud máxima real de un VARCHAR en MySQL 5.0 se determina por el tamaño de registro máximo y el conjunto de caracteres que use. La longitud máxima efectiva desde MySQL 5.0.3 es de 65,532 bytes)', '{ 
        "maxlength":255 
      }'),
      ( 'string',           'tex', 'ora', 'Cadena Corta',     'php,jso',  NULL, '{    
      }'),
      ( 'tinytext',         'tex', 'par', 'Párrafo',          'sql',      'TINYTEXT: Una columna TEXT con longitud máxima de 255 (2^8 - 1) caracteres', '{ 
        "maxlength":255 
      }'),
      ( 'text',             'tex', 'par', 'Texto Chico',      'sql',      'TEXT[(M)]: Una columna TEXT con longitud máxima de 65,535 (2^16 - 1) caracteres. En MySQL 5.0, se puede dar una longitud opcional M. En ese caso MySQL creará las columnas con el tipo TEXT de longitud mínima para almacenar los valors de longitud M', '{ 
        "maxlength":65535 
      }'),
      ( 'mediumtext',       'tex', 'par', 'Texto Mediano',    'sql',      'MEDIUMTEXT: Una columna TEXT con longitud máxima de 16,777,215 (2^24 - 1) caracteres', '{ 
        "maxlength":16777215 
      }'),
      ( 'longtext',         'tex', 'par', 'Texto Grande',     'sql',      'LONGTEXT: Una columna TEXT con longitud máxima de 4,294,967,295 or 4GB (2^32 - 1) caracteres. La longitud máxima efectiva (permitida) de columnas LONGTEXT depende del tamaño máximo de paquete configurado en el protocolo cliente/servidor y la memoria disponible', '{ 
        "maxlength":2147483647 
      }'),
    -- fechas
      ( 'timestamp',        'fec', 'dyh', 'Marca de Tiempo',  'sql',      'TIMESTAMP[(M)]:  Una marca temporal. El rango es de "1970-01-01 00:00:00" hasta el año 2037.\r\n\r\nUna columna TIMESTAMP es útil para registrar la fecha y hora de una operación INSERT o UPDATE. La primera columna TIMESTAMP en una tabla se rellena automáticamente con la fecha y hora de la operación más reciente si no le asigna un valor. Puede asignar a cualquier columna TIMESTAMP la fecha y hora actual asignándole un valor NULL', '{ 
        "maxlength":19 
      }'),
      ( 'datetime',         'fec', 'dyh', 'Día y Hora',       'sql,php',  'DATETIME: Combinación de fecha y hora. El rango soportado es de "1000-01-01 00:00:00" a "9999-12-31 23:59:59". MySQL muestra valores DATETIME en formato "YYYY-MM-DD HH:MM:SS", pero permite asignar valores a las columnas DATETIME usando cadenas de caracteres o números', '{    
      }'),
      ( 'date',             'fec', 'dia', 'Día',              'sql,php',  'DATE: Una fecha. El rango soportado es de "1000-01-01" a "9999-12-31". MySQL muestra valores DATE en formato "YYYY-MM-DD", pero permite asignar valores a columnas DATE usando cadenas de caracteres o números', '{    
      }'),
      ( 'time',             'fec', 'hor', 'Horario',          'sql,php',  'TIME: Una hora. El rango es de "-838:59:59" a "838:59:59". MySQL muestra los valores TIME en formato "HH:MM:SS", pero permite asingar valores a columnas TIME usando números o cadenas de caracteres', '{    
      }'),
      ( 'week',             'fec', 'sem', 'Semana',           'htm',      'Selector de Tiempo en base a períodos de 7 días', '{    
      }'),
      ( 'year',             'fec', 'año', 'Año',              'sql',      'YEAR[(2|4)]: Un año en formato de dos o cuatro dígitos. El valor por defecto está en formato de cuatro dígitos. En formato de cuatro dígitos, los valores permitidos son de 1901 a 2155, y 0000. En formato de dos dígitos, los valores permitidos son de 70 a 69, representando los años de 1970 a 2069. MySQL muestra los valores YEAR en formato YYYY pero permite asignar valores a columnas YEAR usando cadenas de caracteres o números', '{ 
        "min":1901, "max":2155
      }'),

    -- figuras
      ( 'color',            'fig', 'col', 'Color',            'css',      'Valor de Color expresado en Hexadecimal con #', '{
      }'),
      ( 'geometry',         'fig', 'geo', 'Figura',           'sql',       NULL, '{ 
        "tip":"geo", "val_lis":1 
      }'),
      ( 'geomcollection',   'fig', 'geo', 'Figuras',          'sql',       NULL, '{ 
        "tip":"geo" 
      }'),
      ( 'point',            'fig', 'pun', 'Punto',            'sql',       NULL, '{ 
        "tip":"pun", "val_lis":1 
      }'),
      ( 'multipoint',       'fig', 'pun', 'Puntos',           'sql',       NULL, '{ 
        "tip":"pun" 
      }'),
      ( 'line',             'fig', 'lin', 'Linea',            'sql',       NULL, '{ 
        "tip":"lin", "val_lis":1 
      }'),
      ( 'multiline',        'fig', 'lin', 'Líneas',           'sql',       NULL, '{ 
        "tip":"lin" 
      }'),  
      ( 'polygon',          'fig', 'pol', 'Poligono',         'sql',       NULL, '{ 
        "tip":"pol", "val_lis":1 
      }'),
      ( 'multipolygon',     'fig', 'pol', 'Polígonos',        'sql',       NULL, '{ 
        "tip":"pol" 
      }'),    

    -- navegador
      ( 'link',             'nav', 'url', 'Enlace',             'htm',      NULL, '{
      }'),

    -- archivos
      ( 'fileList',         'arc', 'fic', 'Carpeta',            'php,jso',  NULL, '{
      }'),
      ( 'file',             'arc', 'val', 'Archivo',            'php,jso',  NULL, '{ 
      }'),
      ( 'tinyblob',         'arc', 'val', 'Archivo',    'sql',      'TINYBLOB: Una columna BLOB con una longitud máxima de 255 (2^8 - 1) bytes' , '{ 
        "tip":"B", "max":"255" 
      }'),
      ( 'blob',             'arc', 'val', 'Archivo Pequeño',    'sql',      'BLOB: Una columna BLOB con longitud máxima de 65,535 (2^16 - 1) bytes' , '{ 
        "tip":"KB", "max":"65535" 
      }'),    
      ( 'mediumblob',       'arc', 'val', 'Archivo Mediano',    'sql',      'MEDIUMBLOB: Una columna BLOB con longitud de 16,777,215 (2^24 - 1) bytes' , '{ 
        "tip":"MB", "max":"16777215" 
      }'),
      ( 'longblob',         'arc', 'val', 'Archivo Grande',     'sql',      'LONGBLOB: Una columna BLOB con longitud máxima de 4,294,967,295 o 4GB (2^32 - 1) bytes. La longitud máxima efectiva (permitida) de las columnas LONGBLOB depende del tamaño máximo configurado para los paquetes en el protocolo cliente/servidor y la memoria disponible' , '{ 
        "tip":"GB", "max":"4294967295" 
      }'),

    -- objetos
      ( 'array',            'obj', 'pos',  'Escalar',         'php,jso',   'Listado de Elementos cuya posición numérica es su clave de acceso', '{    
      }'),
      ( 'json',             'obj', 'nom',  'Asociativo',      'sql',       'JSON (JavaScript Object Notation - Notación de Objetos de JavaScript) es un formato ligero de intercambio de datos', '{    
      }'),
      ( 'asoc',             'obj', 'nom',  'Asociativo',      'php,jso',   'Listado de elementos de tipo "clave":"valor"', '{    
      }'),
      ( 'object',           'obj', 'atr',  'de Clase',        'php,jso',   'Objeto de Clase dentro de un entorno de Programación', '{    
      }'),

    -- ejecuciones
      ( 'function',         'eje', 'fun',  'Función',           'php,jso',  NULL, '{
      }'),
      ( 'class',            'eje', 'cla',  'Constructor',       'php,jso',  NULL, '{
      }'),  

    -- elementos
      ( 'element',          'ele', 'eti', 'Etiqueta',           'jso',      NULL, '{
      }'),
      ( 'elementList',      'ele', 'nod', 'Nodos',              'jso',      NULL, '{
      }'),
      ( 'elementAttr', 'ele', 'atr', 'Atributo',           'jso',      NULL, '{
      }')  
  ;  
  -- de dato 
  DELETE FROM `val_tip_dat`;
  INSERT INTO `val_tip_dat` (`ide`,`nom`,`des`,`ope`) VALUES

    ( 'opc', 'Opción',    '', '{}' ),
    ( 'num', 'Número',    '', '{}' ),
    ( 'tex', 'Texto',     '', '{}' ),
    ( 'fec', 'Fecha',     '', '{}' ),
    ( 'arc', 'Archivo',   '', '{}' ),
    ( 'obj', 'Objeto',    '', '{}' ),
    ( 'eje', 'Ejecución', '', '{}' ),
    ( 'ele', 'Elemento',  '', '{}' ),
    ( 'fig', 'Figura',    '', '{}' )   
  ;  
  -- de valor 
  DELETE FROM `val_tip_val`;
  INSERT INTO `val_tip_val` (`dat`,`ide`,`nom`,`des`,`len`,`ope`) VALUES

    -- opciones
      ( 'opc', 'val', 'Opción',         '', '', '{    
      }'),
      ( 'opc', 'vac', 'Vacío',          'Valor nulo, tipo de dato indefinido o no-numérico', NULL, '{    
      }'),
      ( 'opc', 'bin', 'Binario',        '2 Valores posibles: 0/1, si/no, cierto/falso...', 'sql', '{    
      }'),
      ( 'opc', 'uni', 'Único',          'Conjunto de Valores para selección única', 'sql', '{    
      }'),
      ( 'opc', 'mul', 'Múltiple',       'Conjunto de valores para selección múltiple', 'sql', '{    
      }'),

    -- números
      ( 'num', 'val', 'Número',         '', '', '{    
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

    -- archivo
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
--  
-- Operaciones
  DELETE FROM `val_ope`;
  INSERT INTO `val_ope` (`tip`,`dat`,`ide`,`pos`,`nom`,`des`) VALUES

    ( 'ver', 'opc', '==', 1, '==', 'Tiene que ser igual que...' ),
    ( 'ver', 'opc', '!=', 2, '!=', 'Tiene que ser distinto que...' ),  

    ( 'ver', 'num', '<<', 12, '<<', 'Tiene que ser Menor que...' ),
    ( 'ver', 'num', '<=', 13, '<=', 'Tiene que ser Menor o igual que...' ),
    ( 'ver', 'num', '>=', 15, '>=', 'Tiene que ser Mayor o igual que...' ),
    ( 'ver', 'num', '>>', 14, '>>', 'Tiene que ser Mayor que...' ),

    ( 'ver', 'tex', '^^', 20, '^^', 'Debe empezar con...' ),
    ( 'ver', 'tex', '!*', 21, '!*', 'No debe contener...' ),
    ( 'ver', 'tex', '**', 22, '**', 'Debe contener...' ),
    ( 'ver', 'tex', '!^', 23, '!^', 'No debe empezar con...' ),
    ( 'ver', 'tex', '$$', 24, '$$', 'Debe terminar en...' ),
    ( 'ver', 'tex', '!$', 25, '!$', 'No debe terminar en...' ),

    ( 'ver', 'lis', '()', 31, '()', 'Debe incluir...' ),
    ( 'ver', 'lis', '!()', 31, '!()', 'No debe incluir...' )
  ;
--
