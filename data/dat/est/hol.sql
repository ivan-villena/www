-- Holon
  DELETE FROM `dat_est` WHERE `esq`='hol';

  DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'uni%'; INSERT INTO `dat_est` VALUES

    -- flujos: respiraciones y poderes
    ('hol','uni_flu_res', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"uni_flu_res" }
        },        
        "val": { 
          "nom": "Respiración de flujo polar #()($)ide() de 2: ()($)nom()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/flu_res/()($)ide().png);",
          "col": 2
        },
        "opc": { 
          "ima": [ "ide" ]
        }
    }' ),
    ('hol','uni_flu_pod', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"uni_flu_pod" }
        },
        "val": { 
          "nom": "Poder de flujo polar #()($)ide() de 2: ()($)nom()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/flu_pod/()($)ide().png);",
          "col": 2
        },
        "opc": { 
          "ima": [ "ide" ]
        }
    }' ),

    -- holon solar
    ('hol','uni_sol_res', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_uni_sol_res" }
        },
        "val": { 
          "nom": "Respiración del Holon Solar #()($)ide() de 2: ()($)nom()",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/sol_res/()($)ide().png);",
          "col": 2
        },
        "opc": { 
          "ima": [ "ide" ]
        }
    }' ),  
    ('hol','uni_sol_pla', '{
        "atr": { 
          "ide": { "min":1, "max":10, "dat":"hol_uni_sol_pla" },
          "orb": { "min":1, "max": 2, "dat":"hol_uni_sol_orb" },
          "cel": { "min":1, "max": 5, "dat":"hol_uni_sol_cel" },
          "cir": { "min":1, "max": 5, "dat":"hol_uni_sol_cir" },
          "sel": { "dat":"hol_sel" },
          "ele": { "dat":"hol_sel_cro_ele" },
          "fam": { "dat":"hol_sel_cro_fam" }
        },
        "val": { 
          "nom": "Órbita Planetaria #()($)ide() de 10: ()($)nom().",
          "des": "Código ()($)nom_cod()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/sol_pla/()($)ide().png);",
          "col": 10,
          "num": 10
        },
        "opc": { 
          "ima": [ "ide","sel","ele","fam","orb","cel","cir" ]
        },
        "inf": { 
          "det": [ "nom_cod" ],
          
          "fic": [ "orb", "cel", "cir" ],
          "tab": [ "hol.uni.sol", { "sec": { "pla":"()($)ide()" } }],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-03-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "sel" ],
          
          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Clanes Cromáticos</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-2": [ "ele" ],

          "htm-3": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-14-\'>Familias Terrestres</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-3": [ "fam" ]
        }
    }' ),
    ('hol','uni_sol_orb', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_uni_sol_orb" },
          "ele": { "dat":"hol_sel_cro_ele" },
          "pla": { "dat":"hol_uni_sol_pla" }                        
        },
        "val": { 
          "nom": "Grupo Orbital #()($)ide() de 2: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/sol_orb/()($)ide().png);",
          "col": 2,
          "num": 2
        },
        "opc": { 
          "ima": [ "ide", "ele" ]
        },
        "inf": { 
          "opc": [ "des" ],

          "tab": [ "hol.uni.sol", { "sec": { "orb":"()($)ide()" } }],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-03-\'>Órbitas Planetarias</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "pla" ],

          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Clanes Cromáticos</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-2": [ "ele" ]
        }
    }' ),
    ('hol','uni_sol_cel', '{
        "atr": { 
          "ide": { "min":1, "max":5, "dat":"hol_uni_sol_cel" },
          "pla": { "dat":"hol_uni_sol_pla" },
          "sel": { "dat":"hol_sel" },
          "ele": { "dat":"hol_sel_cro_ele" },
          "fam": { "dat":"hol_sel_cro_fam" }            
        },
        "val": { 
          "nom": "Célula Solar #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/sol_cel/()($)ide().png);",
          "col": 5, 
          "num": 5
        },
        "opc": { 
          "ima": [ "ide", "sel", "ele", "fam", "pla" ]
        },
        "inf": { 
          "opc": [ "des" ],

          "tab": [ "hol.uni.sol", { "sec": { "cel":"()($)ide()" } }],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-03-\'>Órbitas Planetarias</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "pla" ],

          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-2": [ "sel" ],

          "htm-3": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Clanes Cromáticos</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-3": [ "ele" ],

          "htm-4": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-14-\'>Familias Terrestres</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-4": [ "fam" ]
        }
    }' ),
    ('hol','uni_sol_cir', '{
        "atr": { 
          "ide": { "min":1, "max":5, "dat":"hol_uni_sol_cir" },
          "pla": { "dat":"hol_uni_sol_pla" },
          "sel": { "dat":"hol_sel" },
          "fam": { "dat":"hol_sel_cro_fam" }
        },
        "val": { 
          "nom": "Circuito de Telepatía #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/sol_cir/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "opc": { 
          "ima": [ "ide", "sel", "fam", "pla" ]
        },
        "inf": { 
          "det-lis": [ "cod", "des" ],

          "tab": [ "hol.uni.sol", { "sec": { "cir":"()($)ide()" } }],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-03-\'>Órbitas Planetarias</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "pla" ],

          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-2": [ "sel" ],

          "htm-3": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-14-\'>Familias Terrestres</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-3": [ "fam" ]
        }
    }' ),
    
    -- holon planetario
    ('hol','uni_pla_res', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_uni_pla_res" },
          "fam": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },            
          "sel": { "dat":"hol_sel" }        
        },
        "val": { 
          "nom": "Respiración del Holon Planetario #()($)ide() de 2: ()($)nom()",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/pla_res/()($)ide().png);",
          "col": 2
        },
        "opc": { 
          "ima": [ "ide", "fam", "sel" ]
        }
    }' ),
    ('hol','uni_pla_cen', '{
        "atr": { 
          "ide": { "min":1, "max":5, "dat":"hol_uni_pla_cen" },
          "fam": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },            
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Centro Planetario #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/pla_cen/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "opc": { 
          "ima": [ "ide", "fam", "sel" ]            
        },
        "inf": {
          
          "tab": [ "hol.uni.pla", { "sec": { "cen":"()($)ide()" } } ],
          "fic": [ "fam" ],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "sel" ]
        }
    }' ),
    ('hol','uni_pla_hem', '{
        "atr": { 
          "ide": { "min":1, "max":3, "dat":"hol_uni_pla_hem" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Hemisferio #()($)ide() de 3: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/pla_hem/()($)ide().png);",
          "col": 3,
          "num": 3
        },
        "opc": { 
          "ima": [ "ide" ]            
        },
        "inf": { 
          "tab": [ "hol.uni.pla", { "sec": { "cen":[], "hem":"()($)ide()" } } ],
          "fic": [ "sel" ]
        }
    }' ),
    ('hol','uni_pla_mer', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_uni_pla_mer" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Meridiano #()($)ide() de 2: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/pla_mer/()($)ide().png);",
          "col": 2,
          "num": 2
        },
        "opc": { 
          "ima": [ "ide" ]            
        },
        "inf": { 
          "tab": [ "hol.uni.pla", { "sec": { "cen":[], "mer":"()($)ide()" } } ],
          "fic": [ "sel" ]
        }
    }' ),
    ('hol','uni_pla_tie', '{
        "atr": { 
          "ide": { "min":1, "max":3, "dat":"hol_uni_pla_tie" }
        },
        "val": { 
          "nom": "Campo #()($)ide() de 3: ()($)nom()",
          "des": "()($)tra(): ()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/pla_tie/()($)ide().png);",
          "col": 3
        },
        "opc": { 
          "ima": [ "ide" ]
        }
    }' ),  
    
    -- holon humano
    ('hol','uni_hum_res', '{
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_uni_hum_res" },
          "sel": { "dat":"hol_sel" },
          "ele": { "dat":"hol_sel_cro_ele" }
        },
        "val": { 
          "nom": "Respiración del Holon Humano #()($)ide() de 2: ()($)nom()",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/flu_res/()($)ide().png);",
          "col": 2
        },
        "opc": { 
          "ima": [ "ide", "sel", "ele" ]
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "ded":[], "cen":[], "res":"()($)ide()" } } ],
          "fic-1-tit": [ "ele", "sel" ]
        }
    }' ),
    ('hol','uni_hum_lad', '{
        "atr": { 
          "ide": { "min":1, "max":3, "dat":"uni_hum_lad" },
          "ton": { "dat":"hol_ton" }
        },
        "val": { 
          "nom": "Lado del Holon Humano #()($)ide() de 3: ()($)nom()",
          "des": "()($)des()",
          "col": 3
        },
        "opc": {
          "ima": [ "ton" ]
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "ton":[], "res":"()($)ide()" } } ],
          "fic-tit": [ "ton" ]
        }
    }' ),  
    ('hol','uni_hum_cen', '{
        "atr": { 
          "ide": { "min":1, "max":5, "dat":"hol_uni_hum_cen" },
          "fam": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Centro Galáctico #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_cen/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "opc": { 
          "ima": [ "ide", "fam", "sel" ]
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "ded":[], "cen":"()($)ide()" } } ],
          "fic": [ "fam" ],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "sel" ]
        }
    }' ),
    ('hol','uni_hum_ded', '{
        "atr": { 
          "ide": { "min":1, "max":5, "dat":"hol_uni_hum_ded" },
          "fam": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Dedo #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_ded/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "opc": { 
          "ima": [ "ide", "fam", "sel" ] 
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "ded":"()($)ide()", "cen":[] } } ],
          "fic": [ "fam" ],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "sel" ]
        }
    }' ),
    ('hol','uni_hum_ext', '{
        "atr": { 
          "ide": { "min":1, "max":4, "dat":"hol_uni_hum_ext" },
          "ele": { "min":1, "max":4, "dat":"hol_sel_cro_ele" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Extremidad #()($)ide() de 4: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_ext/()($)ide().png);",
          "col": 4,
          "num": 4
        },
        "opc": { 
          "ima": [ "ide", "ele", "sel" ] 
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "ext":"()($)ide()", "ded":[], "cen":[] } } ],
          "fic": [ "ele" ],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "sel" ]
        }
    }' ),
    ('hol','uni_hum_mer', '{
        "atr": { 
          "ide": { "min":1, "max":10, "dat":"hol_uni_hum_mer" },
          "fam": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },
          "ele": { "dat":"hol_sel_cro_ele" },
          "sel": { "dat":"hol_sel" }
        },
        "val": { 
          "nom": "Meridiano Orgánico #()($)ide() de 10: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_mer/()($)ide().png);",
          "col": 10,
          "num": 10
        },
        "opc": { 
          "ima": [ "ide" ] 
        },
        "inf": { 
          "opc": [ "des" ],
          "tab": [ "hol.uni.hum", { "sec": { "res":[], "cen":[], "art":[], "ded":[] } } ],
          "fic": [ "fam" ],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Clanes Cromáticos</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-1": [ "ele" ],

          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_02-03-05-\'>Sellos Solares</a> en <cite>el Encantamiento del Sueño</cite>" },
          "fic-2": [ "sel" ]
        }
    }' ),
    ('hol','uni_hum_art', '{
        "atr": { 
          "ide": { "min":1, "max":7, "dat":"hol_uni_hum_art" },
          "ton": { "dat":"hol_ton" }
        },
        "val": { 
          "nom": "Articulación #()($)ide() de 7: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_art/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "art":"()($)ide()", "ded":[] } } ],

          "htm": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/fac#_08-03-01-\'>Tonos Galácticos</a> en<cite>el Factor Maya</cite>" },
          "fic": [ "ton" ]
        }
    }' ),
    ('hol','uni_hum_cha', '{
        "atr": { 
          "ide": { "min":1, "max":7, "dat":"hol_uni_hum_cha" },
          "rad": { "dat":"hol_rad" }
        },
        "val": { 
          "nom": "Chakra #()($)ide() de 7: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_cha/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "inf": { 
          "tab": [ "hol.uni.hum", { "sec": { "art":[], "ded":[], "cha":"()($)ide()" } } ],
          "fic": [ "rad" ]
        }
    }' ),
    ('hol','uni_hum_mud', '{ 
        "atr": { 
          "ide": { "min":1, "max":7, "dat":"hol_uni_hum_cha" },
          "rad": { "dat":"hol_rad" }
        },
        "val": { 
          "nom": "Mudra #()($)ide() de 7: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/hum_mud/()($)ide().png);",
          "num": 7
        }
    }' )
  ;  

  DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'arm%'; INSERT INTO `dat_est` VALUES

    ('hol','arm', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_arm" }
      },
      "val": { 
        "nom": "Código Armónico #()($)ide() de 4: ()($)des_col().",
        "des": "Dirección: ()($)des_dir(); Poder: ()($)des_pod(); Dualidad: ()($)des_pol(); Momento de Mayor vibración: ()($)des_dia().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/arm/()($)ide().png);",
        "col": 4,
        "num": 4
      }
    }' )
  ;

  DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'cro%'; INSERT INTO `dat_est` VALUES

    ('hol','cro', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_cro" }
      },      
      "val": { 
        "nom": "Código Cromático #()($)ide() de 5: ()($)des_cod().",
        "des": "Color: ()($)des_col(); Posición: ()($)des_lad(); Dirección: ()($)des_dir(); Poder: ()($)des_pod(); Función: ()($)des_fun(); Acción: ()($)des_acc();",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/cro/()($)ide().png);",        
        "col": 5,
        "num": 5
      }
    }' )
  ;

  DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'rad%'; INSERT INTO `dat_est` VALUES

    ('hol','rad', '{
        "atr": { 
          "ide":         { "min":1, "max":7, "dat":"hol_rad" },            
          "tel_año":     { "min":1997, "max":1999, "dat":"api.fec_año" },
          "tel_ora_año": { "min":1, "max":260, "dat":"hol_kin_par" },
          "tel_ora_ani": { "min":1, "max":260, "dat":"hol_kin_par" },
          "tel_ora_gen": { "min":1, "max":260, "dat":"hol_kin_par" },
          "pla_fue":     { "dat":"hol_rad_pla_fue" },
          "pla_qua":     { "min":1, "max":3, "dat":"hol_rad_pla_qua" },
          "pla_cub":     { "min":1, "max":7, "dat":"hol_rad_pla_cub" },
          "hum_cha":     { "min":1, "max":7, "dat":"hol_uni_hum_cha" }
        },
        "val": { 
          "nom": "Plasma #()($)ide() de 7: ()($)nom().",
          "des": "\'()($)pla_lec()\'",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "opc": { 
          "ver": [ 
            "ide", 
            "pla_fue", "pla_cub", "pla_qua",
            "hum_cha"
          ],
          "ima": [
            "ide",
            "pla_fue", "pla_cub", "pla_qua",
            "tel_ora_año", "tel_ora_ani", "tel_ora_gen",
            "hum_cha"
          ],
          "col": [
            "ide", "pla_qua"
          ],
          "num": [
            "ide", "pla_qua" 
          ]
        },
        "lis": { 

          "atr": [ "ide", "nom", "des_pod", "des_fue", "pla_qua" ]
        },
        "inf": {
          "det": [ "des_col", "des_pod", "des_fue" ],

          "tab": [ "hol.rad.pla", {
            "val":{ "pos":"()($)ide()" },
            "pos":{ "ima":"hol.rad.ide", "col":"hol.rad.ide" }
          }],

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/tel#_02-06-\'>Oráculos y las Profecías</a> en <cite>el Telektonon</cite>" },
          "tex-1": "()($)tel() ( ()($)tel_año() ): ()($)tel_des().",
          "fic-1-tit": [ "tel_ora_año", "tel_ora_ani", "tel_ora_gen" ],
          
          "htm-2": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/rin#_02-05-01-\'>Funciones Anuales</a> en <cite>el Proyecto Rinri</cite>" },
          "atr-2": "rin_des",

          "htm-3": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/ato#_03-01-\'>Componentes Electrónicos</a> en <cite>el Átomo del Tiempo</cite>" },
          "atr-3": "pla_des",
          "fic-3": [ "pla_fue" ],

          "htm-4": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/ato#_03-06-\'>Autodeclaraciones Diarias de Padmasambhava</a> en <cite>el Átomo del Tiempo</cite>" },
          "atr-4": "pla_lec",
          "fic-4": [ "pla_qua" ],

          "htm-5": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/ato#_03-06-\'>Chakras y el Cubo del Radión</a> en <cite>el Átomo del Tiempo</cite>" },
          "fic-5": [ "hum_cha", "pla_cub" ],

          "htm-6": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'http://localhost/hol/bib/umb\'>Mapa Estelar</a> en <cite>el Sincronotrón</cite>" },
          "atr-6": [ "umb_map" ], 

          "htm-7": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'http://localhost/hol/bib/umb\'>Esferas Mentales</a> en <cite>el Sincronotrón</cite>" },
          "tex-7": "()($)umb_esf(): ()($)umb_est_fun()"
        }
    }' ),
    -- cubo
    ('hol','rad_pla_cub', '{
        "atr": { 
          "ide": { "min":1, "max":7, "dat":"hol_rad_pla_cub" }
        },
        "val": { 
          "nom": "Posicion #()($)ide() de 7: ()($)nom()",
          "des": "Correspondiente al plasma ()($)pla()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/pla_cub/()($)ide().png);",
          "col": 4,
          "num": 7
        }
    }' ),
    -- plasma: energía cósmica
    ('hol','rad_pla_pol', '{ 
        "atr": { 
          "ide": { "min":1, "max":2, "dat":"hol_rad_pla_pol" }
        },      
        "val": { 
          "nom": "Carga #()($)ide() de 2: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/pla_car/()($)ide().png);"
        }
    }' ),-- tipo de electricidad
    ('hol','rad_pla_ele', '{
        "atr": { 
          "ide": { "min":1, "max":6, "dat":"hol_rad_pla_ele" }
        },      
        "val": { 
          "nom": "Tipo de Electricidad Cósmica #()($)ide() de 6: ()($)nom() ( ()($)des_cod() ).",
          "des": "()($)des().",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/pla_ele/()($)ide().png);"                      
        },
        "opc": { 
          "ima": [ "ide" ]
        }
    }' ),-- lineas de fuerza
    ('hol','rad_pla_fue', '{
        "atr": { 
          "ide": { "min":1, "max":12, "dat":"hol_rad_pla_fue" },
          "ele_pre": { "min":1, "max":6, "dat":"hol_rad_pla_ele" },
          "ele_pos": { "min":1, "max":6, "dat":"hol_rad_pla_ele" }
        },
        "val": { 
          "nom": "Línea de Fuerza #()($)ide() de 12: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/pla_fue/()($)ide().png);"
        },
        "opc": { 
          "ima": [ "ide", "ele_pre", "ele_pos" ]            
        },
        "inf": {

          "htm-1": { "eti":"p", "class":"tit", "htm":"Los <a target=\'_blank\' href=\'http://localhost/hol/bib/ato#_03-01-\'>Componentes Electrónicos</a> en <cite>el Átomo del Tiempo</cite>" },
          "fic-1": [ "ele_pre", "ele_pos" ]
        }
    }' ),
    ('hol','rad_pla_qua', '{
        "atr": { 
          "ide": { "min":1, "max":3, "dat":"hol_rad_pla_qua" },
          "pla": { "dat":"hol_rad" }
        },      
        "val": {
          "nom": "Quantum #()($)ide() de 3: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/rad/pla_qua/()($)ide().png);",
          "col": 3,
          "num": 3
        },
        "opc": {
          "ima": [ "ide", "pla" ]
        },
        "inf": {
          "opc": [ "des" ],
          "fic": [ "pla" ]
        }
    }' )  
  ;
  
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

  DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'sel%'; INSERT INTO `dat_est` VALUES

    ('hol','sel', '{
      "atr": { 
        "ide":      { "min":1, "max":20, "dat":"hol_sel" },
        "ord":      { "min":1, "max":20,  "dat":"hol_sel_cod" },
        "cod":      { "min":0, "max":19 },

        "cic_ser":  { "min":1, "max":3, 	"dat":"hol_sel_cic_ser" },
        "cic_luz":  { "min":1, "max":5, 	"dat":"hol_sel_cic_luz" },

        "arm_tra":  { "min":1, "max":20, 	"dat":"hol_sel.arm_tra" },
        "arm_raz":  { "min":1, "max":4, 	"dat":"hol_sel_arm_raz" },
        "arm_cel":  { "min":1, "max":5, 	"dat":"hol_sel_arm_cel" },

        "cro_fam":  { "min":1, "max":5, 	"dat":"hol_sel_cro_fam" },
        "cro_ele":  { "min":1, "max":4, 	"dat":"hol_sel_cro_ele" },

        "par_ana":  { "min":1, "max":20, 	"dat":"hol_sel" },
        "par_ant":  { "min":1, "max":20, 	"dat":"hol_sel" },
        "par_ocu":  { "min":1, "max":20, 	"dat":"hol_sel" },

        "flu_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_res" },
        
        "sol_pla":  { "min":1, "max":10, 	"dat":"hol_uni_sol_pla" },
        "sol_res":  { "min":1, "max":2, 	"dat":"hol_uni_sol_res" },
        "sol_orb":  { "min":1, "max":2, 	"dat":"hol_uni_sol_orb" },
        "sol_cel":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cel" },
        "sol_cir":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cir" },

        "pla_res":  { "min":1, "max":2, 	"dat":"hol_uni_pla_res" },
        "pla_mer":  { "min":1, "max":2, 	"dat":"hol_uni_pla_mer" },
        "pla_hem":  { "min":1, "max":3, 	"dat":"hol_uni_pla_hem" },
        "pla_cen":  { "min":1, "max":5, 	"dat":"hol_uni_pla_cen" },

        "hum_res":  { "min":1, "max":2, 	"dat":"hol_uni_hum_res" },
        "hum_cen":  { "min":1, "max":5, 	"dat":"hol_uni_hum_cen" },
        "hum_ext":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ext" },
        "hum_ded":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ded" },
        "hum_mer":  { "min":1, "max":10, 	"dat":"hol_uni_hum_mer" }
      },
      "val": { 
        "nom": "Sello Solar #()($)ide() de 20, ()($)arm().",
        "des": "()($)des_car() ()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/()($)ide().png);",
        "col": 4,
        "num": 20
      },
      "opc": { 
        "ver": [ 
          "ide", "ord",
          "cic_ser", "cic_luz", 
          "cro_fam", "cro_ele", "arm_raz", "arm_cel",
          "flu_res",
          "sol_pla", "sol_cel", "sol_cir", 
          "pla_cen", "pla_hem", "pla_mer",
          "hum_cen", "hum_ext", "hum_ded", "hum_mer"
        ],
        "ima": [
          "ide", "ord", 
          "par_ana", "par_ant", "par_ocu",
          "cro_fam", "cro_ele", "arm_raz", "arm_cel",
          "flu_res",
          "sol_pla", "sol_cel", "sol_cir", 
          "pla_cen", "pla_hem", "pla_mer",
          "hum_cen", "hum_mer", "hum_ext", "hum_ded"
        ],
        "col": [
          "ide", 
          "cic_ser", "cic_luz", 
          "cro_fam", "cro_ele", "arm_raz", "arm_cel",
          "flu_res",
          "sol_pla", "sol_cel", "sol_cir", 
          "pla_cen", "pla_hem", "pla_mer",
          "hum_cen", "hum_mer", "hum_ext", "hum_ded"
        ],
        "num": [ 
          "ide", "cod", 
          "cic_ser", "cic_luz", 
          "par_ana", "par_ant", "par_ocu", 
          "cro_fam", "cro_ele", "arm_tra", "arm_raz", "arm_cel",
          "flu_res",
          "sol_pla", "sol_cel", "sol_cir", 
          "pla_cen", "pla_hem", "pla_mer",
          "hum_cen", "hum_mer", "hum_ext", "hum_ded"
        ],
        "tex": [
        ]
      },
      "inf": { 
        "det": [ "des_car", "des_acc", "des_pod" ],

        "htm-1-1": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/fac#_04-04-02-04-\'>Desarrollo del Ser</a> en <cite>el Factor Maya</cite>" },
        "atr-1-1": [ "cic_ser_des" ],

        "htm-1-2": { "eti":"p", "class":"tit", "htm":"Las <a target=\'_blank\' href=\'hol/bib/fac#_04-04-02-05-\'>Familias Cíclicas de la Luz</a> en <cite>el Factor Maya</cite>" },
        "atr-1-2": [ "cic_luz_des" ],

        "htm-1-3": { "eti":"p", "class":"tit", "htm":"El <a target=\'_blank\' href=\'hol/bib/fac#_05-06-03-\'>Modelo Morfogenético</a> en <cite>el Factor Maya</cite>" },
        "atr-1-3": [ "arm_tra_des" ],
        
        "htm-2": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-04-\'>Colocación Armónica</a> en <cite>el Encantamiento del Sueño</cite>" },
        "tab-2": [ "hol.sel.arm", { "val": { "pos":"()($)ide()" }, "pos": { "ima":"hol.sel.ide" } } ],
        "fic-2-1": [ "arm_cel" ],
        "atr-2-1": [ "arm_cel_des" ],
        "fic-2-2": [ "arm_raz" ],
        "atr-2-2": [ "arm_raz_des" ],

        "htm-3": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Colocación Cromática</a> en <cite>el Encantamiento del Sueño</cite>" },
        "tab-3": [ "hol.sel.cro", { "val": { "pos":"()($)ide()" }, "pos": { "ima":"hol.sel.ide" } } ],
        "fic-3-1": [ "cro_ele" ],
        "atr-3-1": [ "cro_ele_des" ],
        "fic-3-2": [ "cro_fam" ],
        "atr-3-2": [ "sol_pla_des" ],

        "htm-4": { "eti":"p", "class":"tit", "htm":"Descripciones en el Libro <cite><q>El kin, tu signo maya</q></cite>" },
        "atr-4": [ "cit_des", "cit_por", "cit_por_som", "cit_por_rec" ],
        "lis-4": [ "cit_lis_pal", "cit_lis_som" ]

      },
      "lis": { 

        "atr": [ "ide", "cod", "nom", "des_car", "des_acc", "des_pod",
          "cro_fam", "cro_ele", 
          "arm_raz", "arm_cel", 
          "par_ana", "par_ant", "par_ocu",
          "flu_res", "sol_pla", "pla_cen", "hum_mer", "hum_cen", "hum_ded"
        ],
        "atr_ocu": [
          "cro_fam", "cro_ele", 
          "arm_raz", "arm_cel", 
          "par_ana", "par_ant", "par_ocu",
          "flu_res", "sol_pla", "pla_cen", "hum_mer", "hum_cen", "hum_ded" 
        ],

        "tit_gru": [ "cro_fam", "arm_raz", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_mer", "hum_cen", "hum_ded" ],
        "tit_cic": [ "cic_ser", "cic_luz", "cro_ele", "arm_cel", "flu_res" ],

        "det_des": [ "cit_des", "cit_por", "cit_por_som", "cit_por_rec" ]
      }
    }' ),
    ('hol','sel_cod', '{
      "atr": {
        "ide":      { "min":1, "max":20,  "dat":"hol_sel_cod" },
        "sel":      { "min":1, "max":20,  "dat":"hol_sel" },
        "cod":      { "min":0, "max":19 },
        "cro_fam":  { "min":1, "max":5, 	"dat":"hol_sel_cro_fam" },
        "cro_ele":  { "min":1, "max":4, 	"dat":"hol_sel_cro_ele" },
        "flu_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_res" },          
        "sol_pla":  { "min":1, "max":10, 	"dat":"hol_uni_sol_pla" },
        "sol_res":  { "min":1, "max":2, 	"dat":"hol_uni_sol_res" },
        "sol_orb":  { "min":1, "max":2, 	"dat":"hol_uni_sol_orb" },
        "sol_cel":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cel" },
        "sol_cir":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cir" },
        "pla_res":  { "min":1, "max":2, 	"dat":"hol_uni_pla_res" },
        "pla_mer":  { "min":1, "max":2, 	"dat":"hol_uni_pla_mer" },
        "pla_hem":  { "min":1, "max":3, 	"dat":"hol_uni_pla_hem" },
        "pla_cen":  { "min":1, "max":5, 	"dat":"hol_uni_pla_cen" },          
        "hum_res":  { "min":1, "max":2, 	"dat":"hol_uni_hum_res" },
        "hum_cen":  { "min":1, "max":5, 	"dat":"hol_uni_hum_cen" },
        "hum_ext":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ext" },
        "hum_ded":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ded" },
        "hum_mer":  { "min":1, "max":10, 	"dat":"hol_uni_hum_mer" }
      },
      "val": { 
        "nom": "Código #()($)cod() de 20: ()($)des_cod()",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cod/()($)ide().png);",
        "col": 5,
        "num": 20
      },
      "opc": { 
        "ima": [
          "ide", "sel",
          "cro_fam", "cro_ele", 
          "flu_res", "sol_pla", "sol_cel", "sol_cir", 
          "pla_res", "pla_cen", "pla_hem", "pla_mer", 
          "hum_res","hum_cen", "hum_ext", "hum_ded", "hum_mer"
        ]            
      },    
      "inf": { 
        "det": [ "des_car", "des_acc", "des_pod" ],

        "htm-3": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Colocación Cromática</a> en <cite>el Encantamiento del Sueño</cite>" },
        "tab-3": [ "hol.sel.cro", { "val": { "pos":"()($)sel()" }, "pos": { "ima":"hol.sel.ide" } } ],
        "fic-3-1": [ "cro_ele" ],
        "atr-3-1": [ "cro_ele_des" ],
        "fic-3-2": [ "cro_fam" ],
        "atr-3-2": [ "sol_pla_des" ]

      }
    }' ),
    -- ciclos: direccional + desarrollo del ser + familia ciclica de la luz
    ('hol','sel_cic_dir', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_sel_cic_dir" },
        "sel": { "dat":"hol_sel" }
      },
      "val": { 
        "nom": "Ciclo Direccional #()($)ide() de 4: ()($)nom()",
        "des": "()($)des().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cic_dir/()($)ide().png);",
        "col": 4
      },
      "opc": { 
        "ima": [ "ide", "sel" ] 
      }
    }' ),
    ('hol','sel_cic_ser', '{
      "atr": { 
        "ide": { "min":1, "max":3, "dat":"hol_sel_cic_ser" },
        "sel": { "dat":"hol_sel" }
      },
      "val": { 
        "nom": "Desarrollo del ser #()($)ide() de 3: ()($)nom().",
        "des": "()($)des().",
        "col": 3,
        "num": 3
      },
      "opc": {
        "ima": [ "sel" ]
      }
    }' ),
    ('hol','sel_cic_luz', '{ 
      "atr": { 
        "ide": { "min":1, "max":5, "dat":"hol_sel_cic_luz" },
        "sel": { "dat":"hol_sel" }
      },
      "val": { 
        "nom": "Familia Cíclica de la luz #()($)ide() de 5: ()($)nom().",
        "des": "()($)des().",
        "col": 5,
        "num": 5
      },
      "opc": {
        "ima": [ "sel" ]
      }
    }' ),
    -- parejas: analogo + antipoda + oculto
    ('hol','sel_par_ana', '{
      "atr": { 
        "ini": { "min":1, "max":20, "dat":"hol_sel" },
        "fin": { "min":1, "max":20, "dat":"hol_sel" }
      },
      "opc": { 
        "ima": [ "ini", "fin" ]            
      }
    }' ),
    ('hol','sel_par_ant', '{
      "atr": { 
        "ini": { "min":1, "max":20, "dat":"hol_sel" },
        "fin": { "min":1, "max":20, "dat":"hol_sel" }
      },
      "opc": { 
        "ima": [ "ini", "fin" ]            
      }
    }' ),
    ('hol','sel_par_ocu', '{
      "atr": { 
        "ini": { "min":1, "max":20, "dat":"hol_sel" },
        "fin": { "min":1, "max":20, "dat":"hol_sel" }
      },
      "opc": { 
        "ima": [ "ini", "fin" ]
      }
    }' ),
    ('hol','sel_par_gui', '{
      "atr": { 
        "ide": { "min":1, "max":20, "dat":"hol_sel" },
        "sel_1": { "min":1, "max":20, "dat":"hol_sel" },
        "sel_2": { "min":1, "max":20, "dat":"hol_sel" },
        "sel_3": { "min":1, "max":20, "dat":"hol_sel" },
        "sel_4": { "min":1, "max":20, "dat":"hol_sel" },
        "sel_5": { "min":1, "max":20, "dat":"hol_sel" }
      },
      "opc": { 
        "ima": [ "ide", "sel_1", "sel_2", "sel_3", "sel_4", "sel_5" ]
      }
    }' ),  
    -- armónicas
    ('hol','sel_arm_raz', '{
      "atr": {
        "ide": { "min":1, "max":4, "dat":"hol_sel_arm_raz" },
        "sel": { "dat":"hol_sel" }
      },
      "val": { 
        "nom": "Raza Raiz Cósmica #()($)ide() de 4: ()($)nom().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/arm_raz/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "opc": { 
        "ima": [ "ide", "sel" ]
      },
      "inf": {
        "opc": [ "des" ],

        "det": [ "des_dir", "des_pod", "des_dia" ],

        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-04-\'>Colocación Armónica</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic": [ "sel" ]
      }
    }' ),
    ('hol','sel_arm_cel', '{
      "atr": { 
        "ide": { "min":1, "max":5, "dat":"hol_sel_arm_cel" },
        "sel": { "dat":"hol_sel" }
      },
      "val": { 
        "nom": "Célula del Tiempo #()($)ide() de 5: ()($)nom().",
        "des": "()($)des().", 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/arm_cel/()($)ide().png);",
        "col": 5,
        "num": 5
      },
      "opc": { 
        "ima": [ "ide", "sel" ]
      },
      "inf": { 
        "opc": [ "des" ],

        "det": [ "des_fun", "des_pod" ],

        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-05-\'>Colocación Armónica</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic": [ "sel" ]
      }
    }' ),
    ('hol','sel_arm_tra', '{
      "atr": { 
        "ide": { "min":1, "max":20, "dat":"hol_sel_arm_tra" },
        "sel": { "dat":"hol_sel" }
      },        
      "val": { 
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel.png), center/contain no-repeat url(http://localhost/img/hol/fic/sel/()($)ide().png);"
      },
      "opc": { 
        "ima": [ "ide" ]
      }  
    }' ),
    -- cromaticas
    ('hol','sel_cro_fam', '{
      "atr": { 
        "ide": { "min":1, "max":5, "dat":"hol_sel_cro_fam" },
        "sel": { "dat":"hol_sel" },
        "pla_cen": { "min":1, "max":5, "dat":"hol_uni_pla_cen" },
        "hum_cen": { "min":1, "max":5, "dat":"hol_uni_hum_cen" },
        "hum_ded": { "min":1, "max":5, "dat":"hol_uni_hum_ded" }
      },
      "val": { 
        "nom": "Familia Terrestre #()($)ide() de 5: ()($)nom().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cro_fam/()($)ide().png);",
        "col": 5,
        "num": 5
      },
      "opc": { 
        "ima": [ "ide", "sel", "pla_cen", "hum_cen", "hum_ded" ]
      },
      "inf": { 
        "det": [ "des_fun", "des_mis", "des_rol", "des_acc" ],

        "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-14-\'>Cuenta atrás</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic": [ "sel" ],

        "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-07-\'>Holon Planetario</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic-1": [ "pla_cen" ], 

        "htm-2": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-08-\'>Holon Humano</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic-2": [ "hum_cen", "hum_ded" ]
      }
    }' ),
    ('hol','sel_cro_ele', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_sel_cro_ele" },
        "sel": { "dat":"hol_sel" },
        "arm_cel": { "dat":"hol_sel_arm_cel" },
        "flu_res": { "min":1, "max":2, "dat":"hol_uni_flu_res" },
        "hum_ext": { "min":1, "max":4, "dat":"hol_uni_hum_ext" }
      },
      "val": { 
        "nom": "Clan #()($)ide() de 4: ()($)nom() ()($)des_col().",
        "des": "()($)des().",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cro_ele/()($)ide().png);",
        "col": 4,
        "num": 4
      },
      "opc": { 
        "ima": [ "ide", "sel", "flu_res", "hum_ext" ]
      },
      "inf": { 
        "det": [ "des_col", "des_men" ],
        "atr": [ "des" ],

        "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Colocación Cromática</a> d<cite>el Encantamiento del Sueño</cite>" },
        "fic-1": [ "sel" ],
        
        "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Células del Tiempo</a> en el Íncide Armónico d<cite>el Encantamiento del Sueño</cite>" },
        "fic-2": [ "arm_cel" ]
      }
    }' )
  ;

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
          "ide": { "min":1, "max":4, "dat":"hol_cas_dim" },
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
          "ide": { "min":1, "max":5, "dat":"hol_cas_mat" },
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
          "ide": { "min":1, "max":7, "dat":"hol_cas_sim" },
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
          "ene", "ene_cam", "chi",
          "gen_enc", "gen_cel", 
          "nav_cas", "nav_ond", 
          "cro_est", "cro_ele", 
          "arm_tra", "arm_cel" 
        ],
        "col": [
          "ene", 
          "gen_enc", "gen_cel", 
          "nav_cas", "nav_ond", 
          "cro_est", "cro_ele", 
          "arm_tra", "arm_cel"
        ],
        "ima": [
          "ide", 
          "ene", "ene_cam", "chi", 
          "par_ana", "par_gui", "par_ant", "par_ocu", 
          "nav_cas", "nav_ond", 
          "arm_tra", "arm_cel", 
          "cro_est", "cro_ele"
        ],
        "num": [ 
          "ide", "psi", "ene", "ene_cam", 
          "gen_enc", "gen_enc_dia", "gen_cel", "gen_cel_dia", 
          "nav_cas", "nav_cas_dia", "nav_ond", "nav_ond_dia", 
          "cro_est", "cro_est_dia", "cro_ele", "cro_ele_dia", 
          "arm_tra", "arm_tra_dia", "arm_cel", "arm_cel_dia"
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
        "opc": [ "par", "dim", "mat", "sim" ]          
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
        "opc": [ "par", "dim", "mat", "sim" ]          
      }
    }' ),
    ('hol','kin_cro_ond', '{
      "atr": { 
        "ide": { "min":1, "max":4, "dat":"hol_kin_cro_ond" },
        "ton": { "dat":"hol_ton" }
      },
      "val": { 
        "nom": "Aventura de la Onda Encantada #()($)ide() de 4: ()($)des().",
        "des": "Lectura del Factor Maya ()($)fac().\\nLectura del Encantamiento ()($)enc()\\n",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/ton/ond/()($)ide().png);",
        "num": 4,
        "col": 4            
      },
      "opc": { 
        "ima": [ "ide", "ton" ]            
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
        "nom": "Espectro Galáctico #()($)ide() de 4: ()($)des_col() ()($)des_dir().",
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
        "opc": [ "par", "dim", "mat", "sim" ]          
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
          "kin",
          "ani_lun", "ani_vin",
          "hep_est", "hep_pla"
        ],
        "ima": [
          "kin",
          "ani_lun", 
          "hep_est", "hep_pla"
        ],
        "col": [
          "ani_lun", "hep_est"
        ],
        "num": [ 
          "ide", "fec", "kin",           
          "ani_lun", "ani_lun_dia", 
          "ani_vin", "ani_vin_dia",  
          "ani_cro", "ani_cro_dia",
          "hep_est", "hep_est_dia",
          "hep_pla", "hep_pla_dia"          
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