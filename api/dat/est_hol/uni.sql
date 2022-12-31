-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

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
        "ima": [ "ide","orb","cel","cir" ]
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
  ('hol','uni_sol_res', '{
      "atr": { 
        "ide": { "min":1, "max":2, "dat":"hol_uni_sol_res" }
      },
      "val": { 
        "nom": "Respiración del Holon Solar #()($)ide() de 2: ()($)nom()",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/flu_res/()($)ide().png);",
        "col": 2
      },
      "opc": { 
        "ima": [ "ide" ]
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
        "ima": [ "ide" ]
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
        "ima": [ "ide" ]
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
        "ide": { "min":1, "max":2, "dat":"hol_uni_pla_res" }
      },
      "val": { 
        "nom": "Respiración del Holon Planetario #()($)ide() de 2: ()($)nom()",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/uni/flu_res/()($)ide().png);",
        "col": 2
      },
      "opc": { 
        "ima": [ "ide" ]
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
        "ima": [ "ide", "fam" ]            
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
        "ima": [ "ide", "fam" ]
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
        "ima": [ "ide", "fam" ] 
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
        "ima": [ "ide", "ele" ] 
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