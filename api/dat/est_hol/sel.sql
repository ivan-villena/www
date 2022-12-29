
DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'sel%'; INSERT INTO `dat_est` VALUES

  ('hol','sel', '{
    "atr": { 
      "ide":      { "min":1, "max":20, "dat":"hol_sel" },
      "cod":      { "min":0, "max":19 },
      "ord":      { "min":1, "max":20,  "dat":"hol_sel_cod" },

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

      "flu_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_pod" },
      
      "sol_pla":  { "min":1, "max":10, 	"dat":"hol_uni_sol_pla" },
      "sol_res":  { "min":1, "max":2, 	"dat":"hol_uni_sol_res" },
      "sol_orb":  { "min":1, "max":2, 	"dat":"hol_uni_sol_orb" },
      "sol_cel":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cel" },
      "sol_cir":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cir" },

      "pla_res":  { "min":1, "max":2, 	"dat":"hol_uni_pla_res" },
      "pla_mer":  { "min":1, "max":2, 	"dat":"hol_uni_pla_mer" },
      "pla_hem":  { "min":1, "max":3, 	"dat":"hol_uni_pla_hem" },
      "pla_cen":  { "min":1, "max":5, 	"dat":"hol_uni_pla_cen" },

      "hum_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_res" },
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
    },
    "opc": { 
      "ver": [ 
        "ide", "cic_luz", "cro_fam", "cro_ele", "arm_raz", "arm_cel",
        "flu_res", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_ext", "hum_cen", "hum_ded", "hum_mer" 
      ],
      "ima": [
        "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
        "flu_res", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
      ],
      "col": [
        "ide", "ord", "par_ana", "par_ant", "par_ocu", "cic_ser", "cic_luz", "cro_fam", "cro_ele", "arm_raz", "arm_cel",
        "flu_res", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_mer"
      ],
      "num": [ 
        "ide", "cod", "cic_ser", "cic_luz", 
        "cro_fam", "cro_ele", "arm_tra", "arm_raz", "arm_cel", "par_ana", "par_ant", "par_ocu", 
        "flu_res", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_ext", "hum_cen", "hum_ded", "hum_mer"
      ],
      "tex": [
      ]
    }
  }' ),
  ('hol','sel_cod', '{
    "atr": { 
      "ide":      { "min":1, "max":20, "dat":"hol_sel" },
      "cod":      { "min":0, "max":19 },
      "ord":      { "min":1, "max":20,  "dat":"hol_sel_cod" },
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
      "flu_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_pod" },          
      "sol_pla":  { "min":1, "max":10, 	"dat":"hol_uni_sol_pla" },
      "sol_res":  { "min":1, "max":2, 	"dat":"hol_uni_sol_res" },
      "sol_orb":  { "min":1, "max":2, 	"dat":"hol_uni_sol_orb" },
      "sol_cel":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cel" },
      "sol_cir":  { "min":1, "max":5, 	"dat":"hol_uni_sol_cir" },
      "pla_res":  { "min":1, "max":2, 	"dat":"hol_uni_pla_res" },
      "pla_mer":  { "min":1, "max":2, 	"dat":"hol_uni_pla_mer" },
      "pla_hem":  { "min":1, "max":3, 	"dat":"hol_uni_pla_hem" },
      "pla_cen":  { "min":1, "max":5, 	"dat":"hol_uni_pla_cen" },          
      "hum_res":  { "min":1, "max":2, 	"dat":"hol_uni_flu_res" },
      "hum_cen":  { "min":1, "max":5, 	"dat":"hol_uni_hum_cen" },
      "hum_ext":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ext" },
      "hum_ded":  { "min":1, "max":5, 	"dat":"hol_uni_hum_ded" },
      "hum_mer":  { "min":1, "max":10, 	"dat":"hol_uni_hum_mer" }
    },
    "val": { 
      "nom": "Código #()($)cod()",
      "des": "()($)car() ()($)des().",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cod/()($)cod().png);",
      "col": 5,
      "num": 20
    },
    "inf": { 
      "det": [ "des_car", "des_acc", "des_pod" ],

      "htm-3": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Colocación Cromática</a> en <cite>el Encantamiento del Sueño</cite>" },
      "tab-3": [ "hol.sel.cro", { "val": { "pos":"()($)ide()" }, "pos": { "ima":"hol.sel.ide" } } ],
      "fic-3-1": [ "cro_ele" ],
      "atr-3-1": [ "cro_ele_des" ],
      "fic-3-2": [ "cro_fam" ],
      "atr-3-2": [ "sol_pla_des" ]

    },
    "opc": { 
      "ima": [
        "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
        "flu_res", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
      ]            
    }
  }' ),
  -- ciclos: direccional + desarrollo del ser + familia ciclica de la luz
  ('hol','sel_cic_dir', '{
    "atr": { 
      "ide": { "min":1, "max":4, "dat":"hol_sel_cic_dir" }
    },
    "val": { 
      "nom": "Ciclo Direccional #()($)ide() de 4: ()($)nom()",
      "des": "()($)des().", 
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cic_dir/()($)ide().png);",
      "col": 4
    },
    "opc": { 
      "ima": [ "ide" ] 
    }
  }' ),
  ('hol','sel_cic_ser', '{
    "atr": { 
      "ide": { "min":1, "max":3, "dat":"hol_sel_cic_ser" }
    },
    "val": { 
      "nom": "Desarrollo del ser #()($)ide() de 3: ()($)nom().",
      "des": "()($)des().",
      "col": 3,
      "num": 3
    }
  }' ),
  ('hol','sel_cic_luz', '{ 
    "atr": { 
      "ide": { "min":1, "max":5, "dat":"hol_sel_cic_luz" }
    },
    "val": { 
      "nom": "Grupo Cíclico de la luz #()($)ide() de 5: ()($)nom().",
      "des": "()($)des().",
      "col": 5,
      "num": 5
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
    "inf": {
      "opc": [ "des" ],

      "det": [ "des_dir", "des_pod", "des_dia" ],

      "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-04-\'>Colocación Armónica</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic": [ "sel" ]
    },
    "opc": { 
      "ima": [ "ide" ]
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
    "inf": { 
      "opc": [ "des" ],

      "det": [ "des_fun", "des_pod" ],

      "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-05-\'>Colocación Armónica</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic": [ "sel" ]
    },
    "opc": { 
      "ima": [ "ide" ]
    }
  }' ),
  ('hol','sel_arm_tra', '{
    "atr": { 
      "ide": { "min":1, "max":20, "dat":"hol_sel_arm_tra" }
    },        
    "val": { 
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel.png), center/contain no-repeat url(http://localhost/img/hol/fic/sel/()($)ide().png);"
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
    "inf": { 
      "det": [ "des_fun", "des_mis", "des_rol", "des_acc" ],

      "htm": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-14-\'>Cuenta atrás</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic": [ "sel" ],

      "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-07-\'>Holon Planetario</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic-1": [ "pla_cen" ], 

      "htm-2": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-08-\'>Holon Humano</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic-2": [ "hum_cen", "hum_ded" ]
    },
    "opc": { 
      "ima": [ "ide", "pla_cen", "hum_cen", "hum_ded" ]
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
      "nom": "Clan #()($)ide() de 4: ()($)nom() ()($)col().",
      "des": "()($)des().",
      "ima": "background: center/contain no-repeat url(http://localhost/img/hol/fic/sel/cro_ele/()($)ide().png);",
      "col": 4,
      "num": 4
    },
    "inf": { 
      "det": [ "des_col", "des_men" ],
      "atr": [ "des" ],

      "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Colocación Cromática</a> d<cite>el Encantamiento del Sueño</cite>" },
      "fic-1": [ "sel" ],
      
      "htm-1": { "eti":"p", "class":"tit", "htm":"La <a target=\'_blank\' href=\'http://localhost/hol/bib/enc#_03-02-\'>Células del Tiempo</a> en el Íncide Armónico d<cite>el Encantamiento del Sueño</cite>" },
      "fic-2": [ "arm_cel" ]
    },
    "opc": { 
      "ima": [ "ide", "flu_res", "hum_ext" ]
    }          
  }' )
;