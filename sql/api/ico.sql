-- Active: 1623270923336@@127.0.0.1@3306@_api

-- estilo 
  DELETE FROM `_api`.`ico_gru`
  ;
  INSERT INTO `_api`.`ico_gru` VALUES
    (NULL,'material-icons',					 'https://fonts.googleapis.com/css?family=Material+Icons'),
    (NULL,'material-icons-outlined', 'https://fonts.googleapis.com/css?family=Material+Icons+Outlined'),
    (NULL,'material-icons-two-tone', 'https://fonts.googleapis.com/css?family=Material+Icons+Two+Tone'),
    (NULL,'material-icons-round',		 'https://fonts.googleapis.com/css?family=Material+Icons+Round'),
    (NULL,'material-icons-sharp',		 'https://fonts.googleapis.com/css?family=Material+Icons+Sharp')
  ;

-- icono 
  DELETE FROM `_api`.`ico`
  ;
  --
  -- por sistema
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'ses%';
    INSERT INTO `_api`.`ico` VALUES   

      ('ses', 'home'),
      -- admin
      ('ses_adm',	'manage_accounts'),
      
      -- logs
      ('ses_ini',	'login'),
      ('ses_fin',	'logout'),

      -- usuario
      ('ses_usu',	'person'),
      ('ses_gru',	'group'),		

      -- tipos
      ('ses_pas',	'password'),
      ('ses_web',	'web_asset'),
      ('ses_mai',	'email'),
      ('ses_tel',	'phone'),
      ('ses_ubi',	'share_location')

    ;
  --
  -- operadores    
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'ope%';
    INSERT INTO `_api`.`ico` VALUES

      ('ope',     'settings'),        

      -- togs
      ('ope_tog',	'arrow_drop_down'),
      
      ('ope_tod',	'playlist_add_check'),
      ('ope_nad',	'playlist_remove'),

      -- filtros      
      ('ope_ver',	'visibility'),
      ('ope_ocu',	'visibility_off'),

      -- valores
      ('ope_val',	'checklist'),
      ('ope_inv',	'flaky'),

      -- seleccion
      ('ope_sel',	'select_all'),
      ('ope_des',	'deselect') 

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'mov%';
    INSERT INTO `_api`.`ico` VALUES
      
      ('mov', 'open_with'),

      ('mov_hor', 'swap_horiz'),
      ('mov_ver', 'import_export'),

      ('mov_arr', 'north'),
      ('mov_aba', 'south'),
      ('mov_izq', 'west'),
      ('mov_der', 'east'),

      ('mov_dyr', 'north_east'),
      ('mov_dyb', 'north_west'),
      ('mov_iyr', 'south_east'),
      ('mov_iyb', 'south_west')
    ;    
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'nav%';
    INSERT INTO `_api`.`ico` VALUES

      ('nav',			'menu'),  

      -- operaciones
      ('nav_val',	'menu_open'),
      ('nav_ini',	'first_page'),
      ('nav_pre',	'navigate_before'),
      ('nav_pos',	'navigate_next'),
      ('nav_fin',	'last_page')

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'art%';
    INSERT INTO `_api`.`ico` VALUES  

      ('art',     'article'),
      -- bibliografias
      ('art_bib', 'auto_stories'),
      -- listados
      ('art_est', 'table'),
      -- tableros
      ('art_tab', 'dataset'),
      -- informes
      ('art_inf', 'description'),
      -- valores
      ('art_val', 'document_scanner')      

    ;      

  --
  -- entornos
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'dat%';
    INSERT INTO `_api`.`ico` VALUES

      ('dat',			'database'),

      -- por operaciones
      ('dat_tip',	'input'),      
      ('dat_val',	'done'),
      ('dat_ver',	'search'),
      ('dat_act',	'sync'),
      ('dat_agr',	'add'),
      ('dat_mod',	'edit'),
      ('dat_eli',	'delete')

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'val%';
    INSERT INTO `_api`.`ico` VALUES
  
      ('val',	'check_box_outline_blank'),

      -- tipos
      ('val_var',	'variables'),
      ('val_col',	'palette'),
      ('val_ima',	'image'),
      ('val_tex',	'text_fields'),
      ('val_num',	'pin'),
      ('val_fec',	'history')

    ;    
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'lis%';
    INSERT INTO `_api`.`ico` VALUES

      ('lis',			'list_alt'),
      -- operaciones
      ('lis_val',	'list'),
      ('lis_cue',	'summarize'),  
      ('lis_ver',	'filter_alt'),
      ('lis_jun',	'join'),
      ('lis_gru',	'low_priority'),
      ('lis_ord',	'sort'),
      ('lis_lim',	'segment'),
      ('lis_mov',	'unfold_more')

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'est%';
    INSERT INTO `_api`.`ico` VALUES

      ('est',	'view_list')
    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'tab%';
    INSERT INTO `_api`.`ico` VALUES
    
      ('tab',	'table_view')
    ;    
  -- 
  -- valores
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'obj%';
    INSERT INTO `_api`.`ico` VALUES
      
      ('obj',	'widgets'),

      -- tipos      
      ('obj_pos',	'format_list_numbered'),
      ('obj_nom',	'data_array'),
      ('obj_atr',	'data_object')      

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'arc%';
    INSERT INTO `_api`.`ico` VALUES  

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
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'eje%';
    INSERT INTO `_api`.`ico` VALUES
      
      ('eje',	    'terminal'),

      -- operadores
      ('eje_val', 'play_arrow'),
      ('eje_ini',	'done'),
      ('eje_fin',	'close')  

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'ele%';
    INSERT INTO `_api`.`ico` VALUES
      
      ('ele', 'code'),

      -- valores
      ('ele_htm', 'html'),
      ('ele_css', 'css'),
      ('ele_eje', 'javascript')

    ;    
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'opc%';
    INSERT INTO `_api`.`ico` VALUES

      ('opc',			'help'),  

      -- tipos    
      ('opc_vac', 'help_center'),
      ('opc_bin',	'rule'),
      ('opc_lis', 'checklist'),
      ('opc_val', 'select_all'),
      ('opc_nad', 'deselect'),
      ('opc_uni',	'format_list_bulleted'),
      ('opc_mul',	'format_list_numbered')

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'num%';
    INSERT INTO `_api`.`ico` VALUES

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
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'tex%';
    INSERT INTO `_api`.`ico` VALUES    

      ('tex',			'edit_note'),

      -- valores
      ('tex_cod',	'format_size'),
      
      -- tipos
      ('tex_let',	'title'),
      ('tex_pal',	'text_fields'),
      ('tex_ora',	'short_text'),
      ('tex_par',	'rtt'),
      ('tex_lib',	'menu_book'),

      -- operadores
      ('tex_ord',	'sort_by_alpha'),
      ('tex_mov',	'wrap_text'),
      ('tex_rot',	'text_rotation_down')      
      

    ;
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'fec%';
    INSERT INTO `_api`.`ico` VALUES

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
    DELETE FROM `_api`.`ico` WHERE `ide` LIKE 'fig%';
    INSERT INTO `_api`.`ico` VALUES

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