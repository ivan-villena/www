-- HOLON
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol';

  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'rad%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'rad',     '{

        "ver": [ 
          "ide", "pla_qua" 
        ],
        "ima": [
          "ide", "hep", "tel_ora_año", "tel_ora_ani", "tel_ora_gen", "pla_fue_pre", "pla_fue_pos", "pla_qua", "hum_cha"
        ],
        "col": [
          "hep", "pla_qua"
        ],
        "num": [ 
          "ide", "pla_qua" 
        ]
    }' ),  
    ( 'hol', 'rad_pla_ele',     '{

        "ima": [
          "ide"
        ]
    }' ),  
    ( 'hol', 'rad_pla_fue',     '{

        "ima": [
          "ide", "ele_pre", "ele_pos"
        ]
    }' ),  
    ( 'hol', 'rad_pla_qua',     '{

        "ima": [
          "ide"
        ]
    }' ) 
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'ton%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'ton', '{

        "ver": [ 
          "ide", "ond", "dim", "mat", "sim", "hum_art" 
        ],
        "ima": [
          "ide", "ond", "dim", "mat" 
        ],
        "col": [
          "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad"
        ],
        "num": [ 
          "ide", "ond", "dim", "mat", "sim", "hum_art", "hum_lad" 
        ]
    }' ),
    ( 'hol', 'ton_ond', '{

        "ima": [
          "ide"
        ]
    }' ),
    ( 'hol', 'ton_dim', '{

        "ima": [
          "ide"
        ]
    }' ),
    ( 'hol', 'ton_mat', '{

        "ima": [
          "ide"
        ]
    }' ),
    ( 'hol', 'ton_sim', '{
    }' )
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'sel%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'sel', '{

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
    }' ),
    ( 'hol', 'sel_cod', '{
        "ima": [
          "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
          "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
        ]
    }' ),
    -- ciclos    
      ( 'hol', 'sel_cic_dir', '{
          "ima": [ "ide" ]
      }' ),
      ( 'hol', 'sel_cic_men', '{
          "ima": [ "ide", "sel" ]
      }' ),    
    -- parejas
      ( 'hol', 'sel_par_ana', '{
          "ima": [ "ini", "fin" ]
      }' ),
      ( 'hol', 'sel_par_ant', '{
          "ima": [ "ini", "fin" ]
      }' ),
      ( 'hol', 'sel_par_ocu', '{
          "ima": [ "ini", "fin" ]
      }' ),
    -- armónicas
      ( 'hol', 'sel_arm_raz', '{
          "ima": [ "ide" ]
      }' ),
      ( 'hol', 'sel_arm_cel', '{
          "ima": [ "ide" ]
      }' ),    
    -- cromaticas
      ( 'hol', 'sel_cro_fam', '{
          "ima": [ "ide", "pla_cen", "hum_cen", "hum_ded" ]
      }' ),
      ( 'hol', 'sel_cro_ele', '{
          "ima": [ "ide", "res_flu", "hum_ext" ]
      }' ),
    -- holon solar
      ( 'hol', 'sel_sol_res', '{
          "ima": [ "ide" ]
      }' ),
      ( 'hol', 'sel_sol_orb', '{
          "ima": [ "ide" ]
      }' ),    
      ( 'hol', 'sel_sol_pla', '{
          "ima": [ "ide","orb","cel","cir" ]
      }' ),
      ( 'hol', 'sel_sol_cel', '{
          "ima": [ "ide" ]
      }' ),
      ( 'hol', 'sel_sol_cir', '{
          "ima": [ "ide" ]
      }' ),
    -- holon planetario
      ( 'hol', 'sel_pla_res', '{
          "ima": [ "ide","hem","fam" ]
      }' ),
      ( 'hol', 'sel_pla_cen', '{
          "ima": [ "ide", "fam" ]
      }' ),
      ( 'hol', 'sel_pla_hem', '{
          "ima": [ "ide" ]
      }' ),
      ( 'hol', 'sel_pla_mer', '{
          "ima": [ "ide" ]
      }' ),
    -- holon humano
      ( 'hol', 'sel_hum_res', '{
          "ima": [ "ide" ]
      }' ),        
      ( 'hol', 'sel_hum_cen', '{
          "ima": [ "ide", "fam" ]
      }' ),
      ( 'hol', 'sel_hum_ded', '{
          "ima": [ "ide", "fam" ]
      }' ),
      ( 'hol', 'sel_hum_ext', '{
          "ima": [ "ide", "ele" ]
      }' ),
      ( 'hol', 'sel_hum_mer', '{
          "ima": [ "ide" ]
      }' )
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'lun%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'lun', '{  

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
    }' ),
    ( 'hol', 'lun_arm', '{  

        "ima": [
          "ide"
        ]
    }' )    
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'cas%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'cas', '{

        "ver": [ 
          "ide", "arm" 
        ],    
        "ima": [
          "arm", "ond"
        ],
        "col": [
          "arm", "ond", "ton_arm"
        ],
        "num": [ 
          "ide", "arm", "ton_arm" 
        ]
    }' )
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'kin%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'kin', '{        

        "est": {
          "ide":"kin",
          "arm_tra_dia":"sel",
          "nav_ond_dia":"ton",
          "nav_cas_dia":"cas"
        },
        
        "fic": { 
          "ide":"ide", "atr":[ "cro_ele", "arm_cel", "nav_ond" ] 
        },

        "ver": [ 
          "pag", "ene", "ene_cam", "gen_enc", "gen_cel", "nav_cas", "nav_ond", "cro_est", "cro_ele", "arm_tra", "arm_cel" 
        ],
        "col": [
          "pag", "ene", "gen_enc", "gen_cel", "nav_cas", "nav_ond", "cro_est", "cro_ele", "arm_tra", "arm_cel"
        ],
        "ima": [            
          "ide", "pag", "ene", "ene_cam", "chi", "par_ana", "par_gui", "par_ant", "par_ocu", 
          "nav_cas", "nav_ond", "nav_ond_dia", "arm_tra", "arm_cel", "arm_tra_dia", "cro_est", "cro_ele"
        ],
        "num": [ 
          "ide", "psi", "ene", "ene_cam", "gen_enc", "gen_enc_dia", "gen_cel", "gen_cel_dia", "nav_cas", "nav_cas_dia", "nav_ond", "nav_ond_dia", 
          "cro_est", "cro_est_dia", "cro_ele", "cro_ele_dia", "arm_tra", "arm_tra_dia", "arm_cel", "arm_cel_dia"
        ],
        "tex": [
          "nom","des"
        ]
    }')
  ;
  DELETE FROM `_api`.`dat_est` WHERE `esq`='hol' AND `ide` LIKE 'psi%';
  INSERT INTO `_api`.`dat_est` VALUES

    ( 'hol', 'psi', '{

        "est": {
          "ide":"psi", 
          "lun_dia":"lun",
          "hep_dia":"rad"
        },
        "fic": { 
          "ide":"tzo", "atr":[ "est", "lun", "hep" ]
        },

        "ver": [ 
          "pag", "est", "lun", "vin", "hep"
        ],
        "ima": [
          "tzo", "lun", "est", "hep", "hep_dia"
        ],
        "col": [
          "est", "lun", "lun_dia", "hep"
        ],
        "num": [ 
          "ide", "fec", "tzo", "est", "est_dia", "lun", "lun_dia", "vin", "vin_dia", "hep", "hep_dia", "cro", "cro_dia" 
        ]     
    }' ),
    ( 'hol', 'psi_lun', '{

        "ima": [
          "ide"
        ]
    }' )    
  ;