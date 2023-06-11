
--
  -- Textos
    DELETE FROM `sis-dat_est` WHERE `esq`='var' AND `ide` LIKE 'tex_%'; INSERT INTO `sis-dat_est` VALUES

      ('var','tex_ico', '{

        "dat": { "niv":["ide"] }
      }' ),

      ('var','tex_let', '{

        "dat": { "niv":["ide"] }      
      }' )    
    ;
  --
  -- Fecha
  DELETE FROM `sis-dat_est` WHERE `esq`='var' AND `ide` LIKE 'fec%'; INSERT INTO `sis-dat_est` VALUES

    ('var', 'fec', '{

        "atr": {
          "dia": { "min":1, "max":31, "dat":"var-fec_dia" },
          "mes": { "min":1, "max":12, "dat":"var-fec_mes" },
          "año": { "min":-9999, "max":9999 },
          "hor": { "min":1, "max":24, "dat":"var-fec_hor" },
          "min": { "min":1, "max":60, "dat":"var-fec_min" },
          "seg": { "min":1, "max":60, "dat":"var-fec_seg" }
        },
        
        "rel": {
          "val":"var-fec",
          "sem":"var-fec_sem",
          "dia":"var-fec_dia",
          "mes":"var-fec_mes",
          "año":"var-fec_año"
        },

        "lis": {
          "atr": [ "val" ]
        },

        "opc": {
          "ver": [ "dia", "sem", "mes" ],
          "num": [ "dia", "sem", "mes" ]
        }

    }')
  ;
  --
