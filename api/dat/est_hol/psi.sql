-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'psi%'; INSERT INTO `dat_est` VALUES

  ('hol','psi', '{
    "atr": { 
      "ide": { "min":1, "max":365, "dat":"hol_psi" },
      "kin": { "min":1, "max":260, "dat":"hol_kin" },
      "ani_lun": { "min":1, "max":13, "dat":"hol_psi_ani_lun" },
      "ani_lun_dia": { "min":1, "max":28, "dat":"hol_lun" },
      "ani_vin": { "min":1, "max":19, "dat":"hol_psi_ani_vin" },
      "ani_vin_dia": { "min":1, "max":20 },
      "ani_cro": { "min":1, "max":75, "dat":"hol_psi_ani_cro" },
      "ani_cro_dia": { "min":1, "max":5 },        
      "hep_est": { "min":1, "max":4, "dat":"hol_psi_hep_est" },
      "hep_est_dia": { "min":1, "max":91 },
      "hep_pla": { "min":1, "max":52, "dat":"hol_psi_hep_pla" },
      "hep_pla_dia": { "min":1, "max":7, "dat":"hol_rad" }
    },
    "rel": { 
      "ide": "hol_psi",
      "ani_lun_dia": "hol_lun",
      "hep_pla_dia": "hol_rad"
    },
    "val": { 
      "nom": "Psi-Crono #()($)ide() de 365, correspondiente al ()($)fec().",
      "des": "- Kin: ()($)kin().\\n- Estación Solar #()($)hep_est() de 4, día ()($)hep_est_dia().\\n- Giro Lunar #()($)ani_lun() de 13, día ()($)ani_lun_dia() de 28.\\n- Héptada #()($)hep_pla() de 52, día ()($)hep_pla_dia() de 7.",
      "ima": "background: top/50% no-repeat url(http://localhost/img/hol/fic/ton/()($)kin_ton().png), bottom/60% no-repeat url(http://localhost/img/hol/fic/sel/()($)kin_sel().png);",
      "num": 365
    },
    "opc": { 
      "ver": [ 
        "ani_lun", "ani_vin",
        "hep_est", "hep_pla"          
      ],
      "ima": [
        "kin", 
        "ani_lun", 
        "hep_est", "hep_pla", "hep_pla_dia"
      ],
      "col": [
        "ani_lun", "ani_lun_dia",
        "hep_est", "hep_pla"
      ],
      "num": [ 
        "ide", "fec", "kin",           
        "ani_lun", "ani_lun_dia", 
        "ani_vin", "ani_vin_dia",  
        "ani_cro", "ani_cro_dia",
        "hep_est", "hep_est_dia",
        "hep_pla", "hep_pla_dia"          
      ],
      "tex": [
      ]                  
    },
    "lis": { 
      "atr": [ "ide", "kin", "hep_est", "hep_est_dia", "ani_lun", "ani_lun_dia", "hep_pla", "hep_pla_dia" ],
      "atr_ocu": [ "hep_est_dia", "ani_lun_dia" ],
      "tit_cic": [ "hep_est", "ani_lun", "ani_vin", "hep_pla" ]
    },
    "pos": {
      "lun": { 
        "nom": "Giro Lunar",
        "ide": "hol.psi", "atr": [ "ani_lun", "ani_lun_dia" ] 
      },
      "est": { 
        "nom": "Estación Solar",  
        "ide": "hol.psi", "atr": [ "hep_est", "hep_est_dia" ] 
      },
      "hep": { 
        "nom": "Héptada Semanal",   
        "ide": "hol.psi", "atr": [ "hep_pla", "hep_pla_dia" ] 
      }
    },
    "fic": [ 
      "kin", [ "hep_est", "ani_lun", "hep_pla" ] 
    ],
    "inf": {
      
    }
  }' ),
  -- Banco-Psi
  ('hol','psi_kin', '{
    "tab": { 
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par" ],
      "pag": { "kin": 1 }
    }
  }' ),
  -- Anillo solar de 13 lunas + 18 vinales y 75 cromaticas
  ('hol','psi_ani', '{
    "tab": { 
      "sec": { "lun-cab":1, "lun-hep":1 },
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par", "dim", "mat", "sim" ]          
    }
  }' ),
  ('hol','psi_ani_lun', '{
    "atr": { 
      "ide": { "min":1, "max":13, "dat":"hol_psi_lun" },
      "ond": { "min":1, "max":4, "dat":"hol_ton_ond" }
    },
    "val": { 
      "nom": "Luna #()($)ide() de 13: ()($)nom().",
      "des": "()($)ton_des() del Giro Solar Anual; Totem ()($)tot(): ()($)tot_pro().",
      "ima": "background: url(http://localhost/img/hol/fic/psi/lun/()($)ide().png) center/contain no-repeat;",
      "num": 13,
      "col": 7
    },
    "opc": { 
      "ima": [
        "ide"
      ]            
    },
    "tab": { 
      "sec": { "par":1, "lun-cab":1, "lun-hep":1, "lun-rad": 1 },
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pul", "par" ]          
    },
    "inf": {
      "det":[ "ton_nom", "ton_des", "fec_ran" ],

      "tab": [ "hol.psi.ani_lun_tot", { 
        "val": { "pos":"()($)ide()" },
        "pos": { "pul":["dim","mat","sim"] }
      }],

      "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-11-\'>Aventura de la Onda Encantada</a> en el <cite>Encantamiento del Sueño</cite>" },
      "tex-1": "()($)ond_nom(): ()($)ond_pos().\\n()($)ond_pod(): ()($)ond_man().",

      "htm-2": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost/hol/bib/lun#_02-07-01-\'>Totem Animal</a> en <cite>Las <n>13</n> Lunas en Movimiento</cite>" },      
      "atr-2": [ "tot_nom", "tot_pro", "tot_por" ]

    }
  }' ),
  ('hol','psi_ani_vin', '{
    "atr": { 
      "ide": { "min":1, "max":18, "dat":"hol_psi_vin" }
    },      
    "val": { 
      "nom": "Vinal #()($)ide() de 19: ()($)nom().",
      "des": "()($)des().",
      "num": 19
    }
  }' ),
  ('hol','psi_ani_cro', '{
    "atr": { 
      "ide": { "min":1, "max":75, "dat":"hol_psi_cro" }
    },
    "val": { 
      "nom": "Cromática Entonada #()($)ide() de 75.",
      "num": 75
    }
  }' ),
  -- Ciclo estacional de 52 heptadas
  ('hol','psi_hep', '{
    "tab": { 
      "sec": { "cas-pos": 1, "cas-orb": 0, "ton-col": 0 },
      "pos": { "ima": "hol.sel.ide", "col": "", "num": "" },
      "opc": [ "pag", "par", "dim", "mat", "sim" ]          
    }
  }' ),    
  ('hol','psi_hep_pla', '{
    "atr": { 
      "ide": { "min":1, "max":52, "dat":"hol_psi_hep" },
      "est": { "min":1, "max":4, "dat":"hol_psi_hep_est" },
      "ton": { "min":1, "max":13, "dat":"hol_ton" },
      "arm": { "min":1, "max":4, "dat":"hol_cas_arm" },
      "pos_arm": { "min":1, "max":4, "dat":"hol_arm" },
      "ond": { "min":1, "max":4, "dat":"hol_cas_ond" },
      "dim": { "min":1, "max":4, "dat":"hol_cas_dim" },
      "mat": { "min":1, "max":5, "dat":"hol_cas_mat" },
      "sim": { "min":1, "max":7, "dat":"hol_cas_sim" },
      "psi": { "dat":"hol_psi" }
    },      
    "val": { 
      "nom": "Heptada #()($)ide() de 52.",
      "des": "()($)ton_des() del cuadrante ()($)des_col() en el ()($)des_dir().",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cod/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/rad.png), center/contain no-repeat url(http://localhost/img/hol/fic/arm/()($)ond().png);",
      "num": 52,
      "col": 4
    },
    "tab": { 
      "sec": { "par": 1 },
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "par" ]
    },        
    "inf": {
      "fic": [ "pos_arm", "ton" ],
      "tab": [ "hol.psi.hep_pla", { "ide": "()($)ide()", "pos": { "ima":"hol.psi.ide" } } ],
      "fic-1": [ "est", "ond", "dim", "mat" ],
      "fic-2": [ "psi" ]
    }
  }' ),
  ('hol','psi_hep_est', '{
    "atr": { 
      "ide": { "min":1, "max":4, "dat":"hol_psi_est" }
    },
    "val": { 
      "nom": "Estación Solar #()($)ide() de 4: ()($)nom().",
      "des": "()($)des() ( ()($)pol_sur() al sur, ()($)pol_nor() al norte )",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/arm/()($)ide().png);",
      "num": 4,
      "col": 4          
    },
    "tab": { 
      "sec": { },
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "par", "dim", "mat", "sim" ]
    },
    "inf": {
      "det": [ "des_col", "des_dir" ],

      "tab": [ "hol.psi.hep_est", { 
        "ide":"()($)ide()", 
        "pos": { "ima":"hol.psi.ide" }
      }],
      "htm": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-09-\'>Estaciones Solares</a> en el <cite>Encantamiento del Sueño</cite>" },
      "atr": "des",
      "dat": [ "pol_nor", "pol_sur" ]
    }
  }' ),
  ('hol','psi_hep_est_dia', '{
    "atr": { 
      "ide": { "min":1, "max":95, "dat":"hol_psi_est_dia" },
      "ton": { "dat":"hol_ton" },
      "ond": { "dat":"hol_ton_ond" },
      "dim": { "dat":"hol_ton_dim" },
      "mat": { "dat":"hol_ton_mat" },
      "sim": { "dat":"hol_ton_sim" }
    },      
    "val": { 
      "nom": "Día estacional #()($)ide() de 91.",
      "des": "()($)ond_nom(): ()($)ond_pos(). ()($)ond_pod(): ()($)ond_man().",
      "ima": "background: center/80% no-repeat url(http://localhost/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/ton.png);",
      "num": 91
    },
    "inf": {

      "fic": [ "ton", "ond", "dim", "mat", "sim" ],

      "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-11-\'>Aventura de la Onda Encantada</a> en el <cite>Encantamiento del Sueño</cite>" },
      "tex-1": "()($)ond_nom(): ()($)ond_pos().\\n()($)ond_pod(): ()($)ond_man()."
    }
  }' )
;