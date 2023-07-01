-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api
--
-- estilo 
  DELETE FROM `var-tex_ico_gru`
  ;
  INSERT INTO `var-tex_ico_gru` VALUES
    (NULL,'material-icons',					 'https://fonts.googleapis.com/css?family=Material+Icons'),
    (NULL,'material-icons-outlined', 'https://fonts.googleapis.com/css?family=Material+Icons+Outlined'),
    (NULL,'material-icons-two-tone', 'https://fonts.googleapis.com/css?family=Material+Icons+Two+Tone'),
    (NULL,'material-icons-round',		 'https://fonts.googleapis.com/css?family=Material+Icons+Round'),
    (NULL,'material-icons-sharp',		 'https://fonts.googleapis.com/css?family=Material+Icons+Sharp')
  ;
--
-- icono 
  DELETE FROM `var-tex_ico`
  ;
  -- 
  -- Sistema
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'app%';
    INSERT INTO `var-tex_ico` VALUES  
      ('app',         'home'),      
      ('app_usu',	    'manage_accounts'),
      ('app_cab',			'menu'),
      ('app_nav',	    'menu_open'),
      ('app_art',     'article'),
      ('app_val',     'document_scanner')
    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'usu%';
    INSERT INTO `var-tex_ico` VALUES
      ('usu',	    'person'),      
      ('usu_pas',	'password'),
      ('usu_ubi',	'share_location'),
      ('usu_mai',	'email'),
      ('usu_tel',	'phone'),
      ('usu_web',	'web_asset'),
      ('usu_gru',	'group'),
      ('usu_age',	'menu_book')
    ;
  --
  -- Documento
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'dat%';
    INSERT INTO `var-tex_ico` VALUES

      ('dat',			'database'),
      
      -- atributos
      ('dat_key',	''),
      ('dat_ide',	''),
      ('dat_nom',	''),
      ('dat_des',	'help_center'),
      ('dat_tip',	'input'),      
      ('dat_var',	'variables'),

      -- tipos
      ('dat_pos', ''),
      ('dat_fic', ''),
      ('dat_ima', 'collections'),
      ('dat_inf',	'description'),
      ('dat_tab', 'dataset'),
      ('dat_lis',	'table_view')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'ope%';
    INSERT INTO `var-tex_ico` VALUES
      ('ope',	    'settings'),
      -- togs : item - expandir - contraer
      ('ope_tog',	    'arrow_drop_down'),
      ('ope_tog-tod',	'expand_circle_down'),
      ('ope_tog-nad',	'expand_circle_down'),
      -- filtros : mostrar - ocultar
      ('ope_ver',	    ''),
      ('ope_ver-tod',	'visibility'),
      ('ope_ver-nad',	'visibility_off'),
      -- seleccion : todo - nada
      ('ope_sel',	''),
      ('ope_sel-tod',	'select_all'),
      ('ope_sel-nad',	'deselect'),
      -- estado : activo - inactivo
      ('ope_est',	''),
      ('ope_est-tod',	'toggle_on'),
      ('ope_est-nad',	'toggle_off'),
      -- navegacion : entrar - salir + movimientos
      ('ope_nav',	    'open_with'),
      ('ope_nav-ini',	'login'),
      ('ope_nav-fin',	'logout'),
      ('ope_nav-hor', 'swap_horiz'),
      ('ope_nav-ver', 'import_export'),
      ('ope_nav-arr', 'north'),
      ('ope_nav-aba', 'south'),
      ('ope_nav-izq', 'west'),
      ('ope_nav-der', 'east'),
      ('ope_nav-dyr', 'north_east'),
      ('ope_nav-dyb', 'north_west'),
      ('ope_nav-iyr', 'south_east'),
      ('ope_nav-iyb', 'south_west'),
      -- mensajes :
      ('ope_tex-ver',	'comment'),
      ('ope_tex-err',	'error'),
      ('ope_tex-adv',	'warning'),
      ('ope_tex-opc',	'help'),
      ('ope_tex-val',	'check_circle'),
      -- valores
      ('ope_val-ver',	'search'),
      ('ope_val-tod',	'checklist'),
      ('ope_val-nad',	'flaky'),
      ('ope_val-act',	'sync'),
      ('ope_val-agr',	'add'),
      ('ope_val-mod',	'edit'),
      ('ope_val-eli',	'delete'),
      ('ope_val-ini',	'done'),
      ('ope_val-fin',	'close'),
      -- listado
      ('ope_lis',	'list_alt'),
      ('ope_lis-ini',	'first_page'),
      ('ope_lis-fin',	'last_page'),
      ('ope_lis-pre',	'navigate_before'),
      ('ope_lis-pos',	'navigate_next'),      
      ('ope_lis-tod',	'playlist_add_check'),
      ('ope_lis-nad',	'playlist_remove'),
      ('ope_lis-cue',	'summarize'),  
      ('ope_lis-ver',	'filter_alt'),
      ('ope_lis-jun',	'join_inner'),
      ('ope_lis-gru',	'low_priority'),
      ('ope_lis-ord',	'sort'),
      ('ope_lis-lim',	'segment'),
      ('ope_lis-mov',	'unfold_more')
    ;    
  --
  -- Variables
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'opc%';
    INSERT INTO `var-tex_ico` VALUES

      ('opc',	    'checklist_rtl'),

      -- tipos
      ('opc_val',	'view_list'),
      ('opc_bin',	'rule'),
      ('opc_uni',	'radio_button_checked'),
      ('opc_mul',	'check_box')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'num%';
    INSERT INTO `var-tex_ico` VALUES

      ('num',	'tag'),

      -- atributos
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
      ('num_ope',	'calculate'),
      ('num_sum',	'add'),
      ('num_res',	'remove'),
      ('num_mul',	'close'),
      ('num_div',	'percent'),

      -- calculos
      ('num_fun',	'function'),
      ('num_adi',	'functions')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'tex%';
    INSERT INTO `var-tex_ico` VALUES    

      ('tex',			'edit_note'),
      
      -- tipos
      ('tex_val',	'rtt'),
      ('tex_let',	'title'),
      ('tex_pal',	'text_fields'),
      ('tex_ora',	'short_text'),
      ('tex_par',	'chat'),
      ('tex_lib',	'auto_stories'),

      -- operaciones
      ('tex_ord',	'sort_by_alpha'),
      ('tex_mov',	'wrap_text'),
      ('tex_rot',	'text_rotation_down')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'fec%';
    INSERT INTO `var-tex_ico` VALUES

      ('fec',			'calendar_today'),      

      -- tipos
      ('fec_val', 'edit_calendar'),
      ('fec_tie',	'access_time'),
      ('fec_hor',	''),
      ('fec_min',	''),
      ('fec_seg',	''),
      ('fec_dia',	'event'),
      ('fec_sem',	'date_range'),
      ('fec_mes',	'calendar_month'),
      ('fec_año',	'event_note')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'fig%';
    INSERT INTO `var-tex_ico` VALUES

      ('fig',	'auto_graph'),
      
      -- tipos
      ('fig_col',	'palette'),
      ('fig_geo',	'shape_line'),
      ('fig_pun',	'scatter_plot'),
      ('fig_lin',	'trending_down'),
      ('fig_pol',	'polyline')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'arc%';
    INSERT INTO `var-tex_ico` VALUES  

      ('arc',			'attach_file'),

      -- tipos
      ('arc_val',	'note'),
      ('arc_fic',	'folder'),
      ('arc_url',	'language'),
      ('arc_tex',	'text_snippet'),
      ('arc_ima',	'image'),
      ('arc_mus',	'audio_file'),
      ('arc_vid',	'video_file')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'eje%';
    INSERT INTO `var-tex_ico` VALUES

      ('eje',     'terminal'),

      -- tipos
      ('eje_val', 'play_arrow'),
      ('eje_fun',	'play_circle'),
      ('eje_cla', 'widgets')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'obj%';
    INSERT INTO `var-tex_ico` VALUES

      ('obj',	'list'),

      -- tipos
      ('obj_val',	 ''),
      ('obj_pos',	'format_list_numbered'),
      ('obj_nom',	'data_array'),
      ('obj_atr',	'data_object')

    ;
    DELETE FROM `var-tex_ico` WHERE `ide` LIKE 'ele%';
    INSERT INTO `var-tex_ico` VALUES

      ('ele', 'code'),

      -- propiedades
      ('ele_htm', 'html'),
      ('ele_css', 'css'),
      ('ele_eje', 'javascript')

    ;
  --
--