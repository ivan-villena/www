
-- Interface

  -- HOLON
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_%';
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_rad%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_rad',     '{ 
            "nom": "Plasma #()($)ide() de 7: ()($)nom().",
            "des": "()($)pla_pod() ()($)pla_fue().\\n\\"()($)pla_lec()\\"",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/()($)ide().png);",
            "col": 7
        }' ),
        ( 'api', 'hol_rad_hep', '{ 
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/hep/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_rad_pla_car', '{ 
            "nom": "Carga #()($)ide() de 2: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/pla_car/()($)ide().png);"          
        }' ),
        ( 'api', 'hol_rad_pla_ele', '{ 
            "nom": "Tipo de Electricidad Cósmica #()($)ide() de 6: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/pla_ele/()($)ide().png);"          
        }' ),
        ( 'api', 'hol_rad_pla_fue', '{ 
            "nom": "Línea de Fuerza #()($)ide() de 12: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/pla_fue/()($)ide().png);"          
        }' ),
        ( 'api', 'hol_rad_pla_qua', '{ 
            "nom": "Quantum #()($)ide() de 3: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/pla_qua/()($)ide().png);",
            "col": 3
        }' ),
        -- holon humano
        ( 'api', 'hol_rad_hum_cha', '{
            "nom": "Chakra #()($)ide() de 7: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/hum_cha/()($)ide().png);",
            "col": 7
        }' ),
        ( 'api', 'hol_rad_hum_mud', '{ 
            "nom": "Mudra #()($)ide() de 7: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad/hum_mud/()($)ide().png);"
        }' )        
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_ton%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_ton', '{
            "nom": "Tono Galáctico #()($)ide() de 13: ()($)nom().",
            "des": "()($)des() ()($)acc_lec().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/()($)ide().png);",
            "col": 7
        }' ),
        ( 'api', 'hol_ton_ond', '{ 
            "nom": "Aventura de la Onda Encantada #()($)ide() de 4: ()($)des().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/ond/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_ton_dim', '{ 
            "nom": "Pulsar Dimensional #()($)ide() de 4: ()($)nom().",
            "des": "Campo de aplicación ()($)des().", 
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/dim/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_ton_mat', '{ 
            "nom": "Pulsar Matiz #()($)ide() de 5: ()($)nom().",
            "des": "()($)des().", 
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/mat/()($)ide().png);",
            "col": 5
        }' ),
        ( 'api', 'hol_ton_sim', '{ 
            "nom": "Simetría Especular de tonos ()($)ide() y ()($)inv(): ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/sim/()($)ide().png);",
            "col": 7
        }' ),
        -- holon humano
        ( 'api', 'hol_ton_hum_art', '{
            "nom": "Articulación #()($)ide() de 7: ()($)nom().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/hum_art/()($)ide().png);",
            "col": 7
        }' )
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_sel%';
    INSERT INTO `_api`.`dat_val` VALUES
        -- datos
        ( 'api', 'hol_sel', '{
            "nom": "Sello Solar #()($)ide(), ()($)arm().",
            "des": "()($)car() ()($)des().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_sel_cod', '{ 
            "nom": "Código #()($)cod()",
            "des": "()($)car() ()($)des().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/cod/()($)cod().png);",
            "col": 5
        }' ),
        -- ciclos
            ( 'api', 'hol_sel_cic_dir', '{
                "nom": "Ciclo Direccional #()($)ide() de 4: ()($)nom()",
                "des": "()($)des().", 
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/cic_dir/()($)ide().png);",
                "col": 4
            }' ),
            ( 'api', 'hol_sel_cic_ser', '{ 
                "nom": "Desarrollo del ser #()($)ide() de 3: ()($)nom().",
                "des": "()($)des().",
                "col": 3
            }' ),
            ( 'api', 'hol_sel_cic_luz', '{ 
                "nom": "Grupo Cíclico de la luz #()($)ide() de 5: ()($)nom().",
                "des": "()($)des().",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_cic_men', '{ 
                "nom": "Etapa evolutiva de la mente #()($)ide() de 4: ()($)nom().",
                "des": "()($)des().",
                "col": 4
            }' ),
        -- armónicas
            ( 'api', 'hol_sel_arm_raz', '{ 
                "nom": "Raza Raiz Cósmica #()($)ide() de 4: ()($)nom().",
                "des": "Poder: ()($)pod(); Dirección: ()($)dir(); Momento de Mayor Vibración: ()($)dia().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/arm_raz/()($)ide().png);",
                "col": 4
            }' ),
            ( 'api', 'hol_sel_arm_cel', '{ 
                "nom": "Célula del Tiempo #()($)ide() de 5: ()($)nom().",
                "des": "Poder: ()($)pod(); Función: ()($)fun().", 
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/arm_cel/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_arm_tra', '{
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel.png), center/contain no-repeat url(http://localhost/_/hol/ima/sel/()($)ide().png);"
            }' ),
        -- cromáticas
            ( 'api', 'hol_sel_cro_fam', '{ 
                "nom": "Familia Terrestre #()($)ide() de 5: ()($)nom().",
                "des": "Función: ()($)pla(); Centro-G: ()($)hum(); Misión: ()($)des();",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/cro_fam/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_cro_ele', '{ 
                "nom": "Clan #()($)ide() de 4: ()($)nom() ()($)col().",
                "des": "()($)des().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/cro_ele/()($)ide().png);",
                "col": 4
            }' ),
        -- holon solar
            ( 'api', 'hol_sel_sol_res', '{ 
                "nom": "()($)nom()",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/sol_res/()($)ide().png);",
                "col": 2
            }' ),
            ( 'api', 'hol_sel_sol_orb', '{ 
                "nom": "Grupo Orbital #()($)nom() de 2: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/sol_orb/()($)ide().png);",
                "col": 2
            }' ),
            ( 'api', 'hol_sel_sol_cel', '{ 
                "nom": "Célula Solar #()($)ide() de 5: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/sol_cel/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_sol_cir', '{ 
                "nom": "Circuito de Telepatía #()($)ide() de 5: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/sol_cir/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_sol_pla', '{ 
                "nom": "Órbita Planetaria #()($)ide() de 10: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/sol_pla/()($)ide().png);",
                "col": 10
            }' ),
        -- holon planetario
            ( 'api', 'hol_sel_pla_res', '{ 
                "nom": "()($)nom()",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/pla_res/()($)ide().png);",
                "col": 2
            }' ),
            ( 'api', 'hol_sel_pla_cen', '{
                "nom": "Centro Planetario #()($)ide() de 5: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/pla_cen/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_pla_hem', '{
                "nom": "Hemisferio #()($)ide() de 3: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/pla_hem/()($)ide().png);",
                "col": 3
            }' ),  
            ( 'api', 'hol_sel_pla_mer', '{
                "nom": "Meridiano #()($)ide() de 2: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/pla_mer/()($)ide().png);",
                "col": 2
            }' ),
        -- holon humano
            ( 'api', 'hol_sel_hum_res', '{ 
                "nom": "()($)nom()",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/hum_res/()($)ide().png);",
                "col": 2
            }' ),      
            ( 'api', 'hol_sel_hum_ext', '{
                "nom": "Extremidad #()($)ide() de 4: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/hum_ext/()($)ide().png);",
                "col": 4
            }' ),
            ( 'api', 'hol_sel_hum_cen', '{ 
                "nom": "Centro Galáctico #()($)ide() de 5: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/hum_cen/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_hum_ded', '{ 
                "nom": "Dedo #()($)ide() de 5: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/hum_ded/()($)ide().png);",
                "col": 5
            }' ),
            ( 'api', 'hol_sel_hum_mer', '{ 
                "nom": "Meridiano Orgánico #()($)ide() de 10: ()($)nom().",
                "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/hum_mer/()($)ide().png);",
                "col": 10
            }' )
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_lun%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_lun', '{
            "nom": "()($)ide()° Día de 28.",
            "des": ""
        }' ),
        ( 'api', 'hol_lun_arm', '{ 
            "nom": "Armonía lunar ()($)ide()",
            "des": "()($)ide()",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/rad.png), center/contain no-repeat url(http://localhost/_/hol/ima/arm/()($)ide().png);",
            "col": 4
        }' )  
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_cas%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_cas', '{
            "nom": "Posicion #()($)ide() de 52.",
            "des": "Cuadrante #()($)arm() de 4; Tono Galáctico #()($)ton() de 13; Onda de la Aventura #()($)ond() de 4."
        }' ),
        ( 'api', 'hol_cas_arm', '{
            "nom": "Cuadrante #()($)ide() de 4",
            "des": "Dirección: ()($)dir(); Poder: ()($)pod(); Color: ()($)col().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/cas/arm/()($)ide().png);",
            "col": 4
        }'),
        ( 'api', 'hol_cas_ond', '{
            "nom": "Aventura de la Onda Encantada #()($)ide() de 4",
            "des": "()($)des().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/cas/ond/()($)ide().png);",
            "col": 4
        }')
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_chi%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_chi', '{ 
            "nom": "",
            "des": "",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/chi/()($)ide().png);"
        }' ),
        ( 'api', 'hol_chi_mon', '{ 
            "nom": "",
            "des": "",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/chi/mon/()($)ide().png);"
        }' ),
        ( 'api', 'hol_chi_bin', '{ 
            "nom": "",
            "des": "",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/chi/bin/()($)ide().png);"
        }' ),
        ( 'api', 'hol_chi_tri', '{ 
            "nom": "",
            "des": "",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/chi/tri/()($)ide().png);"
        }' ) 
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_kin%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_kin', '{
            "nom": "Kin #()($)ide() de 260: ()($)nom().",
            "des": "()($)des().",
            "ima": "background: top/50% no-repeat url(http://localhost/_/hol/ima/ton/()($)nav_ond_dia().png), bottom/60% no-repeat url(http://localhost/_/hol/ima/sel/()($)arm_tra_dia().png);"
        }'),
        ( 'api', 'hol_kin_ene', '{ 
            "nom": "Grupo #()($)ide() de ()($)nom() ( ()($)gru() x ()($)gru_uni() = ()($)uni() unidades )",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/kin/ene/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_kin_ene_cam', '{ 
            "nom": "Campo #()($)ide() de ()($)nom() unidades",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/kin/ene_cam/()($)ide().png);"
        }' ),
        ( 'api', 'hol_kin_arm_tra', '{ 
            "nom": "Trayectoria Armónica #()($)ide() de 13: ()($)nom().",
            "des": "()($)ton_des() del Giro Galáctico.",
            "ima": "background: top/75% no-repeat url(http://localhost/_/hol/ima/ton/()($)ide().png), center/contain no-repeat url(http://localhost/_/hol/ima/sel.png);",
            "col": 7
        }' ),
        ( 'api', 'hol_kin_arm_cel', '{ 
            "nom": "Célula del Tiempo #()($)ide() de 65: ()($)nom().", 
            "des": "()($)des().",
            "ima": "background: top/75% no-repeat url(http://localhost/_/hol/ima/ton/()($)ton().png), center/contain no-repeat url(http://localhost/_/hol/ima/sel/arm_cel/()($)cel().png);",
            "col": 5
        }' ),
        ( 'api', 'hol_kin_nav_cas', '{ 
            "nom": "Castillo #()($)ide() de 5: ()($)nom().",
            "des": "()($)des().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/kin/nav_cas/()($)ide().png);",
            "col": 5
        }' ),
        ( 'api', 'hol_kin_nav_ond', '{ 
            "nom": "Onda Encantada #()($)ide() de 20: ()($)nom().",
            "des": "()($)enc_des().", 
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/ton/arm/()($)cas_arm().png), center/contain no-repeat url(http://localhost/_/hol/ima/sel/()($)sel().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_kin_gen_enc', '{ 
            "nom": "()($)ide()° Génesis del Encantamiento del Sueño: ()($)nom().",
            "des": "()($)des().",
            "col": 3
        }' ),
        ( 'api', 'hol_kin_gen_cel', '{ 
            "nom": "Célula de la Memoria #()($)ide() de 5: ()($)nom().",
            "des": "()($)des().",
            "col": 5
        }' ),
        ( 'api', 'hol_kin_cro_est', '{
            "nom": "Espectro Galáctico #()($)ide() de 4: ()($)col() del ()($)dir().",
            "des": "Guardían ()($)may(): ()($)cer(), ()($)cer_des()",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/cas/arm/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_kin_cro_ele', '{ 
            "nom": "Elemento Cromático #()($)ide() de 52: ()($)nom().",
            "des": "()($)des(): ()($)cas_des().",
            "ima": "background: bottom/75% no-repeat url(http://localhost/_/hol/ima/ton/()($)ton().png), center/contain no-repeat url(http://localhost/_/hol/ima/sel/cro_ele/()($)ele().png);",
            "col": 4
        }' )
    ;
    DELETE FROM `_api`.`dat_val` WHERE `esq`='api' AND `est` LIKE 'hol_psi%';
    INSERT INTO `_api`.`dat_val` VALUES

        ( 'api', 'hol_psi', '{
            "nom": "PSI #()($)ide() de 365, correspondiente al ()($)fec().",
            "des": "Psi-Cronos: ()($)tzo(). Estación Solar #()($)est() de 4, día ()($)est_dia(). Giro Lunar #()($)lun() de 13, día ()($)lun_dia() de 28. Héptada #()($)hep() de 52, día ()($)hep_dia() de 7.",
            "ima": "background: top/50% no-repeat url(http://localhost/_/hol/ima/ton/()($)kin_ton().png), bottom/60% no-repeat url(http://localhost/_/hol/ima/sel/()($)kin_sel().png);"
        }' ),
        ( 'api', 'hol_psi_est',     '{
            "nom": "Estación Solar #()($)ide() de 4: ()($)nom().",
            "des": "()($)des() ( ()($)pol_sur() al sur, ()($)pol_nor() al norte )",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/cas/arm/()($)ide().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_psi_lun',     '{
            "nom": "Luna #()($)ide() de 13: tono ()($)ton_nom().",
            "des": "()($)des() del Giro Solar Anual; Totem ()($)tot(): ()($)tot_pro().",
            "ima": "background: url(http://localhost/_/hol/ima/psi/lun/()($)ide().png) center/contain no-repeat;",
            "col": 7
        }' ),
        ( 'api', 'hol_psi_hep',     '{
            "nom": "Heptada #()($)ide() de 52.",
            "des": "()($)ton_des() del cuadrante ()($)arm_col() en el ()($)arm_dir().",
            "ima": "background: center/contain no-repeat url(http://localhost/_/hol/ima/sel/cod/()($)ton().png), center/contain no-repeat url(http://localhost/_/hol/ima/rad.png), center/contain no-repeat url(http://localhost/_/hol/ima/arm/()($)ond().png);",
            "col": 4
        }' ),
        ( 'api', 'hol_psi_vin',     '{
            "nom": "Vinal #()($)ide() de 19: ()($)nom().",
            "des": "()($)des()."
        }' ),
        ( 'api', 'hol_psi_cro',     '{
            "nom": "Cromática Entonada #()($)ide() de 75.",
            "des": ""
        }' )
    ;
  --
--