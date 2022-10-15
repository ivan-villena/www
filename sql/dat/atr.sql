-- Active: 1665550796793@@127.0.0.1@3306@api

-- Holon
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_%'
	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_rad%';
	INSERT INTO `api`.`dat_atr` VALUES
	
		-- plasma
			('api','hol_rad','ide','{ "min":1, "max":7, "dat":"api.hol_rad" }'),
			-- telektonon
			('api','hol_rad','tel_ora','{ "min":1997, "max":1999, "dat":"api.fec_año" }'),
			('api','hol_rad','tel_ora_año','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_rad','tel_ora_ani','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_rad','tel_ora_gen','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			-- electrico
			('api','hol_rad','pla_fue_pre','{ "min":1, "max":12, "dat":"api.hol_rad_pla_fue" }'),
			('api','hol_rad','pla_fue_pos','{ "min":1, "max":12, "dat":"api.hol_rad_pla_fue" }'),

		-- chakras
			('api','hol_rad','hum_cha','{ "min":1, "max":7, "dat":"api.hol_rad_hum_cha" }'),
				
		-- lineas de fuerza		
			('api','hol_rad_pla_fue','ele_pre','{ "min":1, "max":6, "dat":"api.hol_rad_pla_ele" }'),
			('api','hol_rad_pla_fue','ele_pos','{ "min":1, "max":6, "dat":"api.hol_rad_pla_ele" }')
	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_ton%';
	INSERT INTO `api`.`dat_atr` VALUES

		-- 13 tonos
			('api','hol_ton','ide','{ "min":1, "max":13, 		"dat":"api.hol_ton" }'),
			('api','hol_ton','ond','{ "min":1, "max":4, 		"dat":"api.hol_ton_ond" }'),
			('api','hol_ton','ond_enc','{ "min":0, "max":4, "dat":"api.hol_ton_ond" }'),			
			('api','hol_ton','dim','{ "min":1, "max":4, 		"dat":"api.hol_ton_dim" }'),
			('api','hol_ton','mat','{ "min":1, "max":5, 		"dat":"api.hol_ton_mat" }'),
			('api','hol_ton','sim','{ "min":1, "max":7, 		"dat":"api.hol_ton_sim" }'),
			('api','hol_ton','hum_lad','{ "min":1, "max":3, "dat":"api.hol_ton_hum_lad" }'),
			('api','hol_ton','hum_art','{ "min":1, "max":7, "dat":"api.hol_ton_hum_art" }'),
			('api','hol_ton','hum_sen','{ "min":1, "max":7, "dat":"api.hol_ton_hum_sen" }'),			

		-- Aventura
			('api','hol_ton_ond','ide','{ "min":1, "max":4, "dat":"api.hol_ton_ond" }'),

		-- Dimensional
			('api','hol_ton_dim','ide','{ "min":1, "max":4, "dat":"api.hol_ton_dim" }'),

		-- Matiz
			('api','hol_ton_mat','ide','{ "min":1, "max":5, "dat":"api.hol_ton_mat" }'),

		-- Simetria 
			('api','hol_ton_sim','ide','{ "min":1, "max":7, "dat":"api.hol_ton_sim" }'),
			('api','hol_ton_sim','inv','{ min":1, "max":13, "dat":"api.hol_ton" }')
	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_sel%';
	INSERT INTO `api`.`dat_atr` VALUES

		-- sello solar
			('api','hol_sel','ide','{ "min":1, "max":20, 			"dat":"api.hol_sel" }'),
			('api','hol_sel','cod','{ "min":0, "max":19 }'),
			('api','hol_sel','ord','{ "min":1, "max":20, 			"dat":"api.hol_sel_cod" }'),
			('api','hol_sel','cic_ser','{ "min":1, "max":3, 	"dat":"api.hol_sel_cic_ser" }'),
			('api','hol_sel','cic_luz','{ "min":1, "max":5, 	"dat":"api.hol_sel_cic_luz" }'),
			('api','hol_sel','arm_tra','{ "min":1, "max":20, 	"dat":"api.hol_sel.arm_tra" }'),
			('api','hol_sel','arm_raz','{ "min":1, "max":4, 	"dat":"api.hol_sel_arm_raz" }'),
			('api','hol_sel','arm_cel','{ "min":1, "max":5, 	"dat":"api.hol_sel_arm_cel" }'),
			('api','hol_sel','cro_fam','{ "min":1, "max":5, 	"dat":"api.hol_sel_cro_fam" }'),
			('api','hol_sel','cro_ele','{ "min":1, "max":4, 	"dat":"api.hol_sel_cro_ele" }'),
			('api','hol_sel','par_ana','{ "min":1, "max":20, 	"dat":"api.hol_sel" }'),
			('api','hol_sel','par_ant','{ "min":1, "max":20, 	"dat":"api.hol_sel" }'),
			('api','hol_sel','par_ocu','{ "min":1, "max":20, 	"dat":"api.hol_sel" }'),
			('api','hol_sel','res_flu','{ "min":1, "max":2, 	"dat":"api.hol_sel_res_flu" }'),
			('api','hol_sel','sol_pla','{ "min":1, "max":10, 	"dat":"api.hol_sel_sol_pla" }'),
			('api','hol_sel','sol_cel','{ "min":1, "max":10, 	"dat":"api.hol_sel_sol_cel" }'),
			('api','hol_sel','sol_cir','{ "min":1, "max":10, 	"dat":"api.hol_sel_sol_cir" }'),			
			('api','hol_sel','pla_cen','{ "min":1, "max":5, 	"dat":"api.hol_sel_pla_cen" }'),
			('api','hol_sel','pla_hem','{ "min":1, "max":3, 	"dat":"api.hol_sel_pla_hem" }'),
			('api','hol_sel','pla_mer','{ "min":1, "max":2, 	"dat":"api.hol_sel_pla_mer" }'),
			('api','hol_sel','hum_res','{ "min":1, "max":2, 	"dat":"api.hol_sel_res_flu" }'),
			('api','hol_sel','hum_cen','{ "min":1, "max":5, 	"dat":"api.hol_sel_hum_cen" }'),
			('api','hol_sel','hum_ext','{ "min":1, "max":5, 	"dat":"api.hol_sel_hum_ext" }'),
			('api','hol_sel','hum_ded','{ "min":1, "max":5, 	"dat":"api.hol_sel_hum_ded" }'),
			('api','hol_sel','hum_mer','{ "min":1, "max":10, 	"dat":"api.hol_sel_hum_mer" }'),

		-- código 0-19
			('api','hol_sel_cod','ide','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_cod','cod','{ "min":0, "max":19 }'),
			('api','hol_sel_cod','ord','{ "min":1, "max":20, "dat":"api.hol_sel_cod" }'),
			('api','hol_sel_cod','cro_fam','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),			
			('api','hol_sel_cod','cro_ele','{ "min":1, "max":4, "dat":"api.hol_sel_cro_ele" }'),
			('api','hol_sel_cod','res_flu','{ "min":1, "max":2, "dat":"api.hol_sel_res_flu" }'),
			('api','hol_sel_cod','sol_pla','{ "min":1, "max":10, "dat":"api.hol_sel_sol_pla" }'),
			('api','hol_sel_cod','sol_cel','{ "min":1, "max":10, "dat":"api.hol_sel_sol_cel" }'),
			('api','hol_sel_cod','sol_cir','{ "min":1, "max":10, "dat":"api.hol_sel_sol_cir" }'),
			('api','hol_sel_cod','pla_cen','{ "min":1, "max":5, "dat":"api.hol_sel_pla_cen" }'),
			('api','hol_sel_cod','pla_hem','{ "min":1, "max":3, "dat":"api.hol_sel_pla_hem" }'),
			('api','hol_sel_cod','pla_mer','{ "min":1, "max":2, "dat":"api.hol_sel_pla_mer" }'),
			('api','hol_sel_cod','hum_res','{ "min":1, "max":2, "dat":"api.hol_sel_res_flu" }'),
			('api','hol_sel_cod','hum_cen','{ "min":1, "max":5, "dat":"api.hol_sel_hum_cen" }'),
			('api','hol_sel_cod','hum_ext','{ "min":1, "max":5, "dat":"api.hol_sel_hum_ext" }'),
			('api','hol_sel_cod','hum_ded','{ "min":1, "max":5, "dat":"api.hol_sel_hum_ded" }'),
			('api','hol_sel_cod','hum_mer','{ "min":1, "max":10, "dat":"api.hol_sel_hum_mer" }'),

		-- simbolos direccionales
			('api','hol_sel_cic_dir','ide','{ "min":1, "max":4, "dat":"api.hol_sel_cic_dir" }'),
		-- etapas evolutivas de la mente
			('api','hol_sel_cic_men','ide','{ "min":1, "max":4, "dat":"api.hol_kin_cro_est" }'),
			('api','hol_sel_cic_men','sel','{ "min":1, "max":20, "dat":"api.hol_sel" }'),

		-- familia cromatica
			('api','hol_sel_cro_fam','ide','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
			('api','hol_sel_cro_fam','pla_cen','{ "min":1, "max":5, "dat":"api.hol_sel_pla_cen" }'),
			('api','hol_sel_cro_fam','hum_cen','{ "min":1, "max":5, "dat":"api.hol_sel_hum_cen" }'),
			('api','hol_sel_cro_fam','hum_ded','{ "min":1, "max":5, "dat":"api.hol_sel_hum_ded" }'),
		-- clan cromatico
			('api','hol_sel_cro_ele','ide','{ "min":1, "max":4, "dat":"api.hol_sel_cro_ele" }'),
			('api','hol_sel_cro_ele','res_flu','{ "min":1, "max":2, "dat":"api.hol_sel_res_flu" }'),
			('api','hol_sel_cro_ele','hum_ext','{ "min":1, "max":4, "dat":"api.hol_sel_hum_ext" }'),

		-- parejas
			('api','hol_sel_par_ana','ini','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_par_ana','fin','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_par_ant','ini','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_par_ant','fin','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_par_ocu','ini','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_sel_par_ocu','fin','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			
		-- holon solar
			('api','hol_sel_sol_res','ide','{ "min":1, "max":2, "dat":"api.hol_sel_sol_res" }'),
			-- orbitales
			('api','hol_sel_sol_orb','ide','{ "min":1, "max":2, "dat":"api.hol_sel_sol_orb" }'),
			-- celulas
			('api','hol_sel_sol_cel','ide','{ "min":1, "max":5, "dat":"api.hol_sel_sol_cel" }'),
			-- circuitos
			('api','hol_sel_sol_cir','ide','{ "min":1, "max":5, "dat":"api.hol_sel_sol_cir" }'),			
			-- planetas
			('api','hol_sel_sol_pla','ide','{ "min":1, "max":10, "dat":"api.hol_sel_sol_pla" }'),
			('api','hol_sel_sol_pla','orb','{ "min":1, "max":5, "dat":"api.hol_sel_sol_orb" }'),
			('api','hol_sel_sol_pla','cel','{ "min":1, "max":5, "dat":"api.hol_sel_sol_cel" }'),
			('api','hol_sel_sol_pla','cir','{ "min":1, "max":5, "dat":"api.hol_sel_sol_cir" }'),
		-- holon planetario
			('api','hol_sel_pla_res','ide','{ "min":1, "max":2, "dat":"api.hol_sel_pla_res" }'),
			('api','hol_sel_pla_res','hem','{ "min":1, "max":3, "dat":"api.hol_sel_pla_hem" }'),
			('api','hol_sel_pla_res','fam','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
			-- meridianos
			('api','hol_sel_pla_mer','ide','{ "min":1, "max":2, "dat":"api.hol_sel_pla_mer" }'),			
			-- hemisferios
			('api','hol_sel_pla_hem','ide','{ "min":1, "max":3, "dat":"api.hol_sel_pla_hem" }'),			
			-- centros
			('api','hol_sel_pla_cen','ide','{ "min":1, "max":5, "dat":"api.hol_sel_pla_cen" }'),
			('api','hol_sel_pla_cen','fam','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
		-- holon humano
			('api','hol_sel_hum_res','ide','{ "min":1, "max":2, "dat":"api.hol_sel_hum_res" }'),
			-- extremidades
			('api','hol_sel_hum_ext','ide','{ "min":1, "max":4, "dat":"api.hol_sel_hum_ext" }'),
			('api','hol_sel_hum_ext','ele','{ "min":1, "max":4, "dat":"api.hol_sel_cro_ele" }'),			
			-- centros
			('api','hol_sel_hum_cen','ide','{ "min":1, "max":5, "dat":"api.hol_sel_hum_cen" }'),
			('api','hol_sel_hum_cen','fam','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
			-- dedos
			('api','hol_sel_hum_ded','ide','{ "min":1, "max":5, "dat":"api.hol_sel_hum_ded" }'),
			('api','hol_sel_hum_ded','fam','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
			-- meridianos
			('api','hol_sel_hum_mer','ide','{ "min":1, "max":10, "dat":"api.hol_sel_hum_mer" }')					
	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_lun%';
	INSERT INTO `api`.`dat_atr` VALUES
		-- posiciones
			('api','hol_lun','ide','{ "min":1, "max":28, "dat":"api.hol_lun" }'),
			('api','hol_lun','arm','{ "min":1, "max":4, "dat":"api.hol_lun_arm" }'),
			('api','hol_lun','rad','{ "min":1, "max":7, "dat":"api.hol_rad" }'),
		-- heptadas
			('api','hol_lun_arm','ide','{ "min":1, "max":4, "dat":"api.hol_lun_arm" }'),
		-- tores de poder
			('api','hol_lun_tel_tor','ide','{ "min":1, "max":4, "dat":"api.hol_lun_tel_tor" }'),
		-- caminatas 
			('api','hol_lun_tel_cam','ide','{ "min":1, "max":8, "dat":"api.hol_lun_tel_cam" }'),
		-- cubo del guerrero
			('api','hol_lun_tel_cub','ide','{ "min":1, "max":16, "dat":"api.hol_lun_tel_cub" }')
	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_cas%';
	INSERT INTO `api`.`dat_atr` VALUES
		-- posiciones
			('api','hol_cas','ide','{ "min":1, "max":52, "dat":"api.hol_cas" }'),
			('api','hol_cas','arm','{ "min":1, "max":4, "dat":"api.hol_cas_arm" }'),
			('api','hol_cas','ond','{"min":1, "max":4, "dat":"api.hol_cas_ond" }'),
			('api','hol_cas','pos_arm','{ "min":1, "max":4, "dat":"api.hol_arm" }'),
			('api','hol_cas','ton','{"min":1, "max":13, "dat":"api.hol_ton" }'),
			('api','hol_cas','ton_arm','{ "min":1, "max":13, "dat":"api.hol_ton" }')

	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_kin%';
	INSERT INTO `api`.`dat_atr` VALUES

		-- tzolkin
			('api','hol_kin','ide','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_kin','ene','{ "min":1, "max":4, "dat":"api.hol_kin_ene" }'),
			('api','hol_kin','ene_cam','{ "min":1, "max":14, "dat":"api.hol_kin_ene_cam" }'),
			('api','hol_kin','chi','{ "min":1, "max":65, "dat":"api.hol_chi" }'),
			('api','hol_kin','cro_est','{ "min":1, "max":4, "dat":"api.hol_kin_cro_est" }'),
			('api','hol_kin','cro_est_dia','{ "min":1, "max":65, "dat":"api.hol_chi" }'),
			('api','hol_kin','cro_ele','{ "min":1, "max":4, "dat":"api.hol_kin_cro_ele" }'),
			('api','hol_kin','cro_ele_dia','{ "min":1, "max":5, "dat":"api.hol_sel_cro_fam" }'),
			('api','hol_kin','arm_tra','{ "min":1, "max":13, "dat":"api.hol_kin_arm_tra" }'),
			('api','hol_kin','arm_tra_dia','{ "min":1, "max":20, "dat":"api.hol_sel" }'),
			('api','hol_kin','arm_cel','{ "min":1, "max":5, "dat":"api.hol_kin_arm_cel" }'),
			('api','hol_kin','arm_cel_dia','{ "min":1, "max":4, "dat":"api.hol_sel_arm_raz" }'),  
			('api','hol_kin','gen_enc','{ "min":1, "max":3, "dat":"api.hol_kin_gen_enc" }'),
			('api','hol_kin','gen_enc_dia','{ "min":1, "max":3, "max-1":130, "max-2":90, "max-3":52 }'),
			('api','hol_kin','gen_cel','{ "min":1, "max":5, "dat":"api.hol_kin_gen_cel" }'),
			('api','hol_kin','gen_cel_dia','{ "min":1, "max":26 }'),
			('api','hol_kin','nav_cas','{ "min":1, "max":5, "dat":"api.hol_kin_nav_cas" }'),
			('api','hol_kin','nav_cas_dia','{ "min":1, "max":52, "dat":"api.hol_cas" }'),  
			('api','hol_kin','nav_ond','{ "min":1, "max":20, "dat":"api.hol_kin_nav_ond" }'),
			('api','hol_kin','nav_ond_dia','{ "min":1, "max":13, "dat":"api.hol_ton" }'),
			('api','hol_kin','par_ana','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_kin','par_gui','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_kin','par_ant','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_kin','par_ocu','{ "min":1, "max":260, "dat":"api.hol_kin" }')

	;
	DELETE FROM `api`.`dat_atr` WHERE `esq`='api' AND `est` LIKE 'hol_psi%';
	INSERT INTO `api`.`dat_atr` VALUES

		-- banco-psi
			('api','hol_psi','ide','{ "min":1, "max":365, "dat":"api.hol_psi" }'),
			('api','hol_psi','tzo','{ "min":1, "max":260, "dat":"api.hol_kin" }'),
			('api','hol_psi','est','{ "min":1, "max":4, "dat":"api.hol_psi_est" }'),
			('api','hol_psi','est_dia','{ "min":1, "max":91, "dat":"" }'),
			('api','hol_psi','lun','{ "min":1, "max":13, "dat":"api.hol_psi_lun" }'),
			('api','hol_psi','lun_dia','{ "min":1, "max":28, "dat":"api.hol_lun" }'),
			('api','hol_psi','hep','{ "min":1, "max":52, "dat":"api.hol_psi_hep" }'),
			('api','hol_psi','hep_dia','{ "min":1, "max":7, "dat":"api.hol_rad" }'),
		-- 13 lunas
			('api','hol_psi_lun','ide','{ "min":1, "max":13, "dat":"api.hol_psi_lun" }'),
			('api','hol_psi_lun','ond','{ "min":1, "max":4, "dat":"api.hol_ton_ond" }')
	;
--
--