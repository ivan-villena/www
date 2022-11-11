-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api
--
-- Calendario
  DELETE FROM `app_dat` WHERE `esq` = 'api'
  ;
  DELETE FROM `app_dat` WHERE `esq` = 'fec'; INSERT INTO `app_dat` VALUES
    -- fecha
    ( 'fec', 'dat', '{
        "val": {
        },    
        "rel": {
          "val":"fec_dat", 
          "dia":"fec_dia", 
          "sem":"fec_sem", 
          "año":"fec_año"
        },
        "est":{
          "atr": [ "val" ]
        },
        "opc": {
          "ver": [ "dia", "sem", "mes" ],
          "num": [ "dia", "sem", "mes" ]
        }        
    }')
  ;
--
-- Holon
  DELETE FROM `app_dat` WHERE `esq`='hol'
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'uni%'; INSERT INTO `app_dat` VALUES

    -- holon solar
    ('hol','uni_sol_res', '{
        "val": {
          "nom": "Respiración Solar #()($)ide() de 2: ()($)nom()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/sol_res/()($)ide().png);",
          "col": 2
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":2, "dat":"hol.uni_sol_res" }
        },
        "opc": {
          "ima": [ "ide" ]
        }          
    }' ),
    ('hol','uni_sol_orb', '{
        "val": {
          "nom": "Grupo Orbital #()($)nom() de 2: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/sol_orb/()($)ide().png);",
          "col": 2
        },
        "atr": {
          "ide": { "min":1, "max":2, "dat":"hol.uni_sol_orb" }
        },
        "opc": {
          "ima": [ "ide" ]
        }          
    }' ),    
    ('hol','uni_sol_pla', '{
        "val": {
          "nom": "Órbita Planetaria #()($)ide() de 10: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/sol_pla/()($)ide().png);",
          "col": 10,
          "num": 10
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":10, "dat":"hol.uni_sol_pla" },
          "orb": { "min":1, "max": 5, "dat":"hol.uni_sol_orb" },
          "cel": { "min":1, "max": 5, "dat":"hol.uni_sol_cel" },
          "cir": { "min":1, "max": 5, "dat":"hol.uni_sol_cir" }
        },
        "opc": {
          "ima": [ "ide","orb","cel","cir" ]
        }          
    }' ),
    ('hol','uni_sol_cel', '{
        "val": {
          "nom": "Célula Solar #()($)ide() de 5: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/sol_cel/()($)ide().png);",
          "col": 5, 
          "num": 5
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":5, "dat":"hol.uni_sol_cel" }
        },
        "opc": {
          "ima": [ "ide" ]
        }          
    }' ),
    ('hol','uni_sol_cir', '{
        "val": {
          "nom": "Circuito de Telepatía #()($)ide() de 5: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/sol_cir/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":5, "dat":"hol.uni_sol_cir" }
        },
        "opc": {
          "ima": [ "ide" ]
        }          
    }' ),
    -- holon planetario
    ('hol','uni_pla_res', '{
        "val": {
          "nom": "Flujo de la Respiración Planetaria #()($)ide() de 2: ()($)nom()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/pla_res/()($)ide().png);",
          "col": 2
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":2, "dat":"hol.uni_pla_res" },
          "hem": { "min":1, "max":3, "dat":"hol.uni_pla_hem" },
          "fam": { "min":1, "max":5, "dat":"hol.sel_cro_fam" }
        },
        "opc": {
          "ima": [ "ide","hem","fam" ]
        }          
    }' ),
    ('hol','uni_pla_cen', '{
        "val": {
          "nom": "Centro Planetario #()($)ide() de 5: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/pla_cen/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":5, "dat":"hol.uni_pla_cen" },
          "fam": { "min":1, "max":5, "dat":"hol.sel_cro_fam" }
        },
        "opc": {
          "ima": [ "ide", "fam" ]            
        }          
    }' ),
    ('hol','uni_pla_hem', '{
        "val": {
          "nom": "Hemisferio #()($)ide() de 3: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/pla_hem/()($)ide().png);",
          "col": 3,
          "num": 3
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":3, "dat":"hol.uni_pla_hem" }
        },
        "opc": {
          "ima": [ "ide" ]            
        }          
    }' ),
    ('hol','uni_pla_mer', '{
        "val": {
          "nom": "Meridiano #()($)ide() de 2: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/pla_mer/()($)ide().png);",
          "col": 2,
          "num": 2
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":2, "dat":"hol.uni_pla_mer" }
        },
        "opc": {
          "ima": [ "ide" ]            
        }          
    }' ),
    -- holon humano
    ('hol','uni_hum_res', '{
        "val": {
          "nom": "Flujo de la Respiración del Holon Humano #()($)ide() de 2: ()($)nom()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_res/()($)ide().png);",
          "col": 2
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":2, "dat":"hol.uni_hum_res" }
        },
        "opc": {
          "ima": [ "ide" ]            
        }          
    }' ),
    ('hol','uni_hum_cen', '{
        "val": {
          "nom": "Centro Galáctico #()($)ide() de 5: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_cen/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":5, "dat":"hol.uni_hum_cen" },
          "fam": { "min":1, "max":5, "dat":"hol.sel_cro_fam" }
        },
        "opc": {
          "ima": [ "ide", "fam" ] 
        }          
    }' ),
    ('hol','uni_hum_ded', '{
        "val": {
          "nom": "Dedo #()($)ide() de 5: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_ded/()($)ide().png);",
          "col": 5,
          "num": 5
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":5, "dat":"hol.uni_hum_ded" },
          "fam": { "min":1, "max":5, "dat":"hol.sel_cro_fam" }
        },
        "opc": {
          "ima": [ "ide", "fam" ] 
        }          
    }' ),
    ('hol','uni_hum_ext', '{
        "val": {
          "nom": "Extremidad #()($)ide() de 4: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_ext/()($)ide().png);",
          "col": 4,
          "num": 4
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":4, "dat":"hol.uni_hum_ext" },
          "ele": { "min":1, "max":4, "dat":"hol.sel_cro_ele" }
        },
        "opc": {
          "ima": [ "ide", "ele" ] 
        }          
    }' ),
    ('hol','uni_hum_mer', '{
        "val": {
          "nom": "Meridiano Orgánico #()($)ide() de 10: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_mer/()($)ide().png);",
          "col": 10,
          "num": 10
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":10, "dat":"hol.uni_hum_mer" }
        },
        "opc": {
          "ima": [ "ide" ] 
        }          
    }' ),
    ('hol','uni_hum_art', '{
        "val": {
          "nom": "Articulación #()($)ide() de 7: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_art/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','uni_hum_cha', '{
        "val": {
          "nom": "Chakra #()($)ide() de 7: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_cha/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','uni_hum_mud', '{ 
        "val": {
          "nom": "Mudra #()($)ide() de 7: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/hum_mud/()($)ide().png);",
          "num": 7
        }
    }' )
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'rad%'; INSERT INTO `app_dat` VALUES

    ('hol','rad',     '{
        "val": {
          "nom": "Plasma #()($)ide() de 7: ()($)nom().",
          "des": "()($)pla_pod() ()($)pla_fue().\\n\\"()($)pla_lec()\\"",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/()($)ide().png);",
          "col": 7,
          "num": 7
        },
        "inf": {
          "atr": [ ]
        },
        "est": { 

          "atr": [ "ide", "nom", "pla_pod", "pla_fue", "pla_qua" ]
        },
        "atr": {
          "ide":         { "min":1, "max":7, "dat":"hol.rad" },            
          "tel_ora":     { "min":1997, "max":1999, "dat":"api.fec_año" },
          "tel_ora_año": { "min":1, "max":260, "dat":"hol.kin" },
          "tel_ora_ani": { "min":1, "max":260, "dat":"hol.kin" },
          "tel_ora_gen": { "min":1, "max":260, "dat":"hol.kin" },
          "pla_cub":     { "min":1, "max":7, "dat":"hol.rad_pla_cub" },
          "pla_fue_pre": { "min":1, "max":12, "dat":"hol.rad_pla_fue" },
          "pla_fue_pos": { "min":1, "max":12, "dat":"hol.rad_pla_fue" },          
          "hum_cha":     { "min":1, "max":7, "dat":"hol.uni_hum_cha" }
        },
        "opc": {
          "ver": [ 
            "ide", "pla_qua" 
          ],
          "ima": [
            "ide", 
            "tel_ora_año", "tel_ora_ani", "tel_ora_gen", 
            "pla_cub", "pla_fue_pre", "pla_fue_pos", "pla_qua", 
            "hum_cha"
          ],
          "col": [
            "pla_qua"
          ],
          "num": [ 
            "ide", "pla_qua" 
          ]
        }
    }' ),
    ('hol','rad_pla_cub', '{ 
        "val": {
          "nom": "Plasma #()($)ide() de 7: ()($)pla()",
          "des": "()($)nom()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/pla_cub/()($)ide().png);",
          "col": 4,
          "num": 7
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    -- plasma: energía cósmica
    ('hol','rad_pla_pol', '{ 
        "val": {
          "nom": "Carga #()($)ide() de 2: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/pla_car/()($)ide().png);"
        }
    }' ),      
    ('hol','rad_pla_ele', '{
        "val": {
          "nom": "Tipo de Electricidad Cósmica #()($)ide() de 6: ()($)nom() - ()($)cod().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/pla_ele/()($)ide().png);"                      
        },
        "opc": {
          "ima": [ "ide" ]
        }
    }' ),  
    ('hol','rad_pla_fue', '{
        "val": {
          "nom": "Línea de Fuerza #()($)ide() de 12: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/pla_fue/()($)ide().png);"
        },
        "atr": {
          "ele_pre": { "min":1, "max":6, "dat":"hol.rad_pla_ele" },
          "ele_pos": { "min":1, "max":6, "dat":"hol.rad_pla_ele" }
        },
        "opc": {
          "ima": [ "ide", "ele_pre", "ele_pos" ]            
        }          
    }' ),  
    ('hol','rad_pla_qua', '{
        "val": {
          "nom": "Quantum #()($)ide() de 3: ()($)nom().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad/pla_qua/()($)ide().png);",
          "col": 3,
          "num": 3
        },
        "inf": {
          "atr": [ ]
        },
        "opc": {
          "ima": [ "ide" ]            
        }          
    }' )  
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'ton%'; INSERT INTO `app_dat` VALUES

    ('hol','ton', '{
        "val": {
          "nom": "Tono Galáctico #()($)ide() de 13: ()($)nom().",
          "des": "()($)des() ()($)acc_lec().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)ide().png);",
          "col": 7,
          "num": 13
        },
        "inf": {
          "atr": [ "car", "acc", "pod" ],
          "tab": [ 
            "hol.ton.ond", 
            { "pos": { "ima":"hol.ton.ide", "tex":"hol.ton.des" } }, 
            { "pos": { "class":"bor-1 pad-1", "style":"width: 5rem; height: 5rem;" } } 
          ],
          "ima": [ "ond", "dim", "mat", "sim" ],
          "tit": [ "des_med", "des_pro", "des_pre", "des_men", "des_por" ]
        },
        "est": {

          "atr": [ "ide", "nom", "car", "pod", "acc", "dim", "mat", "sim" ],
          "atr_ocu": [ "dim", "mat", "sim" ],

          "tit_cic": [ "ond" ],
          "tit_gru": [ "dim", "mat", "sim" ],

          "det_des": [ "des_pro", "des_med", "des_pre", "ond_man" ]
        },
        "atr": {
          "ide": { "min":1, "max":13, "dat":"hol.ton" },
          "ond": { "min":1, "max":4, "dat":"hol.ton_ond" },
          "ond_enc":  { "min":0, "max":4, "dat":"hol.ton_ond" },
          "dim": { "min":1, "max":4, "dat":"hol.ton_dim" },
          "mat": { "min":1, "max":5, "dat":"hol.ton_mat" },
          "sim": { "min":1, "max":7, "dat":"hol.ton_sim" },
          "hum_lad": { "min":1, "max":3, "dat":"hol.uni_hum_lad" },
          "hum_art": { "min":1, "max":7, "dat":"hol.uni_hum_art" },
          "hum_sen": { "min":1, "max":7, "dat":"hol.uni_hum_sen" }
        },
        "opc": {
          "ver": [ "ide", "ond", "dim", "mat", "sim", "hum_art" ],
          "ima": [ "ide", "ond", "dim", "mat" ],
          "col": [ "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" ],
          "num": [ "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" ]
        }
    }' ),
    -- onda encantada
      ('hol','ton_ond', '{
          "val": {
            "nom": "Aventura de la Onda Encantada #()($)ide() de 4.",
            "des": "()($)des().",
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/ond/()($)ide().png);",
            "col": 4,
            "num": 4
          },
          "inf": {
            "atr": [ ]
          },
          "atr": {
            "ide": { "min":1, "max":4, "dat":"hol.ton_ond" }
          },
          "opc": {
            "ima": [ "ide" ] 
          }          
      }' ),
    -- pulsares
      ('hol','ton_dim', '{
          "val": {
            "nom": "Pulsar Dimensional #()($)ide() de 4: ()($)nom().",
            "des": "Campo de aplicación ()($)des().", 
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/dim/()($)ide().png);",
            "col": 4
          },
          "inf": {
            "atr": [ ]
          },
          "atr":{
            "ide": { "min":1, "max":4, "dat":"hol.ton_dim" }
          },
          "opc": {
            "ima": [ "ide" ] 
          }          
      }' ),
      ('hol','ton_mat', '{
          "val": {
            "nom": "Pulsar Matiz #()($)ide() de 5: ()($)nom().",
            "des": "()($)des().", 
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/mat/()($)ide().png);",
            "col": 5
          },
          "inf": {
            "atr": [ ]
          },
          "atr":{
            "ide": { "min":1, "max":5, "dat":"hol.ton_mat" }
          },
          "opc": {
            "ima": [ "ide" ]
          }
      }' ),
      ('hol','ton_sim', '{
          "val": {
            "nom": "Simetría Especular de tonos ()($)ide() y ()($)inv(): ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/sim/()($)ide().png);",
            "col": 7
          },
          "inf": {
            "atr": [ ]
          },
          "atr":{
            "ide": { "min":1, "max":7, "dat":"hol.ton_sim" },
            "inv": { "min":1, "max":13, "dat":"hol.ton" }
          },
          "opc": {
            "ima": [ "ide" ]
          }
      }' )
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'sel%'; INSERT INTO `app_dat` VALUES

    ('hol','sel', '{
        "val": {
          "nom": "Sello Solar #()($)ide() de 20, ()($)arm().",
          "des": "()($)car() ()($)des().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/()($)ide().png);",
          "col": 4,
          "num": 20
        },
        "inf": {
          "atr": [ "car", "acc", "pod" ],
          "ima": [ "arm_cel", "arm_raz", "cro_ele", "cro_fam" ],
          "lec-lis": [ "cic_ser_des", "cic_luz_des", "arm_tra_des", "arm_raz_des", "arm_cel_des", "cro_ele_des", "sol_pla_des" ],
          "lec-1": [ "des_pro", "des_por", "des_som" ],
          "lis-1": [ "des_rec" ],
          "lec-2": [ "des_men", "des_afi" ],
          "lis-2": [ "pal", "pal_som" ],
          "htm": [
            { "eti":"p", "class":"tit", "htm":"Fuentes" },
            { "eti":"ul", "htm": [
              { "eti":"li", "htm":"Libro<c>:</c> El Kin<c>,</c> tu Signo Maya<c>.</c>" },
              { "eti":"li", "htm":"Página<c>:</c> <c>.</c>" }
            ]}
          ]
        },
        "est": {

          "atr": [ 
            "ide", "cod", "nom", "car", "acc", "pod", 
            "cro_fam", "cro_ele", "arm_raz", "arm_cel", "par_ana", "par_ant", "par_ocu", 
            "res_flu", "sol_pla", "pla_cen", "hum_cen", "hum_ded", "hum_mer" 
          ],
          "atr_ocu": [ 
            "cro_fam", "cro_ele", "arm_raz", "arm_cel", "par_ana", "par_ant", "par_ocu", 
            "res_flu", "sol_pla", "pla_cen", "hum_mer", "hum_cen", "hum_ded" 
          ],
          "tit_cic": [ "cic_ser", "cic_luz", "cro_ele", "arm_cel", "res_flu" ],

          "tit_gru": [ "cro_fam", "arm_raz", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_cen", "hum_ded", "hum_mer" ],

          "det_des": [ "des_pro", "des_pal", "des_som" ]
        },
        "atr": {
          "ide":      { "min":1, "max":20, "dat":"hol.sel" },
          "cod":      { "min":0, "max":19 },
          "ord":      { "min":1, "max":20,  "dat":"hol.sel_cod" },
          "cic_ser":  { "min":1, "max":3, 	"dat":"hol.sel_cic_ser" },
          "cic_luz":  { "min":1, "max":5, 	"dat":"hol.sel_cic_luz" },
          "arm_tra":  { "min":1, "max":20, 	"dat":"hol.sel.arm_tra" },
          "arm_raz":  { "min":1, "max":4, 	"dat":"hol.sel_arm_raz" },
          "arm_cel":  { "min":1, "max":5, 	"dat":"hol.sel_arm_cel" },
          "cro_fam":  { "min":1, "max":5, 	"dat":"hol.sel_cro_fam" },
          "cro_ele":  { "min":1, "max":4, 	"dat":"hol.sel_cro_ele" },
          "par_ana":  { "min":1, "max":20, 	"dat":"hol.sel" },
          "par_ant":  { "min":1, "max":20, 	"dat":"hol.sel" },
          "par_ocu":  { "min":1, "max":20, 	"dat":"hol.sel" },
          "res_flu":  { "min":1, "max":2, 	"dat":"hol.sel_res_flu" },
          "sol_pla":  { "min":1, "max":10, 	"dat":"hol.uni_sol_pla" },
          "sol_cel":  { "min":1, "max":10, 	"dat":"hol.uni_sol_cel" },
          "sol_cir":  { "min":1, "max":10, 	"dat":"hol.uni_sol_cir" },			
          "pla_cen":  { "min":1, "max":5, 	"dat":"hol.uni_pla_cen" },
          "pla_hem":  { "min":1, "max":3, 	"dat":"hol.uni_pla_hem" },
          "pla_mer":  { "min":1, "max":2, 	"dat":"hol.uni_pla_mer" },
          "hum_res":  { "min":1, "max":2, 	"dat":"hol.sel_res_flu" },
          "hum_cen":  { "min":1, "max":5, 	"dat":"hol.uni_hum_cen" },
          "hum_ext":  { "min":1, "max":5, 	"dat":"hol.uni_hum_ext" },
          "hum_ded":  { "min":1, "max":5, 	"dat":"hol.uni_hum_ded" },
          "hum_mer":  { "min":1, "max":10, 	"dat":"hol.uni_hum_mer" }
        },
        "opc": {
          "ver": [ 
            "ide", "cic_luz", "cro_fam", "cro_ele", "arm_raz", "arm_cel",
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_ext", "hum_cen", "hum_ded", "hum_mer" 
          ],
          "ima": [
            "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
          ],
          "col": [
            "ide", "ord", "par_ana", "par_ant", "par_ocu", "cic_ser", "cic_luz", "cro_fam", "cro_ele", "arm_raz", "arm_cel",
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_mer"
          ],
          "num": [ 
            "ide", "cod", "cic_ser", "cic_luz", 
            "cro_fam", "cro_ele", "arm_tra", "arm_raz", "arm_cel", "par_ana", "par_ant", "par_ocu", 
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_ext", "hum_cen", "hum_ded", "hum_mer"
          ]            
        }          
    }' ),
    ('hol','sel_cod', '{
        "val": {
          "nom": "Código #()($)cod()",
          "des": "()($)car() ()($)des().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cod/()($)cod().png);",
          "col": 5,
          "num": 20
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide":     { "min":1, "max":20, "dat":"hol.sel" },
          "cod":     { "min":0, "max":19 },
          "ord":     { "min":1, "max":20, "dat":"hol.sel_cod" },
          "cro_fam": { "min":1, "max":5, "dat":"hol.sel_cro_fam" },
          "cro_ele": { "min":1, "max":4, "dat":"hol.sel_cro_ele" },
          "res_flu": { "min":1, "max":2, "dat":"hol.sel_res_flu" },
          "sol_pla": { "min":1, "max":10, "dat":"hol.uni_sol_pla" },
          "sol_cel": { "min":1, "max":10, "dat":"hol.uni_sol_cel" },
          "sol_cir": { "min":1, "max":10, "dat":"hol.uni_sol_cir" },
          "pla_cen": { "min":1, "max":5, "dat":"hol.uni_pla_cen" },
          "pla_hem": { "min":1, "max":3, "dat":"hol.uni_pla_hem" },
          "pla_mer": { "min":1, "max":2, "dat":"hol.uni_pla_mer" },
          "hum_res": { "min":1, "max":2, "dat":"hol.sel_res_flu" },
          "hum_cen": { "min":1, "max":5, "dat":"hol.uni_hum_cen" },
          "hum_ext": { "min":1, "max":5, "dat":"hol.uni_hum_ext" },
          "hum_ded": { "min":1, "max":5, "dat":"hol.uni_hum_ded" },
          "hum_mer": { "min":1, "max":10, "dat":"hol.uni_hum_mer" }
        },
        "opc": {
          "ima": [
            "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
          ]            
        }
    }' ),
    -- ciclos    
      ('hol','sel_cic_dir', '{
          "val": {
            "nom": "Ciclo Direccional #()($)ide() de 4: ()($)nom()",
            "des": "()($)des().", 
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cic_dir/()($)ide().png);",
            "col": 4
          },
          "atr": {
            "ide": { "min":1, "max":4, "dat":"hol.sel_cic_dir" }
          },
          "opc": {
            "ima": [ "ide" ] 
          }          
      }' ),
      ('hol','sel_cic_ser', '{ 
          "val": {
            "nom": "Desarrollo del ser #()($)ide() de 3: ()($)nom().",
            "des": "()($)des().",
            "col": 3,
            "num": 3
          }
      }' ),
      ('hol','sel_cic_luz', '{ 
          "val": {
            "nom": "Grupo Cíclico de la luz #()($)ide() de 5: ()($)nom().",
            "des": "()($)des().",
            "col": 5,
            "num": 5
          }
      }' ),
    -- parejas
      ('hol','sel_par_ana', '{
          "atr": {
            "ini": { "min":1, "max":20, "dat":"hol.sel" },
            "fin": { "min":1, "max":20, "dat":"hol.sel" }
          },
          "opc": {
            "ima": [ "ini", "fin" ]            
          }          
      }' ),
      ('hol','sel_par_ant', '{
          "atr": {
            "ini": { "min":1, "max":20, "dat":"hol.sel" },
            "fin": { "min":1, "max":20, "dat":"hol.sel" }
          },
          "opc": {
            "ima": [ "ini", "fin" ]            
          }          
      }' ),
      ('hol','sel_par_ocu', '{
          "atr": {
            "ini": { "min":1, "max":20, "dat":"hol.sel" },
            "fin": { "min":1, "max":20, "dat":"hol.sel" }
          },
          "opc": {
            "ima": [ "ini", "fin" ]
          }          
      }' ),
    -- armónicas
      ('hol','sel_arm_raz', '{
          "val": {
            "nom": "Raza Raiz Cósmica #()($)ide() de 4: ()($)nom().",
            "des": "()($)des().",
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/arm_raz/()($)ide().png);",
            "col": 4,
            "num": 4
          },
          "inf": {
            "atr": [ "dir", "pod", "dia" ]
          },
          "opc": {
            "ima": [ "ide" ]
          }          
      }' ),
      ('hol','sel_arm_cel', '{
          "val": {
            "nom": "Célula del Tiempo #()($)ide() de 5: ()($)nom().",
            "des": "Poder: ()($)pod(); Función: ()($)fun().", 
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/arm_cel/()($)ide().png);",
            "col": 5,
            "num": 5
          },
          "inf": {
            "atr": [ ]
          },
          "opc": {
            "ima": [ "ide" ]
          }          
      }' ),
      ('hol','sel_arm_tra', '{
          "val": {
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel.png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/()($)ide().png);"
          }          
      }' ),
    -- cromaticas
      ('hol','sel_cro_fam', '{
          "val": {
            "nom": "Familia Terrestre #()($)ide() de 5: ()($)nom().",
            "des": "Función: ()($)pla(); Centro-G: ()($)hum(); Misión: ()($)des();",
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cro_fam/()($)ide().png);",
            "col": 5,
            "num": 5
          },
          "inf": {
            "atr": [ ]
          },
          "atr": {
            "ide": { "min":1, "max":5, "dat":"hol.sel_cro_fam" },
            "pla_cen": { "min":1, "max":5, "dat":"hol.uni_pla_cen" },
            "hum_cen": { "min":1, "max":5, "dat":"hol.uni_hum_cen" },
            "hum_ded": { "min":1, "max":5, "dat":"hol.uni_hum_ded" }
          },
          "opc": {
            "ima": [ "ide", "pla_cen", "hum_cen", "hum_ded" ]
          }          
      }' ),
      ('hol','sel_cro_ele', '{
          "val": {
            "nom": "Clan #()($)ide() de 4: ()($)nom() ()($)col().",
            "des": "()($)des().",
            "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cro_ele/()($)ide().png);",
            "col": 4,
            "num": 4
          },
          "inf": {
            "atr": [ ]
          },
          "atr": {
            "ide": { "min":1, "max":4, "dat":"hol.sel_cro_ele" },
            "res_flu": { "min":1, "max":2, "dat":"hol.sel_res_flu" },
            "hum_ext": { "min":1, "max":4, "dat":"hol.uni_hum_ext" }
          },
          "opc": {
            "ima": [ "ide", "res_flu", "hum_ext" ]
          }          
      }' )
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'lun%'; INSERT INTO `app_dat` VALUES

    ('hol','lun', '{  
        "val": {
          "nom": "Día Lunar #()($)ide() de 28",
          "des": "()($)ato_des()",
          "ima": "background: center/60% no-repeat url(http://icpv.com.ar/img/hol/fic/rad/()($)rad().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad.png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/col/()($)arm().png);",
          "col": 4,
          "num": 28
        },
        "inf": {
          "atr": [ ]
        },
        "est": {

          "atr": [ "ide", "arm", "rad" ],
          
          "tit_cic": [ "arm" ]
        },
        "atr": {
          "ide": { "min":1, "max":28, "dat":"hol.lun" },
          "arm": { "min":1, "max":4, "dat":"hol.lun_arm" },
          "rad": { "min":1, "max":7, "dat":"hol.rad" }
        },
        "opc": {
          "ver": [ 
            "ide", "arm" 
          ],
          "ima": [
            "arm", "rad"
          ],
          "col": [
            "arm", "rad"
          ],
          "num": [ 
            "ide", "arm" 
          ]
        }          
    }' ),
    -- ciclo armonico
    ('hol','lun_arm', '{  
        "val": {
          "nom": "Armonía lunar ()($)ide()",
          "des": "()($)nom(), ()($)col(). ()($)dia(): ()($)des()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad.png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/col/()($)ide().png);",
          "col": 4,
          "num": 4
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":4, "dat":"hol.lun_arm" }
        },
        "opc": {
          "ima": [
            "ide"
          ]            
        }
    }' ),
    -- telektonon
    ('hol','lun_tel_tor', '{  
        "atr": {
          "ide": { "min":1, "max":4, "dat":"hol.lun_tel_tor" }
        }
    }' ),
    ('hol','lun_tel_cam', '{  
        "atr": {
          "ide": { "min":1, "max":8, "dat":"hol.lun_tel_cam" }
        }
    }' ),
    ('hol','lun_tel_cub', '{  
        "atr": {
          "ide": { "min":1, "max":16, "dat":"hol.lun_tel_cub" }
        }
    }' ),
    -- Atomo del Tiempo    
    ('hol','lun_pla_ato', '{
      "val": {
        "nom": "Atomo del Tiempo #()($)ide() de 4. ()($)nom()",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/lun/pla_ato/()($)ide().png);",
        "col": 4,
        "num": 4
      }
    }' ),
    ('hol','lun_pla_tet', '{
      "val": {
        "nom": "Tetraedro #()($)ide() de 2. ()($)nom()",
        "des": "()($)des()",
        "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/lun/pla_tet/()($)ide().png);",
        "col": 2,
        "num": 2
      }
    }' )
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'cas%'; INSERT INTO `app_dat` VALUES

    ('hol','cas', '{
        "val": {
          "nom": "Posicion #()($)ide() de 52: ()($)nom().",
          "des": "Cuadrante #()($)arm() de 4; Tono Galáctico #()($)ton() de 13; Onda de la Aventura #()($)ond() de 4.",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/arm/()($)arm().png), center/70% no-repeat url(http://icpv.com.ar/img/hol/fic/uni/col/()($)pos_arm().png);",
          "col": 4,
          "num": 52
        },
        "inf": {
          "lec": [ "lec" ],
          "ima": [ "arm", "ond", "ton" ]
        },
        "atr": {
          "ide": { "min":1, "max":52, "dat":"hol.cas" },
          "arm": { "min":1, "max":4, "dat":"hol.cas_arm" },
          "ond": {"min":1, "max":4, "dat":"hol.cas_ond" },
          "pos_arm": { "min":1, "max":4, "dat":"hol.arm" },
          "ton": {"min":1, "max":13, "dat":"hol.ton" },
          "ton_arm": { "min":1, "max":13, "dat":"hol.ton" }
        },
        "opc": {
          "ver": [ "ide", "arm" ],    
          "ima": [ "arm", "ond" ],
          "col": [ "arm", "ond", "ton_arm" ],
          "num": [ "ide", "arm", "ton_arm" ]
        }
    }' ),
    ('hol','cas_arm', '{
        "val": {
          "nom": "Cuadrante #()($)ide() de 4: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/cas/arm/()($)ide().png);",
          "col": 4,
          "num": 4
        },
        "inf": {
          "atr": [ ]
        }
    }'),
    ('hol','cas_ond', '{
        "val": {
          "nom": "Aventura de la Onda Encantada #()($)ide() de 4: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/cas/ond/()($)ide().png);",
          "col": 4,
          "num": 4
        },
        "inf": {
          "atr": [ ]
        }
    }')
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'chi%'; INSERT INTO `app_dat` VALUES

    ('hol','chi', '{ 
        "val": {
          "nom": "Hexagrama #()($)ide() de 64",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/chi/()($)ide().png);",
          "num": 64
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','chi_mon', '{ 
        "val": {
          "nom": "Monograma #()($)ide() de 2",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/chi/mon/()($)ide().png);"
        }
    }' ),
    ('hol','chi_bin', '{ 
        "val": {
          "nom": "Bigrama #()($)ide() de 4",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/chi/bin/()($)ide().png);"
        }
    }' ),
    ('hol','chi_tri', '{ 
        "val": {
          "nom": "Trigrama #()($)ide() de 8",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/chi/tri/()($)ide().png);"
        }
    }' )     
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'kin%'; INSERT INTO `app_dat` VALUES

    ('hol','kin', '{
        "val": {
          "nom": "Kin #()($)ide() de 260: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: top/50% no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)nav_ond_dia().png), bottom/60% no-repeat url(http://icpv.com.ar/img/hol/fic/sel/()($)arm_tra_dia().png);",
          "num": 260
        },
        "fic": [
          "ide", [ "nav_cas", "nav_ond", "arm_tra", "arm_cel", "cro_est", "cro_ele" ]
        ],
        "inf": {
          "cit" : "des",
          "tab" : [ "hol.kin.par", { "sec":{}, "pos":{ "ima":"hol.kin.ide" } } ],          
          "ima" : [ "nav_cas", "nav_ond", "nav_ond_dia", "cro_est", "cro_ele", "arm_tra", "arm_cel", "arm_tra_dia" ],
          "eje" : { "ide": "app_hol::inf", "par": [ "kin", "par", "()($)ide()" ] }
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
            "ide": "hol.sel", "atr": [ "sol_pla", "sol_res", "sol_cel", "sol_cir" ] 
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
        "est": {
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
        "atr": {
          "ide": { "min":1, "max":260, "dat":"hol.kin" },
          "ene": { "min":1, "max":4, "dat":"hol.kin_ene" },
          "ene_cam": { "min":1, "max":14, "dat":"hol.kin_ene_cam" },
          "chi": { "min":1, "max":65, "dat":"hol.chi" },
          "cro_est": { "min":1, "max":4, "dat":"hol.kin_cro_est" },
          "cro_est_dia": { "min":1, "max":65, "dat":"hol.kin_cro_est_dia" },
          "cro_ele": { "min":1, "max":4, "dat":"hol.kin_cro_ele" },
          "cro_ele_dia": { "min":1, "max":5, "dat":"hol.sel_cro_fam" },
          "arm_tra": { "min":1, "max":13, "dat":"hol.kin_arm_tra" },
          "arm_tra_dia": { "min":1, "max":20, "dat":"hol.sel" },
          "arm_cel": { "min":1, "max":5, "dat":"hol.kin_arm_cel" },
          "arm_cel_dia": { "min":1, "max":4, "dat":"hol.sel_arm_raz" },  
          "gen_enc": { "min":1, "max":3, "dat":"hol.kin_gen_enc" },
          "gen_enc_dia": { "min":1, "max":3, "max-1":130, "max-2":90, "max-3":52 },
          "gen_cel": { "min":1, "max":5, "dat":"hol.kin_gen_cel" },
          "gen_cel_dia": { "min":1, "max":26 },
          "nav_cas": { "min":1, "max":5, "dat":"hol.kin_nav_cas" },
          "nav_cas_dia": { "min":1, "max":52, "dat":"hol.cas" },  
          "nav_ond": { "min":1, "max":20, "dat":"hol.kin_nav_ond" },
          "nav_ond_dia": { "min":1, "max":13, "dat":"hol.ton" },
          "par_ana": { "min":1, "max":260, "dat":"hol.kin" },
          "par_gui": { "min":1, "max":260, "dat":"hol.kin" },
          "par_ant": { "min":1, "max":260, "dat":"hol.kin" },
          "par_ocu": { "min":1, "max":260, "dat":"hol.kin" }
        },
        "rel": {
          "ide"         : "hol_kin",
          "arm_tra_dia" : "hol_sel",
          "nav_ond_dia" : "hol_ton",
          "nav_cas_dia" : "hol_cas"
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
        }
    }'),
    -- modulo armonico
    ('hol','kin_ene', '{ 
        "val": {
          "nom": "Grupo #()($)ide() de ()($)nom()",
          "des": "()($)gru() x ()($)gru_uni() = ()($)uni() unidades",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/kin/ene/()($)ide().png);",
          "col": 4
        },
        "inf": {
          "atr": [ "cue", "gru", "gru_uni" ],
          "tab": [ "hol.kin.tzo", { "sec": { "col":"hol.kin.ene" }, "pos":{ "ima":"hol.ton.ide", "num":"hol.kin.ide" } } ]
        }      
    }' ),
    ('hol','kin_ene_cam', '{ 
        "val": {
          "nom": "Campo #()($)ide() de ()($)nom() unidades",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/kin/ene_cam/()($)ide().png);"
        }
    }' ),
    -- giro galáctico
    ('hol','kin_arm_tra', '{ 
        "val": {
          "nom": "Trayectoria Armónica #()($)ide() de 13: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: top/75% no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)ide().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel.png);",
          "num": 13,
          "col": 7
        },
        "inf": {
          "opc": [ "des" ],
          "atr": [ "fac", "may" ],
          "lec": [ "lec" ],
          "tab": [ "hol.kin.arm_tra", { "sec":{ "par":1 }, "pos":{ "ima":"hol.kin.ide" } } ]
        }
    }' ),
    ('hol','kin_arm_cel', '{ 
        "val": {
          "nom": "Célula del Tiempo #()($)ide() de 65: ()($)nom().", 
          "des": "()($)des().",
          "ima": "background: top/75% no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/arm_cel/()($)cel().png);",
          "num": 65,
          "col": 5
        },
        "inf": {
          "cit": "des",
          "tab": [ "hol.kin.arm_cel", { "sec":{ "par":1 }, "pos":{ "ima":"hol.kin.ide" } } ]
        }
    }' ),
    -- giro espectral
    ('hol','kin_cro_est', '{
        "val": {
          "nom": "Espectro Galáctico #()($)ide() de 4: ()($)col() del ()($)dir().",
          "des": "Guardían ()($)nom(): ()($)des(), ()($)det()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/cas/arm/()($)ide().png);",
          "num": 4,
          "col": 4            
        },
        "inf": {
          "atr": [ "nom", "des", "det" ],
          "lec": [ "lec" ],
          "tab": [ "hol.kin.cro_est", { "pos":{ "ima":"hol.kin.ide" } } ]
        },
        "atr": {
          "ide": { "min":1, "max":4, "dat":"hol.kin_cro_est" },
          "sel": { "min":1, "max":20, "dat":"hol.sel" },
          "cas": { "min":1, "max":52, "dat":"hol.cas" }
        },
        "opc": {
          "ima": [ "ide", "sel", "cas" ]            
        }   
    }' ),
    ('hol','kin_cro_est_dia', '{
        "val": {
          "nom": "Día estacional #()($)ide() de 65.",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/kin/cro_est_dia/()($)ide().png);",
          "num": 65
        }
    }' ),    
    ('hol','kin_cro_ele', '{ 
        "val": {
          "nom": "Elemento Cromático #()($)ide() de 52: ()($)nom().",
          "des": "()($)des().",
          "ima": "background: bottom/75% no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)ton().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cro_ele/()($)ele().png);",
          "num": 52,
          "col": 4            
        },
        "inf": {
          "cit": "des",
          "lec": [ "cas_des" ],
          "tab": [ "hol.kin.cro_ele", { "pos":{ "ima":"hol.kin.ide" } } ]
        }
    }' ),
    -- nave del tiempo
    ('hol','kin_nav_cas', '{ 
        "val": {
          "nom": "Castillo #()($)ide() de 5: ()($)nom().",
          "des": "()($)des()",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/kin/nav_cas/()($)ide().png);",
          "num": 5,
          "col": 5            
        },
        "inf": {
          "atr": [ "cor", "pod", "acc" ],
          "lec": [ "fun" ],
          "tab": [ "hol.kin.nav_cas", { "pos":{ "ima":"hol.kin.ide" } } ]
        }
    }' ),
    ('hol','kin_nav_ond', '{ 
        "val": {
          "nom": "Onda Encantada #()($)ide() de 20: ()($)nom().",
          "des": "()($)enc_des().", 
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/ton/arm/()($)cas_arm().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/()($)sel().png);",
          "num": 20,
          "col": 4
        },
        "inf": {
          "atr": [ "fac", "enc" ],
          "lec": [ "fac_des", "enc_des"],
          "tab": [ "hol.kin.nav_ond", { "sec":{ "par":1 }, "pos":{ "ima":"hol.kin.ide" } } ]
        }
    }' ),
    ('hol','kin_gen_enc', '{ 
        "val": {
          "nom": "()($)ide()° Génesis del Encantamiento del Sueño: ()($)nom().",
          "des": "()($)des().",
          "num": 3,
          "col": 3
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','kin_gen_cel', '{ 
        "val": {
          "nom": "Célula de la Memoria #()($)ide() de 5: ()($)nom().",
          "des": "()($)des().",
          "num": 5,
          "col": 5            
        },
        "inf": {
          "atr": [ ]
        }
    }' )
  ;
  DELETE FROM `app_dat` WHERE `esq`='hol' AND `ide` LIKE 'psi%'; INSERT INTO `app_dat` VALUES

    ('hol','psi', '{
        "val": {
          "nom": "PSI #()($)ide() de 365, correspondiente al ()($)fec().",
          "des": "Psi-Cronos: ()($)tzo(). Estación Solar #()($)est() de 4, día ()($)est_dia(). Giro Lunar #()($)lun() de 13, día ()($)lun_dia() de 28. Héptada #()($)hep() de 52, día ()($)hep_dia() de 7.",
          "ima": "background: top/50% no-repeat url(http://icpv.com.ar/img/hol/fic/ton/()($)kin_ton().png), bottom/60% no-repeat url(http://icpv.com.ar/img/hol/fic/sel/()($)kin_sel().png);",
          "num": 365
        },
        "fic": [ 
          "tzo", [ "est", "lun", "hep" ] 
        ],
        "inf": {
          "atr": [ ]
        },
        "pos": {
          "est": { 
            "nom": "Estación Solar",  
            "ide": "hol.psi", "atr": [ "est", "est_dia" ] 
          },
          "lun": { 
            "nom": "Giro Lunar",
            "ide": "hol.psi", "atr": [ "lun", "lun_dia" ] 
          },
          "hep": { 
            "nom": "Héptada Semanal",   
            "ide": "hol.psi", "atr": [ "hep", "hep_dia" ] 
          }
        },
        "est": {

          "atr": [ "ide", "tzo", "est", "est_dia", "lun", "lun_dia", "hep", "hep_dia" ],

          "atr_ocu": [ "est_dia", "lun_dia" ],

          "tit_cic": [ "est", "lun", "vin", "hep" ]
        },
        "atr": {
          "ide": { "min":1, "max":365, "dat":"hol.psi" },
          "tzo": { "min":1, "max":260, "dat":"hol.kin" },
          "est": { "min":1, "max":4, "dat":"hol.psi_est" },
          "est_dia": { "min":1, "max":91 },
          "lun": { "min":1, "max":13, "dat":"hol.psi_lun" },
          "lun_dia": { "min":1, "max":28, "dat":"hol.lun" },
          "hep": { "min":1, "max":52, "dat":"hol.psi_hep" },
          "hep_dia": { "min":1, "max":7, "dat":"hol.rad" }
        },
        "rel": {
          "ide"    :"hol_psi", 
          "lun_dia":"hol_lun",
          "hep_dia":"hol_rad"
        },
        "opc": {
          "ver": [ 
            "est", "lun", "vin", "hep"
          ],
          "ima": [
            "tzo", 
            "lun", "est", 
            "hep", "hep_dia"
          ],
          "col": [
            "est", 
            "lun", "lun_dia", 
            "hep"
          ],
          "num": [ 
            "ide", "fec", "tzo", 
            "est", "est_dia", 
            "lun", "lun_dia", 
            "vin", "vin_dia", 
            "hep", "hep_dia", 
            "cro", "cro_dia" 
          ],
          "tex": [
          ]                  
        }    
    }' ),
    ('hol','psi_est', '{
        "val": {
          "nom": "Estación Solar #()($)ide() de 4: ()($)nom().",
          "des": "()($)des() ( ()($)pol_sur() al sur, ()($)pol_nor() al norte )",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/cas/arm/()($)ide().png);",
          "num": 4,
          "col": 4          
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','psi_est_dia', '{
      "val": {
        "nom": "Día estacional #()($)ide() de 91",
        "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/psi/est_dia/()($)ide().png);",
        "num": 91
      }
    }' ),
    ('hol','psi_lun', '{
        "val": {
          "nom": "Luna #()($)ide() de 13: tono ()($)ton_nom().",
          "des": "()($)des() del Giro Solar Anual; Totem ()($)tot(): ()($)tot_pro().",
          "ima": "background: url(http://icpv.com.ar/img/hol/fic/psi/lun/()($)ide().png) center/contain no-repeat;",
          "num": 13,
          "col": 7
        },
        "inf": {
          "atr": [ ]
        },
        "atr": {
          "ide": { "min":1, "max":13, "dat":"hol.psi_lun" },
          "ond": { "min":1, "max":4, "dat":"hol.ton_ond" }
        },
        "opc": {
          "ima": [
            "ide"
          ]            
        }
    }' ),
    ('hol','psi_hep', '{
        "val": {
          "nom": "Heptada #()($)ide() de 52.",
          "des": "()($)ton_des() del cuadrante ()($)arm_col() en el ()($)arm_dir().",
          "ima": "background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/sel/cod/()($)ton().png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad.png), center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/uni/col/()($)ond().png);",
          "num": 52,
          "col": 4
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','psi_vin', '{
        "val": {
          "nom": "Vinal #()($)ide() de 19: ()($)nom().",
          "des": "()($)des().",
          "num": 19
        },
        "inf": {
          "atr": [ ]
        }
    }' ),
    ('hol','psi_cro', '{
        "val": {
          "nom": "Cromática Entonada #()($)ide() de 75.",
          "num": 75
        },
        "inf": {
          "atr": [ ]
        }
    }' )      
  ;
--
