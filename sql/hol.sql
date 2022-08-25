-- Active: 1623270923336@@127.0.0.1@3306@_api
-- HOLON
  --
  -- x7 : plasma radial
    DROP VIEW IF EXISTS `_hol`.`_rad`; 
    CREATE VIEW `_hol`.`_rad` AS
      SELECT 
        _rad.*,
        _hep.ide AS `hep`,
        _hep.pos AS `hep_pos`,
        _cha.nom as `cha_nom`
      FROM 
        `_hol`.`rad` _rad
      INNER JOIN 
        `_hol`.`rad_hep` _hep ON _rad.ide = _hep.ide
      INNER JOIN 
        `_hol`.`rad_hum_cha` _cha ON _rad.hum_cha = _cha.ide
    ;
  --
  -- x20 : sello solar
    DROP VIEW IF EXISTS `_hol`.`_sel`; 
    CREATE VIEW `_hol`.`_sel` AS
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
        `_hol`.`sel` _sel
      INNER JOIN 
        `_hol`.`sel_cic_dir` _dir ON _sel.arm_raz = _dir.ide        
      INNER JOIN 
        `_hol`.`sel_sol_pla` _pla ON _sel.sol_pla = _pla.ide
      INNER JOIN 
        `_hol`.`sel_cro_ele` _ele ON _sel.cro_ele = _ele.ide
      INNER JOIN 
        `_hol`.`sel_cro_fam` _fam ON _sel.cro_fam = _fam.ide
      ORDER BY
        _sel.ide ASC
    ;
    -- código 0-19...
    DROP VIEW IF EXISTS `_hol`.`_sel_cod`; 
    CREATE VIEW `_hol`.`_sel_cod` AS
      SELECT
        _sel.*
      FROM 
        `_hol`.`_sel` _sel
      ORDER BY 
        _sel.ord ASC
    ;
    -- parejas analogas
    DROP VIEW IF EXISTS `_hol`.`_sel_par_ana`;
    CREATE VIEW `_hol`.`_sel_par_ana` AS
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
        `_hol`.`sel_par_ana` _ana
      INNER JOIN
        `_hol`.`sel` _ini ON _ana.ini = _ini.ide
      INNER JOIN
        `_hol`.`sel` _fin ON _ana.fin = _fin.ide        
    ;
    -- parejas antípodas
    DROP VIEW IF EXISTS `_hol`.`_sel_par_ant`;
    CREATE VIEW `_hol`.`_sel_par_ant` AS
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
        `_hol`.`sel_par_ant` _ant
      INNER JOIN
        `_hol`.`sel` _ini ON _ant.ini = _ini.ide
      INNER JOIN
        `_hol`.`sel` _fin ON _ant.fin = _fin.ide        
    ;
    -- parejas oculas
    DROP VIEW IF EXISTS `_hol`.`_sel_par_ocu`;
    CREATE VIEW `_hol`.`_sel_par_ocu` AS
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
        `_hol`.`sel_par_ocu` _ocu
      INNER JOIN
        `_hol`.`sel` _ini ON _ocu.ini = _ini.ide
      INNER JOIN
        `_hol`.`sel` _fin ON _ocu.fin = _fin.ide        
    ;
  --
  -- x52 : Castillo fractal-galactico
    -- onda de la aventura
    DROP VIEW IF EXISTS `_hol`.`_cas_ond`; 
    CREATE VIEW `_hol`.`_cas_ond` AS
      SELECT 
        _ond.*
      FROM 
        `_hol`.`cas_ond` _cas
      INNER JOIN 
        `_hol`.`ton_ond` _ond ON _cas.ide = _ond.ide
    ;  
  --
  -- x260 : Kin  
    -- trayectorias armónicas
    DROP VIEW IF EXISTS `_hol`.`_kin_arm_tra`; 
    CREATE VIEW `_hol`.`_kin_arm_tra` AS
      SELECT 
        _tra.*,
        _ton.des as `ton_des`
      FROM 
        `_hol`.`kin_arm_tra` _tra
      INNER JOIN 
        `_hol`.`ton` _ton ON _tra.ide = _ton.ide
    ;    
    -- estacion galáctica
    DROP VIEW IF EXISTS `_hol`.`_kin_cro_est`; 
    CREATE VIEW `_hol`.`_kin_cro_est` AS
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
        `_hol`.`kin_cro_est` _est      
      INNER JOIN 
        `_hol`.`sel_cic_men` _cic ON _est.ide = _cic.ide
      INNER JOIN 
        `_hol`.`cas_arm` _cas ON _est.ide = _cas.ide
      INNER JOIN 
        `_hol`.`sel` _sel ON _cic.sel = _sel.ide    
    ;
    -- ondas de las estaciones galácticas
    DROP VIEW IF EXISTS `_hol`.`_kin_cro_ond`; 
    CREATE VIEW `_hol`.`_kin_cro_ond` AS
      SELECT 
        _cro.*, 
        _ond.nom,
        _ond.des
      FROM 
        `_hol`.`kin_cro_ond` _cro
      INNER JOIN 
        `_hol`.`ton_ond` _ond ON _cro.ide = _ond.ide
    ;
    -- elemento galáctico
    DROP VIEW IF EXISTS `_hol`.`_kin_cro_ele`; 
    CREATE VIEW `_hol`.`_kin_cro_ele` AS
      SELECT 
        _ele.*,
        _cas.des AS `cas_des`,
        _cas.arm AS `arm`,
        _cas.ond AS `ond`,
        _cas.ton AS `ton`,
        _ton.des AS `ton_des`
      FROM 
        `_hol`.`kin_cro_ele` _ele
      INNER JOIN
        `_hol`.`cas` _cas ON _ele.ide = _cas.ide
      INNER JOIN 
        `_hol`.`ton` _ton ON _cas.ton = _ton.ide
    ;

  --
  -- x365 : Banco-psi
    -- psi-cronos
    DROP VIEW IF EXISTS `_hol`.`_psi`; 
    CREATE VIEW `_hol`.`_psi` AS 
      SELECT 
        _psi.*,
        _kin.pag AS 'pag',
        _kin.arm_tra_dia AS 'kin_sel',
        _kin.nav_ond_dia AS 'kin_ton'
      FROM 
        `_hol`.`psi` _psi
      INNER JOIN 
        `_hol`.`kin` _kin ON _kin.ide = _psi.tzo
    ;
    -- 13 lunas del giro solar
    DROP VIEW IF EXISTS `_hol`.`_psi_lun`; 
    CREATE VIEW `_hol`.`_psi_lun` AS 
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
        `_hol`.`psi_lun` _lun      
      INNER JOIN 
        `_hol`.`ton` _ton ON _lun.ide = _ton.ide
    ;
    -- 52 heptadas del giro solar
    DROP VIEW IF EXISTS `_hol`.`_psi_hep`; 
    CREATE VIEW `_hol`.`_psi_hep` AS
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
        `_hol`.`psi_hep` _hep
      INNER JOIN 
        `_hol`.`cas` _cas ON _hep.ide = _cas.ide
      INNER JOIN 
        `_hol`.`cas_arm` _arm ON _cas.arm = _arm.ide        
      INNER JOIN 
        `_hol`.`ton` _ton ON _cas.ton = _ton.ide
    ;
  --
  -- x250x365 : anillos del encantamiento
    DROP VIEW IF EXISTS `_hol`.`_ani`; 
    CREATE VIEW `_hol`.`_ani` AS 
      SELECT 
        _ani.ide, 
        _kin.nom, 
        _cas.ide AS `cas`, 
        _cas.ton, 
        _ani.fam_2,
        _ani.fam_3,
        _ani.fam_4
      FROM 
        `_hol`.`ani` _ani
      INNER JOIN 
        `_hol`.`kin` _kin ON _kin.ide = _ani.fam_4 
      INNER JOIN 
        `_hol`.`cas` _cas ON _ani.ide+1 = _cas.ide
    ;
  --