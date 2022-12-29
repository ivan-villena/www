
DELETE FROM `dat_est` WHERE `esq` = 'fec' AND `ide` LIKE 'dat%';; INSERT INTO `dat_est` VALUES
  -- fecha
  ( 'fec', 'dat', '{
      "atr": {
        "dia": { "min":1, "max":31, "dat":"fec_dia" },
        "mes": { "min":1, "max":12, "dat":"fec_mes" },
        "año": { "min":-9999, "max":9999 },
        "hor": { "min":1, "max":24, "dat":"fec_hor" },
        "min": { "min":1, "max":60, "dat":"fec_min" },
        "seg": { "min":1, "max":60, "dat":"fec_seg" }
      },
      "rel": {
        "val":"fec_dat", 
        "dia":"fec_dia", 
        "sem":"fec_sem", 
        "año":"fec_año"
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