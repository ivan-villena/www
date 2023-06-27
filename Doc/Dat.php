<?php

class Doc_Dat {

  static string $IDE = "Doc_Dat-";
  static string $EJE = "Doc_Dat.";
  static string $DAT = "doc_dat";

  
  /* busco valor por seleccion ( esq.est.atr ) : variable, html, ficha, color, texto, numero */
  static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    
    // proceso estructura
    extract( Dat::ide($ide) );
    
    // proceso dato
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = Dat::get($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }
    
    // busco el atributo solicitado
    $_atr = Dat::est($esq,$est,'atr',$atr);

    // busco valor
    $val = is_object($dat) && isset($dat->$atr) ? $dat->$atr : $dat;

    // variable por tipo
    if( $tip == 'var' ){

      $_var = $_atr->var;

      $_var['val'] = $val;

      $_ = Ele::val($_val);

    }
    // color en atributo
    elseif( $tip == 'col' ){
      
      if( $col = Dat::est_ide('col',$esq,$est,$atr) ){ $_ = "

        <div".Ele::atr(Ele::cla($ele,"fon_col-{$col}-{$val} alt-100 anc-100",'ini'))."></div>";
      }
      else{
        $_ = "<div class='err' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$val}</div>";
      }
    }
    // imagen en atributo
    elseif( $tip == 'ima' ){

      if( !empty($_atr->var['dat']) ){
        $_ima_ide = explode('-',$_atr->var['dat']);
        $_ima['esq'] = $_ima_ide[0];
        $_ima['est'] = $_ima_ide[1];
      }
      
      if( !empty($_ima) || !empty( $_ima = Dat::est_ide('ima',$esq,$est,$atr) ) ){
        
        $_ = Doc_Val::ima($_ima['esq'],$_ima['est'],$val,$ele);
      }
      else{
        $_ = "<div class='err' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$val}</div>";
      }
    }
    // por tipos de dato
    elseif( $tip == 'val' || in_array($tip,['num','tex','fec']) ){      

      if( $tip == 'val' ){

        $tip = $_atr->var_dat;
      }

      if( in_array( $tip, ['num','tex','fec'] ) ){

        $_ = Doc_Val::$tip($val,$ele);
      }
      else{
        $_ = Doc_Val::let($val);
      }
    }
    // devuelvo valor sin procesar
    else{

      $_ = $val;
    }   

