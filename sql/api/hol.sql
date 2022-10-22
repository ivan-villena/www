-- Active: 1665550796793@@127.0.0.1@3306@api

-- HOLON
  --
  -- x7 : plasma radial
    DROP VIEW IF EXISTS `api`.`_hol_rad`; CREATE VIEW `api`.`_hol_rad` AS
      SELECT 
        _rad.*,
        _cha.nom as `cha_nom`
      FROM 
        `api`.`hol_rad` _rad
      INNER JOIN 
        `api`.`hol_rad_hum_cha` _cha ON _rad.hum_cha = _cha.ide
      ORDER BY
        _rad.ide ASC
    ;
    DROP VIEW IF EXISTS `api`.`_hol_rad_pla_cub`; CREATE VIEW `api`.`_hol_rad_pla_cub` AS
      SELECT 
        _cub.*,
        _rad.pla_cub_pos AS `nom`,
        _rad.nom         AS `pla`        
      FROM 
        `api`.`hol_rad_pla_cub` _cub
      INNER JOIN 
        `api`.`hol_rad` _rad ON _rad.ide = _cub.ide
    ;
  --
  -- x20 : sello solar
    DROP VIEW IF EXISTS `api`.`_hol_sel`; CREATE VIEW `api`.`_hol_sel` AS
      SELECT 
        _sel.*,
        _dir.nom     AS `cic_dir`,
        _pla.cel     AS `sol_cel`,
        _pla.cir     AS `sol_cir`,
        _fam.hum_cen AS `hum_cen`,
        _fam.hum_ded AS `hum_ded`,
        _ele.hum_ext AS `hum_ext`,        
        _ele.res_flu AS `hum_res`
      FROM 
        `api`.`hol_sel` _sel
      INNER JOIN 
        `api`.`hol_sel_cic_dir` _dir ON _sel.arm_raz = _dir.ide        
      INNER JOIN 
        `api`.`hol_sel_sol_pla` _pla ON _sel.sol_pla = _pla.ide
      INNER JOIN 
        `api`.`hol_sel_cro_ele` _ele ON _sel.cro_ele = _ele.ide
      INNER JOIN 
        `api`.`hol_sel_cro_fam` _fam ON _sel.cro_fam = _fam.ide
      ORDER BY
        _sel.ide ASC
    ;
    -- código 0-19...
    DROP VIEW IF EXISTS `api`.`_hol_sel_cod`; CREATE VIEW `api`.`_hol_sel_cod` AS
      SELECT
        _sel.*
      FROM 
        `api`.`_hol_sel` _sel
      ORDER BY 
        _sel.ord ASC
    ;
    -- parejas analogas
    DROP VIEW IF EXISTS `api`.`_hol_sel_par_ana`;CREATE VIEW `api`.`_hol_sel_par_ana` AS
      SELECT
        _ana.ini,
        _ini.nom AS `ini_nom`,
        _ini.car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ana.fin,
        _fin.nom AS `fin_nom`,
        _fin.car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `api`.`hol_sel_par_ana` _ana
      INNER JOIN
        `api`.`hol_sel` _ini ON _ana.ini = _ini.ide
      INNER JOIN
        `api`.`hol_sel` _fin ON _ana.fin = _fin.ide        
    ;
    -- parejas antípodas
    DROP VIEW IF EXISTS `api`.`_hol_sel_par_ant`;CREATE VIEW `api`.`_hol_sel_par_ant` AS
      SELECT
        _ant.ini,
        _ini.nom AS `ini_nom`,
        _ini.car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ant.fin,
        _fin.nom AS `fin_nom`,
        _fin.car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `api`.`hol_sel_par_ant` _ant
      INNER JOIN
        `api`.`hol_sel` _ini ON _ant.ini = _ini.ide
      INNER JOIN
        `api`.`hol_sel` _fin ON _ant.fin = _fin.ide        
    ;
    -- parejas oculas
    DROP VIEW IF EXISTS `api`.`_hol_sel_par_ocu`;CREATE VIEW `api`.`_hol_sel_par_ocu` AS
      SELECT
        _ocu.ini,
        _ini.nom AS `ini_nom`,
        _ini.car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ocu.fin,
        _fin.nom AS `fin_nom`,
        _fin.car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `api`.`hol_sel_par_ocu` _ocu
      INNER JOIN
        `api`.`hol_sel` _ini ON _ocu.ini = _ini.ide
      INNER JOIN
        `api`.`hol_sel` _fin ON _ocu.fin = _fin.ide        
    ;
  --
  -- x52 : Castillo fractal-galactico
    -- onda de la aventura
    DROP VIEW IF EXISTS `api`.`_hol_cas_ond`; CREATE VIEW `api`.`_hol_cas_ond` AS
      SELECT 
        _ond.*
      FROM 
        `api`.`hol_cas_ond` _cas
      INNER JOIN 
        `api`.`hol_ton_ond` _ond ON _cas.ide = _ond.ide
    ;  
  --
  -- x260 : Kin  
    -- trayectorias armónicas
    DROP VIEW IF EXISTS `api`.`_hol_kin_arm_tra`; CREATE VIEW `api`.`_hol_kin_arm_tra` AS
      SELECT 
        _tra.*,
        _ton.des as `ton_des`
      FROM 
        `api`.`hol_kin_arm_tra` _tra
      INNER JOIN 
        `api`.`hol_ton` _ton ON _tra.ide = _ton.ide
    ;    
    -- estacion galáctica
    DROP VIEW IF EXISTS `api`.`_hol_kin_cro_est`; CREATE VIEW `api`.`_hol_kin_cro_est` AS
      SELECT 
        _est.*,
        _cic.nom as `cer`,
        _cic.des as `cer_des`,      
        _cic.sel,
        _sel.may,
        _cas.cas,
        _cas.col,
        _cas.dir
      FROM 
        `api`.`hol_kin_cro_est` _est      
      INNER JOIN 
        `api`.`hol_sel_cic_men` _cic ON _est.ide = _cic.ide
      INNER JOIN 
        `api`.`hol_cas_arm` _cas ON _est.ide = _cas.ide
      INNER JOIN 
        `api`.`hol_sel` _sel ON _cic.sel = _sel.ide    
    ;
    -- ondas de las estaciones galácticas
    DROP VIEW IF EXISTS `api`.`_hol_kin_cro_ond`; CREATE VIEW `api`.`_hol_kin_cro_ond` AS
      SELECT 
        _cro.*, 
        _ond.nom,
        _ond.des
      FROM 
        `api`.`hol_kin_cro_ond` _cro
      INNER JOIN 
        `api`.`hol_ton_ond` _ond ON _cro.ide = _ond.ide
    ;
    -- elemento galáctico
    DROP VIEW IF EXISTS `api`.`_hol_kin_cro_ele`; CREATE VIEW `api`.`_hol_kin_cro_ele` AS
      SELECT 
        _ele.*,
        _cas.des AS `cas_des`,
        _cas.arm AS `arm`,
        _cas.ond AS `ond`,
        _cas.ton AS `ton`,
        _ton.des AS `ton_des`
      FROM 
        `api`.`hol_kin_cro_ele` _ele
      INNER JOIN
        `api`.`hol_cas` _cas ON _ele.ide = _cas.ide
      INNER JOIN 
        `api`.`hol_ton` _ton ON _cas.ton = _ton.ide
    ;

  --
  -- x365 : Banco-psi
    -- psi-cronos
    DROP VIEW IF EXISTS `api`.`_hol_psi`; CREATE VIEW `api`.`_hol_psi` AS 
      SELECT 
        _psi.*,
        _kin.pag AS `pag`,
        _kin.arm_tra_dia AS `kin_sel`,
        _kin.nav_ond_dia AS `kin_ton`
      FROM 
        `api`.`hol_psi` _psi
      INNER JOIN 
        `api`.`hol_kin` _kin ON _kin.ide = _psi.tzo
    ;
    -- 13 lunas del giro solar
    DROP VIEW IF EXISTS `api`.`_hol_psi_lun`; CREATE VIEW `api`.`_hol_psi_lun` AS 
      SELECT 
        _lun.*,         
        _ton.ide AS `ton`,
        _ton.nom AS `ton_nom`,
        _ton.des AS `ton_des`,
        _ton.gal AS `ton_gal`,
        _ton.car AS `ton_car`,
        _ton.pod AS `ton_pod`,
        _ton.acc AS `ton_acc`,
        _ton.pre AS `ton_pre`,
        _ton.sim AS `ton_sim`,
        _ton.mat AS `ton_mat`,
        _ton.dim AS `ton_dim`,
        _ton.ond AS `ond`,
        _ton.ond_enc AS `ond_enc`,
        _ton.ond_nom AS `ond_nom`,
        _ton.ond_pos AS `ond_pos`,
        _ton.ond_pod AS `ond_pod`,
        _ton.ond_man AS `ond_man`
      FROM 
        `api`.`hol_psi_lun` _lun      
      INNER JOIN 
        `api`.`hol_ton` _ton ON _lun.ide = _ton.ide
    ;
    -- 52 heptadas del giro solar
    DROP VIEW IF EXISTS `api`.`_hol_psi_hep`; CREATE VIEW `api`.`_hol_psi_hep` AS
      SELECT 
        _hep.*, 
        _cas.ond AS `ond`, 
        _cas.arm AS `arm`,
        _arm.col AS `arm_col`,
        _arm.dir AS `arm_dir`,
        _cas.ton AS `ton`,                 
        _ton.nom AS `ton_nom`,
        _ton.des AS `ton_des`,
        _ton.pre AS `ton_pre`,
        _cas.des AS `cas_des`
      FROM 
        `api`.`hol_psi_hep` _hep
      INNER JOIN 
        `api`.`hol_cas` _cas ON _hep.ide = _cas.ide
      INNER JOIN 
        `api`.`hol_cas_arm` _arm ON _cas.arm = _arm.ide        
      INNER JOIN 
        `api`.`hol_ton` _ton ON _cas.ton = _ton.ide
    ;
  --
  -- x250x365 : anillos del encantamiento
    DROP VIEW IF EXISTS `api`.`_hol_ani`; CREATE VIEW `api`.`_hol_ani` AS 
      SELECT 
        _ani.ide, 
        _kin.nom, 
        _cas.ide AS `cas`, 
        _cas.ton, 
        _ani.fam_2,
        _ani.fam_3,
        _ani.fam_4
      FROM 
        `api`.`hol_ani` _ani
      INNER JOIN 
        `api`.`hol_kin` _kin ON _kin.ide = _ani.fam_4 
      INNER JOIN 
        `api`.`hol_cas` _cas ON _ani.ide+1 = _cas.ide
    ;
  --
--