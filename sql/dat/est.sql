
-- Interface
  --
  -- calendario
    DELETE FROM `_api`.`dat_opc` WHERE `esq` = 'api' AND `est` LIKE 'fec_%'
    ;
    
    DELETE FROM `_api`.`dat_opc` WHERE `esq` = 'api' AND `est` LIKE 'fec%';
    INSERT INTO `_api`.`dat_opc` VALUES
      -- fecha
      ( 'api', 'fec',         '{
        
          "est": {
            "val":"fec", 
            "dia":"fec_dia", 
            "sem":"fec_sem", 
            "año":"fec_año"
          },
          
          "ver": [
            "dia", "sem", "mes"
          ],
          "num": [
            "dia", "sem", "mes"
          ]
          
      }')
    ;
  --
  -- HOLON
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_%'
    ;  
    
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_rad%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_rad',     '{

          "ver": [ 
            "ide", "pla_qua" 
          ],
          "ima": [
            "ide", "hep", 
            "tel_ora_año", "tel_ora_ani", "tel_ora_gen", 
            "pla_fue_pre", "pla_fue_pos", "pla_qua", 
            "hum_cha"
          ],
          "col": [
            "hep", "pla_qua"
          ],
          "num": [ 
            "ide", "pla_qua" 
          ]
      }' ),  
      ( 'api', 'hol_rad_pla_ele',     '{

          "ima": [
            "ide"
          ]
      }' ),  
      ( 'api', 'hol_rad_pla_fue',     '{

          "ima": [
            "ide", "ele_pre", "ele_pos"
          ]
      }' ),  
      ( 'api', 'hol_rad_pla_qua',     '{

          "ima": [
            "ide"
          ]
      }' ) 
    ;
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_ton%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_ton', '{

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
      ( 'api', 'hol_ton_ond', '{

          "ima": [
            "ide"
          ]
      }' ),
      ( 'api', 'hol_ton_dim', '{

          "ima": [
            "ide"
          ]
      }' ),
      ( 'api', 'hol_ton_mat', '{

          "ima": [
            "ide"
          ]
      }' ),
      ( 'api', 'hol_ton_sim', '{
      }' )
    ;
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_sel%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_sel', '{

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
      ( 'api', 'hol_sel_cod', '{
          "ima": [
            "ide", "ord", "par_ana", "par_ant", "par_ocu", "cro_fam", "cro_ele", 
            "res_flu", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "pla_hem", "pla_mer", "hum_res", "hum_mer", "hum_cen", "hum_ext", "hum_ded"
          ]
      }' ),
      -- ciclos    
        ( 'api', 'hol_sel_cic_dir', '{
            "ima": [ "ide" ]
        }' ),
        ( 'api', 'hol_sel_cic_men', '{
            "ima": [ "ide", "sel" ]
        }' ),    
      -- parejas
        ( 'api', 'hol_sel_par_ana', '{
            "ima": [ "ini", "fin" ]
        }' ),
        ( 'api', 'hol_sel_par_ant', '{
            "ima": [ "ini", "fin" ]
        }' ),
        ( 'api', 'hol_sel_par_ocu', '{
            "ima": [ "ini", "fin" ]
        }' ),
      -- armónicas
        ( 'api', 'hol_sel_arm_raz', '{
            "ima": [ "ide" ]
        }' ),
        ( 'api', 'hol_sel_arm_cel', '{
            "ima": [ "ide" ]
        }' ),    
      -- cromaticas
        ( 'api', 'hol_sel_cro_fam', '{
            "ima": [ "ide", "pla_cen", "hum_cen", "hum_ded" ]
        }' ),
        ( 'api', 'hol_sel_cro_ele', '{
            "ima": [ "ide", "res_flu", "hum_ext" ]
        }' ),
      -- holon solar
        ( 'api', 'hol_sel_sol_res', '{
            "ima": [ "ide" ]
        }' ),
        ( 'api', 'hol_sel_sol_orb', '{
            "ima": [ "ide" ]
        }' ),    
        ( 'api', 'hol_sel_sol_pla', '{
            "ima": [ "ide","orb","cel","cir" ]
        }' ),
        ( 'api', 'hol_sel_sol_cel', '{
            "ima": [ "ide" ]
        }' ),
        ( 'api', 'hol_sel_sol_cir', '{
            "ima": [ "ide" ]
        }' ),
      -- holon planetario
        ( 'api', 'hol_sel_pla_res', '{
            "ima": [ "ide","hem","fam" ]
        }' ),
        ( 'api', 'hol_sel_pla_cen', '{
            "ima": [ "ide", "fam" ]
        }' ),
        ( 'api', 'hol_sel_pla_hem', '{
            "ima": [ "ide" ]
        }' ),
        ( 'api', 'hol_sel_pla_mer', '{
            "ima": [ "ide" ]
        }' ),
      -- holon humano
        ( 'api', 'hol_sel_hum_res', '{
            "ima": [ "ide" ]
        }' ),        
        ( 'api', 'hol_sel_hum_cen', '{
            "ima": [ "ide", "fam" ]
        }' ),
        ( 'api', 'hol_sel_hum_ded', '{
            "ima": [ "ide", "fam" ]
        }' ),
        ( 'api', 'hol_sel_hum_ext', '{
            "ima": [ "ide", "ele" ]
        }' ),
        ( 'api', 'hol_sel_hum_mer', '{
            "ima": [ "ide" ]
        }' )
    ;
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_lun%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_lun', '{  

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
      ( 'api', 'hol_lun_arm', '{  

          "ima": [
            "ide"
          ]
      }' )    
    ;
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_cas%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_cas', '{

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
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_kin%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_kin', '{        

          "est": {
            "ide":"hol_kin",
            "arm_tra_dia":"hol_sel",
            "nav_ond_dia":"hol_ton",
            "nav_cas_dia":"hol_cas"
          },
          
          "atr_ima" : [
            "nav_cas", "nav_ond", "arm_tra", "arm_cel", "cro_est", "cro_ele"
          ],

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
    DELETE FROM `_api`.`dat_est` WHERE `esq`='api' AND `ide` LIKE 'hol_psi%';
    INSERT INTO `_api`.`dat_est` VALUES

      ( 'api', 'hol_psi', '{

          "est": {
            "ide":"hol_psi", 
            "lun_dia":"hol_lun",
            "hep_dia":"hol_rad"
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
      ( 'api', 'hol_psi_lun', '{

          "ima": [
            "ide"
          ]
      }' )    
    ;
  --
--