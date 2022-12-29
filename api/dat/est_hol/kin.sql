-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'kin%'; INSERT INTO `dat_est` VALUES

  ('hol','kin', '{
    "atr": { 
      "ide": { "min":1, "max":260, "dat":"hol_kin" },
      "ene": { "min":1, "max":4, "dat":"hol_kin_ene" },
      "ene_cam": { "min":1, "max":14, "dat":"hol_kin_ene_cam" },
      "chi": { "min":1, "max":65, "dat":"hol_chi" },
      "cro_est": { "min":1, "max":4, "dat":"hol_kin_cro_est" },
      "cro_est_dia": { "min":1, "max":65, "dat":"hol_kin_cro_est_dia" },
      "cro_ele": { "min":1, "max":4, "dat":"hol_kin_cro_ele" },
      "cro_ele_dia": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },
      "arm_tra": { "min":1, "max":13, "dat":"hol_kin_arm_tra" },
      "arm_tra_dia": { "min":1, "max":20, "dat":"hol_sel" },
      "arm_cel": { "min":1, "max":5, "dat":"hol_kin_arm_cel" },
      "arm_cel_dia": { "min":1, "max":4, "dat":"hol_sel_arm_raz" },  
      "gen_enc": { "min":1, "max":3, "dat":"hol_kin_gen_enc" },
      "gen_enc_dia": { "min":1, "max":3, "max-1":130, "max-2":90, "max-3":52 },
      "gen_cel": { "min":1, "max":5, "dat":"hol_kin_gen_cel" },
      "gen_cel_dia": { "min":1, "max":26 },
      "nav_cas": { "min":1, "max":5, "dat":"hol_kin_nav_cas" },
      "nav_cas_dia": { "min":1, "max":52, "dat":"hol_cas" },  
      "nav_ond": { "min":1, "max":20, "dat":"hol_kin_nav_ond" },
      "nav_ond_dia": { "min":1, "max":13, "dat":"hol_ton" },
      "par_ana": { "min":1, "max":260, "dat":"hol_kin" },
      "par_gui": { "min":1, "max":260, "dat":"hol_kin" },
      "par_ant": { "min":1, "max":260, "dat":"hol_kin" },
      "par_ocu": { "min":1, "max":260, "dat":"hol_kin" }
    },
    "rel": { 
      "ide": "hol_kin",
      "arm_tra_dia": "hol_sel",
      "nav_ond_dia": "hol_ton",
      "nav_cas_dia": "hol_cas"
    },
    "val": { 
      "nom": "Kin #()($)ide() de 260: ()($)nom().",
      "des": "()($)des()",
      "ima": "background: top/50% no-repeat url(http://localhost/img/hol/fic/ton/()($)nav_ond_dia().png), bottom/60% no-repeat url(http://localhost/img/hol/fic/sel/()($)arm_tra_dia().png);",
      "num": 260
    },
    "opc": { 
      "ver": [ 
        "ene", "ene_cam", "gen_enc", "gen_cel", "nav_cas", "nav_ond", 
        "cro_est", "cro_ele", "arm_tra", "arm_cel" 
      ],
      "col": [
        "ene", 
        "gen_enc", "gen_cel", 
        "nav_cas", "nav_ond", 
        "cro_est", "cro_ele", 
        "arm_tra", "arm_cel"
      ],
      "ima": [
        "ide", "ene", "ene_cam", "chi", 
        "par_ana", "par_gui", "par_ant", "par_ocu", 
        "nav_cas", "nav_ond", "nav_ond_dia", 
        "arm_tra", "arm_cel", "arm_tra_dia", 
        "cro_est", "cro_ele"
      ],
      "num": [ 
        "ide", "psi", "ene", "ene_cam", 
        "gen_enc", "gen_enc_dia", "gen_cel", "gen_cel_dia", 
        "nav_cas", "nav_cas_dia", "nav_ond", "nav_ond_dia", 
        "cro_est", "cro_est_dia", "cro_ele", "cro_ele_dia", 
        "arm_tra", "arm_tra_dia", "arm_cel", "arm_cel_dia"
      ],
      "tex": [
        "nom","des"
      ]            
    },
    "lis": { 
      "atr": [
        "ide",
        "pag", "chi",
        "ene", "ene_cam",
        "cro_est", "cro_ele", 
        "arm_tra", "arm_cel", 
        "gen_enc", "gen_cel", 
        "nav_cas", "nav_ond", 
        "par_ana", "par_gui", 
        "par_ant", "par_ocu"
      ],
      "atr_ocu": [ "pag", "chi", "ene", "ene_cam", "gen_enc", "gen_cel", "par_ana", "par_gui", "par_ant", "par_ocu" ],
      "tit_cic": [ "nav_cas", "nav_ond", "cro_est", "cro_ele", "arm_tra", "arm_cel" ],
      "det_des": [ "des","des_tie","des_umb" ]
    },
    "pos": { 
      "nav": { 
        "nom": "Nave del Tiempo",  
        "ide": "hol.kin", "atr": [ "nav_cas", "nav_cas_dia", "nav_ond", "nav_ond_dia" ] 
      },
      "arm": { 
        "nom": "Giro Galáctico",
        "ide": "hol.kin", "atr": [ "arm_tra", "arm_tra_dia", "arm_cel", "arm_cel_dia" ] 
      },
      "cro": { 
        "nom": "Giro Espectral",   
        "ide": "hol.kin", "atr": [ "cro_est", "cro_est_dia", "cro_ele", "cro_ele_dia" ] 
      },
      "sol": { 
        "nom": "Holon Solar",   
        "ide": "hol.sel", "atr": [ "sol_pla", "sol_res", "sol_orb", "sol_cel", "sol_cir" ] 
      },
      "pla": { 
        "nom": "Holon Planetario",
        "ide": "hol.sel", "atr": [ "pla_cen", "pla_hem", "pla_mer" ] 
      },
      "hum": { 
        "nom": "Holon Humano",
        "ide": "hol.sel", "atr": [ "hum_cen", "hum_ext", "hum_ded", "hum_mer" ] 
      }
    },
    "fic": [
      "ide", [ "nav_cas", "nav_ond", "arm_tra", "arm_cel", "cro_est", "cro_ele" ]
    ],
    "inf": { 
      "atr": "des",

      "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_05-07-\'>Ciclos Ahau</a> en <cite>el Factor Maya</cite> y la <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_02-03-10-\'>Nave del Tiempo</a> en <cite>el Encantamiento del Sueño</cite>" },
      "fic-1": [ "nav_cas", "nav_ond", "nav_ond_dia" ],

      "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_04-04-02-06-\'>Ciclos evolutivos</a> en <cite>el Factor Maya</cite> y el <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-16-\'>Giro Espectral</a> en <cite>el Encantamiento del Sueño</cite>" },
      "fic-2": [ "cro_est", "cro_ele" ],

      "htm-3": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_05-04-\'>Rayo de Sincronización Galáctica</a> en <cite>el Factor Maya</cite>, y las <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_04-\'>Trayectorias del Giro Galáctico</a> en <cite>el Encantamiento del Sueño</cite>" },
      "fic-3": [ "arm_tra", "arm_cel", "arm_tra_dia" ],

      "htm-4": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_02-03-06-\'>Oráculo del Destino</a> en <cite>el Encantamiento del Sueño</cite>" },
      "eje-4": { "ide": "api_hol::inf", "par": [ "kin-par", "()($)ide()" ] }
      
    }
  }'),
  -- parejas
  ('hol','kin_par', '{
    "val": {
      "nom": "Kin #()($)ide() de 260: ()($)nom().",
      "des": "()($)des()",
      "ima": [ "hol.kin.par", { "pos":{ "ima":"hol.kin.ide" } } ]
    },
    "tab": {
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "", "tex":"" },
      "opc": [ "pag", "par" ]
    }
  }'),
  -- Módulo Armónico
  ('hol','kin_tzo', '{
    "tab": { 
      "sec": { "kin-sel": 1, "kin-ton": 0 },
      "pos": { "ima": "hol.ton.ide", "col": "", "num": "hol.kin.ide" }, 
      "opc": [ "pag", "par" ],
      "pag": { "kin": 1 }
    }
  }' ),
  ('hol','kin_ene', '{ 
    "atr": {
      "ide": { "min":1, "max":4, "dat":"hol_kin_ene" }
    },
    "val": { 
      "nom": "Grupo #()($)ide() de ()($)nom()",
      "des": "()($)gru() x ()($)gru_uni() = ()($)uni() unidades",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/kin/ene/()($)ide().png);",
      "col": 4
    },
    "inf": { 
      "det": [ "cue", "gru", "gru_uni" ],
      "tab": [ "hol.kin.tzo", { "pos": { "ima":"hol.ton.ide", "num":"hol.kin.ide", "col":"hol.kin.ene" } } ]
    }
  }' ),
  ('hol','kin_ene_cam', '{
    "atr": {
      "ide": { "min":1, "max":14, "dat":"hol_kin_ene_cam" }
    },        
    "val": { 
      "nom": "Campo #()($)ide() de ()($)nom() unidades",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/kin/ene_cam/()($)ide().png);"
    }
  }' ),
  -- Giro Galáctico ( Colocacion Armónica )
  ('hol','kin_arm', '{
    "tab": { 
      "sec": { "sel-arm_tra-bor": 0, "sel-arm_cel-pos": 1, "sel-arm_cel-bor": 0, "sel-arm_cel-col": 0},
      "pos": { "ima": "hol.sel.ide", "col": "", "num": "" },
      "opc": [ "par", "pul" ]          
    }
  }' ),
  ('hol','kin_arm_tra', '{
    "atr": {
      "ide": { "min":1, "max":13, "dat":"hol_kin_arm_tra" },
      "cel": { "dat":"hol_kin_arm_cel" }
    },
    "val": { 
      "nom": "Trayectoria Armónica #()($)ide() de 13: ()($)nom().",
      "des": "()($)des() ()($)tit().",
      "ima": "background: top/75% no-repeat url(http://localhost/img/hol/fic/ton/()($)ide().png), center/contain no-repeat url(http://localhost/img/hol/fic/sel.png);",
      "num": 13,
      "col": 7
    },
    "tab": {
      "sec": { "par":1 },
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par" ]
    },              
    "inf": {
      "det": [ "tit", "may", "fac" ],

      "tab": [ "hol.kin.arm_tra", { 
        "sec": { "par":1 }, 
        "pos": { "ima":"hol.kin.ide" } 
      }],
      "htm-1": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/fac#_05-04-\'>Rayo de Sincronización Galáctica</a> en el Factor Maya" },
      "atr-1": [ "lec" ],
      
      "htm-2": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/enc#_04-\'>Índice Armónico</a> en <cite>el Encantamiento del Sueño</cite>" },
      "atr-2": [ "des" ],
      "fic-2": [ "cel" ]
    }
  }' ),
  ('hol','kin_arm_cel', '{ 
    "atr": { 
      "ide": { "min":1, "max":65, "dat":"hol_kin_arm_cel" },            
      "tra": { "min":1, "max":13, "dat":"hol_kin_arm_tra" },            
      "cel": { "min":1, "max":5, "dat":"hol_sel_arm_cel" },
      "inv": { "min":1, "max":65, "dat":"hol_kin_arm_cel" },
      "ton": { "min":1, "max":13, "dat":"hol_ton" },
      "chi": { "min":1, "max":64, "dat":"hol_chi" },
      "kin": { "dat":"hol_kin" }
    },
    "val": { 
      "nom": "Célula del Tiempo #()($)ide() de 65: ()($)nom().", 
      "des": "()($)des()",
      "ima": "background: top/75% no-repeat url(http://localhost/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/sel/arm_cel/()($)cel().png);",
      "num": 65,
      "col": 5
    },
    "tab": { 
      "sec": { "par":1 },
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par" ]
    },        
    "inf": {
      "fic": [ "tra", "cel", "ton" ],
      "tab": [ "hol.kin.arm_cel", { "sec": { "par":1 }, "pos": { "ima":"hol.kin.ide" } } ],

      "htm-1": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/tab#_06-04-\'>I-Ching Galáctico</a> en Las 20 Tablas del Tiempo" },
      "fic-1": [ "chi" ],

      "htm-2": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/enc#_04-\'>Índice Armónico</a> en <cite>el Encantamiento del Sueño</cite>" },
      "atr-2": [ "des" ],
      "fic-2": [ "kin" ]
    }
  }' ),
  -- Giro Espectral ( Colocación Cromática )
  ('hol','kin_cro', '{
    "tab": { 
      "sec": { "cas-pos": 1, "cas-orb": 1, "ton-col":1, "sel-cro_ele-pos": 1 },
      "pos": { "ima": "hol.sel.ide", "col": "", "num": "" },
      "opc": [ "par", "pul" ]          
    }
  }' ),
  ('hol','kin_cro_est', '{
    "atr": { 
      "ide": { "min":1, "max":4, "dat":"hol_kin_cro_est" },
      "sel": { "min":1, "max":20, "dat":"hol_sel" },
      "cas": { "min":1, "max":52, "dat":"hol_cas" },
      "ele": { "dat":"hol_kin_cro_ele" }
    },
    "val": { 
      "nom": "Espectro Galáctico #()($)ide() de 4: ()($)col() d()($)dir().",
      "des": "Guardían ()($)nom(): ()($)des(), ()($)det()",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cas/arm/()($)ide().png);",
      "num": 4,
      "col": 4            
    },
    "opc": { 
      "ima": [ "ide", "sel", "cas" ]            
    },
    "tab": { 
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par" ]
    },        
    "inf": {
      "det": [ "nom", "des", "det" ],

      "tab": [ "hol.kin.cro_est", { 
        "pos": { "ima":"hol.kin.ide" } 
      }],
      "htm-1": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/fac#_04-04-02-06-\'>Etapas Evolutivas del Ser Galáctico</a> en <cite>el Factor Maya</cite>" },
      "atr-1": [ "fac_des" ],

      "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-16-\'>Elementos Galácticos del Giro Espectral</a> en <cite>el Encantamiento del Sueño</cite>" },
      "fic-2": [ "ele" ]
    }
  }' ),
  ('hol','kin_cro_est_dia', '{
    "atr": { 
      "ide": { "min":1, "max":65, "dat":"hol_kin_cro_est_dia" },
      "ton": { "dat":"hol_ton" },
      "ond": { "dat":"hol_ton_ond" },
      "dim": { "dat":"hol_ton_dim" },
      "mat": { "dat":"hol_ton_mat" },
      "sim": { "dat":"hol_ton_sim" }
    },
    "val": { 
      "nom": "Día estacional #()($)ide() de 65.",
      "des": "()($)ond_nom(): ()($)ond_pos(). ()($)ond_pod(): ()($)ond_man().",
      "ima": "background: center/80% no-repeat url(http://localhost/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/ton.png);",
      "num": 65
    },
    "inf": {
      "fic": [ "ton", "ond", "dim", "mat", "sim" ],
      
      "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/fac#_04-04-02-06-\'>Ciclos del Quemador</a> en <cite>el Factor Maya</cite>" },
      "atr-1": "fac",

      "htm-2": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-16-\'>Función del Kin Polar</a> en <cite>el Encantamiento del Sueño</cite>" },
      "atr-2": "enc",

      "htm-3": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-11-\'>Aventura de la Onda Encantada</a> en <cite>el Encantamiento del Sueño</cite>" },
      "tex-3": "()($)ond_nom(): ()($)ond_pos().\\n()($)ond_pod(): ()($)ond_man()."      
    }
  }' ),    
  ('hol','kin_cro_ele', '{ 
    "atr": { 
      "ide": { "min":1, "max":52, "dat":"hol_kin_cro_ele" },
      "ele": { "min":1, "max":4, "dat":"hol_sel_cro_ele" },
      "est": { "min":1, "max":4, "dat":"hol_sel_cro_est" },
      "pos_arm": { "min":1, "max":4, "dat":"hol_arm" },
      "ton": { "min":1, "max":13, "dat":"hol_ton" },
      "arm": { "min":1, "max":4, "dat":"hol_cas_arm" },
      "ond": { "min":1, "max":4, "dat":"hol_cas_ond" },
      "dim": { "min":1, "max":4, "dat":"hol_cas_dim" },
      "mat": { "min":1, "max":5, "dat":"hol_cas_mat" },
      "sim": { "min":1, "max":7, "dat":"hol_cas_sim" },      
      "kin": { "dat":"hol_kin" }
    },
    "val": { 
      "nom": "Elemento Cromático #()($)ide() de 52: ()($)nom().",
      "des": "()($)des().",
      "ima": "background: center/75% no-repeat url(http://localhost/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://localhost/img/hol/fic/sel/cro_ele/()($)ele().png);",
      "num": 52,
      "col": 4            
    },
    "tab": { 
      "sec": { "par":1 },
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par" ]
    },        
    "inf": { 
      "opc": [ "des" ],

      "fic": [ "ele", "ton" ],
      "tab": [ "hol.kin.cro_ele", { "pos": { "ima":"hol.kin.ide" } } ],
      
      "fic-1": [ "arm", "ond", "dim", "mat" ],

      "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-16-\'>Kines del Giro Espectral</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic-2": [ "kin" ]
    }
  }' ),
  -- Nave del Tiempo ( Tonos Galácticos )
  ('hol','kin_nav', '{
    "tab": { 
      "sec": { "cas-pos":1, "cas-bor": 0, "cas-col": 1, "cas-orb": 0, "ton-col":0 },
      "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
      "opc": [ "par", "pul" ]          
    }
  }' ),    
  ('hol','kin_nav_cas', '{ 
    "atr": { 
      "ide": { "min":1, "max":5, "dat":"hol_kin_nav_cas" },
      "nav_ond": { "dat":"hol_kin_nav_ond" }
    },
    "val": { 
      "nom": "Castillo #()($)ide() de 5: ()($)nom().",
      "des": "()($)des()",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/kin/nav_cas/()($)ide().png);",
      "num": 5,
      "col": 5            
    },
    "tab": {
      "sec": { "par":1, "cas-pos":1, "cas-col": 1, "cas-orb": 0, "ton-col":0 },
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par", "dim", "mat", "sim" ]
    },        
    "inf": {
      "det": [ "des_cor", "des_pod", "des_acc", "des_mis" ],

      "tab": [ "hol.kin.nav_cas", { 
        "pos": { "ima":"hol.kin.ide" } 
      }],
      "htm": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-06-\'>Castillos de la Nave del Tiempo</a> en <cite>el Encantamiento del Sueño</cite>" },
      "atr": [ "des" ],
      "fic": [ "nav_ond" ]
    }
  }' ),
  ('hol','kin_nav_ond', '{
    "atr": {
      "ide": { "min":1, "max":20, "dat":"hol_kin_nav_ond" },
      "kin_lis": { "dat":"hol_kin" }
    },
    "val": { 
      "nom": "Onda Encantada #()($)ide() de 20: ()($)nom().",
      "des": "()($)enc_des()", 
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/arm/()($)cas_arm().png), center/contain no-repeat url(http://localhost/img/hol/fic/sel/()($)sel().png);",
      "num": 20,
      "col": 4
    },
    "tab": { 
      "sec": { "par":1 },
      "pos": { "ima":"hol.kin.ide", "col": "", "num": "" },
      "opc": [ "pag", "par", "dim", "mat", "sim" ]
    },
    "inf": {
      "det": [ "fac", "enc" ],
      "tab": [ "hol.kin.nav_ond", { 
        "pos": { "ima":"hol.kin.ide", "pul":[ "dim","mat","sim"] }
      }],

      "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost//hol/bib/fac#_05-07-01-\'>Ciclos Ahau</a> en el Factor Maya" },
      "atr-1": [ "fac_des" ],

      "htm-2": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost//hol/bib/enc#_03-06-\'>Ondas Encantadas de la Nave del Tiempo</a> en <cite>el Encantamiento del Sueño</cite>" },
      "atr-2": [ "enc_des" ],
      "fic-2": [ "kin_lis" ]
    }
  }' ),
  ('hol','kin_gen_enc', '{ 
    "atr": {
      "ide": { "min":1, "max":3, "dat":"hol_kin_gen_enc" }
    },
    "val": { 
      "nom": "()($)ide()° Génesis d<cite>el Encantamiento del Sueño</cite>: ()($)nom().",
      "des": "()($)des().",
      "num": 3,
      "col": 3
    }
  }' ),
  ('hol','kin_gen_cel', '{
    "atr": {
      "ide": { "min":1, "max":5, "dat":"hol_kin_gen_cel" }
    },        
    "val": { 
      "nom": "Célula de la Memoria #()($)ide() de 5: ()($)nom().",
      "des": "()($)des().",
      "num": 5,
      "col": 5            
    }
  }' )
;