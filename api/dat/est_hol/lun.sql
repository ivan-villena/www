
DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'lun%'; INSERT INTO `dat_est` VALUES

  -- dias
  ('hol','lun', '{  
      "atr": { 
        "ide": { "min":1, "max":28, "dat":"hol_lun" },
        "sel": { "min":1, "max":20, "dat":"hol_sel" },        
        "fas": { "min":1, "max":3, "dat":"hol_lun_fas" },
        "cub": { "min":0, "max":16, "dat":"hol_lun_cub" },
        "arm": { "min":1, "max":4, "dat":"hol_lun_arm" },        
        "rad": { "min":1, "max":7, "dat":"hol_rad" }        
      },
      "val": { 
        "nom": "Día Lunar #()($)ide() de 28",
        "des": "()($)ato_des()",
        "ima": "background: center/60% no-repeat url(http://localhost/img/hol/fic/rad/()($)rad().png), center/contain no-repeat url(http://localhost/img/hol/fic/rad.png), center/contain no-repeat url(http://localhost/img/hol/fic/arm/()($)arm().png);",
        "col": 4,
        "num": 28
      },
      "opc": { 
        "ver": [ 
          "ide", "arm", "rad"
        ],
        "ima": [
          "ide", "arm", "rad"
        ],
        "col": [
          "ide", "arm"
        ],
        "num": [ 
          "ide", "arm", "rad"
        ]
      },
      "lis": {
        "atr": [ "ide", "arm", "rad" ],        
        "tit_cic": [ "arm" ]
      },
      "inf": {
        "fic": [ "arm", "rad" ],
        "tab": [ "hol.lun.pla", { "val":{ "pos":"()($)ide()" } } ]
      }
  }' ),
  -- ciclo armonico
  ('hol','lun_arm', '{  
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_lun_arm" },
        "dia": { "min":1, "max":4, "dat":"hol_lun" }
      },
      "val": { 
        "nom": "Armonía lunar #()($)ide() de 4: Héptada ()($)des_col().",
        "des": "()($)des(). ()($)nom(). Días ()($)dia()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad.png), center/contain no-repeat url(http://localhost/img/hol/fic/arm/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "opc": { 
        "ima": [
          "ide"
        ]            
      },
      "inf": {
        "opc": [ "des" ],
        "det": [ "des_col", "des_pod", "des_pol" ],
        "fic": [ "dia" ]
      }
  }' ),
  -- telektonon
  ('hol','lun_tel_tor', '{  
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_lun_tel_tor" }
      }
  }' ),
  ('hol','lun_tel_cam', '{  
      "atr": { 
        "ide": { "min":1, "max":8, "dat":"hol_lun_tel_cam" }
      }
  }' ),
  ('hol','lun_tel_cub', '{  
      "atr": { 
        "ide": { "min":1, "max":16, "dat":"hol_lun_tel_cub" }
      }
  }' ),
  -- Atomo del Tiempo    
  ('hol','lun_pla_ato', '{
    "atr": { 
      "ide": { "min":1, "max":4, "dat":"hol_lun_pla_ato" }
    },
    "val": { 
      "nom": "Atomo del Tiempo #()($)ide() de 4. ()($)nom()",
      "des": "()($)des()",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/lun/pla_ato/()($)ide().png);",
      "col": 4,
      "num": 4
    }
  }' ),
  ('hol','lun_pla_tet', '{
    "atr": { 
      "ide": { "min":1, "max":2, "dat":"hol_lun_pla_tet" }
    },      
    "val": { 
      "nom": "Tetraedro #()($)ide() de 2. ()($)nom()",
      "des": "()($)des()",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/lun/pla_tet/()($)ide().png);",
      "col": 2,
      "num": 2
    }
  }' )
;