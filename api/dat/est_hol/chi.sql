
DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'chi%'; INSERT INTO `dat_est` VALUES

  ('hol','chi', '{
      "atr": {
        "ide": { "min":1, "max":64, "dat":"hol_chi" },
        "sup": { "min":1, "max":"8", "dat":"hol_chi_tri" },
        "inf": { "min":1, "max":"8", "dat":"hol_chi_tri" }
      },      
      "val": { 
        "nom": "Hexagrama #()($)ide() de 64: ()($)nom().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/chi/()($)ide().png);",
        "num": 64
      },
      "inf": { 
        "opc": [ "des" ],

        "atr-1": "tit",
        
        "atr-2": "lec",
        
        "fic": [ "sup", "inf" ]
      }
  }' ),
  ('hol','chi_mon', '{
      "atr": { 
        "ide": { "min":1, "max":2, "dat":"hol_chi_mon" }
      },
      "val": { 
        "nom": "Monograma #()($)ide() de 2",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/chi/mon/()($)ide().png);"
      }
  }' ),
  ('hol','chi_bin', '{ 
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_chi_bin" }
      },
      "val": { 
        "nom": "Bigrama #()($)ide() de 4",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/chi/bin/()($)ide().png);"
      }
  }' ),
  ('hol','chi_tri', '{ 
      "atr": { 
        "ide": { "min":1, "max":8, "dat":"hol_chi_tri" }
      },
      "val": { 
        "nom": "Trigrama #()($)ide() de 8",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/chi/tri/()($)ide().png);"
      }
  }' )     
;