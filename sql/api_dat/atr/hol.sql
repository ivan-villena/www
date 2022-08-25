-- Active: 1623270923336@@127.0.0.1@3306@_api
-- Holon
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol'
	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'rad%';
	INSERT INTO `_api`.`dat_atr` VALUES
	
		-- plasma
			('hol','rad','ide','{ "min":1, "max":7, "dat":"hol.rad" }'),
			-- telektonon
			('hol','rad','tel_ora','{ "min":1997, "max":1999, "dat":"api.fec_año" }'),
			('hol','rad','tel_ora_año','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','rad','tel_ora_ani','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','rad','tel_ora_gen','{ "min":1, "max":260, "dat":"hol.kin" }'),
			-- electrico
			('hol','rad','pla_fue_pre','{ "min":1, "max":12, "dat":"hol.rad_pla_fue" }'),
			('hol','rad','pla_fue_pos','{ "min":1, "max":12, "dat":"hol.rad_pla_fue" }'),

		-- chakras
			('hol','rad','hum_cha','{ "min":1, "max":7, "dat":"hol.rad_hum_cha" }'),
				
		-- lineas de fuerza		
			('hol','rad_pla_fue','ele_pre','{ "min":1, "max":6, "dat":"hol.rad_pla_ele" }'),
			('hol','rad_pla_fue','ele_pos','{ "min":1, "max":6, "dat":"hol.rad_pla_ele" }')
	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'ton%';
	INSERT INTO `_api`.`dat_atr` VALUES

		-- 13 tonos
			('hol','ton','ide','{ "min":1, "max":13, "dat":"hol.ton" }'),
			('hol','ton','ond','{ "min":1, "max":4, "dat":"hol.ton_ond" }'),
			('hol','ton','ond_enc','{ "min":0, "max":4, "dat":"hol.ton_ond" }'),			
			('hol','ton','dim','{ "min":1, "max":4, "dat":"hol.ton_dim" }'),
			('hol','ton','mat','{ "min":1, "max":5, "dat":"hol.ton_mat" }'),
			('hol','ton','sim','{ "min":1, "max":7, "dat":"hol.ton_sim" }'),
			('hol','ton','hum_lad','{ "min":1, "max":3, "dat":"hol.ton_hum_lad" }'),
			('hol','ton','hum_art','{ "min":1, "max":7, "dat":"hol.ton_hum_art" }'),
			('hol','ton','hum_sen','{ "min":1, "max":7, "dat":"hol.ton_hum_sen" }'),			

		-- Aventura
			('hol','ton_ond','ide','{ "min":1, "max":4, "dat":"hol.ton_ond" }'),

		-- Dimensional
			('hol','ton_dim','ide','{ "min":1, "max":4, "dat":"hol.ton_dim" }'),

		-- Matiz
			('hol','ton_mat','ide','{ "min":1, "max":5, "dat":"hol.ton_mat" }'),

		-- Simetria 
			('hol','ton_sim','ide','{ "min":1, "max":7, "dat":"hol.ton_sim" }'),
			('hol','ton_sim','inv','{ min":1, "max":13, "dat":"hol.ton" }')
	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'sel%';
	INSERT INTO `_api`.`dat_atr` VALUES

		-- sello solar
			('hol','sel','ide','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel','cod','{ "min":0, "max":19 }'),
			('hol','sel','ord','{ "min":1, "max":20, "dat":"hol.sel_cod" }'),
			('hol','sel','cic_ser','{ "min":1, "max":3, "dat":"hol.sel_cic_ser" }'),
			('hol','sel','cic_luz','{ "min":1, "max":5, "dat":"hol.sel_cic_luz" }'),
			('hol','sel','arm_tra','{ "min":1, "max":20, "dat":"hol.sel.arm_tra" }'),
			('hol','sel','arm_raz','{ "min":1, "max":4, "dat":"hol.sel_arm_raz" }'),
			('hol','sel','arm_cel','{ "min":1, "max":5, "dat":"hol.sel_arm_cel" }'),
			('hol','sel','cro_fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			('hol','sel','cro_ele','{ "min":1, "max":4, "dat":"hol.sel_cro_ele" }'),
			('hol','sel','par_ana','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel','par_ant','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel','par_ocu','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel','res_flu','{ "min":1, "max":2, "dat":"hol.sel_res_flu" }'),
			('hol','sel','sol_pla','{ "min":1, "max":10, "dat":"hol.sel_sol_pla" }'),
			('hol','sel','sol_cel','{ "min":1, "max":10, "dat":"hol.sel_sol_cel" }'),
			('hol','sel','sol_cir','{ "min":1, "max":10, "dat":"hol.sel_sol_cir" }'),			
			('hol','sel','pla_cen','{ "min":1, "max":5, "dat":"hol.sel_pla_cen" }'),
			('hol','sel','pla_hem','{ "min":1, "max":3, "dat":"hol.sel_pla_hem" }'),
			('hol','sel','pla_mer','{ "min":1, "max":2, "dat":"hol.sel_pla_mer" }'),
			('hol','sel','hum_res','{ "min":1, "max":2, "dat":"hol.sel_res_flu" }'),
			('hol','sel','hum_cen','{ "min":1, "max":5, "dat":"hol.sel_hum_cen" }'),
			('hol','sel','hum_ext','{ "min":1, "max":5, "dat":"hol.sel_hum_ext" }'),
			('hol','sel','hum_ded','{ "min":1, "max":5, "dat":"hol.sel_hum_ded" }'),
			('hol','sel','hum_mer','{ "min":1, "max":10, "dat":"hol.sel_hum_mer" }'),

		-- código 0-19
			('hol','sel_cod','ide','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_cod','cod','{ "min":0, "max":19 }'),
			('hol','sel_cod','ord','{ "min":1, "max":20, "dat":"hol.sel_cod" }'),
			('hol','sel_cod','cro_fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),			
			('hol','sel_cod','cro_ele','{ "min":1, "max":4, "dat":"hol.sel_cro_ele" }'),
			('hol','sel_cod','res_flu','{ "min":1, "max":2, "dat":"hol.sel_res_flu" }'),
			('hol','sel_cod','sol_pla','{ "min":1, "max":10, "dat":"hol.sel_sol_pla" }'),
			('hol','sel_cod','sol_cel','{ "min":1, "max":10, "dat":"hol.sel_sol_cel" }'),
			('hol','sel_cod','sol_cir','{ "min":1, "max":10, "dat":"hol.sel_sol_cir" }'),
			('hol','sel_cod','pla_cen','{ "min":1, "max":5, "dat":"hol.sel_pla_cen" }'),
			('hol','sel_cod','pla_hem','{ "min":1, "max":3, "dat":"hol.sel_pla_hem" }'),
			('hol','sel_cod','pla_mer','{ "min":1, "max":2, "dat":"hol.sel_pla_mer" }'),
			('hol','sel_cod','hum_res','{ "min":1, "max":2, "dat":"hol.sel_res_flu" }'),
			('hol','sel_cod','hum_cen','{ "min":1, "max":5, "dat":"hol.sel_hum_cen" }'),
			('hol','sel_cod','hum_ext','{ "min":1, "max":5, "dat":"hol.sel_hum_ext" }'),
			('hol','sel_cod','hum_ded','{ "min":1, "max":5, "dat":"hol.sel_hum_ded" }'),
			('hol','sel_cod','hum_mer','{ "min":1, "max":10, "dat":"hol.sel_hum_mer" }'),

		-- simbolos direccionales
			('hol','sel_cic_dir','ide','{ "min":1, "max":4, "dat":"hol.sel_cic_dir" }'),
		-- etapas evolutivas de la mente
			('hol','sel_cic_men','ide','{ "min":1, "max":4, "dat":"hol.kin_cro_est" }'),
			('hol','sel_cic_men','sel','{ "min":1, "max":20, "dat":"hol.sel" }'),
		-- familia cromatica
			('hol','sel_cro_fam','ide','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			('hol','sel_cro_fam','pla_cen','{ "min":1, "max":5, "dat":"hol.sel_pla_cen" }'),
			('hol','sel_cro_fam','hum_cen','{ "min":1, "max":5, "dat":"hol.sel_hum_cen" }'),
			('hol','sel_cro_fam','hum_ded','{ "min":1, "max":5, "dat":"hol.sel_hum_ded" }'),
		-- clan cromatico
			('hol','sel_cro_ele','ide','{ "min":1, "max":4, "dat":"hol.sel_cro_ele" }'),
			('hol','sel_cro_ele','res_flu','{ "min":1, "max":2, "dat":"hol.sel_res_flu" }'),
			('hol','sel_cro_ele','hum_ext','{ "min":1, "max":4, "dat":"hol.sel_hum_ext" }'),
		-- parejas
			('hol','sel_par_ana','ini','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_par_ana','fin','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_par_ant','ini','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_par_ant','fin','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_par_ocu','ini','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','sel_par_ocu','fin','{ "min":1, "max":20, "dat":"hol.sel" }'),
		-- holon solar
			('hol','sel_sol_res','ide','{ "min":1, "max":2, "dat":"hol.sel_sol_res" }'),
			-- orbitales
			('hol','sel_sol_orb','ide','{ "min":1, "max":2, "dat":"hol.sel_sol_orb" }'),
			-- celulas
			('hol','sel_sol_cel','ide','{ "min":1, "max":5, "dat":"hol.sel_sol_cel" }'),
			-- circuitos
			('hol','sel_sol_cir','ide','{ "min":1, "max":5, "dat":"hol.sel_sol_cir" }'),			
			-- planetas
			('hol','sel_sol_pla','ide','{ "min":1, "max":10, "dat":"hol.sel_sol_pla" }'),
			('hol','sel_sol_pla','orb','{ "min":1, "max":5, "dat":"hol.sel_sol_orb" }'),
			('hol','sel_sol_pla','cel','{ "min":1, "max":5, "dat":"hol.sel_sol_cel" }'),
			('hol','sel_sol_pla','cir','{ "min":1, "max":5, "dat":"hol.sel_sol_cir" }'),
		-- holon planetario
			('hol','sel_pla_res','ide','{ "min":1, "max":2, "dat":"hol.sel_pla_res" }'),
			('hol','sel_pla_res','hem','{ "min":1, "max":3, "dat":"hol.sel_pla_hem" }'),
			('hol','sel_pla_res','fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			-- meridianos
			('hol','sel_pla_mer','ide','{ "min":1, "max":2, "dat":"hol.sel_pla_mer" }'),			
			-- hemisferios
			('hol','sel_pla_hem','ide','{ "min":1, "max":3, "dat":"hol.sel_pla_hem" }'),			
			-- centros
			('hol','sel_pla_cen','ide','{ "min":1, "max":5, "dat":"hol.sel_pla_cen" }'),
			('hol','sel_pla_cen','fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
		-- holon humano
			('hol','sel_hum_res','ide','{ "min":1, "max":2, "dat":"hol.sel_hum_res" }'),
			-- extremidades
			('hol','sel_hum_ext','ide','{ "min":1, "max":4, "dat":"hol.sel_hum_ext" }'),
			('hol','sel_hum_ext','ele','{ "min":1, "max":4, "dat":"hol.sel_cro_ele" }'),			
			-- centros
			('hol','sel_hum_cen','ide','{ "min":1, "max":5, "dat":"hol.sel_hum_cen" }'),
			('hol','sel_hum_cen','fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			-- dedos
			('hol','sel_hum_ded','ide','{ "min":1, "max":5, "dat":"hol.sel_hum_ded" }'),
			('hol','sel_hum_ded','fam','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			-- meridianos
			('hol','sel_hum_mer','ide','{ "min":1, "max":10, "dat":"hol.sel_hum_mer" }')					
	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'lun%';
	INSERT INTO `_api`.`dat_atr` VALUES
		-- posiciones
			('hol','lun','ide','{ "min":1, "max":28, "dat":"hol.lun" }'),
			('hol','lun','arm','{ "min":1, "max":4, "dat":"hol.lun_arm" }'),
			('hol','lun','rad','{ "min":1, "max":7, "dat":"hol.rad" }'),
		-- heptadas
			('hol','lun_arm','ide','{ "min":1, "max":4, "dat":"hol.lun_arm" }'),
		-- tores de poder
			('hol','lun_tel_tor','ide','{ "min":1, "max":4, "dat":"hol.lun_tel_tor" }'),
		-- caminatas 
			('hol','lun_tel_cam','ide','{ "min":1, "max":8, "dat":"hol.lun_tel_cam" }'),
		-- cubo del guerrero
			('hol','lun_tel_cub','ide','{ "min":1, "max":16, "dat":"hol.lun_tel_cub" }')
	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'cas%';
	INSERT INTO `_api`.`dat_atr` VALUES
		-- posiciones
			('hol','cas','ide','{ "min":1, "max":52, "dat":"hol.cas" }'),
			('hol','cas','arm','{ "min":1, "max":4, "dat":"hol.cas_arm" }'),
			('hol','cas','ond','{"min":1, "max":4, "dat":"hol.cas_ond" }'),
			('hol','cas','pos_arm','{ "min":1, "max":4, "dat":"hol.arm" }'),
			('hol','cas','ton','{"min":1, "max":13, "dat":"hol.ton" }'),
			('hol','cas','ton_arm','{ "min":1, "max":13, "dat":"hol.ton" }')

	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'kin%';
	INSERT INTO `_api`.`dat_atr` VALUES

		-- tzolkin
			('hol','kin','ide','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','kin','ene','{ "min":1, "max":4, "dat":"hol.kin_ene" }'),
			('hol','kin','ene_cam','{ "min":1, "max":14, "dat":"hol.kin_ene_cam" }'),
			('hol','kin','chi','{ "min":1, "max":65, "dat":"hol.chi" }'),
			('hol','kin','cro_est','{ "min":1, "max":4, "dat":"hol.kin_cro_est" }'),
			('hol','kin','cro_est_dia','{ "min":1, "max":65, "dat":"hol.chi" }'),
			('hol','kin','cro_ele','{ "min":1, "max":4, "dat":"hol.kin_cro_ele" }'),
			('hol','kin','cro_ele_dia','{ "min":1, "max":5, "dat":"hol.sel_cro_fam" }'),
			('hol','kin','arm_tra','{ "min":1, "max":13, "dat":"hol.kin_arm_tra" }'),
			('hol','kin','arm_tra_dia','{ "min":1, "max":20, "dat":"hol.sel" }'),
			('hol','kin','arm_cel','{ "min":1, "max":5, "dat":"hol.kin_arm_cel" }'),
			('hol','kin','arm_cel_dia','{ "min":1, "max":4, "dat":"hol.sel_arm_raz" }'),  
			('hol','kin','gen_enc','{ "min":1, "max":3, "dat":"hol.kin_gen_enc" }'),
			('hol','kin','gen_enc_dia','{ "min":1, "max":3, "max-1":130, "max-2":90, "max-3":52 }'),
			('hol','kin','gen_cel','{ "min":1, "max":5, "dat":"hol.kin_gen_cel" }'),
			('hol','kin','gen_cel_dia','{ "min":1, "max":26 }'),
			('hol','kin','nav_cas','{ "min":1, "max":5, "dat":"hol.kin_nav_cas" }'),
			('hol','kin','nav_cas_dia','{ "min":1, "max":52, "dat":"hol.cas" }'),  
			('hol','kin','nav_ond','{ "min":1, "max":20, "dat":"hol.kin_nav_ond" }'),
			('hol','kin','nav_ond_dia','{ "min":1, "max":13, "dat":"hol.ton" }'),
			('hol','kin','par_ana','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','kin','par_gui','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','kin','par_ant','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','kin','par_ocu','{ "min":1, "max":260, "dat":"hol.kin" }')

	;
	DELETE FROM `_api`.`dat_atr` WHERE `esq`='hol' AND `est` LIKE 'psi%';
	INSERT INTO `_api`.`dat_atr` VALUES

		-- banco-psi
			('hol','psi','ide','{ "min":1, "max":365, "dat":"hol.psi" }'),
			('hol','psi','tzo','{ "min":1, "max":260, "dat":"hol.kin" }'),
			('hol','psi','est','{ "min":1, "max":4, "dat":"hol.psi_est" }'),
			('hol','psi','est_dia','{ "min":1, "max":91, "dat":"" }'),
			('hol','psi','lun','{ "min":1, "max":13, "dat":"hol.psi_lun" }'),
			('hol','psi','lun_dia','{ "min":1, "max":28, "dat":"hol.lun" }'),
			('hol','psi','hep','{ "min":1, "max":52, "dat":"hol.psi_hep" }'),
			('hol','psi','hep_dia','{ "min":1, "max":7, "dat":"hol.rad" }'),
		-- 13 lunas
			('hol','psi_lun','ide','{ "min":1, "max":13, "dat":"hol.psi_lun" }'),
			('hol','psi_lun','ond','{ "min":1, "max":4, "dat":"hol.ton_ond" }')
	;