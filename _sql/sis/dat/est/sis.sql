
  -- Textos
    DELETE FROM `sis-dat_est` WHERE `esq`='sis' AND `ide` LIKE 'tex_%'; INSERT INTO `sis-dat_est` VALUES

      ('sis','tex_ico', '{

        "dat": { "niv":["ide"] }
      }' ),

      ('sis','tex_let', '{

        "dat": { "niv":["ide"] }      
      }' )    
    ;
  --
-- Calendario:
  -- Fecha
  DELETE FROM `sis-dat_est` WHERE `esq` = 'sis' AND `ide` LIKE 'fec%'; INSERT INTO `sis-dat_est` VALUES

  ( 'sis', 'fec', '{

      "atr": {
        "dia": { "min":1, "max":31, "dat":"sis-fec_dia" },
        "mes": { "min":1, "max":12, "dat":"sis-fec_mes" },
        "año": { "min":-9999, "max":9999 },
        "hor": { "min":1, "max":24, "dat":"sis-fec_hor" },
        "min": { "min":1, "max":60, "dat":"sis-fec_min" },
        "seg": { "min":1, "max":60, "dat":"sis-fec_seg" }
      },
      
      "rel": {
        "val":"sis-fec",
        "sem":"sis-fec_sem",
        "dia":"sis-fec_dia",
        "mes":"sis-fec_mes",
        "año":"sis-fec_año"
      },

      "lis": {
        "atr": [ "val" ]
      },

      "opc": {
        "ver": [ "dia", "sem", "mes" ],
        "num": [ "dia", "sem", "mes" ]
      }

  }');
--