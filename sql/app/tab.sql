-- Active: 1665550796793@@127.0.0.1@3306@c1461857_api

-- Holon
  DELETE FROM `app_tab` WHERE `esq`='hol'
  ;
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'uni%'; INSERT INTO `app_tab` VALUES
    -- holon solar
    ( 'hol','uni_sol', 
      '{
        "sec":{ "style":"grid:repeat(9,1fr)/repeat(9,1fr);" },

        "fon":{ "style":"grid-column: 1/sp; grid-row: 1/sp; width: 100%; height: 100%;" },
          "fon-map":{ "class":"fon map", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/map.png);" },
          "fon-ato":{ "class":"fon ato", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/ato.png);" },
          "fon-res":{ "class":"fon res", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/res.png);" },
          "fon-cel":{ "class":"fon cel", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/cel.png);" },
          "fon-cir":{ "class":"fon cir", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/cir.png);" },
          "fon-pla":{ "class":"fon pla", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/sol/pla.png);" },

        "pla":{ "style":"position: relative; width: 110%; height: 100%;" },
          "pla-10":{ "data-sol_pla":"10", "style":"grid-column:3/5; grid-row:2; transform: rotate( 053deg); top: 25%; left: 15%;" },
          "pla-09":{ "data-sol_pla":"09", "style":"grid-column:2/4; grid-row:3; transform: rotate( 053deg); left: 17%;" },
          "pla-08":{ "data-sol_pla":"08", "style":"grid-column:2/4; grid-row:5; transform: rotate( 340deg); top: 25%; right: 20%;" },
          "pla-07":{ "data-sol_pla":"07", "style":"grid-column:2/4; grid-row:6; transform: rotate( 340deg); top: 45%;" },
          "pla-06":{ "data-sol_pla":"06", "style":"grid-column:4/6; grid-row:8; transform: rotate( 270deg); bottom: 20%; right: 5%;" },
          "pla-05":{ "data-sol_pla":"05", "style":"grid-column:5/7; grid-row:8; transform: rotate( 270deg); bottom: 20%; left: 7%;" },
          "pla-04":{ "data-sol_pla":"04", "style":"grid-column:7/9; grid-row:6; transform: rotate( 018deg); top: 35%; left: 3%;" },
          "pla-03":{ "data-sol_pla":"03", "style":"grid-column:7/9; grid-row:5; transform: rotate( 018deg); top: 15%; left: 20%;" },
          "pla-02":{ "data-sol_pla":"02", "style":"grid-column:6/8; grid-row:3; transform: rotate( 305deg); bottom: 5%; left: 32%;" },
          "pla-01":{ "data-sol_pla":"01", "style":"grid-column:6/8; grid-row:2; transform: rotate( 305deg); top: 30%; right: 18%;" },

        "sel":{ "style":"position: relative; width: 110%; height: 110%; border-radius: 5%;" },      
          "sel-20":{ "data-hol_sel":"20", "style":"grid-column:3; grid-row:2; transform: rotate( 053deg); bottom: 22%; left: 45%;" },
          "sel-01":{ "data-hol_sel":"01", "style":"grid-column:2; grid-row:3; transform: rotate( 054deg); bottom: 49%; left: 47%;" },
          "sel-02":{ "data-hol_sel":"02", "style":"grid-column:1; grid-row:6; transform: rotate( 340deg); bottom: 54%; left: 55%;" },
          "sel-03":{ "data-hol_sel":"03", "style":"grid-column:2; grid-row:7; transform: rotate( 340deg); bottom: 39%; right: 5%;" },
          "sel-04":{ "data-hol_sel":"04", "style":"grid-column:4; grid-row:8; transform: rotate( 270deg); top: 35%; left: 41%;" },
          "sel-05":{ "data-hol_sel":"05", "style":"grid-column:6; grid-row:8; transform: rotate( 270deg); top: 35%; right: 37%;" },
          "sel-06":{ "data-hol_sel":"06", "style":"grid-column:8; grid-row:6; transform: rotate( 196deg); top: 50%; left: 13%;" },
          "sel-07":{ "data-hol_sel":"07", "style":"grid-column:8; grid-row:5; transform: rotate( 196deg); top: 32%; left: 47%;" },
          "sel-08":{ "data-hol_sel":"08", "style":"grid-column:7; grid-row:2; transform: rotate( 125deg); top: 44%; left: 48%;" },
          "sel-09":{ "data-hol_sel":"09", "style":"grid-column:6; grid-row:2; transform: rotate( 125deg); bottom: 26%; left: 50%;" },
          "sel-10":{ "data-hol_sel":"10", "style":"grid-column:6; grid-row:3; transform: rotate( 306deg); bottom: 28%; right: 20%;" },
          "sel-11":{ "data-hol_sel":"11", "style":"grid-column:7; grid-row:3; transform: rotate( 306deg); top: 40%; right: 20%;" },
          "sel-12":{ "data-hol_sel":"12", "style":"grid-column:7; grid-row:5; transform: rotate( 017deg); bottom: 2%; left: 32%;" },
          "sel-13":{ "data-hol_sel":"13", "style":"grid-column:7; grid-row:6; transform: rotate( 017deg); top: 14%; right: 3%;" },
          "sel-14":{ "data-hol_sel":"14", "style":"grid-column:6; grid-row:7; transform: rotate( 090deg); top: 15%; right: 35%;" },
          "sel-15":{ "data-hol_sel":"15", "style":"grid-column:4; grid-row:7; transform: rotate( 090deg); top: 15%; left: 40%;" },
          "sel-16":{ "data-hol_sel":"16", "style":"grid-column:3; grid-row:6; transform: rotate( 160deg); top: 20%; left: 10%;" },
          "sel-17":{ "data-hol_sel":"17", "style":"grid-column:3; grid-row:5; transform: rotate( 160deg); top: 5%; right: 30%;" },
          "sel-18":{ "data-hol_sel":"18", "style":"grid-column:3; grid-row:4; transform: rotate( 234deg); bottom: 52%; left: 18%;" },
          "sel-19":{ "data-hol_sel":"19", "style":"grid-column:4; grid-row:3; transform: rotate( 234deg); bottom: 25%; left: 15%;" },

        "sel-fic":{ "style":"width: 75%; height: 75%;" } 
        
      }', NULL
    ),
    -- holon planetario
    ( 'hol','uni_pla', '
      {
        "sec":{ "style":"grid: repeat(5,1fr)/repeat(9,1fr); },

        "fon":{ "style":"grid-column:2/10; grid-row:1/6; width: 100%; height: 100%;" },
          "fon-map":{ "class":"fon map", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/pla/map.png);" },
          "fon-sel":{ "class":"fon sel", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/pla/sel.png);" },
          "fon-res":{ "class":"fon res", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/pla/res.png);" },
          "fon-flu":{ "class":"fon flu", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/pla/flu.png);" },

        "fam":{ "style":"height: 80%; width: 80%;" },
          "fam-1":{ "data-cro_fam":"1", "style":"grid-column:1/2; grid-row:2;" },
          "fam-2":{ "data-cro_fam":"2", "style":"grid-column:1/2; grid-row:3;" },
          "fam-3":{ "data-cro_fam":"3", "style":"grid-column:1/2; grid-row:4;" },
          "fam-4":{ "data-cro_fam":"4", "style":"grid-column:1/2; grid-row:5;" },
          "fam-5":{ "data-cro_fam":"5", "style":"grid-column:1/2; grid-row:1;" },

        "sel":{ "style":"position: relative; width: 150%; height: 115%; border-radius: 50%;" },
          "sel-20":{ "data-hol_sel":"20", "style":"grid-column:6; grid-row:1; top: 25%;" },
          "sel-05":{ "data-hol_sel":"05", "style":"grid-column:8; grid-row:1; top: 25%;" },
          "sel-10":{ "data-hol_sel":"10", "style":"grid-column:2; grid-row:1; top: 25%; left: 20%;" },
          "sel-15":{ "data-hol_sel":"15", "style":"grid-column:4; grid-row:1; top: 25%;" },
          "sel-01":{ "data-hol_sel":"01", "style":"grid-column:7; grid-row:2; top: 20%;" },
          "sel-06":{ "data-hol_sel":"06", "style":"grid-column:9; grid-row:2; top: 20%;" },
          "sel-11":{ "data-hol_sel":"11", "style":"grid-column:3; grid-row:2; top: 20%; left: 15%;" },
          "sel-16":{ "data-hol_sel":"16", "style":"grid-column:5; grid-row:2; top: 20%; left: 10%;" },
          "sel-02":{ "data-hol_sel":"02", "style":"grid-column:8; grid-row:3; bottom: 10%;" },
          "sel-07":{ "data-hol_sel":"07", "style":"grid-column:2; grid-row:3; bottom: 10%; left: 25%;" },
          "sel-12":{ "data-hol_sel":"12", "style":"grid-column:4; grid-row:3; bottom: 10%; left: 15%;" },
          "sel-17":{ "data-hol_sel":"17", "style":"grid-column:6; grid-row:3; bottom: 10%;" },
          "sel-03":{ "data-hol_sel":"03", "style":"grid-column:9; grid-row:4; bottom: 20%;" },
          "sel-08":{ "data-hol_sel":"08", "style":"grid-column:3; grid-row:4; bottom: 20%; left: 15%;" },
          "sel-13":{ "data-hol_sel":"13", "style":"grid-column:5; grid-row:4; bottom: 20%; left: 15%;" },
          "sel-18":{ "data-hol_sel":"18", "style":"grid-column:7; grid-row:4; bottom: 20%;" },
          "sel-04":{ "data-hol_sel":"04", "style":"grid-column:2; grid-row:5; bottom: 45%; left: 30%;" },
          "sel-09":{ "data-hol_sel":"09", "style":"grid-column:4; grid-row:5; bottom: 45%; left: 20%;" },
          "sel-14":{ "data-hol_sel":"14", "style":"grid-column:6; grid-row:5; bottom: 45%; left: 8%;" },
          "sel-19":{ "data-hol_sel":"19", "style":"grid-column:8; grid-row:5; bottom: 45%; left: 5%;" },

        "sel-fic":{ "style":"width: 30%;" }  

      }', NULL
    ),
    -- holon humano
    ( 'hol','uni_hum', 
      '{
        "sec":{ "style":"grid: repeat(20,1fr)/repeat(13,1fr);" },
        
        "fon":{ "style":"grid-column: 1/sp; grid-row: 1/sp; width: 100%; height: 100%;" },
          "fon-map":{ "class":"fon map", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/hum/map.png);" },
          "fon-res":{ "class":"fon res", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/hum/res.png);" },
          "fon-cir":{ "class":"fon cir", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/hum/cir.png);" },
          "fon-cen":{ "class":"fon cen", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/hum/cen.png);" },
          "fon-ext":{ "class":"fon ext", "style":"background: center/contain no-repeat url(http://icpv.com.ar/img/hol/tab/hum/ext.png);" },

        "raz":{ "style":"position: relative;" },
          "raz-4":{ "data-arm_raz":"4", "style":"grid-column:2/5; grid-row:10/12; left:5%; top:5%;" },
          "raz-1":{ "data-arm_raz":"1", "style":"grid-column:5/7; grid-row:18/20; left: 30%; top:5%; z-index:-1;" },
          "raz-2":{ "data-arm_raz":"2", "style":"grid-column:11/13; grid-row:10/12; right:35%; top:5%;" },
          "raz-3":{ "data-arm_raz":"3", "style":"grid-column:8/10; grid-row:18/20; right:20%; top:5%; z-index:-1;" },

        "fam":{ "style":"" },
          "fam-5":{ "data-cro_fam":"5", "style":"grid-column:6/9; grid-row:1/3;" },
          "fam-1":{ "data-cro_fam":"1", "style":"grid-column:6/9; grid-row:3/6; z-index:1;" },
          "fam-2":{ "data-cro_fam":"2", "style":"grid-column:6/9; grid-row:5/8;" },
          "fam-3":{ "data-cro_fam":"3", "style":"grid-column:6/9; grid-row:7/10;" },
          "fam-4":{ "data-cro_fam":"4", "style":"grid-column:6/9; grid-row:10/12;" },

        "ton":{ "style":"position:relative; width:20px; height:20px; border-radius: 20%;" },
          "ton-01":{ "data-hol_ton":"01", "style":"grid-column:05; grid-row:18; bottom: 30%;" },
          "ton-02":{ "data-hol_ton":"02", "style":"grid-column:05; grid-row:15; right: 15%;" },
          "ton-03":{ "data-hol_ton":"03", "style":"grid-column:05; grid-row:11; right: 20%;" },
          "ton-04":{ "data-hol_ton":"04", "style":"grid-column:03; grid-row:10; bottom: 40%;" },
          "ton-05":{ "data-hol_ton":"05", "style":"grid-column:04; grid-row:08; bottom: 35%; right: 40%;" },
          "ton-06":{ "data-hol_ton":"06", "style":"grid-column:04; grid-row:05;" },
          "ton-07":{ "data-hol_ton":"07", "style":"grid-column:07; grid-row:04; bottom: 35%;" },
          "ton-08":{ "data-hol_ton":"08", "style":"grid-column:10; grid-row:05;" },
          "ton-09":{ "data-hol_ton":"09", "style":"grid-column:10; grid-row:08; bottom: 35%; left: 40%;" },
          "ton-10":{ "data-hol_ton":"10", "style":"grid-column:11; grid-row:10; bottom: 40%;" },
          "ton-11":{ "data-hol_ton":"11", "style":"grid-column:09; grid-row:11; left: 20%;" },
          "ton-12":{ "data-hol_ton":"12", "style":"grid-column:09; grid-row:15; left: 35%;" },
          "ton-13":{ "data-hol_ton":"13", "style":"grid-column:09; grid-row:18; bottom: 30%; left: 30%;" },

        "sel":{ "style":"width:15px; height:15px; position:relative; " },
          "sel-20":{ "data-hol_sel":"20", "style":"grid-column:1;  grid-row:10; top:40%; right:25%;" },
          "sel-01":{ "data-hol_sel":"01", "style":"grid-column:1;  grid-row:11; top:30%; right:17%;" },
          "sel-02":{ "data-hol_sel":"02", "style":"grid-column:1;  grid-row:12; top:20%; left: 25%;" },
          "sel-03":{ "data-hol_sel":"03", "style":"grid-column:2;  grid-row:12; top:40%; left: 28%;" },
          "sel-04":{ "data-hol_sel":"04", "style":"grid-column:3;  grid-row:12; top:25%; left: 30%;" },
          "sel-10":{ "data-hol_sel":"10", "style":"grid-column:13; grid-row:10; top:35%; left:20%;" },
          "sel-11":{ "data-hol_sel":"11", "style":"grid-column:13; grid-row:11; top:30%; left:15%;" },
          "sel-12":{ "data-hol_sel":"12", "style":"grid-column:13; grid-row:12; top:20%; right:25%;" },
          "sel-13":{ "data-hol_sel":"13", "style":"grid-column:12; grid-row:12; top:43%; right:25%;" },
          "sel-14":{ "data-hol_sel":"14", "style":"grid-column:11; grid-row:12; top:30%; right:25%;" },
          "sel-05":{ "data-hol_sel":"05", "style":"grid-column:6;  grid-row:20; top:15%; left:25%;" },
          "sel-06":{ "data-hol_sel":"06", "style":"grid-column:5;  grid-row:20; top:35%; left:20%;" },
          "sel-07":{ "data-hol_sel":"07", "style":"grid-column:4;  grid-row:20; top:25%; left:20%;" },
          "sel-08":{ "data-hol_sel":"08", "style":"grid-column:4;  grid-row:19; top:35%; left:5%;" },
          "sel-09":{ "data-hol_sel":"09", "style":"grid-column:4;  grid-row:19; bottom:50%; left:20%;" },
          "sel-15":{ "data-hol_sel":"15", "style":"grid-column:8;  grid-row:20; top:20%;" },
          "sel-16":{ "data-hol_sel":"16", "style":"grid-column:9;  grid-row:20; top:35%;" },
          "sel-17":{ "data-hol_sel":"17", "style":"grid-column:10; grid-row:20; top:25%;" },
          "sel-18":{ "data-hol_sel":"18", "style":"grid-column:10; grid-row:19; top:35%; left:15%;" },
          "sel-19":{ "data-hol_sel":"19", "style":"grid-column:10; grid-row:19; bottom:50%;" }  

      }', NULL
    )
  ;  
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'arm%'; INSERT INTO `app_tab` VALUES
    -- cuadrado-cubo
    ( 'hol','arm', 
      '{        
        "sec":{ "style":"grid: repeat(2,1fr)/repeat(2,1fr); border-radius: 15%;" },

        "pos-0":{ "style":"grid-column:3/5; grid-row:3/5; border-radius: 50%; padding: 1rem; width: 200%; height: 200%; justify-self: center; align-self: center;" },
        "pos-1":{ "style":"grid-column:4/7; grid-row:1/4;" },
        "pos-2":{ "style":"grid-column:1/4; grid-row:1/4;" },
        "pos-3":{ "style":"grid-column:1/4; grid-row:4/8;" },
        "pos-4":{ "style":"grid-column:4/7; grid-row:4/8;" }   

      }', NULL
    )
  ;  
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'cro%'; INSERT INTO `app_tab` VALUES
    -- cruz-atomo
    ( 'hol','cro', 
      '{
        "sec":{ "style":"grid: repeat(3,1fr) / repeat(3,1fr); border-radius: 50%;" },

        "pos-0":{ "style":"grid-column:2; grid-row:2;" },
        "pos-1":{ "style":"grid-column:3; grid-row:2;" },
        "pos-2":{ "style":"grid-column:2; grid-row:1;" },
        "pos-3":{ "style":"grid-column:1; grid-row:2;" },
        "pos-4":{ "style":"grid-column:2; grid-row:3;" },
        "pos-5":{ "style":"grid-column:2; grid-row:2;" }   

      }', NULL
    ),
    -- pentagono tipo petalos
    ( 'hol','cro_cir', 
      '{
        "sec":{ "style":"grid:repeat(3,1fr)/repeat(3,1fr); border-radius: 50%;" },

        "pos":{ "style":"position: relative;" },
        "pos-0":{ "style":"grid-column:2/3; grid-row:2/3; align-self: center; justify-self: center; justify-content: center; align-items: center; width: 150%; height: 150%; color: black" },
        "pos-1":{ "style":"grid-column:1/2; grid-row:1/2; top: 3%;    left: 28%; transform: rotate(145deg);" },
        "pos-2":{ "style":"grid-column:1/2; grid-row:2/3; top: 38%;   left:-13%; transform: rotate(070deg);" },
        "pos-3":{ "style":"grid-column:2/3; grid-row:3/4; top: 20%;" },
        "pos-4":{ "style":"grid-column:3/4; grid-row:2/3; top: 35.5%; left: 12%; transform: rotate(287deg);" },
        "pos-5":{ "style":"grid-column:3/4; grid-row:1/2; top: 3%;    left:-30%; transform: rotate(217deg);" }      

      }', NULL
    )
  ;  
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'rad%'; INSERT INTO `app_tab` VALUES
    -- heptágono
    ( 'hol','rad', 
      '{ 
        "sec":{ "style":"grid:repeat(4,1fr)/repeat(4,1fr); background: center/contain no-repeat url(http://icpv.com.ar/img/hol/fic/rad.png);" },

        "pos":{ "style":"position: relative;" },
        "pos-1":{ "style":"grid-column:2; grid-row:1; top:     15%; left:  50%;" },
        "pos-2":{ "style":"grid-column:2; grid-row:4; bottom:  15%; left:  53%; " },
        "pos-3":{ "style":"grid-column:1; grid-row:2; bottom:  20%; left:  25%;" },
        "pos-4":{ "style":"grid-column:4; grid-row:3; top:     20%; right: 25%;" },
        "pos-5":{ "style":"grid-column:1; grid-row:3; top:     20%; left:  30%;" },
        "pos-6":{ "style":"grid-column:4; grid-row:2; bottom:  20%; right: 35%;" },
        "pos-7":{ "style":"grid-column:2; grid-row:2; top:     50%; left:  50%;" } 
        
      }', NULL
    ),
    -- atomo por quantum
    ( 'hol','rad_ato', 
      '{
      }', NULL
    )
  ;  
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'ton%'; INSERT INTO `app_tab` VALUES

    ( 'hol','ton', 
      '{
        "sec":{ "style":"grid: repeat(5,1fr)/repeat(5,1fr); justify-items: start;" },

        "ima":{ "class":"sec -ima", "style":"z-index:1; grid-column:1/sp; grid-row:1/sp; width:100%; height:100%;" },

        "fon":{ "class":"sec -fon", "style":"z-index:2; grid-column:1/sp; grid-row:1/sp; width:95%; height:91%; border-radius:50%;" },

        "ond":{ "class":"sec -ond", "style":"z-index:3; grid-column:1/sp; grid-row:1/sp; width: 100%; height: 100%;" },
        
        "pos":{ "style":"z-index:4;" },
        "pos-01":{ "style":"grid-column:1/2; grid-row:1/2;" },
        "pos-02":{ "style":"grid-column:1/2; grid-row:2/3;" },
        "pos-03":{ "style":"grid-column:1/2; grid-row:3/4;" },
        "pos-04":{ "style":"grid-column:1/2; grid-row:4/5;" },
        "pos-05":{ "style":"grid-column:1/2; grid-row:5/6;" },
        "pos-06":{ "style":"grid-column:2/3; grid-row:5/6;" },
        "pos-07":{ "style":"grid-column:3/4; grid-row:5/6;" },
        "pos-08":{ "style":"grid-column:4/5; grid-row:5/6;" },
        "pos-09":{ "style":"grid-column:5/6; grid-row:5/6;" },
        "pos-10":{ "style":"grid-column:5/6; grid-row:4/5;" },
        "pos-11":{ "style":"grid-column:5/6; grid-row:3/4;" },
        "pos-12":{ "style":"grid-column:5/6; grid-row:2/3;" },
        "pos-13":{ "style":"grid-column:4/5; grid-row:2/3;" }   
        
      }', NULL
    )
  ;  
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'sel%'; INSERT INTO `app_tab` VALUES

    ( 'hol','sel', 
      '{ 
        "sec":{ "style":"grid:repeat(4,1fr)/repeat(5,1fr); grid-gap:.5rem;" }

      }', NULL)
    ,-- parejas del oráculo
    ( 'hol','sel_par', 
      '{ 
        "sec":{ "style":"border: 1px solid var(--col_ver); border-radius: 50%;" }
        
      }', NULL)
    ,-- colocacion armónica
    ( 'hol','sel_arm', 
      '{ 
        "sec":{ "style":"grid: repeat(5,1fr)/repeat(6,1fr); grid-auto-flow: column; border-radius: 20%;" },

        "raz-1":{ "data-arm_raz":"1", "style":"grid-column:1/2; grid-row:2/3;" },
        "raz-2":{ "data-arm_raz":"2", "style":"grid-column:1/2; grid-row:3/4;" },
        "raz-3":{ "data-arm_raz":"3", "style":"grid-column:1/2; grid-row:4/5;" },
        "raz-4":{ "data-arm_raz":"4", "style":"grid-column:1/2; grid-row:5/6;" },

        "cel-1":{ "data-arm_cel":"1", "style":"grid-column:2/3; grid-row:1/2;" },
        "cel-2":{ "data-arm_cel":"2", "style":"grid-column:3/4; grid-row:1/2;" },
        "cel-3":{ "data-arm_cel":"3", "style":"grid-column:4/5; grid-row:1/2;" },
        "cel-4":{ "data-arm_cel":"4", "style":"grid-column:5/6; grid-row:1/2;" },
        "cel-5":{ "data-arm_cel":"5", "style":"grid-column:6/7; grid-row:1/2;" }
        
      }', NULL)
      ,-- trayectoria
      ( 'hol','sel_arm_tra', 
        '{
        }', NULL)
    ,-- colocacion cromática
    ( 'hol','sel_cro', 
      '{ 

        "sec":{ "style":"grid-auto-flow: column; border-radius: 20%;" },

        "fam-5":{ "data-cro_fam":"5", "style":"grid-column:1/2; grid-row:2/3;" },
        "fam-1":{ "data-cro_fam":"1", "style":"grid-column:1/2; grid-row:3/4;" }, 
        "fam-2":{ "data-cro_fam":"2", "style":"grid-column:1/2; grid-row:4/5;" }, 
        "fam-3":{ "data-cro_fam":"3", "style":"grid-column:1/2; grid-row:5/6;" }, 
        "fam-4":{ "data-cro_fam":"4", "style":"grid-column:1/2; grid-row:6/7;" },

        "ele-4":{ "data-cro_ele":"4", "style":"grid-column:2/3; grid-row:1/2;" },
        "ele-1":{ "data-cro_ele":"1", "style":"grid-column:3/4; grid-row:1/2;" }, 
        "ele-2":{ "data-cro_ele":"2", "style":"grid-column:4/5; grid-row:1/2;" }, 
        "ele-3":{ "data-cro_ele":"3", "style":"grid-column:5/6; grid-row:1/2;" }

      }', NULL
    )
  ;
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'lun%'; INSERT INTO `app_tab` VALUES
    -- forma de almanque
    ( 'hol','lun', 
      '{
      }', NULL
    )
  ;
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'cas%'; INSERT INTO `app_tab` VALUES
    -- fractal en cruz
    ( 'hol','cas', 
      '{ 
        "sec":{ "style":"grid: repeat(11,1fr)/repeat(11,1fr); align-items: center; justify-items: center; border-radius: 10%;" },

        "ima":{ "class":"sec ima", "style":"z-index:1; width:100%; height:100%;" },
        
        "fon":{ "class":"sec fon", "style":"z-index:2; width:95%; height:91%; border-radius:50%;" },

        "orb":{ "style":"z-index:3; border-radius: 50%; border: 1px solid green;" },
        "orb-1":{ "class":"sec orb-1", "style":"grid-column:5/8;  grid-row:5/8;  width: 70%; height: 70%;" },
        "orb-2":{ "class":"sec orb-2", "style":"grid-column:4/9;  grid-row:4/9;  width: 82%; height: 82%;" },
        "orb-3":{ "class":"sec orb-3", "style":"grid-column:3/10; grid-row:3/10; width: 86%; height: 86%;" },
        "orb-4":{ "class":"sec orb-4", "style":"grid-column:2/11; grid-row:2/11; width: 89%; height: 89%;" },
        "orb-5":{ "class":"sec orb-5", "style":"grid-column:1/12; grid-row:1/12; width: 91%; height: 91%;" },

        "ond":{ "class":"sec -ond", "style":"z-index:4; width: 100%; height: 100%;" },
        "ond-1":{ "class":"sec ond-1", "style":"grid-column:7/12; grid-row:2/7 ; transform: rotate(270deg);" },
        "ond-2":{ "class":"sec ond-2", "style":"grid-column:2/7 ; grid-row:1/6 ; transform: rotate(180deg);" },
        "ond-3":{ "class":"sec ond-3", "style":"grid-column:1/6 ; grid-row:6/11; transform: rotate(090deg);" },
        "ond-4":{ "class":"sec ond-4", "style":"grid-column:6/11; grid-row:7/12;" },

        "pos":{ "style":"z-index:5;" },
          "pos-00":{ "style":"grid-column:06; grid-row:06; width:80%; height:80%; border-radius:50%;" },
          "pos-01":{ "style":"grid-column:07; grid-row:06;" },
          "pos-02":{ "style":"grid-column:08; grid-row:06;" },
          "pos-03":{ "style":"grid-column:09; grid-row:06;" },
          "pos-04":{ "style":"grid-column:10; grid-row:06;" },
          "pos-05":{ "style":"grid-column:11; grid-row:06;" },
          "pos-06":{ "style":"grid-column:11; grid-row:05;" },
          "pos-07":{ "style":"grid-column:11; grid-row:04;" },
          "pos-08":{ "style":"grid-column:11; grid-row:03;" },
          "pos-09":{ "style":"grid-column:11; grid-row:02;" },
          "pos-10":{ "style":"grid-column:10; grid-row:02;" },
          "pos-11":{ "style":"grid-column:09; grid-row:02;" },
          "pos-12":{ "style":"grid-column:08; grid-row:02;" },
          "pos-13":{ "style":"grid-column:08; grid-row:03;" },
          "pos-14":{ "style":"grid-column:06; grid-row:05;" },
          "pos-15":{ "style":"grid-column:06; grid-row:04;" },
          "pos-16":{ "style":"grid-column:06; grid-row:03;" },
          "pos-17":{ "style":"grid-column:06; grid-row:02;" },
          "pos-18":{ "style":"grid-column:06; grid-row:01;" },
          "pos-19":{ "style":"grid-column:05; grid-row:01;" },
          "pos-20":{ "style":"grid-column:04; grid-row:01;" },
          "pos-21":{ "style":"grid-column:03; grid-row:01;" },
          "pos-22":{ "style":"grid-column:02; grid-row:01;" },
          "pos-23":{ "style":"grid-column:02; grid-row:02;" },
          "pos-24":{ "style":"grid-column:02; grid-row:03;" },
          "pos-25":{ "style":"grid-column:02; grid-row:04;" },
          "pos-26":{ "style":"grid-column:03; grid-row:04;" },
          "pos-27":{ "style":"grid-column:05; grid-row:06;" },
          "pos-28":{ "style":"grid-column:04; grid-row:06;" },
          "pos-29":{ "style":"grid-column:03; grid-row:06;" },
          "pos-30":{ "style":"grid-column:02; grid-row:06;" },
          "pos-31":{ "style":"grid-column:01; grid-row:06;" },
          "pos-32":{ "style":"grid-column:01; grid-row:07;" },
          "pos-33":{ "style":"grid-column:01; grid-row:08;" },
          "pos-34":{ "style":"grid-column:01; grid-row:09;" },
          "pos-35":{ "style":"grid-column:01; grid-row:10;" },
          "pos-36":{ "style":"grid-column:02; grid-row:10;" },
          "pos-37":{ "style":"grid-column:03; grid-row:10;" },
          "pos-38":{ "style":"grid-column:04; grid-row:10;" },
          "pos-39":{ "style":"grid-column:04; grid-row:09;" },
          "pos-40":{ "style":"grid-column:06; grid-row:07;" },
          "pos-41":{ "style":"grid-column:06; grid-row:08;" },
          "pos-42":{ "style":"grid-column:06; grid-row:09;" },
          "pos-43":{ "style":"grid-column:06; grid-row:10;" },
          "pos-44":{ "style":"grid-column:06; grid-row:11;" },
          "pos-45":{ "style":"grid-column:07; grid-row:11;" },
          "pos-46":{ "style":"grid-column:08; grid-row:11;" },
          "pos-47":{ "style":"grid-column:09; grid-row:11;" },
          "pos-48":{ "style":"grid-column:10; grid-row:11;" },
          "pos-49":{ "style":"grid-column:10; grid-row:10;" },
          "pos-50":{ "style":"grid-column:10; grid-row:09;" },
          "pos-51":{ "style":"grid-column:10; grid-row:08;" },
          "pos-52":{ "style":"grid-column:09; grid-row:08;" } 

      }', NULL
    ),-- circular
    ( 'hol','cas_cir', 
      '{ 
        "sec":{ "style":"grid: repeat(18,1fr)/repeat(18,1fr); border-radius: 50%;" },

        "ima":{ "class":"sec ima", "style":"z-index:1; width:100%; height:100%;" },

        "fon":{ "class":"sec fon", "style":"z-index:2; width:95%; height:91%; border-radius:50%;" },

        "orb":{ "style":"z-index:3; width: 100%; height: 100%; border-radius: 50%;" },
        "orb-1":{ "class":"sec orb-1", "style":"grid-column:9/11; grid-row:9/11;" },
        "orb-2":{ "class":"sec orb-2", "style":"grid-column:8/12; grid-row:8/12;" },
        "orb-3":{ "class":"sec orb-3", "style":"grid-column:7/13; grid-row:7/13;" },
        "orb-4":{ "class":"sec orb-4", "style":"grid-column:6/14; grid-row:6/14;" },
        "orb-5":{ "class":"sec orb-5", "style":"grid-column:5/15; grid-row:5/15;" },
        "orb-6":{ "class":"sec orb-6", "style":"grid-column:4/16; grid-row:4/16;" },
        "orb-7":{ "class":"sec orb-7", "style":"grid-column:3/17; grid-row:3/17;" },
        "orb-8":{ "class":"sec orb-8", "style":"grid-column:2/18; grid-row:2/18;" },

        "ond":{ "style":"z-index:3; width: 100%; height: 100%;" },
        "ond-1":{ "class":"sec ond-1", "style":"grid-column:10/19; grid-row:01/10; border-radius: 0 100% 0 0; border-bottom: 1.5px solid green;" },
        "ond-2":{ "class":"sec ond-2", "style":"grid-column:01/10; grid-row:01/10; border-radius: 100% 0 0 0; border-right:  1.5px solid green;" },
        "ond-3":{ "class":"sec ond-3", "style":"grid-column:01/10; grid-row:10/19; border-radius: 0 0 0 100%; border-top:    1.5px solid green;" },
        "ond-4":{ "class":"sec ond-4", "style":"grid-column:10/19; grid-row:10/19; border-radius: 0 0 100% 0; border-left:   1.5px solid green;" },
        
        "pos":{ "style":"z-index:4; position: relative;" },
          "pos-00":{ "style":"grid-column:09/11; grid-row:09/11; width: 25%; height: 25%;" },
          "pos-01":{ "style":"grid-column:18; grid-row:09; left:  10%;" },
          "pos-02":{ "style":"grid-column:18; grid-row:08; left:  05%;" },
          "pos-03":{ "style":"grid-column:18; grid-row:07; right: 25%;" },
          "pos-04":{ "style":"grid-column:17; grid-row:06; left: 35%;" },
          "pos-05":{ "style":"grid-column:17; grid-row:05; right: 20%;  top: 05%;" },
          "pos-06":{ "style":"grid-column:16; grid-row:04; left: 15%;   top: 15%;" },
          "pos-07":{ "style":"grid-column:16; grid-row:03; right: 47%;  top: 30%;" },
          "pos-08":{ "style":"grid-column:15; grid-row:03; right: 20%;  bottom: 30%;" },
          "pos-09":{ "style":"grid-column:14; grid-row:02;              top: 22%;" },
          "pos-10":{ "style":"grid-column:13; grid-row:02; left: 15%;   bottom: 30%;" },
          "pos-11":{ "style":"grid-column:12; grid-row:01; left: 15%;   top: 35%;" },
          "pos-12":{ "style":"grid-column:11; grid-row:01; left: 15%;   top: 10%;" },
          "pos-13":{ "style":"grid-column:10; grid-row:01; left: 10%;   bottom: 10%;" },
          "pos-14":{ "style":"grid-column:09; grid-row:01; bottom: 10%;" },
          "pos-15":{ "style":"grid-column:08; grid-row:01;" },
          "pos-16":{ "style":"grid-column:07; grid-row:01; top: 30%;" },
          "pos-17":{ "style":"grid-column:06; grid-row:02; bottom: 40%;" },
          "pos-18":{ "style":"grid-column:05; grid-row:02; top: 15%;" },
          "pos-19":{ "style":"grid-column:04; grid-row:03; bottom: 20%; left: 10%;" },
          "pos-20":{ "style":"grid-column:03; grid-row:03; top:40%; left: 35%;" },
          "pos-21":{ "style":"grid-column:03; grid-row:04; top:15%; right: 30%;" },
          "pos-22":{ "style":"grid-column:02; grid-row:05; left: 15%;" },
          "pos-23":{ "style":"grid-column:02; grid-row:06; right: 40%;" },
          "pos-24":{ "style":"grid-column:01; grid-row:07; left: 30%;" },
          "pos-25":{ "style":"grid-column:01; grid-row:08; left: 10%;" },
          "pos-26":{ "style":"grid-column:01; grid-row:09;" },
          "pos-27":{ "style":"grid-column:01; grid-row:10;" },
          "pos-28":{ "style":"grid-column:01; grid-row:11; left: 10%;" },
          "pos-29":{ "style":"grid-column:01; grid-row:12; left: 40%;" },
          "pos-30":{ "style":"grid-column:02; grid-row:13; right: 20%;" },
          "pos-31":{ "style":"grid-column:02; grid-row:14; left: 30%;" },
          "pos-32":{ "style":"grid-column:03; grid-row:15; bottom: 15%; right: 5%;" },
          "pos-33":{ "style":"grid-column:03; grid-row:16; bottom: 50%; left: 60%;" },
          "pos-34":{ "style":"grid-column:04; grid-row:16; top: 15%; left: 30%;" },
          "pos-35":{ "style":"grid-column:05; grid-row:17; bottom: 30%; left: 5%;" },
          "pos-36":{ "style":"grid-column:06; grid-row:17; top: 20%;" },
          "pos-37":{ "style":"grid-column:07; grid-row:18; bottom: 45%;" },
          "pos-38":{ "style":"grid-column:08; grid-row:18; bottom: 20%;" },
          "pos-39":{ "style":"grid-column:09; grid-row:18; bottom: 5%;" },
          "pos-40":{ "style":"grid-column:10; grid-row:18; bottom: 5%;" },
          "pos-41":{ "style":"grid-column:11; grid-row:18; bottom: 20%;" },
          "pos-42":{ "style":"grid-column:12; grid-row:18; bottom: 45%;" },
          "pos-43":{ "style":"grid-column:13; grid-row:17; top: 20%;" },
          "pos-44":{ "style":"grid-column:14; grid-row:17; bottom: 30%; right: 5%;" },
          "pos-45":{ "style":"grid-column:15; grid-row:16; top: 15%; right: 30%;" },
          "pos-46":{ "style":"grid-column:16; grid-row:16; bottom: 50%; right: 60%;" },
          "pos-47":{ "style":"grid-column:16; grid-row:15; bottom: 15%; left: 5%;" },
          "pos-48":{ "style":"grid-column:17; grid-row:14; right: 30%;" },
          "pos-49":{ "style":"grid-column:17; grid-row:13; left: 20%;" },
          "pos-50":{ "style":"grid-column:18; grid-row:12; right: 40%;" },
          "pos-51":{ "style":"grid-column:18; grid-row:11; right: 10%;" },
          "pos-52":{ "style":"grid-column:18; grid-row:10;" }      
        
      }', NULL
    )
  ;
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'kin%'; INSERT INTO `app_tab` VALUES
    -- tzolkin
    ( 'hol','kin_tzo', 
      '{ 
        "sec":{ "style":"grid:repeat(20,1fr)/repeat(13,1fr); grid-auto-flow:column;" }

      }','{
        "sec":{ "kin-sel": 1, "kin-ton": 0 },
        "pos":{ "ima": "hol.ton.ide", "col": "", "num": "hol.kin.ide" }, 
        "opc": [ "pag", "par" ],
        "pag": { "kin": 1 }
      }'
    ),-- parejas del oráculo
    ( 'hol','kin_par', 
      '{ 
        "sec":{ "style":"border: 1px solid var(--col_ver); border-radius: 50%;" }

      }', NULL
    ),-- nave del tiempo
    ( 'hol','kin_nav', 
        '{
          "sec": {},

          "pos-00":{ "style":"font-size:.5rem" }

        }','{
          "sec": { "cas-pos":1, "cas-bor": 0, "cas-col": 1, "cas-orb": 0, "ton-col":0 },
          "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
          "opc": [ "par", "pul" ]
        }'
      ),
      ( 'hol','kin_nav_cas', 
        '{ 
          "cas":{ "style":"padding: .2rem;" }

        }', NULL
      ),
      ( 'hol','kin_nav_ond', 
        '{ 
          "ond":{ "style":"grid-gap: .2rem;" }

        }', NULL
    ),-- trayectoria armónica
    ( 'hol','kin_arm', 
        '{
          "sec":{}

        }', '{
          "sec": { "sel-arm_tra-bor": 0, "sel-arm_cel-pos": 1, "sel-arm_cel-bor": 0, "sel-arm_cel-col": 0},
          "pos": { "ima": "hol.sel.ide", "col": "", "num": "" },
          "opc": [ "par", "pul" ]
        }'
      ),
      ( 'hol','kin_arm_tra',
        '{ 
          "tra":{ "style":"border-radius: 50%;" }

        }', NULL
      ),
      ( 'hol','kin_arm_cel',
        '{
          "cel":{ "style":"grid-gap:.15rem;" }

        }', NULL
    ),-- estacion galáctica
    ( 'hol','kin_cro', 
        '{ 
          "sec":{}

        }','{
          "sec": { "cas-pos": 1, "cas-orb": 1, "ton-col":1, "sel-cro_ele-pos": 1 },
          "pos": { "ima": "hol.sel.ide", "col": "", "num": "" },
          "opc": [ "par", "pul" ]
        }'
      ),
      ( 'hol','kin_cro_est', 
        '{
        }', NULL
      ),
      ( 'hol','kin_cro_ele', 
        '{
          "rot-ton":[ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
          "rot-cas":[ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
        }', NULL
    )
  ;
  DELETE FROM `app_tab` WHERE `esq`='hol' AND `est` LIKE 'psi%'; INSERT INTO `app_tab` VALUES
    -- anillo solar
    ( 'hol','psi_ban', 
      '{
      }', '{
        "sec": { "lun-cab":1, "lun-hep":1 },
        "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
        "opc": [ "pag", "par", "pul" ]
      }'
    ),-- 4 estaciones
    ( 'hol','psi_est', 
      '{ 
      }','{
        "sec": { "cas-pos": 1, "cas-orb": 0, "ton-col": 0 },
        "pos": { "ima": "hol.rad.ide", "col": "", "num": "" },
        "opc": [ "par", "pul" ]
      }'
    ),-- 13 lunas
    ( 'hol','psi_lun', 
      '{
      }','{
        "sec": { "lun-cab":1, "lun-hep":1, "lun-rad": 1 },
        "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
        "opc": [ "pul", "par" ]
      }'
    ),-- 52 heptadas
    ( 'hol','psi_hep', 
      '{
      }', NULL
    ),-- banco-psi
    ( 'hol','psi_tzo', 
      '{
        "sec":{ "style": "grid-template-columns: repeat(4,1fr);" },
        
        "tzo-5":{ "style":"transform: rotate(180deg);" },
        "tzo-6":{ "style":"transform: rotate(180deg);" },
        "tzo-7":{ "style":"transform: rotate(180deg);" },
        "tzo-8":{ "style":"transform: rotate(180deg);" }
        
      }','{
        "pos": { "ima": "hol.kin.ide", "col": "", "num": "" },
        "opc": [ "pag", "par" ],
        "pag": { "kin": 1 }
      }'
    )
  ;
--