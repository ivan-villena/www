-- Operaciones
  DELETE FROM `var-ope`;
  INSERT INTO `var-ope` (`tip`,`dat`,`ide`,`pos`,`nom`,`des`) VALUES

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