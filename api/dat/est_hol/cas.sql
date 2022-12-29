-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'cas%'; INSERT INTO `dat_est` VALUES

  ('hol','cas', '{
      "atr": { 
        "ide": { "min":1, "max":52, "dat":"hol_cas" },
        "arm": { "min":1, "max":4, "dat":"hol_cas_arm" },
        "pos_arm": { "min":1, "max":4, "dat":"hol_arm" },
        "ton_arm": { "min":1, "max":13, "dat":"hol_ton" },        
        "ton": { "min":1, "max":13, "dat":"hol_ton" },
        "ond": { "min":1, "max":4, "dat":"hol_cas_ond" },
        "dim": { "min":1, "max":4, "dat":"hol_cas_dim" },
        "mat": { "min":1, "max":5, "dat":"hol_cas_mat" },
        "sim": { "min":1, "max":7, "dat":"hol_cas_sim" }        
      },
      "val": { 
        "nom": "Posicion #()($)ide() de 52: ()($)nom().",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/ton/arm/()($)arm().png), center/70% no-repeat url(http://localhost/img/hol/fic/arm/()($)pos_arm().png);",
        "col": 4,
        "num": 52
      },
      "opc": { 
        "ver": [ "ide", "arm", "ond", "pos_arm", "ton_arm" ],    
        "ima": [ "ide", "arm", "ond", "pos_arm", "ton_arm" ],
        "col": [ "arm", "ond", "pos_arm", "ton_arm" ],
        "num": [ "ide", "arm", "ond", "pos_arm", "ton_arm" ]
      },
      "inf": {
        "fic": [ "arm", "ton", "pos_arm" ],
        "tab": [ "hol.cas.ond", { 
          "val": { "pos":"()($)ide()" }, 
          "pos": { "bor":1, "ima":"hol.cas.ton", "num":"hol.cas.ide", "col":"hol.cas.pos_arm", "pul":["dim","mat","sim"] }
        }],

        "fic-1": [ "ond", "dim", "mat", "sim" ],
        "htm-2": { "eti":"p", "class":"tit", "htm":"Los Preceptos del Recorrido por el Castillo" },
        "atr-2": [ "lec" ]
      }
  }' ),
  -- cuadrante
  ('hol','cas_arm', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_cas_arm" },
        "cas": { "dat":"hol_cas" }
      },
      "val": { 
        "nom": "Cuadrante #()($)ide() de 4: ()($)nom().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/arm/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "inf": {
        "htm": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_02-03-08-\'>Castillo del Destino</a> en el Encantamiento del Sueño" },        
        "fic": [ "cas" ]
      }
  }'),
  -- aventura
  ('hol','cas_ond', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_cas_ond" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Aventura de la Onda Encantada #()($)ide() de 4: ()($)nom().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/ond/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "inf": {
        "opc": [ "des" ],
        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-12-\'>Onda Encantada de la Aventura</a> en el Encantamiento del Sueño" },        
        "fic": [ "ton" ]
      }
  }'),
  -- Pulsares
  ('hol','cas_dim', '{
      "atr": {
        "ide": { "min":1, "max":4, "dat":"hol_ton_dim" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Pulsar Dimensional #()($)ide() de 4: ()($)nom().",
        "des": "()($)des_ond(). ()($)des_dim() dimensión, Campo de aplicación ()($)des_cam().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/dim/()($)ide().png);",
        "col": 4
      },
      "opc": { 
        "ima": [ "ide" ] 
      },
      "inf": { 
        "det": [ "des_dim", "des_cam" ],
        "tab": [ "hol.cas.ond", {
          "pos": { "bor":1, "ima":"hol.cas.ton", "num":"hol.cas.ide", "col":"hol.cas.pos_arm" },
          "pul": { "dim":[] }
        }],
        "htm": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-13-\'>Código pulsar</a> en el Encantamiento del Sueño" },
        "atr": [ "des_ond" ],
        "fic": [ "ton" ]
      }
  }' ),
  ('hol','cas_mat', '{
      "atr": { 
        "ide": { "min":1, "max":5, "dat":"hol_ton_mat" },
        "ton": { "dat":"hol_ton" }
      },      
      "val": { 
        "nom": "Pulsar Matiz #()($)ide() de 5: ()($)nom().",
        "des": "Código: ()($)des_cod(). Pulsares Dimensionales: ()($)des_dim().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/mat/()($)ide().png);",
        "col": 5
      },
      "opc": { 
        "ima": [ "ide" ]
      },
      "inf": { 
        "det": [ "des_cod", "des_dim" ],

        "htm": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-13-\'> Código pulsar</a> en el Encantamiento del Sueño" },
        "atr": [ "des_ond" ],
        "fic": [ "ton" ]
      }
  }' ),
  ('hol','cas_sim', '{
      "atr": {
        "ide": { "min":1, "max":7, "dat":"hol_ton_sim" },
        "inv": { "min":1, "max":13, "dat":"hol_ton" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Simetría Especular #()($)ide() de 7: ()($)nom().",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/sim/()($)ide().png);",
        "col": 7
      },
      "opc": { 
        "ima": [ "ide" ]
      },
      "inf": { 
        "opc": [ "des" ],
        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_04-04-01-02-\'>Simetría Especular</a> en el Factor Maya" },
        "atr": [ "fac_des" ],
        "fic": [ "ton" ]
      }
  }' )
;