    return $_;
  }

  /* imrpimo selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas */
  static function opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."opc";
    $_eje = self::$EJE."opc(";

    // opciones
    $opc_esq = in_array('esq',$opc);
    $opc_est = in_array('est',$opc);
    $opc_val = in_array('val',$opc);
    $opc_ope_tam = in_array('ope_tam',$opc) ? "max-width: 6rem;" : NULL;

    // capturo elemento select
    if( !isset($ope['ope']) ) $ope['ope'] = [];
    if( empty($ope['ope']['name']) ) $ope['ope']['name'] = $ide;
    // valor seleccionado
    if( isset($ope['val']) ) $_val = explode('-',$ope['val']);
    
    // cargo selector de estructura
    $ele_eje = isset($ope['ope']['onchange']) ? $ope['ope']['onchange'] : FALSE;
    $ele_val = [ 'eti'=>[ 
      'name'=>"val", 
      'class'=>"mar_ver-1", 
      'title'=>"Seleccionar el Regisrto por Estructura",
      'style'=>$opc_ope_tam, 
      'onchange'=>$_eje."'val',this);" 
    ] ];
    if( $opc_esq || $opc_est ){
      
      // operador por esquemas
      if( $opc_esq ){
        $dat_esq = [];
        $ele_esq = [ 'eti'=>[ 
          'name'=>"esq", 
          'class'=>"mar_ver-1", 
          'title'=>"Seleccionar el Esquema de Datos...",
          'style'=>$opc_ope_tam, 
          'onchange'=>$_eje.",'esq');" 
        ] ];
      }

      // operador por estructuras
      $ele_est = [ 'eti'=>[ 
        'name'=>"est", 
        'class'=>"mar_ver-1", 
        'title'=>"Seleccionar la Estructura de Datos...",
        'style'=>$opc_ope_tam, 
        'onchange'=>$_eje."'est',this);" 
      ] ];
      
      // operador por relaciones de atributo
      $ope['ope'] = Ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
      if( !empty($opc_ope_tam) ) $ope['ope'] = Ele::css($ope['ope'],$opc_ope_tam);
      // oculto items
      $cla = "dis-ocu";
      // copio eventos
      if( $ele_eje ) $ele_est['eti'] = Ele::eje($ele_est['eti'],'cam',$ele_eje);
      // aseguro valores seleccionado
      if( $opc_esq ){          
        if( isset($_val[0]) ) $ele_esq['eti']['val'] = $_val[0];
        if( isset($_val[1]) ) $ele_est['eti']['val'] = $_val[1];
        if( isset($_val[2]) ) $ope['ope']['val'] = $_val[2];
        if( isset($_val[3]) ){ $ele_val['eti']['val'] = $_val[3]; $dat_val = []; }
      }else{
        if( isset($_val[0]) ) $ele_est['eti']['val'] = $_val[0];
        if( isset($_val[1]) ) $ope['ope']['val'] = $_val[1];
        if( isset($_val[2]) ){ $ele_val['eti']['val'] = $_val[2]; $dat_val = []; }
      }
    }
    else{
      
      if( isset($_val[0]) ) $ope['ope']['val'] = $_val[0];
      if( isset($_val[1]) ){ 
        $ele_val['eti']['val'] = $_val[1]; $dat_val = []; 
      }
    }
    // de donde tomo los datos? esquemas => estructuras
    $_ = "";
    // atributos por relacion
    $dat_ope = [];
    // estructuras
    $dat_est = [];
    // agrupador
    $ele_ope['gru'] = [];
    $ele_ope['eti'] = $ope['ope'];
    // proceso identificador de dato
    if( is_string($dat) || Obj::pos_val($dat) ){

      $_ide = is_string($dat) ? explode('.',$dat) : $dat;

      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = Dat::est($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          
          // cargo atributo
          $_atr = Dat::est($esq,$est,'atr',$atr);
          $atr_nom = $_atr->nom;
          if( $_atr->ide == 'ide' && empty($_atr->nom) && !empty($_atr_nom = Dat::est($esq,$est,'atr','nom')) ){
            $atr_nom = $_atr_nom->nom;
          }
          // armo identificadores
          $_ide = Dat::est_rel($esq,$est,$atr);

          $_ []= [
            'data-esq'=>$esq, 
            'data-est'=>$est, 
            'data-dat'=>$_ide['dat'],
            'value'=>"{$esq}.{$est}.{$atr}", 
            'class'=>$cla, 
            'htm'=>$atr_nom
          ];
        }
      }
      return $_;
    };

    $val_cla = isset($cla);
    $val_est = isset($ele_est['eti']['val']) ? $ele_est['eti']['val'] : FALSE;
    foreach( $dat as $esq_ide => $est_lis ){
      // cargo esquema [opcional]
      if( $opc_esq ){
        $dat_esq []= $esq_ide;
      }
      // recorro estructura/s por esquema
      foreach( $est_lis as $est_ide ){
        
        // busco estructuras dependientes
        if( $dat_opc_est = Dat::est($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            
            // datos de la estructura relacional
            $rel_ide = explode('-',$dep_ide);
            $rel_esq = $rel_ide[0];
            $rel_est = $rel_ide[1];                        
            $rel_dat = "{$rel_esq}.{$rel_est}";
            $_est = Dat::est($rel_esq,$rel_est);

            // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
            if( !empty( $_opc_val = $_opc_ite($rel_esq, $rel_est, $ide, $val_cla && ( !$val_est || $val_est != $rel_dat ) ? $cla : "") ) ){
              // con selector de estructura
              if( $opc_est ){
                // cargo opcion de la estructura
                $dat_est[] = [ 'value'=>$rel_dat, 'htm'=>isset($_est->nom) ? $_est->nom : $rel_est ];
                // cargo todos los atributos a un listado general
                array_push($dat_ope, ...$_opc_val);

              }// por agrupador
              else{
                // agrupo por estructura
                $ele_ope['gru'][$_est->ide] = isset($_est->nom) ? $_est->nom : $rel_est;
                // cargo atributos por estructura
                $dat_ope[$_est->ide] = $_opc_val;
              }                    
            }
          }
        }// estructura sin dependencias
        else{
          $dat_ope[] = $_opc_ite($esq_ide, $est_ide, $ide);
        }
      }
    }

    // selector de esquema [opcional]
    if( $opc_esq ){
      $_ .= Doc_Val::opc($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }

    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= Doc_Val::opc($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }

    // selector de atributo con nombre de variable por operador
    $_ .= Doc_Val::opc($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = Ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='-val'>
        ".Doc_Val::opc( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }

    return $_;
  }

  /* Imagen principal y por relaciones : .val_ima => { ...atributos }  */
  static function ima( string $ide, mixed $val = NULL ) : string {
    $_ = "";

    extract( Dat::ide($ide) );

    // imagen principal
    if( ( $_fic = Dat::est($esq,$est,'ima') ) && isset($_fic[0]) ){ $_ .= 

      "<div class='-val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
      
        ( !empty($val) ? Doc_Val::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."

      </div>";

      // listado de imágenes por relaciones de atributos
      if( !empty($_fic[1]) && is_object( $val = Dat::get($esq,$est,$val) ) ){

        if( !isset($ele['ima']) ) $ele['ima'] = [];        

        $_ .= "
        <c class='sep'>=></c>

        <ul class='lis val tam-mov'>
          <li><c>{</c></li>";
          foreach( $_fic[1] as $atr ){
    
            // Cargo identificadores
            $_ima = Dat::est_ide('ima',$esq,$est,$atr); 
            
            // imprimo imagen
            $_ .= "
            <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
    
              ".( isset($val->$atr) ? Doc_Val::ima($esq,"{$est}_{$atr}",$val->$atr,$ele['ima']) : "" )."

            </li>";
          } $_ .= "
          <li><c>}</c></li>
        </ul>";        
      }
    }

    return $_;
  }
  
  /* Ficha: imagen + { nombre + descripcion } */
  static function fic( string $ide, mixed $val = NULL ) : string {
    $_ = "";
    
    // proceso estructura
    extract( Dat::ide($ide) );

    // obtengo valores
    $_val = Dat::est($esq,$est,'val');
    
    // proceso valores
    $val_lis = [];
    if( is_numeric($val) || is_object($val) ){
      
      $val_lis = [ $val ];
    }// proceso rangos por ", " o " - "
    elseif( is_string($val) ){
      
      $sep = ( preg_match("/, /",$val) ) ? ", " : (  preg_match("/\s-\s/",$val) ? " - " : FALSE );
      
      if( $sep == ', ' ){
        
        $val_lis = explode($sep,$val);
      }
      else{
        $ran_sep = explode($sep,$val);
        
        if( isset($ran_sep[0]) && isset($ran_sep[1]) ){

          $val_lis = range(Num::val($ran_sep[0]), Num::val($ran_sep[1]));
        }
      }
    }
    elseif( is_array($val) ){

      $val_lis = $val;
    }

    // armo fichas
    foreach( $val_lis as $val ){
      
      // cargo datos
      $_dat = Dat::_("{$esq}.{$est}",$val);
      
      $_ .= "
      <div class='-val'>";
  
        if( isset($_val['ima']) ) $_ .= Doc_Val::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
  
        if( isset($_val['nom']) || isset($_val['des']) ){ 
          $_ .= "
          <div class='tex_ali-izq'>";
            if( isset($_val['nom']) ){
              $_ .= Doc_Val::tex( Obj::val($_dat,$_val['nom']), ['class'=>"tit"] );
            }
            if( isset($_val['des']) ){
              $_ .= Doc_Val::tex( Obj::val($_dat,$_val['des']), ['class'=>"des"] );
            }
            $_ .= "
          </div>";
        }
        $_ .= "
        
      </div>";
    }

    return $_;
  }  

  /* Informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ... */
  static function inf( string $ide, mixed $dat = NULL, array $ope = NULL ) : string {

    $_ = "";

    extract( Dat::ide($ide) );    

    if( $_inf = isset($ope) ? $ope : Dat::est($esq,$est,'inf') ){
      
      // cargo atributos
      $_atr = Dat::est($esq,$est,'atr');
      
      // cargo datos
      $_dat = isset($dat) ? Dat::get($esq,$est,$dat) : new stdClass;
      
      // cargo valores
      $_val = Dat::est($esq,$est,'val');
      
      // cargo opciones
      $opc = [];
      if( isset($_inf['opc']) ){ 

        $opc = Obj::pos_ite($_inf['opc']); 
        unset($_inf['opc']); 
      }

      // Nombre
      if( in_array('nom',$opc) && isset($_dat->nom) ){

        $_ .= Doc_Val::tex($_dat->nom,[ 'class'=>"tit" ]);

      }// por valor
      elseif( isset($_val['nom'])  ){

        $_ .= Doc_Val::tex(Obj::val($_dat,$_val['nom']),[ 'class'=>"tit" ]);

      }

      // Descripcion
      if( in_array('des',$opc) ){

        if( isset($_val['des']) ){

          $_ .= Doc_Val::tex(Obj::val($_dat,$_val['des']),['class'=>"des"]);
        }
        elseif( isset($_dat->des) ){
          
          $_ .= Doc_Val::tex($_dat->des,['class'=>"des"]);
        }        
      }

      // Detalle: imagen + atributos
      if( !empty($_val['ima']) || !empty($_inf['det']) ){ 
        $_ .= "
        <div class='-val jus-cen mar_arr-1'>";
          
          if( !empty($_val['ima']) ){
            $_ .= Doc_Val::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
          }
          
          if( !empty($_inf['det']) ){
            $atr = $_inf['det'];
            unset($_inf['det']);
            $_ .= is_array($atr) ? Doc_Dat::atr($esq,$est,$atr,$_dat) : Doc_Val::tex($_dat->$atr,['class'=>"det"]);
          }
          $_ .= "
        </div>";
      }
      
      // Componentes: atributo + texto + listado + tablero + fichas + html + ejecuciones
      foreach( $_inf as $inf_ide => $inf_val ){

        $inf_ide_pri = explode('-',$inf_ide)[0];

        if( $inf_sep = !in_array($inf_ide_pri,['dat','htm']) ){ $_ .= "
          <section class='".( $inf_ide_pri != 'tab' ? 'ali_pro-cre' : '' )."'>";
        }

        switch( $inf_ide_pri ){
        // Datos: atributo nombre = valor
        case 'dat':
          $_ .= Doc_Dat::atr($esq,$est,$inf_val,$_dat);
          break;
        // Atributos : valor c/s titulo
        case 'atr':
          $agr_tit = preg_match("/-tit/",$inf_ide);
          foreach( Obj::pos_ite($inf_val) as $atr ){
            if( isset($_dat->$atr) ){
              // titulo
              if( $agr_tit ) $_ .= Doc_Val::tex($_atr[$atr]->nom,['class'=>"tit"]);
              // contenido
              foreach( explode("\n",$_dat->$atr) as $tex_par ){
                $_ .= Doc_Val::tex($tex_par);
              }
            }
          }
          break;
        // Texto por valor: parrafos por \n
        case 'tex':
          foreach( Obj::pos_ite($inf_val) as $tex ){
            // por contenido
            if( is_string($tex) ){
              foreach( explode("\n",$tex) as $tex_val ){
                $_ .= Doc_Val::tex(Obj::val($_dat,$tex_val));
              }
            }// por elemento {<>}
            else{
              foreach( $tex as &$ele_val ){
                if( is_string($ele_val) ) $ele_val = Obj::val($_dat,$ele_val);
              }
              $_ .= Ele::val($tex);
            }
          }
          break;          
        // listados : "\n",
        case 'lis':
          foreach( Obj::pos_ite($inf_val) as $lis ){
            if( isset($_atr[$lis]) && isset($_dat->$lis) ){
              // con atributo-titulo
              $_ .= Doc_Val::tex($_atr[$lis]->nom,['class'=>"tit"]).Doc_Ope::lis('pos',$_dat->$lis);
            }
          }
          break;
        // Tablero por identificador
        case 'tab':

          $tab_ide = $inf_val[0];
          $tab_var = isset($inf_val[1]) ? $inf_val[1] : [];
          $tab_ele = isset($inf_val[2]) ? $inf_val[2] : [];

          $_ .= Doc_Dat::tab($tab_ide, $_dat, $tab_var, $tab_ele);

          break;
        // Fichas : por relaciones con valores(", ") o rangos(" - ")
        case 'fic':
          
          $agr_tit = preg_match("/-tit/",$inf_ide);

          foreach( Obj::pos_ite($inf_val) as $ide ){

            if( isset($_atr[$ide]) && isset($_atr[$ide]->var['dat']) && isset($_dat->$ide) ){

              $dat_ide = explode('-',$_atr[$ide]->var['dat']);
              $dat_esq = $dat_ide[0];
              $dat_est = $dat_ide[1];
              
              // titulo
              if( $agr_tit ) $_ .= Doc_Val::tex($_atr[$ide]->nom,['class'=>"tit"]);
              
              // pido ficha/s
              $_ .= Doc_Dat::fic("{$dat_esq}.{$dat_est}", $_dat->$ide);
            }
          }
          break;
        // Contenido HTML : textual o con objetos
        case 'htm':
          if( is_string($inf_val) ){
            $_ .= Obj::val($_dat,$inf_val);
          }else{
            foreach( Obj::pos_ite($inf_val) as $ele ){
              // convierto texto ($), y genero elemento/s
              $_ .= Ele::val( Obj::val_lis($ele,$_dat) );
            }
          }
          break;
        // Ejecuciones : por clase::metodo([...parametros])
        case 'eje':
          // convierto valores ($), y ejecuto por identificador
          $_ .= Eje::val( $inf_val['ide'], isset($inf_val['par']) ? Obj::val_lis($inf_val['par'],$_dat) : [] );
          break;
        }

        if( $inf_sep ){ $_ .= "
          </section>";
        }
      }

    }

    return $_;
  }  

  /* Atributos : item => Atributo: "valor" */
  static function atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    
    // cargo dato
    if( !is_object($dat) ) $dat = Dat::get($esq,$est,$dat);
    
    // cargo atributos
    $dat_atr = Dat::est($esq,$est,'atr');      
    $ite = [];
    
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){

      if( isset($dat_atr[$atr]) && ( isset($dat->$atr) || $dat->$atr === NULL ) ){ 

        $_atr = $dat_atr[$atr];

        $val = $dat->$atr;

        if( is_numeric($val) && isset($_atr->var['dat']) ){

          // busco nombres /o/ iconos
          $atr_ide = explode('-',$_atr->var['dat']);
          $atr_esq = $atr_ide[0];
          $atr_est = $atr_ide[1];

          $atr_dat = Dat::est($atr_esq,$atr_est,'val');

          $atr_obj = Dat::_("{$atr_esq}.{$atr_est}", $val);

          if( isset($atr_dat['nom']) ){

            $val = Doc_Val::let( Obj::val($atr_obj,$atr_dat['nom']) );

            if( isset($atr_dat['des']) && !$_opc_des ){

              $val .= "<br>".Doc_Val::let(Obj::val($atr_obj,$atr_dat['des']));
            }
          }

          $val = str_replace($dat_atr[$atr]->nom,"<b class='ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          
          $val = "<b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".Doc_Val::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = Doc_Ope::lis('pos',$ite,$ope);          

    return $_;
  }

  /* Definiciones : imagen + nombre > ...atributos */
  static function des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    $_ = [];

    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'dep';

    // atributos de la estructura
    $atr = Obj::pos_ite($atr);

    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;

    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    Ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "{$esq}.{$est}";

    foreach( Dat::_($ele['lis']['data-ide']) as $pos => $_dat ){ 
      $htm = 
      Doc_Val::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
      <dl>
        <dt>".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".Obj::val($_dat,$des) : "" )."</dt>";
        foreach( $atr as $ide ){ 
          if( isset($_dat->$ide) ){ $htm .= "
            <dd>".Doc_Val::let($_dat->$ide)."</dd>";
          }
        }$htm .= "
      </dl>";
      $_ []= $htm;
    }
    
    return Doc_Ope::lis( $tip, $_, $ele, ...$opc );

  }

  /* Posiciones : ficha + nombre ~ descripcion ~ posicion */
  static function pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];

    foreach( Dat::est($esq,$est,'pos') as $ite ){

      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( Dat::ide($ite['ide']) );

      $est_atr = Dat::est($esq,$est,'atr');

      foreach( $ite['atr'] as $atr ){

        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;

        $_atr = isset($est_atr[$atr]->var) ? $est_atr[$atr]->var : [];
        
        // identificadores
        $_ide = explode('-', isset($_atr['dat']) ? $_atr['dat'] : "{$esq}-{$est}_{$atr}");
        $esq_ide = $_ide[0];
        $est_ide = $_ide[1];
        $dat_ide = "{$esq_ide}.{$est_ide}";
        
        // datos
        $_dat = Dat::get($esq_ide,$est_ide,$val);
        $_val = Dat::est($esq_ide,$est_ide,'val');
        
        $htm = isset($_val['ima']) ? Doc_Val::ima($esq_ide ,$est_ide, $_dat, [ 'class'=>"tam-3 mar_der-1" ]) : "";
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".Doc_Val::let( Doc_Dat::val('nom',$dat_ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".Doc_Val::let( Doc_Dat::val('des',$dat_ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            Doc_Var::num('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    $ele['opc'] = [ "tog","ver","not-sep" ];
    $ele['ope'] = [ 'class'=>"mar_arr-1" ];

    return Doc_Ope::lis('dep',$_,$ele);
  }

  /*-- Administrador --*/
  static array $Abm = [
    'ope'=>[
      'eli'=>[ 'nom'=>"Eliminar" ],
      'fin'=>[ 'nom'=>"Reiniciar" , 'tip'=>"reset" ],
      'ini'=>[ 'nom'=>"Confirmar", 'tip'=>"submit" ]
    ]
  ];// imprimo formulario por estructura
  static function abm( string $ide, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."abm";
    
    extract( Dat::ide($ide) );

    $ele['form']['eti'] = "form";

    $htm = !empty($ope['htm']) ? $ope['htm'] : "";
    $htm_ini = !empty($ope['htm-ini']) ? $ope['htm-ini'] : "";
    $htm_med = !empty($ope['htm-med']) ? $ope['htm-med'] : "";
    $htm_ope = !empty($ope['htm-ope']) ? $ope['htm-ope'] : "";
    $htm_fin = !empty($ope['htm-fin']) ? $ope['htm-fin'] : "";

    $ele['form']['data-esq'] = $esq;
    $ele['form']['data-est'] = $est;

    // por cada atributo genero una variable
    if( !empty($ope['atr']) ){

      $val_obj = isset($ope['dat']) && is_object($ope['dat']);
      
      foreach( $ope['atr'] as $atr_ide ){
        
        $atr_var = isset($ope['var'][$atr_ide]) ? $ope['var'][$atr_ide] : [];

        if( $val_obj && isset($ope['dat']->$atr_ide) ){
          $atr_var['val'] = $ope['dat']->$atr_ide;
        }

        $atr_opc = isset($ope['opc'][$atr_ide]) ? $ope['opc'][$atr_ide] : [];

        $htm .= Doc_Ope::var('dat.atr', [ $esq, $est, $atr_ide ], $atr_var, ...$atr_opc );  
      }
    }
    
    // proceso operadores abm
    if( isset($ope['ope']) ){

      $_abm = self::$Abm['ope'];

      foreach( $ope['ope'] as $ope_ide => $ope_eje ){

        $ope_var = is_array($ope_eje) ? $ope_eje : [];

        if( $val_ope = isset($_abm[$ope_ide]) ){

          $ope_var = Obj::val_jun( $_abm[$ope_ide], $ope_var );
        }

        $bot = [
          'eti' => "button",
          'htm' => isset($ope_var['nom']) ? $ope_var['nom'] : ( isset($ope_var['htm']) ? $ope_var['htm'] : "" ),
          'type' => !empty($ope_var['tip']) ? $ope_var['tip'] : "button"
        ];

        if( isset($ope_var['onclick']) ){

          $bot['onclick'] = $ope_var['onclick'];
        }
        elseif( $val_ope ){

          $bot['onclick'] = "{$_eje}('{$ope_ide}',this);";
        }

        if( isset($ope_var['data-eje']) ){

          $bot['data-eje'] = $ope_var['data-eje'];

        }elseif( is_string($ope_eje) && !empty($ope_eje) ){

          $bot['data-eje'] = $ope_eje;
        }

        $htm_ope .= Ele::val($bot);
      }
    }

    // Agrego Botones y Errores
    if( !empty($htm_ope) ){
      $htm_ope = "
      <fieldset class='ope_err dis-ocu'>
      </fieldset>

      <fieldset class='ope_bot dir-ver'>
        $htm_ope
      </fieldset>";
    } 

    Ele::cla($ele['form'],'dat','ini');
    $ele['form']['htm'] = $htm_ini.$htm.$htm_med.$htm_ope.$htm_fin;
    $_ = Ele::val($ele['form']);    

    return $_;
  }// proceso datos
  static function abm_val( object | array $dat, string $tip, string $ide ) : string {
    $_ = "";

    extract( Dat::ide($ide) );

    switch( $tip ){
    case 'agr': 

      break;
    case 'mod': 

      break;
    case 'eli':

      foreach( Obj::pos_ite($dat) as $val ){

        if( is_object($val) ){

          if( isset($val->key) ){

          }
        }
        elseif( is_array($val) ){

          foreach( $val as $ide => $atr ){

          }
        }
        elseif( is_string($val) ){
          
        }
        elseif( is_numeric($val) ){
          
        }
      }
      break;
    }

    return $_;
  }

  /* Valores */  
  static array $Val = [
    
    // elementos de un proceso por [ esq => [...est] ] 
    'var'=>[
    ],

    // Operadores de listado y tablero
    'ope'=>[
      'acu'=>[ 'nom'=>"Acumulados" ], 
      'ver'=>[ 'nom'=>"Selección"  ], 
      'sum'=>[ 'nom'=>"Sumatorias" ], 
      'cue'=>[ 'nom'=>"Conteos"    ]
    ]
    
  ];// devuelvo: nombre, descripcion, tablero, imagen, color, cantidad, texto, numero
  static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";

    // proceso estructura
    extract( Dat::ide($ide) );

    // cargo valores
    $_val = Dat::est($esq,$est,'val');
    
    // cargo datos/registros
    if(  $tip != 'ima' ) $_dat = Dat::get($esq,$est,$dat);

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? Obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n".Obj::val($_dat,$_val['des']) : "");
    }
    // proceso atributos con texto : nom + des + ima
    elseif( isset($_val[$tip]) ){

      if( $tip == 'ima' ){
        
        if( is_array($_val[$tip]) ) $tip = 'tab';
      }
      elseif( is_string($_val[$tip]) ){
        
        $_ = Obj::val($_dat,$_val[$tip]);
      }
    }

    // ficha por imagen
    if( $tip == 'ima' ){

      // identificador      
      $ele['data-esq'] = $esq;
      $ele['data-est'] = $est;

      // 1 o muchos: valores ", " o rango " - "
      $_ = "";
      $ele_ima = $ele;
      $ima_lis = is_string($dat) ? explode(preg_match("/, /",$dat) ? ", ": " - ",$dat) : [ $dat ];

      foreach( $ima_lis as $dat_val ){

        $_dat = Dat::get($esq,$est,$dat_val);

        if( is_object($_dat) ){
          
          $ele_ima['data-ide'] = $_dat->ide;
      
          // cargo titulos
          if( !isset($ele_ima['title']) ){
            $ele_ima['title'] = Doc_Dat::val('tit',"$esq.$est",$_dat);
          }
          elseif( $ele_ima['title'] === FALSE  ){

            unset($ele_ima['title']);
          }
          
          // acceso a informe
          if( !isset($ele_ima['onclick']) ){ 
            
            if( Dat::est($esq,$est,'inf') ) Ele::eje($ele_ima,'cli',"Doc_Dat.inf('{$esq}.{$est}', ".intval($_dat->ide)." )");
          }
          elseif( $ele_ima['onclick'] === FALSE ){

            unset($ele_ima['onclick']);
          }
          
          $_ .= Doc_Val::ima( [ 'style' => Obj::val($_dat,$_val[$tip]) ], $ele_ima );          
        }
      }
    }
    // tablero por imagen
    elseif( $tip == 'tab' ){

      $_dat = Dat::get($esq,$est,$dat);
      
      $par = $_val['ima'];
      
      $ele_ima = $ele;
      
      // $cla_ide = $par[0];
      $tab_ide = $par[0];
      $tab_var = isset($par[1]) ? $par[1] : [];
      
      $tab_ele['sec'] = Ele::val_jun($ele_ima,isset($tab_ele['sec']) ? $tab_ele['sec'] : []);
      
      Ele::cla($tab_ele['sec'],"ima");

      $_ = Doc_Dat::tab($tab_ide, $_dat, $tab_var, $tab_ele);
    }
    // variable por dato
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos por valor
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = Doc_Val::let($_);
      $_ = Ele::val($ele);
    }    

    return $_;
  }// cargo datos de un proceso ( absoluto o con dependencias )
  static function val_var( array $ope ) : array {
    $_ = [];

    // cargo temporal
    foreach( $ope as $esq => $est_lis ){

      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        
        // recorro dependencias
        foreach( ( !empty($dat_est = Dat::est($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){

          // acumulo valores
          if( isset($dat->$ide) ) $_[$ref] = $dat->$ide;
        }
      }
    }

    // agrego
    $_['pos'] = count(self::$Val['var']) + 1;

    self::$Val['var'] []= $_;

    return $_;

  }// acumulado : posicion + marcas + seleccion
  static function val_acu( array $dat, array $ope = [], array $opc = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."val_acu";

    if( empty($opc) ) $opc = array_keys($dat);

    $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

    if( !empty($ope['ide']) ) $_ide = $ope['ide'];

    $_ .= "
    <div class='-ren'>";

      foreach( $opc as $ide ){

        $_ .= Doc_Ope::var('doc',"dat_val.acu.$ide", [
          'ope'=> [ 
            'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
          ],
          'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
        ]);
      }

      if( !empty($ope['htm_fin']) ){
        $_ .= $ope['htm_fin'];
      } $_ .= "

    </div>";

    return $_;
  }// filtros : datos + valores
  static function val_ver( array $ope, string $ide, string $eje, ...$opc ) : string {
    $_ = "";
    
    $tip = isset($ope['tip']) && !empty($ope['tip']) ? $ope['tip'] : [ 'pos','fec' ];

    // Controladores: ( desde - hasta ) + ( cada - cuántos: del inicio, del final )
    $ope_ver_var = function ( array $var = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      
      // proceso controlador 
      $_ite = function ( $ide, &$var ) {
  
        if( !isset($var[$ide]) ) $var[$ide] = [];
        
        if( !empty($var[$ide]['id']) ) $var[$ide]['id'] .= "-{$ide}";
  
        // aseguro tipos numericos para incremento y limite
        if( ( $ide == 'inc' || $ide == 'lim' ) ) $var[$ide]['tip'] = "num_int";
  
        return $var[$ide];
      };
      
      // operadores por defecto
      if( empty($var) ) $var = [ 'ini'=>[], 'fin'=>[] ];
  
      // imprimo: desde - hasta
      foreach( ['ini','fin'] as $ide ){
        
        if( isset($var[$ide]) ) $_ .= Doc_Ope::var('doc',"dat_val.ver.$ide", [ 'ope'=>$_ite($ide,$var) ]);
      }

      // imprimo cada
      if( isset($var['inc']) ){
        $_ .= Doc_Ope::var('doc',"dat_val.ver.inc", [ 'ope'=>$_ite('inc',$var) ]);
      }

      // imprimo cuántos
      if( isset($var['lim']) ){
        $_eje = "Doc_Ope.var('mar',this,'bor-sel');".( isset($var['lim']['onchange']) ? " {$var['lim']['onchange']}" : "" );
        $_ .= "
        <div class='-ren tam-ini'>

          ".Doc_Ope::var('doc',"dat_val.ver.lim", [ 'ope'=>$_ite('lim',$var) ] )."

          <fieldset class='nav.'>
            ".Doc_Val::ico('ope_lis-ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            ".Doc_Val::ico('ope_lis-fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>

        </div>";
      }

      return $_;
    };

    // por listado de registros
    if( isset($ope['dat']) ){

      // form: dato por estructuras
      if( isset($ope['est']) ){
        
        // aseguro estructuras
        $ope_dat = [];
        foreach( $ope['est'] as $esq_ide => $est_lis ){
          $ope_dat[$esq_ide] = Obj::pos_val($est_lis) ? $est_lis : array_keys($est_lis);
        }

        // opciones: pido selectores ( + ajustar tamaño... )
        array_push($opc,'est','val');
        $_ .= "
        <form class='ide-dat'>
          <fieldset class='ope_inf -ren'>
            <legend>por Datos</legend>

            ".Doc_Ope::var('doc',"dat_val.ver.dat",[ 
              'ite'=>[ 'class'=>"tam-mov" ], 
              'htm'=>Doc_Dat::opc('ver',$ope_dat,[ 'ope'=>[ 'id'=>"{$ide}-val", 'onchange'=>"$eje('dat');" ] ], ...$opc)
            ])."

          </fieldset>
        </form>";
      }

      // form: posicion
      $dat_cue = count($ope['dat']);
      if( in_array('pos',$tip) ){
        $pos_var = [ 'id'=>"{$ide}-pos", 'min'=>"1", 'max'=>$dat_cue, 'onchange'=>"$eje('pos');" ];
        $pos_var_val = array_merge($pos_var,[ 'tip'=>"num_int" ]); 
        $_ .= "
        <form class='ide-pos'>
          <fieldset class='ope_inf -ren'>
            <legend>por Posiciones</legend>

            ".$ope_ver_var([ 'ini'=>$pos_var_val, 'fin'=>$pos_var_val, 'inc'=>$pos_var, 'lim'=>$pos_var ])."

          </fieldset>
        </form>";
      }

      // form: fecha principal
      
      $fin = $dat_cue-1;
      if( in_array('fec',$tip) && isset($ope['dat'][0]['var-fec']) && isset($ope['dat'][$fin]['var-fec']) ){
        $fec_var = [ 
          'id'=>"{$ide}-fec", 
          'min'=>1, 
          'max'=>$dat_cue, 
          'onchange'=>"$eje('fec');" 
        ];
        $fec_var_val = array_merge($fec_var,[ 
          'tip'=>"fec_dia",
          'min'=>Fec::val_var($ope['dat'][0]['var-fec']),
          'max'=>Fec::val_var($ope['dat'][$fin]['var-fec'])
        ]);
        $_ .= "
        <form class='ide-fec'>
          <fieldset class='ope_inf -ren'>
            <legend>por Fechas</legend>

            ".$ope_ver_var([ 'ini'=>$fec_var_val, 'fin'=>$fec_var_val, 'inc'=>$fec_var, 'lim'=>$fec_var ])."
            
          </fieldset>          
        </form>";
      }          
    }
    // valores por atributo
    elseif( isset($ope['var']) ){
      $var = $ope['var'];
      $var_ope = array_merge($ope['var'],[ 'tip'=>"num_int", 'min'=>1 ]);
      $_ .= "
      <form>
        <fieldset>

          ".$ope_ver_var([ 'ini'=>$var, 'fin'=>$var, 
            'inc'=>in_array('inc',$opc) ? $var_ope : NULL, 
            'lim'=>in_array('lim',$opc) ? $var_ope : NULL 
          ])."
          
        </fieldset>          
      </form>";
    }

    return $_;
  }// conteos : por valores de estructura relacionada por atributo
  static function val_cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."val_cue";
    $_eje = self::$EJE."val_cue";

    if( is_string($dat) ){
      extract( Dat::ide($dat) );
      $_ide = "$esq-$est $_ide";
    }
    switch( $tip ){        
    case 'dat': 
      $_ = [];
      
      // -> por esquemas
      foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){
        
        // -> por estructuras
        foreach( $est_lis as $est_ide ){
          
          // -> por dependencias ( est_atr )
          foreach( ( !empty($dat_opc_est = Dat::est($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){

            // Adapto identificador por esquema
            $est = str_replace("{$esq}-",'',$est);
            
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = Dat::est($esq,$est,'opc.ver') ){

              // nombre de la estructura
              $est_nom = Dat::est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                
                // armo relacion por atributo
                $rel = Dat::est_rel($esq,$est,$atr);
                
                // busco nombre de estructura relacional
                $rel_nom = Dat::est($rel['esq'],$rel['est'])->nom;
                
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='val mar_izq-2 dis-ocu'>
                    ".Doc_Dat::val_cue('est',"{$esq}.{$est}.{$atr}",$ope)."
                  </div>"
                ];
              }
              $_[] = [ 
                'ite'=> $est_nom, 
                'lis'=> $htm_lis 
              ];
            }
          }
        }
      }

      break;
    case 'est':

      if( isset($ope['ide']) ) $_ide = $ope['ide'];
      
      // armo relacion por atributo
      $rel = !empty($atr) ? Dat::est_rel($esq,$est,$atr) : [ 
        'esq'=>$esq, 'est'=>$est, 'dat'=>"{$esq}-{$est}" 
      ];
          
      $_ = "
      <!-- filtros -->
      <form class='-val'>

        ".Doc_Ope::var('_','ver',[ 
          'nom'=>"Filtrar", 
          'id'=> "{$_ide}-ver {$rel['ide']}",
          'htm'=> Doc_Ope::lis_ver([ 
            'ide'=>"{$_ide}-ver {$rel['ide']}", 
            'eje'=>"$_eje('ver',this);" 
          ])
        ])."

      </form>

      <!-- valores -->
      <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
        <tbody>";
        foreach( Dat::get( $rel['esq'], $rel['est'] ) as $ide => $_var ){
        
          $ide = isset($_var->ide) ? $_var->ide : $ide;

          if( !empty($atr) ){
            $ima = !empty( $_ima = Dat::est_ide('ima',$esq,$est,$atr) ) ? Doc_Val::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
          }
          else{

            $ima = Doc_Val::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
          }
          $_ .= "
          <tr class='pos' data-ide='{$ide}'>
            <td data-atr='ima'>{$ima}</td>
            <td data-atr='ide'>".Doc_Val::let($ide)."</td>
            <td data-atr='nom'>".Doc_Val::let(isset($_var->nom) ? $_var->nom : '')."</td>
            <td><c class='sep'>:</c></td>
            <td data-atr='tot' title='Cantidad seleccionada...'><n>0</n></td>
            <td><c class='sep'>=></c></td>
            <td data-atr='por' title='Porcentaje sobre el total...'><n>0</n><c>%</c></td>
          </tr>";
        } $_ .= "
        </tbody>
      </table>";
      break;
    }

    return $_;
  }

  /* Tablero: valores + secciones + posiciones + opciones */
  static array $Tab = [
    'var' => [
      // de datos
      'ide'=>"",
      'dat'=>[],     // registros
      'dat_est'=>[], // estructuras
      
      // posiciones
      'cue'=>0,     // total de posiciones
      'dep'=>FALSE, // dependencias: habilito operador ( .pos.ope )
      'htm'=>[],    // contenido

      // Habilito Operadores
      'ope'=>[],

      // valores
      'val'=>[ 'acu'=>[], 'pos'=>[], 'mar'=>[], 'ver'=>[], 'opc'=>[] ],

      // seccion principal: fondo
      'sec'=>[ 'bor'=>"", 'col'=>"", 'ima'=>"" ],

      // posiciones
      'pos'=>[ 'col'=>"", 'ima'=>"", 'num'=>"", 'tex'=>"", 'fec'=>"" ],

      // atributos

      // estructuras      
    ],        
    'ope' => [
      'ver' => [ 'ico'=>"ope_val-ver",  'nom'=>"Selección",    'des'=>"" ],
      'opc' => [ 'ico'=>"opc_bin",      'nom'=>"Opciones",     'des'=>"" ],
      'val' => [ 'ico'=>"dat_lis",      'nom'=>"Valores",      'des'=>"" ],
      'lis' => [ 'ico'=>"ope_lis",      'nom'=>"Listado",      'des'=>"" ]      
    ],
    'val' => []
  ];
  static function tab( mixed $ide, object $dat = NULL, array $var = NULL, array $ele = NULL ) : string {
    $_ = "";

    if( is_string($ide) ){

      // tablero por aplicacion: esq.est.atr
      extract( Dat::ide($ide) );      
      
      // convierto parametros por valores ($)
      if( isset($dat) && !empty($var) ) $var = Obj::val_lis($var,$dat);
      
      // aseguro identificador del tablero
      if( !isset($var['ide']) && isset($dat->ide) ) $var['ide'] = $dat->ide;

      // - omito esquema por nombre de aplicacion guardado en session
      // ...
      if( ( $cla = Eje::cla_val("App/{$_SESSION['Uri']->esq}")['ide'] ) && method_exists($cla,"dat_tab") ){
        
        $_ = $cla::dat_tab( $est, $var, $ele );
      }
    }

    return $_;
  }// inicializo variables del proceso
  static function tab_var( string $esq, string $est, array &$var = [], array &$ele = [] ) : array {

    foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ) $ele[$v] = []; }

    $_ = [ 
      'esq' => $esq,
      'est' => $est
    ];

    // aseguro etiqueta de lista
    if( !isset($ele['pos']['eti']) ) $ele['pos']['eti'] = "li";

    // agrego clase por identificadores: .est_atr
    if( empty($ele['sec']['class']) || !preg_match("/^tab/",$ele['sec']['class']) ){

      Ele::cla($ele['sec'], "dat_tab {$esq} {$est}",'ini' );    
    }

    // inicializo opciones
    if( !isset($var['opc']) ) $var['opc'] = [];

    // valido dependencia para operaciones por posicion
    if( !isset($var['dep']) ) $var['dep'] = FALSE;
    
    // inicializo contador de posiciones
    $var['cue'] = 0;
    
    // posicion: agrego bordes
    if( !empty($var['pos']['bor']) && ( !isset($ele['pos']['class']) || !preg_match("/bor-1/",$ele['pos']['class']) ) ){

      Ele::cla($ele['pos'],"bor-1");
    }
    
    //////////////////////////
    // ejecucion por contenido
    $var['eje'] = FALSE;
    if( class_exists($cla_ide = Tex::let_pal($_SESSION['Uri']->esq)) ){

      if( method_exists($cla_ide, $cla_eje = "dat_tab_var") ){

        $cla_ide::$cla_eje( $esq, $est, $var, $ele );
      }

      // metodo por posicion
      if( method_exists($cla_ide, $cla_eje = "dat_tab_pos") ){

        $var['eje'] = [ 'cla' => $cla_ide, 'met' => $cla_eje ];
      }
    }

    // identificadores de datos
    if( is_object( $ide = !empty($var['ide']) ? $var['ide'] : 0 ) ) $ide = $ide->ide;
    
    // tomo valor por posicion principal 
    $val = NULL;
    if( !empty($var['val']['pos']) ){
      
      $val = $var['val']['pos'];

      if( is_object($val) ){

        if( isset($val->ide) ){

          $val = intval($val->ide);
        }
        elseif( isset($val->pos) ){ 

          $val = intval($val->pos);
        }
        else{
          $val = 0;
        }
      }// array, indice o valor numerico
      else{
        
        $val = is_string($val) && is_numeric($val) ? intval($val) : $val;
      }
    }
    
    $_['ide'] = $ide;
    $_['val'] = $val;
    $_['var'] = $var;
    $_['ele'] = $ele;

    return $_;    
  }// operadores por aplicacion
  static function tab_ope( string $tip, string $dat, array $var = [], array $ele = [] ){
    $_ = "";
    $_ide = self::$IDE."tab_$tip";
    $_eje = self::$EJE."tab_$tip";
    
    // proceso datos del tablero
    extract( Dat::ide($dat) );      
    
    // elementos
    if( !isset($ele['ope']) ) $ele['ope'] = [];

    // por aplicacion : posicion + seleccion
    if( !isset($var['est']) ) $var['est'] = [ $esq =>[ $est ] ];

    // agrego conenido incial
    if( !empty($ele['htm-ini']) ) $_ .= $ele['htm-ini'];
    
    switch( $tip ){
    // Valores : sumatorias + acumulados + cuentas
    case 'val':
  
      // por acumulados
      $_ .= "
      <form class='ide-acu'>
        <fieldset class='ope_inf -ren'>
          <legend>Acumulados</legend>";

          $_ .= Doc_Ope::var('doc',"dat_val.ver.tot", [ 
            'ope'=>[ 
              'id'=>"{$_ide}-ver-tot" 
            ] 
          ]);
          
          $_ .= Doc_Dat::val_acu($var['val']['acu'],[ 
            'ide'=>"{$_ide}-acu", 
            'eje'=>"{$_eje}('acu',this);",
            'ope'=>[ 
              'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" 
            ]
          ]);
          $_ .="
        </fieldset>
      </form>";

      if( !empty($ele['htm']) ) $_ .= $ele['htm'];

      // cuentas por estructura
      $_ .= "
      <section class='ope_inf ide-cue pad_hor-2'>
        <h3>Totales por Estructura</h3>

        ".Doc_Ope::lis('dep', Doc_Dat::val_cue('dat', $var['est'], [ 'ide'=>$_ide ]), [ 
          'dep'=>[ 'class'=>"dis-ocu" ], 
          'opc'=>[ 'tog', 'ver', 'cue', 'tog-dep' ] 
        ])."
        
      </section>";

      break;
    // Opciones : Seccion + Posiciones + Estructuras
    case 'opc':

      // Fondo: bordes + imagen + color
      if( !empty($var[$tip = 'sec']) ){
        
        $_ide = "Doc_Dat-tab_{$tip}";
        $_eje = "Doc_Dat.tab_{$tip}";
        
        $ele_ope = $ele['ope'];
        Ele::cla($ele_ope,"ide-$tip",'ini'); 
        
        $_ .= "
        <form".Ele::atr($ele_ope).">
          <fieldset class='ope_inf -ren'>
            <legend>Fondo del Tablero</legend>";

            // operadores globales
            if( !empty( $tab_sec = Dat::var('doc','dat_tab',$tip) ) ){ 
              $_ .= "
              <div class='-val'>";
              foreach( $tab_sec as $ide => $ite ){

                if( isset($var[$tip][$ide]) ){ 

                  $_ .= Doc_Ope::var('doc',"dat_tab.{$tip}.{$ide}", [ 
                    'val'=>$var[$tip][$ide], 
                    'ope'=>[ 
                      'id'=>"{$_ide}-{$ide}", 
                      'onchange'=>"{$_eje}('{$ide}');"
                    ]
                  ]); 
                }
              }$_ .= "
              </div>";
            }
            
            $_ .= "
          </fieldset>
        </form>";          
      }

      // Posiciones: borde + color + imagen + numero + texto + fecha [ + variable ? ]
      if( !empty($var[$tip = 'pos']) ){ 
        
        $_ide = "Doc_Dat-tab_{$tip}";
        $_eje = "Doc_Dat.tab_{$tip}";
        
        $ele_ope = $ele['ope'];
        Ele::cla($ele_ope,"ide-{$tip}",'ini'); 
        $_ .= "
        <form".Ele::atr($ele_ope).">
          <fieldset class='ope_inf -ren'>
            <legend>Posiciones del Tablero</legend>";
            
            // bordes
            $ide = 'bor';
            $_ .= Doc_Ope::var('doc',"dat_tab.{$tip}.{$ide}",[
              'val'=>isset($var[$tip][$ide]) ? $var[$tip][$ide] : 0,
              'ope'=>[ 
                'id'=>"{$_ide}-{$ide}", 
                'onchange'=>"$_eje('{$ide}');"
              ] 
            ]);

            // selectores de valores: color de fondo - numero - texto - fecha
            foreach( ['col','num','tex','fec'] as $ide ){

              if( isset($var[$tip][$ide]) ){

                $_ .= Doc_Ope::var('doc',"dat_tab.{$tip}.{$ide}", [
                  'id'=>"{$_ide}-{$ide}",
                  'htm'=>Doc_Dat::opc($ide, $var['est'], [
                    'val'=>$var[$tip][$ide], 
                    'ope'=>[ 
                      'id'=>"{$_ide}-{$ide}", 
                      'onchange'=>"$_eje('{$ide}');"
                    ]
                  ])
                ]);                      
              }
            }

            // selector con acumulados : imagen
            foreach( [ 'ima' ] as $ide ){

              if( isset($var[$tip][$ide]) ){ 
                $_ .= "
                <div class='-ren'>

                  ".Doc_Ope::var('doc',"dat_tab.{$tip}.{$ide}",[
                    'id'=>"{$_ide}-{$ide}",
                    'htm'=>Doc_Dat::opc($ide, $var['est'], [ 
                      'val'=>$var[$tip][$ide], 
                      'ope'=>[ 
                        'id'=>"{$_ide}-{$ide}", 
                        'onchange'=>"$_eje('{$ide}');"
                      ]
                    ])
                  ]);

                  // vistas por acumulados
                  if( isset($var['val']['acu']) ){
                    
                    foreach( array_keys($var['val']['acu']) as $atr ){

                      $_ .= Doc_Ope::var('doc',"dat_tab.{$tip}.{$ide}-{$atr}", [
                        'val'=>isset($var[$tip]["{$ide}-{$atr}"]) ? $var[$tip]["{$ide}-{$atr}"] : FALSE,
                        'ope'=>[ 
                          'id'=>"{$_ide}-{$ide}-{$atr}",
                          'onchange'=>"$_eje('{$ide}-{$atr}');"
                        ]
                      ]);
                    }
                  }
                  $_ .= "
                </div>";
              }
            }
            
            $_ .= "
          </fieldset>    
        </form>";          
      }

      // operadores por estructuras
      $_ .= Doc_Dat::tab_ope_var("est",$esq,$var,$ele);

      break;
    // Seleccion : Datos + Posiciones + Fechas
    case 'ver':

      $_ .= Doc_Dat::val_ver([ 'dat'=>$var['dat'], 'est'=>$var['est'] ], $_ide, $_eje, 'ope_tam' );

      // operadores por atributos
      $_ .= Doc_Dat::tab_ope_var("atr",$esq,$var,$ele);

      break;
    // Listado de posiciones
    case 'lis':
      
      // elementos
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      Ele::cla($ele['lis'],"mar_aba-0");

      // cargo operadores del listado: Valores + Columnas + Descripciones
      $var['ope'] = [ "ver", "atr", "des" ];

      // oculto items del listado inicial
      if( !isset($var['opc']) ) $var['opc'] = [];
      array_push($var['opc'],"ite_ocu");
      
      $_ = Doc_Dat::lis($dat,$var,$ele);
      
      break;
    }

    // agrego conenido final
    if( !empty($ele['htm-fin']) ) $_ .= $ele['htm-fin'];

    return $_;    
  }// - imprimo variables en formularios del operador : sis-dat_var
  static function tab_ope_var( string $tip, string $esq, array $var, array $ele ) : string {
    $_ = "";

    $_ide = "dat_tab-{$tip}";
    $_eje = "dat_tab.{$tip}";

    // recorrer todas las opciones
    $htm = "";
    foreach( Dat::var($esq,'dat_tab',"{$tip}") as $est => $Ope ){

      // me fijo si alguna esta declarada en el tablero
      if( isset($var[$ide = "{$tip}-{$est}"]) ){

        $ele_ope = $ele['ope'];
        Ele::cla($ele_ope,"ide-{$est}",'ini');              

        // imrpimo todas las variables de la seccion
        $htm .= "
        <form".Ele::atr($ele_ope)."> 
          <fieldset class='ope_inf -ren'>
            <legend>{$Ope['nom']}</legend>";

            // recorro todas esas opciones y las imprimo:
            foreach( Dat::var($esq,'dat_tab',$ide) as $atr => $Val ){
        
              $val_ope = [
                'val'=>isset($var[$ide][$atr]) ? $var[$ide][$atr] : NULL,
                'ope'=>[ 
                  'id'=>"{$_ide}-{$est}-{$atr}" 
                ]
              ];
  
              if( isset($Val['ope']['tip']) && $Val['ope']['tip'] != 'num' ){
  
                $val_ope['ope']['onchange'] = "{$_eje}('{$est}-{$atr}')";
              }
  
              $htm .= Doc_Ope::var($esq,"dat_tab.{$ide}.{$atr}", $val_ope);
            }                  

            $htm .= "
          </fieldset>
        </form>";
      }
    }        

    // imprimo secciones
    if( !empty($htm) ){

      $ele_sec = [ 'eti' => "section", 'htm' => $htm ];

      Ele::cla($ele_sec,"ide-{$tip}",'ini');
      $_ .= Ele::val($ele_sec);
    }
    
    return $_;    
  }// imprimo posicion
  static function tab_pos( string $esq, string $est, mixed $val, array &$var, array &$ele ) : string {
    
    // recibo objeto 
    $_dat = FALSE;
    if( is_object( $val_ide = $val ) ){
      $_dat = $val;
      $val_ide = intval($_dat->ide);
    }
    // o identificador
    elseif( !empty($val) ){

      $_dat = Dat::_("{$esq}.{$est}",$val);
    }

    //////////////////////////////////////////////
    // cargo datos ///////////////////////////////
    $Ele = $ele['pos'];
    
    // por joins
    if( isset($var['dat']) ){

      foreach( $var['dat'] as $pos => $_ref ){

        if( isset($_ref["{$esq}-{$est}"]) && intval($_ref["{$esq}-{$est}"]) == $val_ide ){

          foreach( $_ref as $ref => $ref_dat ){

            $Ele["{$ref}"] = $ref_dat;
          }            
          break;
        }
      }
    }
    // por dependencias estructura
    elseif( $_dat ){

      // cargo al elementos los valores por estructura relacional
      if( !empty( $dat_est = Dat::est($esq,$est,'rel') ) ){
        
        foreach( $dat_est as $atr => $ref ){

          if( empty($Ele["{$ref}"]) ){

            $Ele["{$ref}"] = $_dat->$atr;
          }        
        }
      }
      // cargo valor pos posicion
      elseif( empty($Ele["{$esq}-{$est}"]) ){

        $Ele["{$esq}-{$est}"] = $_dat->ide;
      }
    }
    
    //////////////////////////////////////////////
    // .posiciones del tablero principal /////////
    
    $cla_agr = [];
    
    // habilito operador
    if( $_dat && !$var['dep'] ){

      $cla_agr []= "ope";

      // marco seleccion de posicion por estructura
      if( isset($var['val']['pos']) ){

        $dat_ide = $var['val']['pos'];

        if( is_array($dat_ide) && isset($dat_ide[$est]) ){

          $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
        }

        // agrego seleccion
        if( $_dat->ide == $dat_ide ) $cla_agr []= '_pos- _pos-bor';
      }

    }

    // clases adicionales
    if( !empty($cla_agr) ) Ele::cla($Ele,$cla_agr,'ini');
    
    //////////////////////////////////////////////
    // Contenido html ///////////////////////////
    
    /* -- ejecuto metodo por aplicacion -- */
    $htm = !!$var['eje'] ? $var['eje']['cla']::{$var['eje']['met']}( $est, $val, $var, $ele, $Ele ) : "";

    // contenido automático
    if( $_dat && empty($htm) && !isset($Ele['htm']) ){
      
      // color de fondo
      if( !empty($var['pos']['col']) ){

        $_ide = Dat::ide($var['pos']['col']);

        if( isset($Ele[$dat_ide = "{$_ide['esq']}-{$_ide['est']}"]) && !empty( $_dat = Dat::get($_ide['esq'],$_ide['est'],$Ele[$dat_ide]) ) ){
          $col = Dat::est_ide('col', ...explode('.',$var['pos']['col']));          
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            Ele::cla($Ele, "fon_col-$col-".( $val === 0 ? $val : Num::ran($val,$col) ) );
          }
        }
      }

      // imagen + numero + texto + fecha
      foreach( ['ima','num','tex','fec'] as $tip ){

        if( !empty($var['pos'][$tip]) ){          

          if( $tip == 'ima' ){
            // anulo titulos de la ficha/imagen
            if( !isset($ele['ima']) ) $ele['ima'] = [];
            if( !empty($Ele['title']) ) $ele['ima']['title'] = FALSE;
          }          

          // busco contenido por aplicacion
          $ide = Dat::ide( $var['pos'][$tip] );

          $htm .= Doc_Dat::ver($tip, $var['pos'][$tip], $Ele["{$ide['esq']}-{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
        }
      }
    }

    // cargo contenido por aplicacion o automático
    if( !isset($Ele['htm']) && !empty($htm) ) $Ele['htm'] = $htm;

    //////////////////////////////////////////////
    // valor de posicion automatica-incremental
    
    $var['cue']++;
    Ele::cla($Ele,"pos ide-{$var['cue']}",'ini');

    // devuelvo posicion
    return Ele::eti($Ele);
  }

  /* Tabla */
  static array $Lis = [
    // Parametros de un proceso
    'var' => [

      // Habilito Operadores
      'ope'=>[],

      // opciones
      'opc'=>[
        'cab_ocu',  // ocultar cabecera de columnas
        'ite_ocu',  // oculto items: en titulo + detalle
        'ima',      // buscar imagen para el dato
        'var',      // mostrar variable en el dato
        'htm'       // convertir texto html en el dato
      ],      

      // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est'=>[],

      // Operaciones ( viene de tablero ) : acumulado + posicion principal
      'val'=>[ 
        'acu'=>[], 
        'pos'=>[] 
      ],

      // columnas
      'atr'=>[],        // listado de identificadores
      'atr_dat'=>[],    // listado de objetos
      'atr_tot'=>0,
      'atr_ocu'=>[],    // columnas ocultas
      'atr_nav'=>FALSE, // enlaces en cabecera        

      // titulos : item superior
      'tit'=>[],
      'tit_cic'=>[], // atributos por ciclos-secuencias
      'tit_gru'=>[], // atributos por agrupaciones-clasificaciones
      
      // Datos por ciclos
      'cic_val'=>[],
      
      // datos de filas
      'dat'=>[],

      // detalles : item inferior
      'det'=>[],
      'det_cic'=>[], // atributos terminados en "_des"
      'det_gru'=>[], // atributos terminados en "_des"
      'det_des'=>[], // atributos por descripciones

      // pie : calculos, totales o referencias
      'pie'=>[]

    ],

    // Operadores
    'ope' => [
      'val' => [ 'ide'=>'ope', 'ico'=>"dat_lis",      'nom'=>"Valores"       , 'des'=>"" ],
      'ver' => [ 'ide'=>'ver', 'ico'=>"ope_val-ver",  'nom'=>"Filtros"       , 'des'=>"" ],
      'atr' => [ 'ide'=>'atr', 'ico'=>"ope_lis-ver",  'nom'=>"Columnas"      , 'des'=>"" ],
      'des' => [ 'ide'=>'des', 'ico'=>"ope_lis-gru",  'nom'=>"Descripciones" , 'des'=>"" ],
      'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav",      'nom'=>"Cuentas"       , 'des'=>"" ]
    ]

  ];// thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td ) 
  static function lis( string | array $dat, array $var = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis";
    
    // elementos
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
    
    // opciones
    if( !isset($var['opc']) ) $var['opc']=[];
    
    // inicializo operadores por esquema-estructura-ide
    self::$Lis['var'] = [];    
    /////////////////////////////////////////
    // 1- proceso estructura de la base /////
    if( $_val_dat = is_string($dat) ){
      
      // identificador unico
      extract( Dat::ide($dat) );
      $_ide = "$esq-$est $_ide";      
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
            
      // cargo operadores
      self::$Lis['var'] = Doc_Dat::lis_var($esq,$est,$var);
      $_val = [ 'tit_cic'=>[], 'tit_gru'=>[], 'det_des'=>[], 'det_cic'=>[], 'det_gru'=>[] ];
      $_val_tit = [ 'cic', 'gru' ];
      $_val_det = [ 'des', 'cic', 'gru'];      
      $ele_ite = [ ];
      $var['atr_tot'] = 0;

      foreach( self::$Lis['var'] as $esq_ide => $est_lis ){

        foreach( $est_lis as $est_ide => $est_ope ){
          // total de columnas    
          $var['atr_tot'] =+ $est_ope['atr_tot'];
          
          // valido contenido : titulos y detalles por estructura de la base
          foreach( $_val as $i => &$v ){

            if( isset($est_ope[$i]) ){

              $v []= "{$esq_ide}.{$est_ide}";

              if( !isset($ele_ite[$i]) ){

                $_i = explode('_',$i);
                $ele_ite[$i] = [ 'ite'=>[ 'data-opc'=>$_i[0], 'data-ope'=>$_i[1] ], 'atr'=>[ 'colspan'=>$var['atr_tot'] ] ]; 
              }
            }
          }
        }
      }
      // aseguro valores
      if( !isset($var['dat']) ) $var['dat'] = Dat::get($esq,$est);

      // valido item por { objeto( tabla) / array( joins) }
      foreach( $var['dat'] as $val ){ $_val_obj = is_object($val); break; }

    }
    else{
      // datos de atributos
      if( !isset($var['atr_dat']) ) $var['atr_dat'] = Dat::est_atr($dat);
      // listado de columnas
      if( !isset($var['atr']) ) $var['atr'] = array_keys($var['atr_dat']);
      // total de columnas
      $var['atr_tot'] = count($var['atr']);
    }

    /////////////////////////////////////////
    // 2- imprimo operador //////////////////
    if( isset($var['ope']) ){

      if( empty($var['ope']) ) $var['ope'] = [ "val" ];

      if( !empty( $_ope = Obj::nom(self::$Lis['ope'],'ver',Obj::pos_ite($var['ope'])) ) ){

        foreach( $_ope as $var_ide => &$var_lis ){

          $var_lis['htm'] = Doc_Dat::lis_ope($var_ide,$dat,$var,$ele);
        }

        $_ .= Doc_Ope::nav('bar', $_ope,[ 'lis'=>[ 'class'=>"mar_hor-1" ] ], 'ico', 'tog' );
      }    
    }

    /////////////////////////////////////////
    // 3- imprimo listado ///////////////////
    Ele::cla($ele['lis'],"dat_lis",'ini'); 
    $_ .= "
    <div".Ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$var['opc']) ){ $_ .= "
          <thead>
            ".Doc_Dat::lis_atr($dat,$var,$ele)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            $_ite = function( string $tip, int $pos, array $var, array $ele ) : string {
              $_ = "";
              foreach( Obj::pos_ite($var[$tip][$pos]) as $val ){ 
                $_ .= "
                <tr".Ele::atr($ele["{$tip}_ite"]).">
                  <td".Ele::atr(Ele::val_jun([ 'data-ope'=>$tip, 'colspan'=>$var['atr_tot'] ],$ele["{$tip}_val"])).">
                    ".( is_array($val) ? Ele::val($val) : "<p class='{$tip} tex_ali-izq'>".Doc_Val::let($val)."</p>" )."
                  </td>
                </tr>";
                return $_;
              }
              return $_;
            };            
            foreach( $dat as $ite => $val ){ 
              // Titulo
              if( !empty($var['tit'][$ite]) ) $_ .= self::lis_des('tit',$ite,$var,$ele);
              // Item
              $ele_pos = $ele['ite'];              
              Ele::cla($ele_pos,"pos ide-$ite",'ini'); $_ .= "
              <tr".Ele::atr($ele_pos).">
                ".Doc_Dat::lis_ite( $dat, $val, $var, $ele )."
              </tr>";
              // Detalle
              if( !empty($var['det'][$ite]) ) $_ .= self::lis_des('det',$ite,$var,$ele);
            }
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){

              foreach( self::$Lis['var'] as $esq => $est_lis ){

                foreach( $est_lis as $est => $est_ope ){

                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= Doc_Dat::lis_tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $var['opc']);
                  }                  
                }
              }
            }            
            // recorro datos
            foreach( $var['dat'] as $pos => $val ){

              // 1- arriba: referencias + titulos por ciclos
              foreach( self::$Lis['var'] as $esq => $est_lis ){

                foreach( $est_lis as $est => $est_ope ){
                  // recorro por relaciones
                  if( $dat_rel = Dat::est($esq,$est,'rel') ){

                    foreach( $dat_rel as $atr => $ref ){

                      $ele['ite']["{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }                  
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    
                    $_ .= Doc_Dat::lis_tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}-{$est}"], $est_ope, $ele_ite['tit_cic'], $var['opc']);
                  }
                }
              }

              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              Ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".Ele::atr($ele_pos).">";
              foreach( self::$Lis['var'] as $esq => $est_lis ){

                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){

                  $_ .= Doc_Dat::lis_ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}-{$est}"], $est_ope, $ele, $var['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$Lis['var'] as $esq => $est_lis ){

                foreach( $est_lis as $est => $est_ope ){

                  foreach( $_val_det as $ide ){

                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){

                      $_ .= Doc_Dat::lis_det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}-{$est}"], $est_ope, $ele_ite["det_{$ide}"], $var['opc']);
                    }
                  }                  
                } 
              }                    
            }
          }
          $_ .= "              
        </tbody>";
        // pie
        if( !empty($var['pie']) ){
          foreach( ['pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } $_.="
          <tfoot>";
            // fila de operaciones
            $_.="
            <tr data-tip='ope'>";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>
    </div>";

    return $_;
  }// Inicializo parametros de Tabla 
  static function lis_var( string $esq, string $est, array $var = [] ) : array {     
    $_ = [];

    $_ite = function( string $esq, string $est, array $var = [] ) : array {
      
      // inicializo atributos de lista
      $_ = $var;

      /* columnas 
      */
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = Dat::est($esq,$est,'atr') ) ? array_keys($_atr) : [];
      // totales
      $_['atr_tot'] = count($_['atr']);
      
      /* ciclos y agrupaciones 
      */
      // busco descripciones + inicio de operadores      
      foreach( ['cic','gru'] as $ide ){

        if( isset($_["tit_{$ide}"]) ){

          foreach( $_["tit_{$ide}"] as $atr ){
            
            // inicio ciclo
            if( $ide == 'cic' ) $_['cic_val'][$atr] = -1;

            // busco descripciones
            if( isset( $_atr["{$atr}_des"] ) ){

              if( !isset($_["det_{$ide}"]) ) $_["det_{$ide}"] = []; 
              $_["det_{$ide}"] []= "{$atr}_des";
            }
          }
        }
      }
      return $_;
    };

    // carga inicial
    foreach( ( isset($var['est']) ? $var['est'] : [ $esq => [ $est => $var ] ] ) as $esq_ide => $est_lis ){

      foreach( $est_lis as $est_ide => $est_ope ){

        $_[$esq_ide][$est_ide] = $_ite($esq_ide,$est_ide,$est_ope);
      }
    }

    return $_;
  }// Operadores : valores + filtros + columnas + descripciones + cuentas/conteos
  static function lis_ope( string $tip, string | array $dat, array $var = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis_$tip";
    $_eje = self::$EJE."lis_$tip";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( Dat::ide($dat) );
      $_ide = "$esq-$est $_ide";
    }
    switch( $tip ){
    // Estructura : abm por columnas
    case 'est':
      
      foreach( [ 'lis' ] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $est_ope = self::$Lis['var'];
      
      // tipos de dato
      $_cue = [
        'opc'=>[ "Opción", 0 ], 
        'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
        'tex'=>[ "Texto",  0 ], 
        'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
        'obj'=>[ "Objeto",  0 ] 
      ];
      
      // cuento atributos por tipo
      foreach( $est_ope['atr'] as $atr ){
        $tip_dat = explode('_', Dat::est($esq,$est,'atr',$atr)->ope['tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      
      // operador : toggles + filtros
      $_ .= "
      <form class='-val ide-dat jus-ini'>

        <fieldset class='ope_bot'>
          ".Doc_Val::ico('ope_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".Doc_Val::ico('ope_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".Doc_Ope::var('_','ver',[ 
          'nom'=>"Filtrar", 'htm'=> Doc_Ope::lis_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='-ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='-val'>
            ".Doc_Val::ico($atr,[ 'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');" ])."
            <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
          </div>";
        }$_ .= "
        </fieldset>

      </form>";

      // listado
      $pos = 0; $_ .= "
      <table".Ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
        foreach( $est_ope['atr'] as $atr ){
          $pos++;
          $_atr = Dat::est($esq,$est,'atr',$atr);
          $dat_tip = explode('_',$_atr->ope['tip'])[0];

          $_var = [];        
          if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
          if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
          if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
          if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }

          // operaciones
          $htm = "
          <form class='-ren esp-bet'>
          
            ".Doc_Dat::val_ver([ 'var'=>$_var ],"{$_ide}-{$atr}","{$_eje}_val")."

          </form>";

          // imprimo fila por atributo
          $_ .= "
          <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
            <td data-atr='val'>
              ".Doc_Val::ico( isset($lis->ocu) && in_array($atr,$lis->ocu) ? "val_ver-tod" : "val_ver-nad",[
                'eti'=>"button",'title'=>"Mostrar",'class'=>"tam-2{$cla_ver}",'value'=>"tog",'onclick'=>"$_eje('val',this);"
              ])."
            </td>
            <td data-atr='pos'>
              <n>{$pos}</n>
            </td>
            <td data-atr='ide' title='".( !empty($_atr->ope['des']) ? $_atr->ope['des'] : '' )."'>
              <font class='ide'>{$_atr->nom}</font>
            </td>
            <td data-atr='ope'>
              {$htm}
            </td>
          </tr>";

        }$_ .= "
      </table>";
      
      break;
    // Filtros : operadores | ciclos y agrupaciones por estructura
    case 'ver': 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Filtros</h3>";

      // filtros por operadores: datos + posicion + atributos
      if( isset($var['val']) ){
        
        // acumulados
        if( isset($var['val']['acu']) ){
          // cambio método
          $_eje = self::$EJE."lis_val";
          $_ .= "
          <form class='ide-acu'>
            <fieldset class='ope_inf -ren'>
              <legend>Acumulados</legend>

              ".Doc_Ope::var('doc',"dat_val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
              
              ".Doc_Ope::var('doc',"dat_val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}('tod',this);" ] ])."
              
              ".Doc_Dat::val_acu($var['val']['acu'],[
                'ide'=>$_ide, 
                'eje'=>"{$_eje}('acu'); Doc_Dat.lis_ver();",// agrego evento para ejecutar todos los filtros
                'ope'=>[ 'htm_fin'=>"<span class='mar_izq-1'><c>(</c> <n>0</n> <c>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }

        // pido operadores de filtro: dato + posicion + fecha
        $_ .= Doc_Dat::val_ver([ 'dat'=>$var['dat'], 'est'=>$var['est'] ], $_ide, $_eje );

      }// filtros por : cic + gru
      else{

      }
      
      break;
    // Columnas : ver/ocultar
    case 'atr':
      $lis_val = [];
      foreach( self::$Lis['var'] as $esq => $est_lis ){

        foreach( $est_lis as $est => $est_ope ){
          
          // datos de la estructura
          $est_dat = Dat::est($esq,$est);
          
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip -ren jus-ini mar_izq-2'>
            <fieldset class='ope_bot'>
              ".Doc_Val::ico('ope_ver-tod',[
                'eti'=>"button", 'class'=>"tam-2",
                'title'=>"Mostrar todas las Columnas",
                'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 
                'onclick'=>"{$_eje}('tog',this);"
              ])."
              ".Doc_Val::ico('ope_ver-nad',[ 
                'eti'=>"button", 'class'=>"tam-2",
                'title'=>"Ocultar todas las Columnas", 
                'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 
                'onclick'=>"{$_eje}('tog',this);"
              ])."
            </fieldset>";

            foreach( $est_ope['atr'] as $atr ){
              $_atr = Dat::est($esq,$est,'atr',$atr);
              $atr_nom = empty($_atr->nom) && $atr=='ide' ? Dat::est($esq,$est,'atr','nom')->nom : $_atr->nom ;
              $htm .= Doc_Ope::var('_',$atr,[
                'nom'=>"¿{$atr_nom}?", 
                'val'=>!isset($est_ope['atr_ocu']) || !in_array($atr,$est_ope['atr_ocu']),
                'ope'=>[ 
                  'tip'=>'opc_bin', 
                  'id'=>"{$_ide} {$esq}-{$est}-{$atr}", 
                  'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 
                  'onchange'=>"{$_eje}('tog',this);"
                ] 
              ]);
            } $htm.="
          </form>";
          $lis_val []= [ 'ite'=>$est_dat->nom, 'htm'=>$htm ];
        }              
      }        
      $_ = "        
      <h3 class='mar_arr-0 tex_ali-izq'>Columnas</h3>

      ".Doc_Ope::lis('dep',$lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Descripciones : titulo + detalle
    case 'des':
      $lis_val = [];        

      foreach( self::$Lis['var'] as $esq => $est_lis ){

        foreach( $est_lis as $est => $est_ope){
          
          // ciclos, agrupaciones y lecturas
          if( !empty($est_ope['tit_cic']) || !empty($est_ope['tit_gru']) || !empty($est_ope['det_des']) ){
            
            $lis_dep = [];
            foreach( ['cic','gru','des'] as $ide ){

              $pre = $ide == 'des' ? 'det' : 'tit';

              if( !empty($est_ope["{$pre}_{$ide}"]) ){ $htm = "
                <form class='ide-{$ide} -ren ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";

                  foreach( $est_ope["{$pre}_{$ide}"] as $atr ){
                    $htm .= Doc_Ope::var('_',$atr,[ 
                      'nom'=>"¿".Dat::est($esq,$est,'atr',$atr)->nom."?",
                      'ope'=>[ 
                        'tip'=>'opc_bin', 
                        'id'=>"{$_ide}-{$atr}-{$ide}", 
                        'onchange'=>"{$_eje}('tog',this);" 
                      ] 
                    ]);
                  }$htm .= "

                </form>";
                $lis_dep[] = [ 
                  'ite'=> Dat::var('doc','dat_lis','ver',$ide)['nom'], 
                  'htm'=> $htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=> Dat::est($esq,$est)->nom,
              'lis'=> $lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Descripciones</h3>

      ".Doc_Ope::lis('dep',$lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Cuentas</h3>

      ".Doc_Ope::lis('dep', Doc_Dat::val_cue('dat', $var['est'], [ 'ide'=>$_ide ]), [ 
        'dep'=>[], 
        'opc'=>['tog','ver','cue'] 
      ]);

      break;
    }
    return $_;
  }// Columnas : por atributos
  static function lis_atr( string | array $dat, array $var = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($var['est']) ){
      
      foreach( self::$Lis['var'] as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= Doc_Dat::lis_atr("{$esq}.{$est}",$est_ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      // proceso estructura de la base
      if( $_val_dat = is_string($dat) ){
        extract( Dat::ide($dat) );      
      }
      $var_nav = isset($var['atr_nav']) ? $var['atr_nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($var['atr_dat']) ? $var['atr_dat'] : ( $_val_dat ? Dat::est($esq,$est,'atr') : Dat::est_atr($dat) );
      // ocultos por estructura
      $atr_ocu = isset($var['atr_ocu']) ? $var['atr_ocu'] : [];
      // genero columnas :
      $ele['cab_ite']['scope'] = "col";
      foreach( ( isset($var['atr']) ? $var['atr'] : array_keys($dat_atr) ) as $atr ){
        $ele_ite = $ele['cab_ite'];
        if( $_val_dat ){
          $ele_ite['data-esq'] = $esq;
          $ele_ite['data-est'] = $est;
        } 
        $ele_ite['data-atr'] = $atr;
        if( in_array($atr,$atr_ocu) ) Ele::cla($ele_ite,"dis-ocu");
        
        // poner enlaces
        $htm = Doc_Val::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( $var_nav ) $htm = "<a href='".SYS_NAV."{$var['atr_nav']}' target='_blank'>{$htm}</a>";
        
        // ...agregar operadores ( iconos )
        $htm_ope = "";
        $_ .= "
        <th".Ele::atr($ele_ite).">
          <p class='ide'>{$htm}</p>
          {$htm_ope}
        </th>";
      }         
    }
    return $_;
  }// Descripción : por posición, titulo o detalle
  static function lis_des( string $tip, int $pos, array $var, array $ele ) : string {
    $_ = "";

    foreach( Obj::pos_ite($var[$tip][$pos]) as $val ){ 
      $_ .= "
      <tr".Ele::atr($ele["{$tip}_ite"]).">
        <td".Ele::atr(Ele::val_jun([ 'data-ope'=>$tip, 'colspan'=>$var['atr_tot'] ], $ele["{$tip}_val"])).">
          ".( is_array($val) ? Ele::val($val) : "<p class='{$tip} tex_ali-izq'>".Doc_Val::let($val)."</p>" )."
        </td>
      </tr>";
      return $_;
    }
    return $_;
  }// Titulo por : posicion + ciclos + agrupaciones
  static function lis_tit( string $tip, string | array $dat, mixed $val, array $var = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( Dat::ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      
      // Busco ficha
      if( in_array('ite_ocu',$opc) ) Ele::cla($ele['ite'],'dis-ocu'); $_ .= "
      <tr".Ele::atr($ele['ite']).">
        <td".Ele::atr($ele['atr']).">
          ".Doc_Dat::fic($ide,$val)."
        </td>
      </tr>";
    }
    // ciclos + agrupaciones
    else{
      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        Ele::cla($ele['atr'],"anc-100");
      }
      // por ciclos : secuencias
      if( $tip == 'cic' ){
        // acumulo posicion actual, si cambia -> imprimo ciclo
        if( isset($var['cic_val']) ){

          $val = Dat::get($esq,$est,$val);
          // actualizo ultimo titulo para no repetir por cada item
          foreach( $var['cic_val'] as $atr => &$pos ){
            
            if( !empty($_ide = Dat::est_rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= Doc_Dat::lis_tit('pos',$dat,[$atr,$_ide['dat'],$val->$atr],$var,$ele,$opc);
              }
              self::$Lis['var'][$esq][$est]['cic_val'][$atr] = $pos = $val->$atr;
            }
          }
        }
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){

        if( isset($var["tit_$tip"]) ){

          foreach( $var["tit_$tip"] as $atr ){

            if( !empty($_ide = Dat::est_rel($esq,$est,$atr)) ){

              foreach( Dat::get($_ide['esq'],$_ide['est']) as $val ){

                $_ .= Doc_Dat::lis_tit('pos',$dat,[$atr,$_ide['dat'],$val],$var,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }// Fila : valores de la estructura|objetos
  static function lis_ite( string | array $dat, mixed $val, array $var = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    $opc_ima = !in_array('ima',$opc);
    $opc_var = in_array('var',$opc);
    $opc_htm = in_array('htm',$opc);
    $atr_ocu = isset($var['atr_ocu']) ? $var['atr_ocu'] : FALSE;
    // proceso estructura de la base
    if( is_string($dat) ){

      extract( Dat::ide($dat) );
      $_atr = Dat::est($esq,$est,'atr');

      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;

      $est_ima = Dat::est($esq,$est,'opc.ima');
      // recorro atributos y cargo campos

      foreach( $var['atr'] as $atr ){

        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) Ele::cla($ele_dat,'dis-ocu');
        
        // contenido
        $ele_val = $ele['val'];

        $tip = "";
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          Ele::cla($ele_val,"tam-5 mar_hor-1");
          $tip = 'ima';
        }
        // variables
        else{
          // adapto estilos por tipo de valor
          if( !empty($_atr[$atr]) ){
            $var_dat = $_atr[$atr]->var_dat;
            $var_val = $_atr[$atr]->var_val;
          }
          elseif( !empty( $_var = Dat::tip($val) ) ){
            $var_dat = $_var->dat;
            $var_val = $_var->val;
          }
          else{
            $var_dat = "val";
            $var_val = "nul";
          }
          // - limito texto vertical
          if( $var_dat == 'tex' ){
            if( $var_dat == 'par' ) Ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
          }
          $ele_dat['data-val_dat'] = $var_dat;
          $ele_dat['data-val_tip'] = $var_val;
          $tip = $opc_var ? 'var' : 'val';
        }

        $htm = Doc_Dat::ver($tip,"{$esq}.{$est}.{$atr}",$val,$ele_val);

        if( $tip == "ima" ) $htm = "<div class='-val'>$htm</div>";
        $ele_dat['data-val'] = $tip;
        $_ .= "
        <td".Ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? Ele::cla($ele_dat,"dis-ocu") : $ele_dat ).">      
          {$htm}
        </td>";
      }
    }
    // por listado del entorno
    else{
      $_val_obj = is_object($val);
      foreach( $var['atr'] as $ide ){
        // valor
        $dat_val = $_val_obj ? $val->{$ide} : $val[$ide];
        // html
        if( $opc_htm ){
          $htm = $dat_val;
        }
        // elementos
        elseif( is_array( $dat_val ) ){
          $htm = isset($dat_val['htm']) ? $dat_val['htm'] : Ele::val($dat_val);
        }
        // textos
        else{
          $htm = Doc_Val::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td".Ele::atr($ele['dat_val']).">
          {$htm}
        </td>";
      }
    }      
    return $_;
  }// Detalle por : posicion + descripciones + lecturas
  static function lis_det( string $tip, string | array $dat, mixed $val, array $var = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // 1 detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $val = $val[1];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      if( in_array('ite_ocu',$opc) ) Ele::cla($ele['ite'],'dis-ocu');
      $_ = "
      <tr".Ele::atr($ele['ite']).">
        <td".Ele::atr($ele['atr']).">
          <p class='tex des'>".Doc_Val::let($val->$atr)."</p>
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($var["det_{$tip}"]) ){

      if( is_string($dat) ){
        extract( Dat::ide($dat) );
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
        $val = Dat::get($esq,$est,$val);        
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        Ele::cla($ele['atr'],"anc-100");
      }

      foreach( $var["det_{$tip}"] as $atr ){
        $_ .= Doc_Dat::lis_det('pos',$dat,[$atr,$val],$var,$ele,$opc);
      }
    }

    return $_;
  }
}