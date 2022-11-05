<?php

// Dato : valores, opciones, imagenes, informe, ficha, listados
class _app_dat {

  static string $IDE = "_app_dat-";
  static string $EJE = "_app_dat.";

  // armo valores ( esq.est ): nombre, descripcion, imagen
  static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( _dat::ide($ide) );
    // cargo datos
    $_dat = _dat::get($esq,$est,$dat);
    // cargo valores
    $_val = _app::dat($esq,$est,'val');

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? _obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n"._obj::val($_dat,$_val['des']) : "");
    }
    // por atributos con texto : nom + des + ima 
    elseif( isset($_val[$tip]) ){

      if( is_string($_val[$tip]) ) $_ = _obj::val($_dat,$_val[$tip]);
    }

    // ficha
    if( $tip == 'ima' ){

      _ele::cla($ele,"{$esq}_{$est} ide-{$_dat->ide}",'ini');
      // cargo titulos
      if( !isset($ele['title']) ){
        $ele['title'] = _app_dat::val('tit',"$esq.$est",$_dat);
      }
      elseif( $ele['title'] === FALSE  ){          
        unset($ele['title']);
      }
      // acceso a informe
      if( !isset($ele['onclick']) ){
        if( _app::dat($esq,$est,'inf') ) _ele::eje($ele,'cli',"_app_dat.inf('$esq','$est',".intval($_dat->ide).")");
      }
      elseif( $ele['onclick'] === FALSE ){
        unset($ele['onclick']);
      }
      $_ = _app::ima( [ 'style' => $_ ], $ele );
    }
    // variable
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = _app::let($_);
      $_ = _ele::val($ele);
    }    

    return $_;
  }
  // por seleccion ( esq.est.atr ) : en tablero y estructura : variable, html, ficha, color, texto, numero, fecha
  static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( _dat::ide($ide) );
    // parametros: "esq.est.atr" 
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = _dat::get($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }

    if( is_object($dat) && isset($dat->$atr) ){
      
      $_atr = _dat::atr($esq,$est,$atr);
      // variable por tipo
      if( $tip == 'var' ){
        $_var = $_atr->var;
        $_var['val'] = $dat->$atr;
        $_ = _ele::val($_val);
      }// proceso texto con letras
      elseif( $tip == 'htm' ){

        $_ = _app::let($dat->$atr);
      }// color en atributo
      elseif( $tip == 'col' ){
        
        if( $col = _dat::opc('col',$esq,$est,$atr) ){
          $_ = "<div"._ele::atr(_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
        }else{
          $_ = "<div class='err fon-roj' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// imagen en atributo
      elseif( $tip == 'ima' ){

        if( !empty($_atr->var['dat']) ){
          $_ima_ide = explode('.',$_atr->var['dat']);
          $_ima['esq'] = $_ima_ide[0];
          $_ima['est'] = $_ima_ide[1];
        }
        if( !empty($_ima) || !empty( $_ima = _dat::opc('ima',$esq,$est,$atr) ) ){
          
          $_ = _app::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
        }
        else{
          $_ = "<div class='err fon-roj' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// por tipos de dato
      elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

        if( $tip=='tip' ){
          $tip = $_atr->var_dat;
        }
        if( $tip == 'num' ){
          $_ = "<n"._ele::atr($ele).">{$dat->$atr}</n>";
        }
        elseif( $tip == 'tex' ){
          $_ = "<p"._ele::atr($ele).">"._app::let($dat->$atr)."</p>";
        }
        elseif( $tip == 'fec' ){
          $ele['value'] = $dat->$atr;
          $_ = "<time"._ele::atr($ele).">"._app::let($dat->$atr)."</time>";
        }
        else{
          $_ = _app::let($dat->$atr);
        }
      }
      else{

        $_ = $dat->$atr;
      }
    }
    else{
      if( is_null($dat->$atr) ){
        $_ = "<p title='Valor nulo para el objeto _{$esq}.{$est}[{$ide}].{$atr}'></p>";
      }else{
        $_ = "<div class='err fon-roj' title='No existe el atributo {$atr} para el objeto _{$esq}.{$est}[{$ide}]'>{-_-}</div>";
      }      
    }      

    return $_;
  }
  // selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
  static function opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."opc(";
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
    $ele_val = [ 'eti'=>[ 'name'=>"val", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] ];
    if( $opc_esq || $opc_est ){
      // operador por esquemas
      if( $opc_esq ){
        $dat_esq = [];
        $ele_esq = [ 'eti'=>[ 'name'=>"esq", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] ];
      }
      // operador por estructuras
      $ele_est = [ 'eti'=>[ 'name'=>"est", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] ];
      
      // operador por relaciones de atributo
      $ope['ope'] = _ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
      if( !empty($opc_ope_tam) ) $ope['ope'] = _ele::css($ope['ope'],$opc_ope_tam);
      // oculto items
      $cla = DIS_OCU;
      // copio eventos
      if( $ele_eje ) $ele_est['eti'] = _ele::eje($ele_est['eti'],'cam',$ele_eje);
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
    }else{
      if( isset($_val[0]) ) $ope['ope']['val'] = $_val[0];
      if( isset($_val[1]) ){ $ele_val['eti']['val'] = $_val[1]; $dat_val = []; }
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
    if( is_string($dat) || _obj::pos($dat) ){
      $_ide = is_string($dat) ? explode('.',$dat) : $dat;
      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = _app::dat($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          // cargo atributo
          $_atr = _dat::atr($esq,$est,$atr);
          // armo identificador
          $dat = "{$esq}."._dat::rel($esq,$est,$atr);
          $_ []= [
            'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat,
            'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 
            'htm'=>$_atr->nom 
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
        
        if( $dat_opc_est = _app::dat($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            // redundancia de esquemas
            $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
            // datos de la estructura relacional
            $_est = _dat::est($esq_ide,$dep_ide);
            $ite_val = "{$esq_ide}.{$dep_ide}";
            // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
            if( !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) ){
              // con selector de estructura
              if( $opc_est ){
                // cargo opcion de la estructura
                $dat_est[] = [ 'value'=>$ite_val, 'htm'=>isset($_est->nom) ? $_est->nom : $dep_ide ];
                // cargo todos los atributos a un listado general
                array_push($dat_ope, ...$_opc_val);

              }// por agrupador
              else{
                // agrupo por estructura
                $ele_ope['gru'][$_est->ide] = isset($_est->nom) ? $_est->nom : $dep_ide;
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
      $_ .= _app_var::opc_val($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }
    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= _app_var::opc_val($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }
    // selector de atributo con nombre de variable por operador
    $_ .= _app_var::opc_val($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = _ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='val'>
        "._app_var::opc_val( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }

    return $_;
  }
  // glosarios : definiciones por esquema
  static function ide( string | array $ide, array $ele = [] ) : string {

    $_ = [];
    $_ide = explode('.',$ide);
    
    if( is_array( $tex = _dat::get('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }

    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return _app_lis::ite($_,$ele);
  }
  // Descripciones : imagen + nombre > ...atributos
  static function des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    
    $_ = [];
    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'val';
    // atributos de la estructura
    $atr = _lis::ite($atr);
    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;
    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    _ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists($_cla = "_$esq") ){

      foreach( $_cla::_($est) as $pos => $_dat ){ 
        $htm = 
        _app::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>
            ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " "._obj::val($_dat,$des) : "" )."
          </dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".( preg_match("/_des/",$ide) ? "<q>"._app::let($_dat->$ide)."</q>" : _app::let($_dat->$ide) )."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }

    return _app_lis::$tip( $_, $ele, ...$opc );
  }
  // Imagenes : listado por => { ... ; ... }
  static function ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
    // busco valores
    if( isset($val) ) $val = _dat::get($esq,$est,$val);
    // busco atributos en : _dat.est.ope
    if( empty($atr) ) $atr = _app::dat($esq,$est,'fic.ima');
    // Elementos
    if( !isset($ope['ima']) ) $ope['ima'] = [];
    if( empty($ope['ima']['class']) ) _ele::cla($ope['ima'],"tam-4");
    $_ = "
    <ul class='val'>
      <li><c>{</c></li>";        
      foreach( $atr as $atr ){
        $_ima = _dat::opc('ima',$esq,$est,$atr); $_ .= "
        <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
          ".( isset($val->$atr) ? _app::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
        </li>";
      } $_ .= "
      <li><c>}</c></li>
    </ul>";
    return $_;
  }
  // Atributos : listado por => atributo: "valor"
  static function atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."inf_atr _$esq.$est";      
    $_eje = self::$EJE."inf_atr";
    
    // cargo dato
    if( !is_object($dat) ) $dat = _dat::get($esq,$est,$dat);

    // cargo atributos
    $dat_atr = _dat::atr($esq,$est);      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ $ite []= "
        <b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> "._app::let($dat->$atr);
      }
    }

    $_ = _app_lis::ite($ite,$ope);          

    return $_;
  }
  // ficha : valor.ima => { ...imagen por atributos } 
  static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( _dat::ide($ide) );
    if( ( $_fic = _app::dat($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

      "<div class='val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
      
        ( !empty($val) ? _app::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."
      </div>";
      // imágenes de atributos
      if( !empty($_fic[1]) ){ $_ .= "
        <c class='sep'>=></c> 
        "._app_dat::ima($esq,$est,$_fic[1], $val);
      }
    }
    return $_;
  }
  // informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
  static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
    $_ = "";      
    if( $_inf = isset($ope) ? $ope : _app::dat($esq,$est,'inf') ){
      // cargo datos
      $_dat = _dat::get($esq,$est,$dat);
      // cargo valores
      $_val = _app::dat($esq,$est,'val');
      // anulo informes de imagenes
      $ele_ima = [];
      if( !isset($ope) ) $ele_ima = [ 'onclick'=>false ];
      
      // opciones
      $opc = [];
      if( isset($_inf['opc']) ){ 
        $opc = _lis::ite($_inf['opc']);
        unset($_inf['opc']);
      }
      // nombre: 
      if( in_array('nom',$opc) && isset($_dat->nom) ){
        $_ .= "<p class='tit'>"._app::let($_dat->nom)."</p>";
      }// por valor
      elseif( isset($_val['nom'])  ){
        $_ .= "<p class='tit'>"._app::let(_obj::val($_dat,$_val['nom']))."</p>";
      }
      // descripciones
      $_ .= "
      <section>";          
        if( in_array('des',$opc) && isset($_dat->des) ){
          $_ .= "<p class='des'>"._app::let($_dat->des)."</p>";
        }// por valor
        elseif( in_array('des-val',$opc) && isset($_val['des'])  ){
          $_ .= "<p class='des'>"._app::let(_obj::val($_dat,$_val['des']))."</p>";
        }
        // imagen + atributos | lectura
        $_ .= "
        <div class='val jus-cen mar-1'>

          "._app::ima($esq,$est,$_dat,_ele::jun([ 'class'=>"mar_der-1" ],$ele_ima));          

          if( !empty($_inf['atr']) ){
            $_ .= _app_dat::atr($esq,$est,$_inf['atr'],$_dat);
            unset($_inf['atr']);
          }
          elseif( !empty($_inf['cit']) ){
            if( isset($_dat->{$_inf['cit']}) ) $_ .= "<q>"._app::let($_dat->{$_inf['cit']})."</q>";
            unset($_inf['cit']);
          }
          $_ .= "
        </div>";
        $_ .= "
      </section>";
      // imprimo por orden
      foreach( $_inf as $inf_ide => $inf_val ){ $_ .= "
        <section>";
        switch( $inf_ide ){
        case 'ima':
          $_ .= _app_dat::ima($esq,$est,$inf_val,$_dat,[ 'ima'=>$ele_ima ]);
          break;
        case 'tab':
          //if( !isset($inf_val[1]) ) $inf_val[2] = [];
          $inf_val[1]['ide'] = $_dat->ide;
          // anulo acceso a informes
          $inf_val[2]['ima']['onclick'] = FALSE;
          $_ .= _app_dat::tab($inf_val[0],$inf_val[1],$inf_val[2]);
          break;
        case 'lec': 
          foreach( _lis::ite($inf_val) as $lec ){
            if( isset($_dat->$lec) ) $_ .= "<q>"._app::let($_dat->$lec)."</q>";
          }
          break;
        case 'des': 
          foreach( _lis::ite($_inf['det']) as $des ){
            $_ .= "<p>"._app::let(_obj::val($_dat,$des))."</p>";
          }
          break;
        }$_ .= "
        </section>";
      }
    }
    return $_;
  }
  // tablero 
  static function tab( $ide, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    extract( _dat::ide($ide) );
    if( $esq && $est && $atr && class_exists($_cla = "_app_{$esq}") && method_exists($_cla,'tab') ){
      $_ .= $_cla::tab( $est, $atr, $ope, $ele );
    }
    return $_;
  }
  // Listado
  static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      _ele::cla($ele['lis'], "ide-{$ide}",'ini');
      $_ = _app_lis::$tip($dat, $ele);
    }
    return $_;
  }
}