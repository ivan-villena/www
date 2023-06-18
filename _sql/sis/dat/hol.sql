-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

--  
  --
  -- x7 : plasma radial

    DROP VIEW IF EXISTS `_hol-rad`; CREATE VIEW `_hol-rad` AS
      SELECT 
        _rad.*,
        _cha.nom as `cha_nom`
      FROM 
        `hol-rad` _rad
      INNER JOIN 
        `hol-hum_cha` _cha ON _rad.hum_cha = _cha.ide
      ORDER BY
        _rad.ide ASC
    ;
    -- cubo
    DROP VIEW IF EXISTS `_hol-rad_pla_cub`; CREATE VIEW `_hol-rad_pla_cub` AS
      SELECT
        _rad.pla_cub      AS `ide`,
        _rad.pla_cub_pos  AS `nom`,
        _rad.nom          AS `pla`
      FROM 
        `hol-rad` _rad
    ;
  --
  -- x20 : sello solar

    DROP VIEW IF EXISTS `_hol-sel`; CREATE VIEW `_hol-sel` AS
      SELECT 
        _sel.*,
        _dir.nom     AS `cic_dir`,
        _sel.flu_res AS `sol_res`,
        _pla.orb     AS `sol_orb`,
        _pla.cel     AS `sol_cel`,
        _pla.cir     AS `sol_cir`,
        _sel.flu_res AS `pla_res`,
        _fam.hum_cen AS `hum_cen`,
        _fam.hum_ded AS `hum_ded`,
        _ele.hum_ext AS `hum_ext`,
        _ele.flu_res AS `hum_res`
      FROM `hol-sel` _sel
        INNER JOIN `hol-sel_cic_dir` _dir ON _sel.arm_raz = _dir.ide        
        INNER JOIN `hol-sol_pla` _pla ON _sel.sol_pla = _pla.ide
        INNER JOIN `hol-sel_cro_ele` _ele ON _sel.cro_ele = _ele.ide
        INNER JOIN `hol-sel_cro_fam` _fam ON _sel.cro_fam = _fam.ide
      ORDER BY
        _sel.ide ASC
    ;
    -- código 0-19...
    DROP VIEW IF EXISTS `_hol-sel_cod`; CREATE VIEW `_hol-sel_cod` AS
      SELECT
        _sel.ord as `ide`,
        _sel.ide as `sel`,
        _sel.cod,
        _sel.nom,
        _sel.nom_may,
        _sel.des,
        _sel.des_col,
        _sel.des_cod,
        _sel.des_acc,
        _sel.des_pod,
        _sel.des_pod_tel,
        _sel.cro,
        _sel.cro_fam,
        _sel.cro_ele,
        _sel.cro_ele_fun,
        _sel.cro_ele_des,
        _sel.flu_res,
        _sel.flu_res_des,
        _sel.sol_res,
        _sel.sol_orb,
        _sel.sol_cel,
        _sel.sol_cir,
        _sel.sol_pla,
        _sel.sol_pla_des,
        _sel.pla_res,
        _sel.pla_cen,
        _sel.pla_hem,
        _sel.pla_hem_cod,
        _sel.pla_mer,
        _sel.pla_mer_cod,
        _sel.hum_des,
        _sel.hum_res,
        _sel.hum_cen,
        _sel.hum_ded,
        _sel.hum_ext,
        _sel.hum_mer
      FROM 
        `_hol-sel` _sel
      ORDER BY 
        _sel.ord ASC
    ;
    -- ciclos
    DROP VIEW IF EXISTS `_hol-sel_cic_dir`; CREATE VIEW `_hol-sel_cic_dir` AS
      SELECT
        _dir.*,
        _arm.des_col
      FROM 
        `hol-sel_cic_dir` _dir
      INNER JOIN
        `hol-arm` _arm ON _arm.ide = _dir.ide
      ORDER BY 
        _dir.ide ASC
    ;
    -- colocacion armónica
    DROP VIEW IF EXISTS `_hol-sel_arm_raz`; CREATE VIEW `_hol-sel_arm_raz` AS
      SELECT
        _raz.*,
        _arm.des_col,
        _arm.des_dir,
        _arm.des_pod,
        _arm.des_pol,
        _arm.des_dia
      FROM 
        `hol-sel_arm_raz` _raz
      INNER JOIN
        `hol-arm` _arm ON _arm.ide = _raz.ide
      ORDER BY 
        _raz.ide ASC
    ;
    -- colocacion cromática
    DROP VIEW IF EXISTS `_hol-sel_cro_fam`; CREATE VIEW `_hol-sel_cro_fam` AS
      SELECT
        _fam.*,
        _cro.des_cod,
        _cro.des_fun,
        _cro.des_pod
      FROM 
        `hol-sel_cro_fam` _fam
      INNER JOIN
        `hol-cro` _cro ON _cro.ide = _fam.cro
      ORDER BY 
        _fam.ide ASC
    ;
    -- parejas analogas
    DROP VIEW IF EXISTS `_hol-sel_par_ana`;CREATE VIEW `_hol-sel_par_ana` AS
      SELECT
        _ana.ini,
        _ini.nom AS `ini_nom`,
        _ini.des_car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ana.fin,
        _fin.nom AS `fin_nom`,
        _fin.des_car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `hol-sel_par_ana` _ana
      INNER JOIN
        `hol-sel` _ini ON _ana.ini = _ini.ide INNER JOIN
        `hol-sel` _fin ON _ana.fin = _fin.ide
      ORDER BY
        _ana.ini
    ;-- parejas antípodas
    DROP VIEW IF EXISTS `_hol-sel_par_ant`;CREATE VIEW `_hol-sel_par_ant` AS
      SELECT
        _ant.ini,
        _ini.nom AS `ini_nom`,
        _ini.des_car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ant.fin,
        _fin.nom AS `fin_nom`,
        _fin.des_car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `hol-sel_par_ant` _ant
      INNER JOIN
        `hol-sel` _ini ON _ant.ini = _ini.ide
      INNER JOIN
        `hol-sel` _fin ON _ant.fin = _fin.ide
      ORDER BY
        _ant.ini
    ;-- parejas oculas
    DROP VIEW IF EXISTS `_hol-sel_par_ocu`;CREATE VIEW `_hol-sel_par_ocu` AS
      SELECT
        _ocu.ini,
        _ini.nom AS `ini_nom`,
        _ini.des_car AS `ini_car`,
        _ini.des AS `ini_des`,
        _ocu.fin,
        _fin.nom AS `fin_nom`,
        _fin.des_car AS `fin_car`,
        _fin.des AS `fin_des`
      FROM 
        `hol-sel_par_ocu` _ocu
      INNER JOIN
        `hol-sel` _ini ON _ocu.ini = _ini.ide
      INNER JOIN
        `hol-sel` _fin ON _ocu.fin = _fin.ide
      ORDER BY
        _ocu.ini
    ;
  --
  -- x28: giro lunar
    -- heptadas
    DROP VIEW IF EXISTS `_hol-lun_arm`; CREATE VIEW `_hol-lun_arm` AS
      SELECT 
        _lun.*,
        _arm.des_pod,
        _arm.des_pol,
        _arm.des_dia
      FROM 
        `hol-lun_arm` _lun
      INNER JOIN 
        `hol-arm` _arm ON _arm.ide = _lun.ide
      ORDER BY
        _lun.ide
    ;
  --
  -- x52 : Castillo fractal-galactico
    -- posiciones
    DROP VIEW IF EXISTS `_hol-cas`; CREATE VIEW `_hol-cas` AS
      SELECT 
        _cas.*,
        _ton.dim,
        _ton.mat,
        _ton.sim
      FROM 
        `hol-cas` _cas
      INNER JOIN 
        `hol-ton` _ton ON _ton.ide = _cas.ton
      ORDER BY
        _cas.ide
    ;
    -- Cuadrantes
    DROP VIEW IF EXISTS `_hol-cas_arm`; CREATE VIEW `_hol-cas_arm` AS
      SELECT 
        _cas.*,
        _arm.des_col,
        _arm.des_dir,
        _arm.des_pod,
        _arm.des_pol,
        _arm.des_dia
      FROM 
        `hol-cas_arm` _cas
      INNER JOIN 
        `hol-arm` _arm ON _arm.ide = _cas.ide
      ORDER BY
        _cas.ide
    ;
    -- onda de la aventura
    DROP VIEW IF EXISTS `_hol-cas_ond`; CREATE VIEW `_hol-cas_ond` AS
      SELECT 
        _ond.*
      FROM 
        `hol-cas_ond` _cas
      INNER JOIN 
        `hol-ton_ond` _ond ON _cas.ide = _ond.ide
      ORDER BY
        _ond.ide
    ;
    -- pulsares
    DROP VIEW IF EXISTS `_hol-cas_dim`; CREATE VIEW `_hol-cas_dim` AS
      SELECT        
        _ond.*,
        _cas.cas
      FROM 
        `hol-cas_dim` _cas
      INNER JOIN 
        `hol-ton_dim` _ond ON _cas.ide = _ond.ide
      ORDER BY
        _ond.ide
    ;
    DROP VIEW IF EXISTS `_hol-cas_mat`; CREATE VIEW `_hol-cas_mat` AS
      SELECT        
        _ond.*,
        _cas.cas
      FROM 
        `hol-cas_mat` _cas
      INNER JOIN 
        `hol-ton_mat` _ond ON _cas.ide = _ond.ide
      ORDER BY
        _ond.ide
    ;
    DROP VIEW IF EXISTS `_hol-cas_sim`; CREATE VIEW `_hol-cas_sim` AS
      SELECT        
        _ond.*,
        _cas.cas
      FROM 
        `hol-cas_sim` _cas
      INNER JOIN 
        `hol-ton_sim` _ond ON _cas.ide = _ond.ide
      ORDER BY
        _ond.ide
    ;       
  --
  -- x260 : Kin
    -- oraculos
    DROP VIEW IF EXISTS `_hol-kin_par`; CREATE VIEW `_hol-kin_par` AS
      SELECT 
        _kin.ide,
        _kin.nom,
        _kin.des,
        _kin.par_ana,
        _kin.par_gui,
        _kin.par_ant,
        _kin.par_ocu
      FROM 
        `hol-kin` _kin
      ORDER BY 
        _kin.ide ASC
    ;
    -- Trayectoria Armónica
    DROP VIEW IF EXISTS `_hol-kin_arm_tra`; CREATE VIEW `_hol-kin_arm_tra` AS
      SELECT 
        _tra.*,
        _ton.nom AS `ton`,
        _ton.des AS `ton_des`
      FROM 
        `hol-kin_arm_tra` _tra
      INNER JOIN 
        `hol-ton` _ton ON _tra.ide = _ton.ide
      ORDER BY 
        _tra.ide ASC
    ;    
    -- célula de tiempo
    DROP VIEW IF EXISTS `_hol-kin_arm_cel`; CREATE VIEW `_hol-kin_arm_cel` AS
      SELECT 
        _cel.*,
        _arm.nom AS `cel_nom`,
        _arm.des_fun AS `cel_fun`,
        _arm.des_pod AS `cel_pod`
      FROM 
        `hol-kin_arm_cel` _cel
      INNER JOIN 
        `hol-sel_arm_cel` _arm ON _cel.cel = _arm.ide
      ORDER BY
        _cel.ide ASC
    ;
    -- estacion galáctica
    DROP VIEW IF EXISTS `_hol-kin_cro_est`; CREATE VIEW `_hol-kin_cro_est` AS
      SELECT 
        _est.*,
        _cas.cas,
        _cas.des_col,
        _cas.des_dir
      FROM 
        `hol-kin_cro_est` _est
      INNER JOIN 
        `_hol-cas_arm` _cas ON _est.ide = _cas.ide
      ORDER BY
        _est.ide        
    ;-- días estacionales
    DROP VIEW IF EXISTS `_hol-kin_cro_est_dia`; CREATE VIEW `_hol-kin_cro_est_dia` AS
      SELECT 
        _dia.*,
        _ond.fac,
        _ond.enc,
        _ton.dim,
        _ton.mat,
        _ton.sim,
        _ton.ond,
        _ton.ond_pos,
        _ton.ond_nom,
        _ton.ond_pod,
        _ton.ond_man
      FROM 
        `hol-kin_cro_est_dia` _dia
      INNER JOIN 
        `hol-ton` _ton ON _ton.ide = _dia.ton
      INNER JOIN 
        `hol-kin_cro_ond` _ond ON _ond.ide = _ton.ond
      ORDER BY
        _dia.ide
    ;
    -- ondas de las estaciones galácticas
    DROP VIEW IF EXISTS `_hol-kin_cro_ond`; CREATE VIEW `_hol-kin_cro_ond` AS
      SELECT 
        _cro.*, 
        _ond.nom,
        _ond.des
      FROM 
        `hol-kin_cro_ond` _cro
      INNER JOIN 
        `hol-ton_ond` _ond ON _cro.ide = _ond.ide
      ORDER BY
        _cro.ide
    ;
    -- elemento galáctico
    DROP VIEW IF EXISTS `_hol-kin_cro_ele`; CREATE VIEW `_hol-kin_cro_ele` AS
      SELECT 
        _ele.*,
        _cas.arm,
        _cas.pos_arm,
        _cas.ond,
        _ton.dim,
        _ton.mat,
        _ton.sim,        
        _cas.ton,        
        _ton.des AS `ton_des`,
        _ton.des_car AS `ton_des_car`,
        _ton.des_pod AS `ton_des_pod`,
        _ton.des_acc AS `ton_des_acc`,
        _ton.ond_nom AS `ond_nom`,
        _ton.ond_pos AS `ond_pos`,
        _ton.ond_pod AS `ond_pod`,
        _ton.ond_man AS `ond_man`,
        _cas.des AS `cas_des`,
        _cas.lec AS `cas_lec`
      FROM 
        `hol-kin_cro_ele` _ele
      INNER JOIN
        `hol-cas` _cas ON _ele.ide = _cas.ide
      INNER JOIN 
        `hol-ton` _ton ON _cas.ton = _ton.ide
      ORDER BY
        _ele.ide ASC
    ;
  --
  -- x365 : Banco-psi
    -- psi-cronos
    DROP VIEW IF EXISTS `_hol-psi`; CREATE VIEW `_hol-psi` AS 
      SELECT 
        _psi.*,
        _kin.pag AS `pag`,
        _kin.arm_tra_dia AS `kin_sel`,
        _kin.nav_ond_dia AS `kin_ton`
      FROM 
        `hol-psi` _psi
      INNER JOIN 
        `hol-kin` _kin ON _kin.ide = _psi.kin
      ORDER BY
        _psi.ide        
    ;
    -- x250x365 : anillos del encantamiento
    DROP VIEW IF EXISTS `_hol-psi_ani`; CREATE VIEW `_hol-psi_ani` AS 
      SELECT 
        _ani.ide, 
        _kin.nom, 
        _cas.ide AS `cas`, 
        _cas.ton, 
        _ani.fam_2,
        _ani.fam_3,
        _ani.fam_4
      FROM 
        `hol-psi_ani` _ani
      INNER JOIN 
        `hol-kin` _kin ON _kin.ide = _ani.fam_4 
      INNER JOIN 
        `hol-cas` _cas ON _ani.ide+1 = _cas.ide
      ORDER BY
        _ani.ide
    ;    
    -- 4 estaciones
    DROP VIEW IF EXISTS `_hol-psi_est`; CREATE VIEW `_hol-psi_est` AS
      SELECT 
        _est.*,
        _cas.cas,
        _cas.des_col,
        _cas.des_dir
      FROM 
        `hol-psi_est` _est
      INNER JOIN 
        `_hol-cas_arm` _cas ON _est.ide = _cas.ide
      ORDER BY
        _est.ide        
    ;-- días estacionales
    DROP VIEW IF EXISTS `_hol-psi_est_dia`; CREATE VIEW `_hol-psi_est_dia` AS
      SELECT 
        _dia.*,
        _ton.dim,
        _ton.mat,
        _ton.sim,
        _ton.ond,
        _ton.ond_nom,
        _ton.ond_pos,
        _ton.ond_pod,
        _ton.ond_man
      FROM 
        `hol-psi_est_dia` _dia
      INNER JOIN 
        `hol-ton` _ton ON _ton.ide = _dia.ton
      ORDER BY
        _dia.ide
    ;
    -- 13 lunas del giro solar
    DROP VIEW IF EXISTS `_hol-psi_lun`; CREATE VIEW `_hol-psi_lun` AS 
      SELECT 
        _lun.*,         
        _ton.ide AS `ton`,
        _ton.nom AS `ton_nom`,
        _ton.des AS `ton_des`,
        _ton.gal AS `ton_gal`,
        _ton.des_car AS `ton_car`,
        _ton.des_pod AS `ton_pod`,
        _ton.des_acc AS `ton_acc`,
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
        `hol-psi_lun` _lun      
      INNER JOIN 
        `hol-ton` _ton ON _lun.ide = _ton.ide
      ORDER BY
        _lun.ide        
    ;
    -- 52 heptadas del giro solar
    DROP VIEW IF EXISTS `_hol-psi_hep`; CREATE VIEW `_hol-psi_hep` AS
      SELECT 
        _hep.*,
        _cas.arm,
        _cas.pos_arm,
        _cas.ond,
        _ton.dim,
        _ton.mat,
        _ton.sim,
        _cas.ton,
        _ton.des AS `ton_des`,
        _ton.des_car AS `ton_des_car`,
        _ton.des_pod AS `ton_des_pod`,
        _ton.des_acc AS `ton_des_acc`,
        _ton.ond_nom AS `ond_nom`,
        _ton.ond_pos AS `ond_pos`,
        _ton.ond_pod AS `ond_pod`,
        _ton.ond_man AS `ond_man`,
        _cas.des AS `cas_des`,
        _cas.lec AS `cas_lec`
      FROM 
        `hol-psi_hep` _hep
      INNER JOIN 
        `hol-cas` _cas ON _hep.ide = _cas.ide
      INNER JOIN 
        `hol-ton` _ton ON _cas.ton = _ton.ide
      ORDER BY
        _hep.ide        
    ;
  --
  -- holon solar:
  -- 
  -- holon planetario:
    DROP VIEW IF EXISTS `_hol-pla_cen`; CREATE VIEW `_hol-pla_cen` AS
      SELECT
        _cen.*,
        _fam.des_cod,
        _fam.des_fun
      FROM 
        `hol-pla_cen` _cen
      INNER JOIN
        `_hol-sel_cro_fam` _fam ON _fam.ide = _cen.fam
      ORDER BY 
        _cen.ide ASC
    ;
  -- 
  -- holon humano:
    DROP VIEW IF EXISTS `_hol-hum_cen`; CREATE VIEW `_hol-hum_cen` AS
      SELECT
        _cen.*,
        _fam.des_cod,
        _fam.des_pod
      FROM 
        `hol-hum_cen` _cen
      INNER JOIN
        `_hol-sel_cro_fam` _fam ON _fam.ide = _cen.fam
      ORDER BY 
        _cen.ide ASC
    ;
    DROP VIEW IF EXISTS `_hol-hum_ded`; CREATE VIEW `_hol-hum_ded` AS
      SELECT
        _ded.*,
        _cro.des_acc
      FROM 
        `hol-hum_ded` _ded
      INNER JOIN
        `_hol-sel_cro_fam` _cro ON _cro.ide = _ded.ide
      ORDER BY 
        _ded.ide ASC
    ;
--