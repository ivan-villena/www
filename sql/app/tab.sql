-- Active: 1663730672989@@127.0.0.1@3306@_api

-- Holon
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol'
  ;
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'uni%';
  INSERT INTO `_api`.`app_tab` VALUES

    -- holon solar
    ( 'hol','uni_sol', 
      '{

        "sec":{ 
          "tab":"uni_sol", 
          "style":"grid: repeat(9,1fr)/repeat(9,1fr); width: 37rem; height: 38rem;" 
        },

        "fon":{ "style":"grid-column: 1/sp; grid-row: 1/sp; width: 100%; height: 100%;" },
        "fon-map":{ "fon":"map", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/map.png);" },
        "fon-ato":{ "fon":"ato", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/ato.png);" },
        "fon-res":{ "fon":"res", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/res.png);" },
        "fon-cel":{ "fon":"cel", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/cel.png);" },
        "fon-cir":{ "fon":"cir", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/cir.png);" },
        "fon-pla":{ "fon":"pla", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/sol/pla.png);" },

        "pla":{ "style":"position: relative; width: 110%; height: 100%;" },
        "pla-10":{ "pla":"10", "style":"grid-column:3/5; grid-row:2; transform: rotate( 053deg); top: 25%; left: 15%;" },
        "pla-09":{ "pla":"09", "style":"grid-column:2/4; grid-row:3; transform: rotate( 053deg); left: 17%;" },
        "pla-08":{ "pla":"08", "style":"grid-column:2/4; grid-row:5; transform: rotate( 340deg); top: 25%; right: 20%;" },
        "pla-07":{ "pla":"07", "style":"grid-column:2/4; grid-row:6; transform: rotate( 340deg); top: 45%;" },
        "pla-06":{ "pla":"06", "style":"grid-column:4/6; grid-row:8; transform: rotate( 270deg); bottom: 20%; right: 5%;" },
        "pla-05":{ "pla":"05", "style":"grid-column:5/7; grid-row:8; transform: rotate( 270deg); bottom: 20%; left: 7%;" },
        "pla-04":{ "pla":"04", "style":"grid-column:7/9; grid-row:6; transform: rotate( 018deg); top: 35%; left: 3%;" },
        "pla-03":{ "pla":"03", "style":"grid-column:7/9; grid-row:5; transform: rotate( 018deg); top: 15%; left: 20%;" },
        "pla-02":{ "pla":"02", "style":"grid-column:6/8; grid-row:3; transform: rotate( 305deg); bottom: 5%; left: 32%;" },
        "pla-01":{ "pla":"01", "style":"grid-column:6/8; grid-row:2; transform: rotate( 305deg); top: 30%; right: 18%;" },

        "sel":{ "style":"position: relative; width: 110%; height: 110%; border-radius: 5%;" },      
        "sel-20":{ "sel":"20", "style":"grid-column:3; grid-row:2; transform: rotate( 053deg); bottom: 22%; left: 45%;" },
        "sel-01":{ "sel":"01", "style":"grid-column:2; grid-row:3; transform: rotate( 054deg); bottom: 49%; left: 47%;" },
        "sel-02":{ "sel":"02", "style":"grid-column:1; grid-row:6; transform: rotate( 340deg); bottom: 54%; left: 55%;" },
        "sel-03":{ "sel":"03", "style":"grid-column:2; grid-row:7; transform: rotate( 340deg); bottom: 39%; right: 5%;" },
        "sel-04":{ "sel":"04", "style":"grid-column:4; grid-row:8; transform: rotate( 270deg); top: 35%; left: 41%;" },
        "sel-05":{ "sel":"05", "style":"grid-column:6; grid-row:8; transform: rotate( 270deg); top: 35%; right: 37%;" },
        "sel-06":{ "sel":"06", "style":"grid-column:8; grid-row:6; transform: rotate( 196deg); top: 50%; left: 13%;" },
        "sel-07":{ "sel":"07", "style":"grid-column:8; grid-row:5; transform: rotate( 196deg); top: 32%; left: 47%;" },
        "sel-08":{ "sel":"08", "style":"grid-column:7; grid-row:2; transform: rotate( 125deg); top: 44%; left: 48%;" },
        "sel-09":{ "sel":"09", "style":"grid-column:6; grid-row:2; transform: rotate( 125deg); bottom: 26%; left: 50%;" },
        "sel-10":{ "sel":"10", "style":"grid-column:6; grid-row:3; transform: rotate( 306deg); bottom: 28%; right: 20%;" },
        "sel-11":{ "sel":"11", "style":"grid-column:7; grid-row:3; transform: rotate( 306deg); top: 40%; right: 20%;" },
        "sel-12":{ "sel":"12", "style":"grid-column:7; grid-row:5; transform: rotate( 017deg); bottom: 2%; left: 32%;" },
        "sel-13":{ "sel":"13", "style":"grid-column:7; grid-row:6; transform: rotate( 017deg); top: 14%; right: 3%;" },
        "sel-14":{ "sel":"14", "style":"grid-column:6; grid-row:7; transform: rotate( 090deg); top: 15%; right: 35%;" },
        "sel-15":{ "sel":"15", "style":"grid-column:4; grid-row:7; transform: rotate( 090deg); top: 15%; left: 40%;" },
        "sel-16":{ "sel":"16", "style":"grid-column:3; grid-row:6; transform: rotate( 160deg); top: 20%; left: 10%;" },
        "sel-17":{ "sel":"17", "style":"grid-column:3; grid-row:5; transform: rotate( 160deg); top: 5%; right: 30%;" },
        "sel-18":{ "sel":"18", "style":"grid-column:3; grid-row:4; transform: rotate( 234deg); bottom: 52%; left: 18%;" },
        "sel-19":{ "sel":"19", "style":"grid-column:4; grid-row:3; transform: rotate( 234deg); bottom: 25%; left: 15%;" },

        "sel-fic":{ "style":"width: 75%; height: 75%;" } 
        
      }', NULL, NULL
    ),
    -- holon planetario
    ( 'hol','uni_pla', '
      { 
        
        "sec":{ 
          "tab":"uni_pla",
          "style":"grid: repeat(5,1fr)/repeat(9,1fr); width: 40rem; height: 28rem;" 
        },

        "fon":{ "style":"grid-column:2/10; grid-row:1/6; width: 100%; height: 100%;" },
        "fon-map":{ "fon":"map", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/pla/map.png);" },
        "fon-sel":{ "fon":"sel", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/pla/sel.png);" },
        "fon-res":{ "fon":"res", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/pla/res.png);" },
        "fon-flu":{ "fon":"flu", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/pla/flu.png);" },

        "fam":{ "style":"height: 80%; width: 80%;" },
        "fam-1":{ "fam":"1", "style":"grid-column:1/2; grid-row:2;" },
        "fam-2":{ "fam":"2", "style":"grid-column:1/2; grid-row:3;" },
        "fam-3":{ "fam":"3", "style":"grid-column:1/2; grid-row:4;" },
        "fam-4":{ "fam":"4", "style":"grid-column:1/2; grid-row:5;" },
        "fam-5":{ "fam":"5", "style":"grid-column:1/2; grid-row:1;" },

        "sel":{ "style":"position: relative; width: 150%; height: 115%; border-radius: 50%;" },
        "sel-20":{ "sel":"20", "style":"grid-column:6; grid-row:1; top: 25%;" },
        "sel-05":{ "sel":"05", "style":"grid-column:8; grid-row:1; top: 25%;" },
        "sel-10":{ "sel":"10", "style":"grid-column:2; grid-row:1; top: 25%; left: 20%;" },
        "sel-15":{ "sel":"15", "style":"grid-column:4; grid-row:1; top: 25%;" },
        "sel-01":{ "sel":"01", "style":"grid-column:7; grid-row:2; top: 20%;" },
        "sel-06":{ "sel":"06", "style":"grid-column:9; grid-row:2; top: 20%;" },
        "sel-11":{ "sel":"11", "style":"grid-column:3; grid-row:2; top: 20%; left: 15%;" },
        "sel-16":{ "sel":"16", "style":"grid-column:5; grid-row:2; top: 20%; left: 10%;" },
        "sel-02":{ "sel":"02", "style":"grid-column:8; grid-row:3; bottom: 10%;" },
        "sel-07":{ "sel":"07", "style":"grid-column:2; grid-row:3; bottom: 10%; left: 25%;" },
        "sel-12":{ "sel":"12", "style":"grid-column:4; grid-row:3; bottom: 10%; left: 15%;" },
        "sel-17":{ "sel":"17", "style":"grid-column:6; grid-row:3; bottom: 10%;" },
        "sel-03":{ "sel":"03", "style":"grid-column:9; grid-row:4; bottom: 20%;" },
        "sel-08":{ "sel":"08", "style":"grid-column:3; grid-row:4; bottom: 20%; left: 15%;" },
        "sel-13":{ "sel":"13", "style":"grid-column:5; grid-row:4; bottom: 20%; left: 15%;" },
        "sel-18":{ "sel":"18", "style":"grid-column:7; grid-row:4; bottom: 20%;" },
        "sel-04":{ "sel":"04", "style":"grid-column:2; grid-row:5; bottom: 45%; left: 30%;" },
        "sel-09":{ "sel":"09", "style":"grid-column:4; grid-row:5; bottom: 45%; left: 20%;" },
        "sel-14":{ "sel":"14", "style":"grid-column:6; grid-row:5; bottom: 45%; left: 8%;" },
        "sel-19":{ "sel":"19", "style":"grid-column:8; grid-row:5; bottom: 45%; left: 5%;" },

        "sel-fic":{ "style":"width: 30%;" }  

      }', NULL, NULL
    ),
    -- holon humano
    ( 'hol','uni_hum', 
      '{

        "sec":{ 
          "tab":"uni_hum",
          "style":"grid: repeat(20,1fr)/repeat(13,1fr); width: 19rem; height: 35rem;"
        },

        "fon":{ "style":"grid-column: 1/sp; grid-row: 1/sp; width: 100%; height: 100%;" },
        "fon-map":{ "fon":"map", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/hum/map.png);" },
        "fon-res":{ "fon":"res", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/hum/res.png);" },
        "fon-cir":{ "fon":"cir", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/hum/cir.png);" },
        "fon-cen":{ "fon":"cen", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/hum/cen.png);" },
        "fon-ext":{ "fon":"ext", "style":"background: center/contain no-repeat url(http://localhost/_/hol/tab/hum/ext.png);" },

        "raz":{ "style":"position: relative;" },
        "raz-4":{ "raz":"4", "style":"grid-column:2/5; grid-row:10/12; left:5%; top:5%;" },
        "raz-1":{ "raz":"1", "style":"grid-column:5/7; grid-row:18/20; left: 30%; top:5%; z-index: -1;" },
        "raz-2":{ "raz":"2", "style":"grid-column:11/13; grid-row:10/12; right:35%; top:5%;" },
        "raz-3":{ "raz":"3", "style":"grid-column:8/10; grid-row:18/20; right:20%; top:5%; z-index: -1;" },

        "fam":{ "style":"" },
        "fam-5":{ "fam":"5", "style":"grid-column:6/9; grid-row:1/3;" },
        "fam-1":{ "fam":"1", "style":"grid-column:6/9; grid-row:3/6; z-index:1;" },
        "fam-2":{ "fam":"2", "style":"grid-column:6/9; grid-row:5/8;" },
        "fam-3":{ "fam":"3", "style":"grid-column:6/9; grid-row:7/10;" },
        "fam-4":{ "fam":"4", "style":"grid-column:6/9; grid-row:10/12;" },

        "ton":{ "style":"position:relative; width:20px; height:20px; border-radius: 20%;" },
        "ton-01":{ "ton":"01", "style":"grid-column:05; grid-row:18; bottom: 30%;" },
        "ton-02":{ "ton":"02", "style":"grid-column:05; grid-row:15; right: 15%;" },
        "ton-03":{ "ton":"03", "style":"grid-column:05; grid-row:11; right: 20%;" },
        "ton-04":{ "ton":"04", "style":"grid-column:03; grid-row:10; bottom: 40%;" },
        "ton-05":{ "ton":"05", "style":"grid-column:04; grid-row:08; bottom: 35%; right: 40%;" },
        "ton-06":{ "ton":"06", "style":"grid-column:04; grid-row:05;" },
        "ton-07":{ "ton":"07", "style":"grid-column:07; grid-row:04; bottom: 35%;" },
        "ton-08":{ "ton":"08", "style":"grid-column:10; grid-row:05;" },
        "ton-09":{ "ton":"09", "style":"grid-column:10; grid-row:08; bottom: 35%; left: 40%;" },
        "ton-10":{ "ton":"10", "style":"grid-column:11; grid-row:10; bottom: 40%;" },
        "ton-11":{ "ton":"11", "style":"grid-column:09; grid-row:11; left: 20%;" },
        "ton-12":{ "ton":"12", "style":"grid-column:09; grid-row:15; left: 35%;" },
        "ton-13":{ "ton":"13", "style":"grid-column:09; grid-row:18; bottom: 30%; left: 30%;" },

        "sel":{ "style":"width:15px; height:15px; position:relative; " },
        "sel-20":{ "sel":"20", "style":"grid-column:1;  grid-row:10; top:40%; right:25%;" },
        "sel-01":{ "sel":"01", "style":"grid-column:1;  grid-row:11; top:30%; right:17%;" },
        "sel-02":{ "sel":"02", "style":"grid-column:1;  grid-row:12; top:20%; left: 25%;" },
        "sel-03":{ "sel":"03", "style":"grid-column:2;  grid-row:12; top:40%; left: 28%;" },
        "sel-04":{ "sel":"04", "style":"grid-column:3;  grid-row:12; top:25%; left: 30%;" },
        "sel-10":{ "sel":"10", "style":"grid-column:13; grid-row:10; top:35%; left:20%;" },
        "sel-11":{ "sel":"11", "style":"grid-column:13; grid-row:11; top:30%; left:15%;" },
        "sel-12":{ "sel":"12", "style":"grid-column:13; grid-row:12; top:20%; right:25%;" },
        "sel-13":{ "sel":"13", "style":"grid-column:12; grid-row:12; top:43%; right:25%;" },
        "sel-14":{ "sel":"14", "style":"grid-column:11; grid-row:12; top:30%; right:25%;" },
        "sel-05":{ "sel":"05", "style":"grid-column:6;  grid-row:20; top:15%; left:25%;" },
        "sel-06":{ "sel":"06", "style":"grid-column:5;  grid-row:20; top:35%; left:20%;" },
        "sel-07":{ "sel":"07", "style":"grid-column:4;  grid-row:20; top:25%; left:20%;" },
        "sel-08":{ "sel":"08", "style":"grid-column:4;  grid-row:19; top:35%; left:5%;" },
        "sel-09":{ "sel":"09", "style":"grid-column:4;  grid-row:19; bottom:50%; left:20%;" },
        "sel-15":{ "sel":"15", "style":"grid-column:8;  grid-row:20; top:20%;" },
        "sel-16":{ "sel":"16", "style":"grid-column:9;  grid-row:20; top:35%;" },
        "sel-17":{ "sel":"17", "style":"grid-column:10; grid-row:20; top:25%;" },
        "sel-18":{ "sel":"18", "style":"grid-column:10; grid-row:19; top:35%; left:15%;" },
        "sel-19":{ "sel":"19", "style":"grid-column:10; grid-row:19; bottom:50%;" }  

      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'arm%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- cuadrado-cubo
    ( 'hol','arm', 
      '{
        
        "sec":{ 
          "tab":"arm", 
          "style":"grid: repeat(2,1fr)/repeat(2,1fr); border-radius: 15%;" 
        },

        "pos-0":{ "pos":"0", "style":"grid-column:3/5; grid-row:3/5; border-radius: 50%; padding: .1rem;" },
        "pos-1":{ "pos":"1", "style":"grid-column:4/7; grid-row:1/4;" },
        "pos-2":{ "pos":"2", "style":"grid-column:1/4; grid-row:1/4;" },
        "pos-3":{ "pos":"3", "style":"grid-column:1/4; grid-row:4/8;" },
        "pos-4":{ "pos":"4", "style":"grid-column:4/7; grid-row:4/8;" }   

      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'cro%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- cruz-atomo
    ( 'hol','cro', 
      '{

        "sec":{ 
          "tab":"cro", 
          "style":"grid: repeat(3,1fr) / repeat(3,1fr); border-radius: 50%;" 
        },

        "pos-0":{ "pos":"0", "style":"grid-column:2; grid-row:2;" },
        "pos-1":{ "pos":"1", "style":"grid-column:3; grid-row:2;" },
        "pos-2":{ "pos":"2", "style":"grid-column:2; grid-row:1;" },
        "pos-3":{ "pos":"3", "style":"grid-column:1; grid-row:2;" },
        "pos-4":{ "pos":"4", "style":"grid-column:2; grid-row:3;" },
        "pos-5":{ "pos":"5", "style":"grid-column:2; grid-row:2;" }   

      }', NULL, NULL
    ),
    -- pentagono tipo petalos
    ( 'hol','cro_cir', 
      '{

        "sec":{ 
          "tab":"cro_cir", 
          "style":"grid: repeat(3,1fr) / repeat(3,1fr); border-radius: 50%;" 
        },

        "pos":{ "style":"position: relative;" },
        "pos-0":{ "pos":"0", "style":"grid-column:2/3; grid-row:2/3; align-self: center; justify-self: center; justify-content: center; align-items: center; width: 150%; height: 150%;" },
        "pos-1":{ "pos":"1", "style":"grid-column:1/2; grid-row:1/2; top: 3%;    left: 28%; transform: rotate(145deg);" },
        "pos-2":{ "pos":"2", "style":"grid-column:1/2; grid-row:2/3; top: 38%;   left:-13%; transform: rotate(070deg);" },
        "pos-3":{ "pos":"3", "style":"grid-column:2/3; grid-row:3/4; top: 20%;" },
        "pos-4":{ "pos":"4", "style":"grid-column:3/4; grid-row:2/3; top: 35.5%; left: 12%; transform: rotate(287deg);" },
        "pos-5":{ "pos":"5", "style":"grid-column:3/4; grid-row:1/2; top: 3%;    left:-30%; transform: rotate(217deg);" }      

      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'rad%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- heptágono
    ( 'hol','rad', 
      '{ 

        "sec":{ 
          "tab": "rad", 
          "style":"grid: repeat(4,1fr)/repeat(4,1fr); background: center/contain no-repeat url(http://localhost/_/hol/ima/rad.png);"
        },

        "pos":{ "style":"position: relative;" },
        "pos-1":{ "pos":"1", "style":"grid-column:2; grid-row:1; top:     15%; left:  50%;" },
        "pos-2":{ "pos":"2", "style":"grid-column:2; grid-row:4; bottom:  15%; left:  53%; " },
        "pos-3":{ "pos":"3", "style":"grid-column:1; grid-row:2; bottom:  20%; left:  25%;" },
        "pos-4":{ "pos":"4", "style":"grid-column:4; grid-row:3; top:     20%; right: 25%;" },
        "pos-5":{ "pos":"5", "style":"grid-column:1; grid-row:3; top:     20%; left:  30%;" },
        "pos-6":{ "pos":"6", "style":"grid-column:4; grid-row:2; bottom:  20%; right: 35%;" },
        "pos-7":{ "pos":"7", "style":"grid-column:2; grid-row:2; top:     50%; left:  50%;" } 
        
      }', NULL, NULL
    ),
    -- atomo por quantum
    ( 'hol','rad_ato', 
      '{
      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'ton%';
  INSERT INTO `_api`.`app_tab` VALUES

    ( 'hol','ton', 
      '{
        "sec":{ 
          "tab":"ton", 
          "style":"grid: repeat(5,1fr)/repeat(5,1fr); justify-items: start;" 
        },
        "ima":{
          "sec":"ima",
          "style":"z-index: 1; grid-column:1/sp; grid-row:1/sp; width:100%; height:100%;" 
        },
        "fon":{           
          "sec":"fon", 
          "style":"z-index: 2; grid-column:1/sp; grid-row:1/sp; width:95%; height:91%; border-radius:50%;" 
        },
        "ond":{ 
          "sec":"ond",
          "style":"z-index: 3; grid-column:1/sp; grid-row:1/sp; width: 100%; height: 100%;"
        },
        "pos":{ 
          "style":"z-index : 4;" 
        },
        "pos-01":{ "pos":"01", "style":"grid-column:1/2; grid-row:1/2;" },
        "pos-02":{ "pos":"02", "style":"grid-column:1/2; grid-row:2/3;" },
        "pos-03":{ "pos":"03", "style":"grid-column:1/2; grid-row:3/4;" },
        "pos-04":{ "pos":"04", "style":"grid-column:1/2; grid-row:4/5;" },
        "pos-05":{ "pos":"05", "style":"grid-column:1/2; grid-row:5/6;" },
        "pos-06":{ "pos":"06", "style":"grid-column:2/3; grid-row:5/6;" },
        "pos-07":{ "pos":"07", "style":"grid-column:3/4; grid-row:5/6;" },
        "pos-08":{ "pos":"08", "style":"grid-column:4/5; grid-row:5/6;" },
        "pos-09":{ "pos":"09", "style":"grid-column:5/6; grid-row:5/6;" },
        "pos-10":{ "pos":"10", "style":"grid-column:5/6; grid-row:4/5;" },
        "pos-11":{ "pos":"11", "style":"grid-column:5/6; grid-row:3/4;" },
        "pos-12":{ "pos":"12", "style":"grid-column:5/6; grid-row:2/3;" },
        "pos-13":{ "pos":"13", "style":"grid-column:4/5; grid-row:2/3;" }   
        
      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'sel%';
  INSERT INTO `_api`.`app_tab` VALUES

    ( 'hol','sel', 
      '{ 

        "sec":{ 
          "tab":"sel", 
          "style":"display: grid; grid: repeat(4,1fr)/repeat(5,1fr); grid-gap: .5rem;" 
        },

        "pos":{ 
          "style":"width: 1.9rem; height: 1.9rem;"
        }    

      }', NULL, NULL)
    ,-- parejas del oráculo
    ( 'hol','sel_par', 
      '{ 

        "sec":{ 
          "tab":"uni_par-sel",
          "style":"border: 1px solid var(--col_ver); border-radius: 50%;"      
        },

        "pos":{ 
          "style":"width: 1.9rem; height: 1.9rem;" 
        } 
        
      }', NULL, NULL)
    ,-- colocacion armónica
    ( 'hol','sel_arm', 
      '{ 
        "sec":{ 
          "tab":"sel_arm", 
          "style":"grid: repeat(5,1fr)/repeat(6,1fr); grid-auto-flow: column; border-radius: 20%;" 
        },
        "raz-1":{ "raz":"1", "style":"grid-column:1/2; grid-row:2/3;" },
        "raz-2":{ "raz":"2", "style":"grid-column:1/2; grid-row:3/4;" },
        "raz-3":{ "raz":"3", "style":"grid-column:1/2; grid-row:4/5;" },
        "raz-4":{ "raz":"4", "style":"grid-column:1/2; grid-row:5/6;" },

        "cel-1":{ "cel":"1", "style":"grid-column:2/3; grid-row:1/2;" },
        "cel-2":{ "cel":"2", "style":"grid-column:3/4; grid-row:1/2;" },
        "cel-3":{ "cel":"3", "style":"grid-column:4/5; grid-row:1/2;" },
        "cel-4":{ "cel":"4", "style":"grid-column:5/6; grid-row:1/2;" },
        "cel-5":{ "cel":"5", "style":"grid-column:6/7; grid-row:1/2;" },

        "pos":{ 
          "style":"width: 8rem; height: 8rem;" 
        }
        
      }', NULL, NULL)
      ,-- trayectoria
      ( 'hol','sel_arm_tra', 
        '{ 
          "sec":{
            "tab":"sel_arm_tra"
          },
          "pos":{ 
            "style":"width: 7.8rem; height: 7.8rem;" 
          }
        }', NULL, NULL)
    ,-- colocacion cromática
    ( 'hol','sel_cro', 
      '{ 

        "sec":{ 
          "tab":"sel_cro",
          "style":"grid-auto-flow: column; border-radius: 20%;" 
        },      

        "fam-5":{ "fam":"5", "style":"grid-column:1/2; grid-row:2/3;" },
        "fam-1":{ "fam":"1", "style":"grid-column:1/2; grid-row:3/4;" }, 
        "fam-2":{ "fam":"2", "style":"grid-column:1/2; grid-row:4/5;" }, 
        "fam-3":{ "fam":"3", "style":"grid-column:1/2; grid-row:5/6;" }, 
        "fam-4":{ "fam":"4", "style":"grid-column:1/2; grid-row:6/7;" },

        "ele-4":{ "ele":"4", "style":"grid-column:2/3; grid-row:1/2;" },
        "ele-1":{ "ele":"1", "style":"grid-column:3/4; grid-row:1/2;" }, 
        "ele-2":{ "ele":"2", "style":"grid-column:4/5; grid-row:1/2;" }, 
        "ele-3":{ "ele":"3", "style":"grid-column:5/6; grid-row:1/2;" },

        "pos":{ 
          "style":"width: 7rem; height: 7rem;" 
        }  
        
      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'lun%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- forma de almanque
    ( 'hol','lun', 
      '{ 

        "sec":{ 
          "tab":"lun"
        },

        "pos":{ 
          "style":"width: 3rem; height: 3rem;"
        }     
      }', NULL, NULL
    )
  ;  
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'cas%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- fractal en cruz
    ( 'hol','cas', 
      '{ 
        "sec":{ 
          "tab":"cas",
          "style":"grid: repeat(11,1fr)/repeat(11,1fr); align-items: center; justify-items: center; border-radius: 10%;" 
        },
        "ima":{
          "sec":"ima",
          "style":"z-index: 1; width:100%; height:100%;"
        },        
        "fon":{   
          "sec":"fon",
          "style":"z-index: 2; width:95%; height:91%; border-radius:50%;" 
        },
        "orb":{
          "sec":"orb",
          "style":"z-index: 3; border-radius: 50%; border: 1px solid green;" 
        },
        "orb-1":{ "sec-orb":"1", "style":"grid-column:5/8;  grid-row:5/8;  width: 70%; height: 70%;" },
        "orb-2":{ "sec-orb":"2", "style":"grid-column:4/9;  grid-row:4/9;  width: 82%; height: 82%;" },
        "orb-3":{ "sec-orb":"3", "style":"grid-column:3/10; grid-row:3/10; width: 86%; height: 86%;" },
        "orb-4":{ "sec-orb":"4", "style":"grid-column:2/11; grid-row:2/11; width: 89%; height: 89%;" },
        "orb-5":{ "sec-orb":"5", "style":"grid-column:1/12; grid-row:1/12; width: 91%; height: 91%;" },

        "ond":{
          "sec":"ond",
          "style":"z-index: 4; width: 100%; height: 100%;"
        },
        "ond-1":{ "sec-ond":"1", "style":"grid-column:7/12; grid-row:2/7 ; transform: rotate(270deg);" },
        "ond-2":{ "sec-ond":"2", "style":"grid-column:2/7 ; grid-row:1/6 ; transform: rotate(180deg);" },
        "ond-3":{ "sec-ond":"3", "style":"grid-column:1/6 ; grid-row:6/11; transform: rotate(090deg);" },
        "ond-4":{ "sec-ond":"4", "style":"grid-column:6/11; grid-row:7/12;" },

        "pos":{ 
          "style":"z-index: 5;" 
        },  
          "pos-00":{ "pos":"00", "style":"grid-column:06; grid-row:06; width:80%; height:80%; border-radius:50%;" },
          "pos-01":{ "pos":"01", "style":"grid-column:07; grid-row:06;" },
          "pos-02":{ "pos":"02", "style":"grid-column:08; grid-row:06;" },
          "pos-03":{ "pos":"03", "style":"grid-column:09; grid-row:06;" },
          "pos-04":{ "pos":"04", "style":"grid-column:10; grid-row:06;" },
          "pos-05":{ "pos":"05", "style":"grid-column:11; grid-row:06;" },
          "pos-06":{ "pos":"06", "style":"grid-column:11; grid-row:05;" },
          "pos-07":{ "pos":"07", "style":"grid-column:11; grid-row:04;" },
          "pos-08":{ "pos":"08", "style":"grid-column:11; grid-row:03;" },
          "pos-09":{ "pos":"09", "style":"grid-column:11; grid-row:02;" },
          "pos-10":{ "pos":"10", "style":"grid-column:10; grid-row:02;" },
          "pos-11":{ "pos":"11", "style":"grid-column:09; grid-row:02;" },
          "pos-12":{ "pos":"12", "style":"grid-column:08; grid-row:02;" },
          "pos-13":{ "pos":"13", "style":"grid-column:08; grid-row:03;" },
          "pos-14":{ "pos":"14", "style":"grid-column:06; grid-row:05;" },
          "pos-15":{ "pos":"15", "style":"grid-column:06; grid-row:04;" },
          "pos-16":{ "pos":"16", "style":"grid-column:06; grid-row:03;" },
          "pos-17":{ "pos":"17", "style":"grid-column:06; grid-row:02;" },
          "pos-18":{ "pos":"18", "style":"grid-column:06; grid-row:01;" },
          "pos-19":{ "pos":"19", "style":"grid-column:05; grid-row:01;" },
          "pos-20":{ "pos":"20", "style":"grid-column:04; grid-row:01;" },
          "pos-21":{ "pos":"21", "style":"grid-column:03; grid-row:01;" },
          "pos-22":{ "pos":"22", "style":"grid-column:02; grid-row:01;" },
          "pos-23":{ "pos":"23", "style":"grid-column:02; grid-row:02;" },
          "pos-24":{ "pos":"24", "style":"grid-column:02; grid-row:03;" },
          "pos-25":{ "pos":"25", "style":"grid-column:02; grid-row:04;" },
          "pos-26":{ "pos":"26", "style":"grid-column:03; grid-row:04;" },
          "pos-27":{ "pos":"27", "style":"grid-column:05; grid-row:06;" },
          "pos-28":{ "pos":"28", "style":"grid-column:04; grid-row:06;" },
          "pos-29":{ "pos":"29", "style":"grid-column:03; grid-row:06;" },
          "pos-30":{ "pos":"30", "style":"grid-column:02; grid-row:06;" },
          "pos-31":{ "pos":"31", "style":"grid-column:01; grid-row:06;" },
          "pos-32":{ "pos":"32", "style":"grid-column:01; grid-row:07;" },
          "pos-33":{ "pos":"33", "style":"grid-column:01; grid-row:08;" },
          "pos-34":{ "pos":"34", "style":"grid-column:01; grid-row:09;" },
          "pos-35":{ "pos":"35", "style":"grid-column:01; grid-row:10;" },
          "pos-36":{ "pos":"36", "style":"grid-column:02; grid-row:10;" },
          "pos-37":{ "pos":"37", "style":"grid-column:03; grid-row:10;" },
          "pos-38":{ "pos":"38", "style":"grid-column:04; grid-row:10;" },
          "pos-39":{ "pos":"39", "style":"grid-column:04; grid-row:09;" },
          "pos-40":{ "pos":"40", "style":"grid-column:06; grid-row:07;" },
          "pos-41":{ "pos":"41", "style":"grid-column:06; grid-row:08;" },
          "pos-42":{ "pos":"42", "style":"grid-column:06; grid-row:09;" },
          "pos-43":{ "pos":"43", "style":"grid-column:06; grid-row:10;" },
          "pos-44":{ "pos":"44", "style":"grid-column:06; grid-row:11;" },
          "pos-45":{ "pos":"45", "style":"grid-column:07; grid-row:11;" },
          "pos-46":{ "pos":"46", "style":"grid-column:08; grid-row:11;" },
          "pos-47":{ "pos":"47", "style":"grid-column:09; grid-row:11;" },
          "pos-48":{ "pos":"48", "style":"grid-column:10; grid-row:11;" },
          "pos-49":{ "pos":"49", "style":"grid-column:10; grid-row:10;" },
          "pos-50":{ "pos":"50", "style":"grid-column:10; grid-row:09;" },
          "pos-51":{ "pos":"51", "style":"grid-column:10; grid-row:08;" },
          "pos-52":{ "pos":"52", "style":"grid-column:09; grid-row:08;" } 

      }', NULL, NULL
    ),-- circular
    ( 'hol','cas_cir', 
      '{ 
        "sec":{ 
          "tab":"cas_cir", 
          "style":"grid: repeat(18,1fr)/repeat(18,1fr); border-radius: 50%;" 
        },
        "ima":{
          "sec":"ima",
          "style":"z-index: 1; width:100%; height:100%;"
        },
        "fon":{   
          "sec":"fon",
          "style":"z-index: 2; width:95%; height:91%; border-radius:50%;" 
        }, 
        "orb":{
          "sec":"orb",
          "style":"z-index: 3; width: 100%; height: 100%; border-radius: 50%;"
        },
        "orb-1":{ "sec-orb":"1", "style":"grid-column:9/11; grid-row:9/11;" },
        "orb-2":{ "sec-orb":"2", "style":"grid-column:8/12; grid-row:8/12;" },
        "orb-3":{ "sec-orb":"3", "style":"grid-column:7/13; grid-row:7/13;" },
        "orb-4":{ "sec-orb":"4", "style":"grid-column:6/14; grid-row:6/14;" },
        "orb-5":{ "sec-orb":"5", "style":"grid-column:5/15; grid-row:5/15;" },
        "orb-6":{ "sec-orb":"6", "style":"grid-column:4/16; grid-row:4/16;" },
        "orb-7":{ "sec-orb":"7", "style":"grid-column:3/17; grid-row:3/17;" },
        "orb-8":{ "sec-orb":"8", "style":"grid-column:2/18; grid-row:2/18;" },

        "ond":{
          "sec":"ond",
          "style":"z-index: 3; width: 100%; height: 100%; " 
        },
        "ond-1":{ "sec-ond":"1", "style":"grid-column:10/19; grid-row:01/10; border-radius: 0 100% 0 0; border-bottom: 1.5px solid green;" },
        "ond-2":{ "sec-ond":"2", "style":"grid-column:01/10; grid-row:01/10; border-radius: 100% 0 0 0; border-right:  1.5px solid green;" },
        "ond-3":{ "sec-ond":"3", "style":"grid-column:01/10; grid-row:10/19; border-radius: 0 0 0 100%; border-top:    1.5px solid green;" },
        "ond-4":{ "sec-ond":"4", "style":"grid-column:10/19; grid-row:10/19; border-radius: 0 0 100% 0; border-left:   1.5px solid green;" },
        
        "pos":{ 
          "style":"z-index: 4; position: relative;" 
        },
          "pos-00":{ "pos":"00", "style":"grid-column:09/11; grid-row:09/11; width: 25%; height: 25%;" },
          "pos-01":{ "pos":"01", "style":"grid-column:18; grid-row:09; left:  10%;" },
          "pos-02":{ "pos":"02", "style":"grid-column:18; grid-row:08; left:  05%;" },
          "pos-03":{ "pos":"03", "style":"grid-column:18; grid-row:07; right: 25%;" },
          "pos-04":{ "pos":"04", "style":"grid-column:17; grid-row:06; left: 35%;" },
          "pos-05":{ "pos":"05", "style":"grid-column:17; grid-row:05; right: 20%;  top: 05%;" },
          "pos-06":{ "pos":"06", "style":"grid-column:16; grid-row:04; left: 15%;   top: 15%;" },
          "pos-07":{ "pos":"07", "style":"grid-column:16; grid-row:03; right: 47%;  top: 30%;" },
          "pos-08":{ "pos":"08", "style":"grid-column:15; grid-row:03; right: 20%;  bottom: 30%;" },
          "pos-09":{ "pos":"09", "style":"grid-column:14; grid-row:02;              top: 22%;" },
          "pos-10":{ "pos":"10", "style":"grid-column:13; grid-row:02; left: 15%;   bottom: 30%;" },
          "pos-11":{ "pos":"11", "style":"grid-column:12; grid-row:01; left: 15%;   top: 35%;" },
          "pos-12":{ "pos":"12", "style":"grid-column:11; grid-row:01; left: 15%;   top: 10%;" },
          "pos-13":{ "pos":"13", "style":"grid-column:10; grid-row:01; left: 10%;   bottom: 10%;" },
          "pos-14":{ "pos":"14", "style":"grid-column:09; grid-row:01; bottom: 10%;" },
          "pos-15":{ "pos":"15", "style":"grid-column:08; grid-row:01;" },
          "pos-16":{ "pos":"16", "style":"grid-column:07; grid-row:01; top: 30%;" },
          "pos-17":{ "pos":"17", "style":"grid-column:06; grid-row:02; bottom: 40%;" },
          "pos-18":{ "pos":"18", "style":"grid-column:05; grid-row:02; top: 15%;" },
          "pos-19":{ "pos":"19", "style":"grid-column:04; grid-row:03; bottom: 20%; left: 10%;" },
          "pos-20":{ "pos":"20", "style":"grid-column:03; grid-row:03; top:40%; left: 35%;" },
          "pos-21":{ "pos":"21", "style":"grid-column:03; grid-row:04; top:15%; right: 30%;" },
          "pos-22":{ "pos":"22", "style":"grid-column:02; grid-row:05; left: 15%;" },
          "pos-23":{ "pos":"23", "style":"grid-column:02; grid-row:06; right: 40%;" },
          "pos-24":{ "pos":"24", "style":"grid-column:01; grid-row:07; left: 30%;" },
          "pos-25":{ "pos":"25", "style":"grid-column:01; grid-row:08; left: 10%;" },
          "pos-26":{ "pos":"26", "style":"grid-column:01; grid-row:09;" },
          "pos-27":{ "pos":"27", "style":"grid-column:01; grid-row:10;" },
          "pos-28":{ "pos":"28", "style":"grid-column:01; grid-row:11; left: 10%;" },
          "pos-29":{ "pos":"29", "style":"grid-column:01; grid-row:12; left: 40%;" },
          "pos-30":{ "pos":"30", "style":"grid-column:02; grid-row:13; right: 20%;" },
          "pos-31":{ "pos":"31", "style":"grid-column:02; grid-row:14; left: 30%;" },
          "pos-32":{ "pos":"32", "style":"grid-column:03; grid-row:15; bottom: 15%; right: 5%;" },
          "pos-33":{ "pos":"33", "style":"grid-column:03; grid-row:16; bottom: 50%; left: 60%;" },
          "pos-34":{ "pos":"34", "style":"grid-column:04; grid-row:16; top: 15%; left: 30%;" },
          "pos-35":{ "pos":"35", "style":"grid-column:05; grid-row:17; bottom: 30%; left: 5%;" },
          "pos-36":{ "pos":"36", "style":"grid-column:06; grid-row:17; top: 20%;" },
          "pos-37":{ "pos":"37", "style":"grid-column:07; grid-row:18; bottom: 45%;" },
          "pos-38":{ "pos":"38", "style":"grid-column:08; grid-row:18; bottom: 20%;" },
          "pos-39":{ "pos":"39", "style":"grid-column:09; grid-row:18; bottom: 5%;" },
          "pos-40":{ "pos":"40", "style":"grid-column:10; grid-row:18; bottom: 5%;" },
          "pos-41":{ "pos":"41", "style":"grid-column:11; grid-row:18; bottom: 20%;" },
          "pos-42":{ "pos":"42", "style":"grid-column:12; grid-row:18; bottom: 45%;" },
          "pos-43":{ "pos":"43", "style":"grid-column:13; grid-row:17; top: 20%;" },
          "pos-44":{ "pos":"44", "style":"grid-column:14; grid-row:17; bottom: 30%; right: 5%;" },
          "pos-45":{ "pos":"45", "style":"grid-column:15; grid-row:16; top: 15%; right: 30%;" },
          "pos-46":{ "pos":"46", "style":"grid-column:16; grid-row:16; bottom: 50%; right: 60%;" },
          "pos-47":{ "pos":"47", "style":"grid-column:16; grid-row:15; bottom: 15%; left: 5%;" },
          "pos-48":{ "pos":"48", "style":"grid-column:17; grid-row:14; right: 30%;" },
          "pos-49":{ "pos":"49", "style":"grid-column:17; grid-row:13; left: 20%;" },
          "pos-50":{ "pos":"50", "style":"grid-column:18; grid-row:12; right: 40%;" },
          "pos-51":{ "pos":"51", "style":"grid-column:18; grid-row:11; right: 10%;" },
          "pos-52":{ "pos":"52", "style":"grid-column:18; grid-row:10;" }      
        
      }', NULL, NULL
    )
  ;
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'kin%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- tzolkin
    ( 'hol','kin_tzo', 
      '{ 
        "sec":{ 
          "tab":"kin-tzo", 
          "style":"grid: repeat(20,1fr) / repeat(13,1fr); grid-auto-flow: column;"
        },        
        "pos":{  
          "style":"width: 1.9rem; height: 1.9rem;" 
        }
      }', 
      '{
        "sec":{ "kin-sel": 1, "kin-ton": 0 },

        "pos":{ "ima": "api.hol_ton.ide", "col": "", "num": "api.hol_kin.ide" }, 

        "pag": { "kin": 1 }

      }',
      '[ "pag", "par" ]'
    ),-- parejas del oráculo
    ( 'hol','kin_par', 
      '{ 
        "sec":{ 
          "tab":"uni_par-kin", 
          "style":"border: 1px solid var(--col_ver); border-radius: 50%;" 
        }
      }', NULL, NULL
    ),-- nave del tiempo
    ( 'hol','kin_nav', 
        '{ 
          "sec":{ 
            "tab": "kin_nav", 
            "style":"grid-gap: .15rem;" 
          }, 
          "pos":{ 
            "style":"width: 1.1rem; height: 1.1rem;" 
          },        
          "pos-00":{ 
            "style":"font-size:.5rem" 
          }
        }', '{

          "sec":{ "cas-pos":1, "cas-bor": 0, "cas-col": 1, "cas-orb": 0, "ton-col":0 },

          "pos":{ "ima": "api.hol_kin.ide", "col": "", "num": "" }

        }', '[ "par", "pul" ]'
      ),
      ( 'hol','kin_nav_cas', 
        '{ 
          "cas":{ 
            "tab": "kin_nav_cas", 
            "style":"padding: .2rem;" 
          },
          "pos":{ 
            "style":"width: 4rem; height: 4rem;" 
          }      
        }', NULL, NULL
      ),
      ( 'hol','kin_nav_ond', 
        '{ 
          "ond":{ 
            "tab": "kin_nav_ond", 
            "style":"grid-gap: .2rem;" 
          },         
          "pos":{ 
            "style":"width: 9rem; height: 9rem;"
          }
        }', NULL, NULL
      )
    ,-- estacion galáctica
    ( 'hol','kin_cro', 
        '{ 
          "sec":{ 
            "tab": "kin_cro", "style":"grid-gap: .3rem;" 
          },
          "pos":{ 
            "style":"width: 1rem; height: 1rem;" 
          }
        }', '{

          "sec":{ "cas-pos": 1, "cas-orb": 1, "ton-col":1, "sel-cro_ele-pos": 1 },

          "pos":{ "ima": "api.hol_sel.ide", "col": "", "num": "" }

        }', '[ "par", "pul" ]'
      ),
      ( 'hol','kin_cro_est', 
        '{
          "est":{ 
            "tab": "kin_cro_est", "style":"margin: 0 1rem; grid-gap: .2rem" 
          },
          "pos":{ 
            "style":"width: 2.8rem; height: 2.8rem;" 
          }
        }', NULL, NULL
      ),
      ( 'hol','kin_cro_ele', 
        '{ 
          "ele":{ 
            "tab": "kin_cro_ele" 
          },
          "pos":{ 
            "style":"width: 13rem; height: 13rem;" 
          },
          "pos-0":{ "style":"width: 150%; height: 150%; color: black;" },

          "rot-ton":[ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
          "rot-cas":[ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
        }', NULL, NULL
      )
    ,-- trayectoria armónica
    ( 'hol','kin_arm', 
        '{ 
          "sec":{ 
            "tab": "kin_arm", "style":"grid-gap: .3rem;" 
          }, 
          "pos":{ 
            "style":"width: 1.2rem; height: 1.2rem;" 
          }
        }', '{

          "sec":{ "sel-arm_tra-bor": 0, "sel-arm_cel-pos": 1, "sel-arm_cel-bor": 0, "sel-arm_cel-col": 0},

          "pos":{ "ima": "api.hol_sel.ide", "col": "", "num": "" }

        }', '[ "par", "pul" ]'
      ),
      ( 'hol','kin_arm_tra',
        '{ 
          "tra":{ 
            "tab": "kin_arm_tra", "style":"border-radius: 50%;" 
          },
          "pos":{ 
            "style":"width: 7.8rem; height: 7.8rem;" 
          }
        }', NULL, NULL
      ),
      ( 'hol','kin_arm_cel',
        '{
          "cel":{ 
            "tab": "kin_arm_cel", "style":"grid-gap: .15rem;" 
          },
          "pos":{ 
            "style":"width: 18rem; height: 18rem;" 
          }
        }', NULL, NULL
      )
  ;
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'psi%';
  INSERT INTO `_api`.`app_tab` VALUES
    -- anillo solar
    ( 'hol','psi_ban', 
      '{
        "sec":{ 
          "tab": "psi", "style": "grid-gap: .5rem;" 
        },
        "pos":{ 
          "style": "width: 1.2rem; height: 1.2rem;" 
        }
      }', '{

        "sec":{ "lun-cab":1, "lun-hep":1 },

        "pos":{ "ima": "api.hol_kin.ide", "col": "", "num": "" }

      }', '[ "pag", "par", "pul" ]'
    ),-- 4 estaciones
    ( 'hol','psi_est', 
      '{ 

        "sec":{ "tab": "psi_est" },

        "pos":{ "style": "width: .855rem; height: .855rem;" }

      }', '{

        "sec":{ "cas-pos": 1, "cas-orb": 0, "ton-col": 0 },

        "pos":{ "ima": "api.hol_rad.ide", "col": "", "num": "" }
      }', '[ "par", "pul" ]'
    ),-- 13 lunas
    ( 'hol','psi_lun', 
      '{

        "lun":{ "tab": "psi_lun" },

        "pos":{ "style": "width: 5rem; height: 5rem; max-height: 5rem;" }
      
      }', '{

        "sec":{ "lun-cab":1, "lun-hep":1, "lun-rad": 1 },

        "pos":{ "ima": "api.hol_kin.ide", "col": "", "num": "" }      

      }', '[ "par" ]'
    ),-- 52 heptadas
    ( 'hol','psi_hep', 
      '{ 

        "sec":{ 
          "tab": "psi_hep" 
        }
        
      }', NULL, NULL
    ),-- banco-psi
    ( 'hol','psi_tzo', 
      '{

        "sec":{ "tab": "psi_tzo", "style": "grid-template-columns: repeat(4,1fr);" },

        "tzo-5":{ "style":"transform: rotate(180deg);" },
        "tzo-6":{ "style":"transform: rotate(180deg);" },
        "tzo-7":{ "style":"transform: rotate(180deg);" },
        "tzo-8":{ "style":"transform: rotate(180deg);" },

        "pos":{ "style":"width: .95rem; height: .95rem;" }
        
      }', '{

        "pos":{ "ima": "api.hol_kin.ide", "col": "", "num": "" },

        "pag":{ "kin": 1 }

      }', '[ "pag", "par" ]'
    )
  ;
  DELETE FROM `_api`.`app_tab` WHERE `esq`='hol' AND `est` LIKE 'umb%'
  ;