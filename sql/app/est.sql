
-- Inteface

  -- Calendario
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'fec%';
    INSERT INTO `api`.`app_est` VALUES

      ('api','fec', '{
        
          "atr": [
            "val"
          ],
          "ocu":[

          ]
      }')
    ;
  --
  -- Holon
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol%'
    ;    
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_rad%';
    INSERT INTO `api`.`app_est` VALUES
      ( 'api', 'hol_rad', '{ 

          "atr": [
            "ide", "nom", "pla_pod", "pla_fue", "pla_qua" 
          ]

      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_ton%';
    INSERT INTO `api`.`app_est` VALUES
      ( 'api', 'hol_ton', '{

          "atr": [
            "ide", "nom", "car", "pod", "acc", "dim", "mat", "sim"
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
            "des_pro", "des_med", "des_pre", "ond_man"
          ]
      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_sel%';
    INSERT INTO `api`.`app_est` VALUES
      ( 'api', 'hol_sel', '{

          "atr": [ 
            "ide", "cod", 
            "nom", "car", "acc", "pod", 
            "cro_fam", "cro_ele", 
            "arm_raz", "arm_cel", 
            "par_ana", "par_ant", "par_ocu", 
            "res_flu", "sol_pla", "pla_cen", "hum_cen", "hum_ded", "hum_mer" 
          ],

          "atr_ocu": [ 
            "cro_fam", "cro_ele", 
            "arm_raz", "arm_cel", 
            "par_ana", "par_ant", "par_ocu", 
            "res_flu", "sol_pla", "pla_cen", "hum_mer", "hum_cen", "hum_ded" 
          ],

          "tit_cic": [ 
            "cic_ser", "cic_luz", "cro_ele", "arm_cel", "res_flu" 
          ],
          "tit_gru": [ 
            "cro_fam", "arm_raz", "sol_pla", "sol_cel", "sol_cir", "pla_cen", "hum_cen", "hum_ded", "hum_mer" 
          ],

          "det_des": [ 
            "des_pro", "des_pal", "des_som" 
          ]
      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_lun%';
    INSERT INTO `api`.`app_est` VALUES

      ( 'api', 'hol_lun', '{

          "atr": [ 
            "ide", "arm", "rad" 
          ],
          
          "tit_cic": [ 
            "arm" 
          ]
      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_chi%';
    INSERT INTO `api`.`app_est` VALUES

      ( 'api', 'hol_chi', '{
      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_kin%';
    INSERT INTO `api`.`app_est` VALUES
      ( 'api', 'hol_kin', '{

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
          
          "atr_ocu": [       
            "pag", "chi",
            "ene", "ene_cam",
            "gen_enc", "gen_cel", 
            "par_ana", "par_gui", "par_ant", "par_ocu"
          ],
          
          "tit_cic": [ 
            "nav_cas", "nav_ond", "cro_est", "cro_ele", "arm_tra", "arm_cel"
          ],

          "det_des": [ 
            "des","des_tie","des_umb"
          ]  
      }' )
    ;
    DELETE FROM `api`.`app_est` WHERE `esq`='api' AND `ide` LIKE 'hol_psi%';
    INSERT INTO `api`.`app_est` VALUES
      ( 'api', 'hol_psi', '{

          "atr": [ 
            "ide", "tzo", "est", "est_dia", "lun", "lun_dia", "hep", "hep_dia" 
          ],
          
          "tit_cic": [ 
            "est", "lun", "vin", "hep" 
          ]

      }' )
    ;
  -- 
-- 