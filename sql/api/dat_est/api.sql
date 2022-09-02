-- Interface
  DELETE FROM `_api`.`dat_opc` WHERE `esq` = 'api';

  -- calendario
  DELETE FROM `_api`.`dat_opc` WHERE `esq` = 'api' AND `est` LIKE 'fec%';
  INSERT INTO `_api`.`dat_opc` VALUES
    -- fecha
    ( 'api', 'fec',         '{
      
        "est": {
          "val":"fec", 
          "dia":"fec_dia", 
          "sem":"fec_sem", 
          "año":"fec_año"
        },
        
        "ver": [
          "dia", "sem", "mes"
        ],
        "num": [
          "dia", "sem", "mes"
        ]
        
    }')
  ;