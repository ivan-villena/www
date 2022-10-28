-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api
--
-- estilo 
  DELETE FROM `app_ico_gru`
  ;
  INSERT INTO `app_ico_gru` VALUES
    (NULL,'material-icons',					 'https://fonts.googleapis.com/css?family=Material+Icons'),
    (NULL,'material-icons-outlined', 'https://fonts.googleapis.com/css?family=Material+Icons+Outlined'),
    (NULL,'material-icons-two-tone', 'https://fonts.googleapis.com/css?family=Material+Icons+Two+Tone'),
    (NULL,'material-icons-round',		 'https://fonts.googleapis.com/css?family=Material+Icons+Round'),
    (NULL,'material-icons-sharp',		 'https://fonts.googleapis.com/css?family=Material+Icons+Sharp')
  ;
--
-- icono 
  DELETE FROM `app_ico`
  ;
  -- 
  -- por aplicacion
    DELETE FROM `app_ico` WHERE `ide` LIKE 'app%';
    INSERT INTO `app_ico` VALUES  
      ('app',         'home'),      
      ('app_ses',	    'manage_accounts'),
      ('app_ini',	    'login'),
      ('app_fin',	    'logout'),
      ('app_ope',     'settings'),
      ('app_cab',			'menu'),
      ('app_nav',	 'menu_open'),
      ('app_art',     'article'),
      ('app_val',     'document_scanner')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'usu%';
    INSERT INTO `app_ico` VALUES
      ('usu',	    'person'),      
      ('usu_pas',	'password'),
      ('usu_ubi',	'share_location'),
      ('usu_mai',	'email'),
      ('usu_tel',	'phone'),
      ('usu_web',	'web_asset'),
      ('usu_gru',	'group'),
      ('usu_age',	'menu_book')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'dat%';
    INSERT INTO `app_ico` VALUES

      ('dat',			'database'),
      -- atributos
      ('dat_pos',	''),
      ('dat_ide',	''),
      ('dat_nom',	''),
      ('dat_des',	'help_center'),
      ('dat_tip',	'input'),
      ('dat_var',	'variables'),
      ('dat_err',	'gpp_bad'),
      ('dat_adv',	'gpp_maybe'),
      -- operaciones
      ('dat_ope', 'play_arrow'),      
      ('dat_ini',	'done'),
      ('dat_fin',	'close'),
      ('dat_tod',	'checklist'),
      ('dat_nad',	'flaky'),
      ('dat_ver',	'search'),
      ('dat_act',	'sync'),
      ('dat_agr',	'add'),
      ('dat_mod',	'edit'),
      ('dat_eli',	'delete')
    ;    
    DELETE FROM `app_ico` WHERE `ide` LIKE 'val%';
    INSERT INTO `app_ico` VALUES

      ('val',	    'check_box_outline_blank'),
      -- carteles :
      ('val_tex',	    'comment'),
      ('val_tex-err',	'error'),
      ('val_tex-adv',	'warning'),
      ('val_tex-opc',	'help'),
      ('val_tex-val',	'check_circle'),
      -- toggs : item - expandir - contraer
      ('val_tog',	    'arrow_drop_down'),
      ('val_tog-tod',	'expand_circle_down'),
      ('val_tog-nad',	'expand_circle_down'),
      -- filtros : mostrar - ocultar
      ('val_ver',	    ''),
      ('val_ver-tod',	'visibility'),
      ('val_ver-nad',	'visibility_off'),
      -- seleccion : todo - nada
      ('val_sel',	''),
      ('val_sel-tod',	'select_all'),
      ('val_sel-nad',	'deselect'),
      -- estado : activo - inactivo
      ('val_est',	''),
      ('val_est-tod',	'toggle_on'),
      ('val_est-nad',	'toggle_off'),
      -- movimiento
      ('val_mov',     'open_with'),  
      ('val_mov-hor', 'swap_horiz'),
      ('val_mov-ver', 'import_export'),
      ('val_mov-arr', 'north'),
      ('val_mov-aba', 'south'),
      ('val_mov-izq', 'west'),
      ('val_mov-der', 'east'),
      ('val_mov-dyr', 'north_east'),
      ('val_mov-dyb', 'north_west'),
      ('val_mov-iyr', 'south_east'),
      ('val_mov-iyb', 'south_west')
    ;  
  --
  -- por tipos
    DELETE FROM `app_ico` WHERE `ide` LIKE 'tab%';
    INSERT INTO `app_ico` VALUES
      ('tab',  'dataset'),
      ('tab_sec',  ''),
      ('tab_pos',  ''),
      ('tab_opc',  ''),
      ('tab_val',  '')
    ;    
    DELETE FROM `app_ico` WHERE `ide` LIKE 'est%';
    INSERT INTO `app_ico` VALUES
      ('est',	     'table_view'),
      ('est_dat',	 ''),
      ('est_atr',	 ''),
      ('est_ide',	 ''),
      ('est_ind',	 ''),
      ('est_val',	 '')
    ;    
    DELETE FROM `app_ico` WHERE `ide` LIKE 'lis%';
    INSERT INTO `app_ico` VALUES

      ('lis',			'list_alt'),
      -- tipos
      ('lis_ite',	 'view_list'),
      ('lis_val',	 'list'),
      ('lis_opc',  'checklist_rtl'),
      -- navegacion      
      ('lis_ini',	'first_page'),
      ('lis_fin',	'last_page'),
      ('lis_pre',	'navigate_before'),
      ('lis_pos',	'navigate_next'),      
      -- operaciones
      ('lis_tod',	'playlist_add_check'),
      ('lis_nad',	'playlist_remove'),
      ('lis_cue',	'summarize'),  
      ('lis_ver',	'filter_alt'),
      ('lis_jun',	'join_inner'),
      ('lis_gru',	'low_priority'),
      ('lis_ord',	'sort'),
      ('lis_lim',	'segment'),
      ('lis_mov',	'unfold_more')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'opc%';
    INSERT INTO `app_ico` VALUES

      ('opc',			'fact_check'),
      -- tipos          
      ('opc_vac', 'check_box_outline_blank'),
      ('opc_bin',	'rule'),      
      ('opc_uni',	'radio_button_checked'),
      ('opc_mul',	'check_box'),
      -- listado
      ('opc_lis',  'checklist_rtl')
    ;    
    DELETE FROM `app_ico` WHERE `ide` LIKE 'num%';
    INSERT INTO `app_ico` VALUES

      ('num',			'calculate'),
      -- valores
      ('num_cod',	'tag'),
      ('num_val',	'pin'),      
      ('num_sep',	''),
      ('num_sup',	'superscript'),
      ('num_inf',	'subscript'),      
      -- tipos
      ('num_bit',	'1k'),
      ('num_int',	'numbers'),  
      ('num_dec',	'percent'),
      ('num_ran',	'linear_scale'),
      -- operadores      
      ('num_sum',	'add'),
      ('num_res',	'remove'),
      ('num_mul',	'close'),
      ('num_div',	'percent'),
      -- calculos
      ('num_fun',	'function'),
      ('num_adi',	'functions')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'tex%';
    INSERT INTO `app_ico` VALUES    

      ('tex',			'edit_note'),
      -- tipos
      ('tex_val',	'rtt'),
      ('tex_let',	'title'),
      ('tex_pal',	'text_fields'),
      ('tex_ora',	'short_text'),
      ('tex_par',	'chat'),      
      ('tex_lib',	'auto_stories'),
      ('tex_inf',	'description'),
      -- operadores
      ('tex_ord',	'sort_by_alpha'),
      ('tex_mov',	'wrap_text'),
      ('tex_rot',	'text_rotation_down')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'fec%';
    INSERT INTO `app_ico` VALUES

      ('fec',			'calendar_today'),      

      -- tipos
      ('fec_tie',	'access_time'),
      ('fec_dia',	'event'),
      ('fec_sem',	'date_range'),
      ('fec_mes',	'calendar_month'),
      ('fec_año',	'event_note'), 

      -- operaciones
      ('fec_val', 'edit_calendar')

    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'fig%';
    INSERT INTO `app_ico` VALUES
      ('fig',	'auto_graph'),      
      -- valores
      ('fig_col',	'palette'),
      ('fig_ima',	'image'),
      -- tipos
      ('fig_geo',	'shape_line'),
      ('fig_pun',	'scatter_plot'),
      ('fig_lin',	'trending_down'),
      ('fig_pol',	'polyline')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'arc%';
    INSERT INTO `app_ico` VALUES  

      ('arc',			'attach_file'),
      -- tipos
      ('arc_val',	'note'),
      ('arc_fic',	'folder'),
      ('arc_url',	'language'),
      ('arc_tex',	'text_snippet'),
      ('arc_ima',	'collections'),
      ('arc_mus',	'audio_file'),
      ('arc_vid',	'video_file'),
      ('arc_eje',	'play_circle')

    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'eje%';
    INSERT INTO `app_ico` VALUES

      ('eje',	    'terminal')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'obj%';
    INSERT INTO `app_ico` VALUES

      ('obj',	'widgets'),
      -- tipos      
      ('obj_pos',	'format_list_numbered'),
      ('obj_nom',	'data_array'),
      ('obj_atr',	'data_object')
    ;
    DELETE FROM `app_ico` WHERE `ide` LIKE 'ele%';
    INSERT INTO `app_ico` VALUES      
      ('ele', 'code'),
      -- valores
      ('ele_htm', 'html'),
      ('ele_css', 'css'),
      ('ele_eje', 'javascript')
    ;
  --
--