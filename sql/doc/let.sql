-- Active: 1623270923336@@127.0.0.1@3306@_api

-- letra
  DELETE FROM `api`.`doc_let`;
  INSERT INTO `api`.`doc_let` (`pos`,`ide`,`tip`,`var`) VALUES
    ( NULL, '.', 'ope', 'cod' ),
    ( NULL, ',', 'ope', 'cod' ),
    ( NULL, ':', 'ope', 'cod' ),
    ( NULL, ';', 'ope', 'cod' ),
    ( NULL, '\\','ope', 'cod' ),
    ( NULL, '~', 'ope', 'val' ),
    ( NULL, '°', 'ope', 'val' ),
    ( NULL, 'º', 'ope', 'val' ),
    ( NULL, '#', 'ope', 'val' ),
    ( NULL, '$', 'ope', 'val' ),
    ( NULL, '@', 'ope', 'val' ),          
    ( NULL, '=', 'ope', 'num' ),  
    ( NULL, '+', 'ope', 'num' ),
    ( NULL, '-', 'ope', 'num' ),    
    ( NULL, '*', 'ope', 'num' ),
    ( NULL, '/', 'ope', 'num' ),
    ( NULL, '%', 'ope', 'num' ),
    ( NULL, '<', 'ope', 'num' ),
    ( NULL, '>', 'ope', 'num' ),
    ( NULL, '–', 'ope', 'tex' ),
    ( NULL, "'", 'ope', 'tex' ),  
    ( NULL, '’', 'ope', 'tex' ),  
    ( NULL, '`', 'ope', 'tex' ),  
    ( NULL, '"', 'ope', 'tex' ),
    ( NULL, '“', 'ope', 'tex' ),
    ( NULL, '”', 'ope', 'tex' ),    
    ( NULL, '¡', 'ope', 'tex' ),
    ( NULL, '!', 'ope', 'tex' ),
    ( NULL, '¿', 'ope', 'tex' ),
    ( NULL, '?', 'ope', 'tex' ),  
    ( NULL, '(', 'ope', 'lis' ),
    ( NULL, ')', 'ope', 'lis' ),
    ( NULL, '[', 'ope', 'pos' ),
    ( NULL, ']', 'ope', 'pos' ),
    ( NULL, '{', 'ope', 'atr' ),
    ( NULL, '}', 'ope', 'atr' )
  ;