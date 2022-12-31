
DELETE FROM `dat_est` WHERE `esq`='hol' AND `ide` LIKE 'rad%'; INSERT INTO `dat_est` VALUES

  ('hol','rad', '{
      "atr": { 
        "ide":         { "min":1, "max":7, "dat":"hol_rad" },            
        "tel_ora":     { "min":1997, "max":1999, "dat":"api.fec_año" },
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