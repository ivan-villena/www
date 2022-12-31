
DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'ton%'; INSERT INTO `dat_est` VALUES

  ('hol','ton', '{
      "atr": {
        "ide": { "min":1, "max":13, "dat":"hol_ton" },

        "ond": { "min":1, "max":4, "dat":"hol_ton_ond" },
        "ond_enc": { "min":0, "max":4, "dat":"hol_ton_ond" },

        "dim": { "min":1, "max":4, "dat":"hol_ton_dim" },
        "mat": { "min":1, "max":5, "dat":"hol_ton_mat" },
        "sim": { "min":1, "max":7, "dat":"hol_ton_sim" },
        
        "hum_lad": { "min":1, "max":3, "dat":"hol_uni_hum_lad" },
        "hum_art": { "min":1, "max":7, "dat":"hol_uni_hum_art" },
        "hum_sen": { "min":1, "max":7, "dat":"hol_uni_hum_sen" }
      },
      "val": { 
        "nom": "Tono Galáctico #()($)ide() de 13: ()($)nom().",
        "des": "()($)des() ()($)des_acc_lec().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/()($)ide().png);",
        "col": 7,
        "num": 13
      },
      "opc": { 
        "ver": [ 
          "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" 
        ],
        "ima": [ 
          "ide", "ond", "dim", "mat", "sim" 
        ],
        "col": [ 
          "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" 
        ],
        "num": [ 
          "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" 
        ]
      },
      "lis": { 
        "atr": [ 
          "ide", "nom", "des_car", "des_pod", "des_acc", "dim", "mat", "sim" 
        ],
        "atr_ocu": [ 
          "dim", "mat", "sim" 
        ],
        "tit_cic": [ 
          "ond" 
        ],
        "tit_gru": [ 
          "dim", "mat", "sim" 
        ],
        "det_des": [ 
          "ond_man", "cit_med", "cit_pre", "cit_por" 
        ]
      },
      "inf": {
        "det": [ "des_car", "des_acc", "des_pod" ],

        "tab": [ "hol.ton.ond", { 
            "val": { "pos":"()($)ide()" }, 
            "pos": { "bor":1, "ima":"hol.ton.ide", "tex":"hol.ton.des", "pul":["dim","mat","sim"] }
          }, { 
            "pos": { "class":"pad-1" } 
          }
        ],
        "fic": [ "ond", "dim", "mat", "sim" ],

        "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_04-04-01-01-\'>Rayos de Pulsación</a> en el Factor Maya" },
        "atr-1": "gal",

        "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-11-\'>Tonos Galácticos de la Creación</a> en el Encantamiento del Sueño" },
        "tex-2": "()($)des(), ()($)des_acc_lec().",

        "htm-3": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-12-\'>Onda Encantada de la Aventura</a> en el Encantamiento del Sueño" },
        "tex-3": "()($)ond_nom(): ()($)ond_pos(). ()($)ond_man().",

        "htm-4": { "eti":"p", "class":"tit", "htm":"Referencias del Orden Sincrónico en la <a target=\'_blank\' href=\'https://13lunas.net/\'>Ley del Tiempo</a>" },
        "atr-4": "cit_med",

        "htm-5": { "eti":"p", "class":"tit", "htm":"Extractos del libro <cite><q>El kin<c>,</c> tu signo maya</q></cite> " },
        "atr-5": [ "cit_pre", "cit_por" ]
      }
  }' ),
  -- aventura de la onda encantada
  ('hol','ton_ond', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_ton_ond" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Aventura de la Onda Encantada #()($)ide() de 4.",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/ond/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "opc": { 
        "ima": [ "ide", "ton" ] 
      },
      "inf": { 
        "opc": [ "des" ],
        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-12-\'>Onda Encantada de la Aventura</a> en el Encantamiento del Sueño" },
        "fic": [ "ton" ]
      }
  }' ),
  -- pulsares
  ('hol','ton_dim', '{
      "atr": {
        "ide": { "min":1, "max":4, "dat":"hol_ton_dim" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Pulsar Dimensional #()($)ide() de 4: ()($)nom().",
        "des": "()($)des_ond(). ()($)des_dim() dimensión, Campo de aplicación ()($)des_cam().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/dim/()($)ide().png);",
        "col": 4
      },
      "opc": { 
        "ima": [ "ide", "ton" ] 
      },
      "inf": { 
        "det": [ "des_dim", "des_cam" ],

        "htm": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-13-\'>Código pulsar</a> en el Encantamiento del Sueño" },
        "atr": [ "des_ond" ],
        "fic": [ "ton" ]
      }
  }' ),
  ('hol','ton_mat', '{
      "atr": { 
        "ide": { "min":1, "max":5, "dat":"hol_ton_mat" },
        "ton": { "dat":"hol_ton" }
      },      
      "val": { 
        "nom": "Pulsar Matiz #()($)ide() de 5: ()($)nom().",
        "des": "Código: ()($)des_cod(). Pulsares Dimensionales: ()($)des_dim().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/mat/()($)ide().png);",
        "col": 5
      },
      "opc": { 
        "ima": [ "ide", "ton" ]
      },
      "inf": { 
        "det": [ "des_cod", "des_dim" ],

        "htm": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-13-\'> Código pulsar</a> en el Encantamiento del Sueño" },
        "atr": [ "des_ond" ],
        "fic": [ "ton" ]
      }
  }' ),
  ('hol','ton_sim', '{
      "atr": {
        "ide": { "min":1, "max":7, "dat":"hol_ton_sim" },
        "inv": { "min":1, "max":13, "dat":"hol_ton" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Simetría Especular #()($)ide() de 7: ()($)nom().",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/sim/()($)ide().png);",
        "col": 7
      },
      "opc": { 
        "ima": [ "ide", "ton" ]
      },
      "inf": { 
        "opc": [ "des" ],
        
        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_04-04-01-02-\'>Simetría Especular</a> en el Factor Maya" },
        "atr": [ "fac_des" ],
        "fic": [ "ton" ]
      }
  }' )
;