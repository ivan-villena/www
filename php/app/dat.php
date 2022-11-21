<?php
// Dato : valores, opciones, imagenes, informe, ficha, listados
class app_dat {

  static string $IDE = "app_dat-";
  static string $EJE = "app_dat.";

  // armo valores ( esq.est ): nombre, descripcion, imagen
  static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_dat::ide($ide) );
    // cargo datos
    $_dat = api::dat($esq,$est,$dat);
    // cargo valores
    $_val = app::dat($esq,$est,'val');

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? api_obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n".api_obj::val($_dat,$_val['des']) : "");
    }
    // por atributos con texto : nom + des + ima 
    elseif( isset($_val[$tip]) ){

      if( is_string($_val[$tip]) ) $_ = api_obj::val($_dat,$_val[$tip]);
    }

    // ficha
    if( $tip == 'ima' ){
      // identificador
      api_ele::cla($ele,"{$esq}_{$est} ide-{$_dat->ide}",'ini');
      
      // cargo titulos
      if( !isset($ele['title']) ){
        $ele['title'] = app_dat::val('tit',"$esq.$est",$_dat);
      }
      elseif( $ele['title'] === FALSE  ){
        unset($ele['title']);
      }
      
      // acceso a informe
      if( !isset($ele['onclick']) ){
        if( app::dat($esq,$est,'inf') ) api_ele::eje($ele,'cli',"app_dat.inf('$esq','$est',".intval($_dat->ide).")");
      }
      elseif( $ele['onclick'] === FALSE ){
        unset($ele['onclick']);
      }
      
      $_ = app::ima( [ 'style' => $_ ], $ele );
    }
    // variable
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = app::let($_);
      $_ = api_ele::val($ele);
    }    

    return $_;
  }
  // por seleccion ( esq.est.atr ) : en tablero y estructura : variable, html, ficha, color, texto, numero, fecha
  static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_dat::ide($ide) );
    // parametros: "esq.est.atr" 
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = api::dat($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }

    if( is_object($dat) && isset($dat->$atr) ){
      
      $_atr = api_dat::atr($esq,$est,$atr);
      // variable por tipo
      if( $tip == 'var' ){
        $_var = $_atr->var;
        $_var['val'] = $dat->$atr;
        $_ = api_ele::val($_val);
      }// proceso texto con letras
      elseif( $tip == 'htm' ){

        $_ = app::let($dat->$atr);
      }// color en atributo
      elseif( $tip == 'col' ){
        
        if( $col = api_dat::opc('col',$esq,$est,$atr) ){
          $_ = "<div".api_ele::atr(api_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
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
        if( !empty($_ima) || !empty( $_ima = api_dat::opc('ima',$esq,$est,$atr) ) ){
          
          $_ = app::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
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
          $_ = "<n".api_ele::atr($ele).">{$dat->$atr}</n>";
        }
        elseif( $tip == 'tex' ){
          $_ = "<p".api_ele::atr($ele).">".app::let($dat->$atr)."</p>";
        }
        elseif( $tip == 'fec' ){
          $ele['value'] = $dat->$atr;
          $_ = "<time".api_ele::atr($ele).">".app::let($dat->$atr)."</time>";
        }
        else{
          $_ = app::let($dat->$atr);
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
      $ope['ope'] = api_ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
      if( !empty($opc_ope_tam) ) $ope['ope'] = api_ele::css($ope['ope'],$opc_ope_tam);
      // oculto items
      $cla = DIS_OCU;
      // copio eventos
      if( $ele_eje ) $ele_est['eti'] = api_ele::eje($ele_est['eti'],'cam',$ele_eje);
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
    if( is_string($dat) || api_obj::pos($dat) ){
      $_ide = is_string($dat) ? explode('.',$dat) : $dat;
      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = app::dat($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          // cargo atributo
          $_atr = api_dat::atr($esq,$est,$atr);
          $atr_nom = $_atr->nom;
          if( $_atr->ide == 'ide' && empty($_atr->nom) && !empty($_atr_nom = api_dat::atr($esq,$est,'nom')) ){
            $atr_nom = $_atr_nom->nom;
          }
          // armo identificador
          $dat = "{$esq}.".api_dat::rel($esq,$est,$atr);
          $_ []= [
            'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat,
            'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 
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
        
        if( $dat_opc_est = app::dat($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            // redundancia de esquemas
            $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
            // datos de la estructura relacional
            $_est = api_dat::est($esq_ide,$dep_ide);
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
      $_ .= app_lis::opc($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }
    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= app_lis::opc($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }
    // selector de atributo con nombre de variable por operador
    $_ .= app_lis::opc($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = api_ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='val'>
        ".app_lis::opc( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }

    return $_;
  }
  // Descripciones : imagen + nombre > ...atributos
  static function des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    
    $_ = [];
    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'val';
    // atributos de la estructura
    $atr = api_lis::ite($atr);
    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;
    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    api_ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists($_cla = "_$esq") ){

      foreach( $_cla::_($est) as $pos => $_dat ){ 
        $htm = 
        app::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>
            ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".api_obj::val($_dat,$des) : "" )."
          </dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".( preg_match("/_des/",$ide) ? "<q>".app::let($_dat->$ide)."</q>" : app::let($_dat->$ide) )."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }

    return app_lis::$tip( $_, $ele, ...$opc );
  }
  // Atributos : listado por => atributo: "valor"
  static function atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    // cargo dato
    if( !is_object($dat) ) $dat = api::dat($esq,$est,$dat);
    // cargo atributos
    $dat_atr = api_dat::atr($esq,$est);      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){       
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ 
        $_atr = $dat_atr[$atr];
        $val = $dat->$atr;
        if( is_numeric($val) && isset($_atr->var['dat']) ){
          // busco nombres /o/ iconos
          $atr_ide = explode('.',$_atr->var['dat']);
          $atr_dat = app::dat( $atr_ide[0], $atr_ide[1], 'val');
          $atr_obj = api::_("{$atr_ide[0]}_{$atr_ide[1]}", $val);
          if( isset($atr_dat['nom']) ){
            $val = app::let( api_obj::val($atr_obj,$atr_dat['nom']) );
            if( isset($atr_dat['des']) && !$_opc_des ){
              $val .= "<br>".app::let(api_obj::val($atr_obj,$atr_dat['des']));
            }
          }
          $val = str_replace($dat_atr[$atr]->nom,"<b class='ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          $val = "<b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".app::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = app_lis::ite($ite,$ope);          

    return $_;
  }
  // Imagenes : listado por => { ... ; ... }
  static function ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
    // busco valores
    if( isset($val) ) $val = api::dat($esq,$est,$val);
    // busco atributos en : _dat.est.ope
    if( empty($atr) ) $atr = app::dat($esq,$est,'fic.ima');
    // Elementos
    if( !isset($ope['ima']) ) $ope['ima'] = [];
    if( empty($ope['ima']['class']) ) api_ele::cla($ope['ima'],"tam-4");
    $_ = "
    <ul class='val'>
      <li><c>{</c></li>";        
      foreach( $atr as $atr ){
        $_ima = api_dat::opc('ima',$esq,$est,$atr); $_ .= "
        <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
          ".( isset($val->$atr) ? app::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
        </li>";
      } $_ .= "
      <li><c>}</c></li>
    </ul>";
    return $_;
  }
  // Ficha : valor.ima => { ...imagen por atributos } 
  static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    // proceso estructura
    extract( api_dat::ide($ide) );
    if( !in_array('det',$opc) ){
      if( ( $_fic = app::dat($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

        "<div class='val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
        
          ( !empty($val) ? app::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."
  
        </div>";
        // imágenes de atributos
        if( !empty($_fic[1]) ){ $_ .= "
          <c class='sep'>=></c> 
          ".app_dat::ima($esq,$est,$_fic[1], $val);
        }
      }
    }// con titulo y detalle
    else{
      $_val = app::dat($esq,$est,'val');
      $_dat = api::_("{$esq}_{$est}",$val);
      $_ .= "
      <div class='val'>";
      if( isset($_val['ima']) ){
        $_ .= app::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
      }
      if( isset($_val['nom']) || isset($_val['des']) ){ $_.="
        <div class='tex_ali-izq'>";
        if( isset($_val['nom']) ){
          $_ .= "<p class='tit'>".app::let(api_obj::val($_dat,$_val['nom']))."</p>";
        }elseif( isset($_dat->nom) ){
          $_ .= "<p class='tit'>".app::let($_dat->nom)."</p>";
        }
        if( isset($_val['des']) ){
          $_ .= "<p class='des'>".app::let(api_obj::val($_dat,$_val['des']))."</p>";
        }elseif( isset($_dat->des) ){
          $_ .= "<p class='des'>".app::let($_dat->des)."</p>";
        }$_ .= "
        </div>";
      }$_ .= "
      </div>";
    }
    return $_;
  }
  // Informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
  static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
    $_ = "";      
    if( $_inf = isset($ope) ? $ope : app::dat($esq,$est,'inf') ){
      // cargo atributos
      $_atr = api_dat::atr($esq,$est);
      // cargo datos
      $_dat = api::dat($esq,$est,$dat);
      // cargo valores
      $_val = app::dat($esq,$est,'val');
      // opciones
      $opc = [];
      if( isset($_inf['opc']) ){ $opc = api_lis::ite($_inf['opc']); unset($_inf['opc']); }

      // nombre: 
      if( in_array('nom',$opc) && isset($_dat->nom) ){ $_ .= "
        <p class='tit mar-0'>".app::let($_dat->nom)."</p>";
      }// por valor
      elseif( isset($_val['nom'])  ){ $_ .= "
        <p class='tit mar-0'>".app::let(api_obj::val($_dat,$_val['nom']))."</p>";
      }
      // descripciones
      $_ .= "
      <section>";
        $opc_cit = in_array('des-cit',$opc);
        if( isset($_inf['des']) ){
          if( is_array(($_inf['des'])) ){
            foreach( $_inf['des'] as $des ){
              if( isset($_dat->$des) ) 
                $_ .= $opc_cit ? "<p class='des'><q>".app::let($_dat->$des)."</q></p>" : "<p class='des'>".app::let($_dat->$des)."</p>";
            }
          }else{
            foreach( explode("\n",$_inf['des']) as $des ){ $_ .= "
              <p class='des'>".app::let(api_obj::val($_dat,$des))."</p>";
            }
          }
        }
        if( in_array('des',$opc) && isset($_dat->des) ){
          $_ .= $opc_cit ? "<p class='des'><q>".app::let($_dat->des)."</q></p>" : "<p class='des'>".app::let($_dat->des)."</p>";
        }
        // imagen + atributos | lectura
        if( !empty($_val['ima']) || ( !empty($_inf['atr']) || !empty($_inf['cit']) ) )
        $_ .= "
        <div class='val jus-cen'>";
          if( !empty($_val['ima']) ){ // 'onclick'=>FALSE
            $_ .= app::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
          }
          if( !empty($_inf['atr']) ){
            $_ .= app_dat::atr($esq,$est,$_inf['atr'],$_dat);
            unset($_inf['atr']);
          }
          elseif( !empty($_inf['cit']) ){
            if( isset($_dat->{$_inf['cit']}) ) $_ .= "
            <q class='mar-1'>".app::let($_dat->{$_inf['cit']})."</q>";
            unset($_inf['cit']);
          }
          $_ .= "
        </div>";
        $_ .= "
      </section>";
      foreach( $_inf as $inf_ide => $inf_val ){ 
        $inf_ide_pri = explode('-',$inf_ide)[0];
        if( $inf_sep = !in_array($inf_ide_pri,['htm']) ){ $_ .= "
          <section class='".( $inf_ide_pri != 'tab' ? 'ali_pro-cre' : '' )."'>";
        }
        switch( $inf_ide_pri ){
        // detalles : por atributos o por valor
        case 'det':
          if( is_array($inf_val) ){
            foreach( $inf_val as $det ){
              if( isset($_dat->$det) ){
                foreach( explode("\n",$_dat->$det) as $tex_par ){
                  $_ .= "<p>".app::let($tex_par)."</p>";
                }
              }
            }
          }else{
            foreach( explode("\n",$inf_val) as $tex_par ){
              $_ .= "<p>".app::let(api_obj::val($_dat,$tex_par))."</p>";
            }
          }
          break;
        // titulos con descripcion
        case 'tit':
          if( is_array($inf_val) ){
            foreach( $inf_val as $tit ){
              if( isset($_atr[$tit]) && isset($_dat->$tit) ){ $_ .= "
                <p class='tit'>{$_atr[$tit]->nom}</p>";
                foreach( explode("\n",$_dat->$tit) as $tex_par ){                
                  $_ .= "<p class='des'>".app::let($tex_par)."</p>";
                }
              }
            }
          }
          break;                       
        // lecturas con "" , alineado, con/sin titulo por atributo
        case 'lec':
          if( is_array($inf_val) ){
            $agr_cla = preg_match("/-lis/",$inf_ide) ? " tex_ali-izq" : "";
            $agr_tit = !preg_match("/-tit/",$inf_ide);
            foreach( $inf_val as $lec ){
              if( isset($_atr[$lec]) && isset($_dat->$lec) ) 
              if( $agr_tit ) $_ .= "
              <p class='tit{$agr_cla}'>{$_atr[$lec]->nom}</p>";
              $_ .= "
              <p class='cit{$agr_cla}'><q>".app::let($_dat->$lec)."</q></p>";
            }
          }else{
            $_ .= "<p class='cit'><q>".app::let(api_obj::val($_dat,$inf_val))."</q></p>";
          }
          break;
        // Fichas : por atributos con Relaciones
        case 'fic':
          if( is_array($inf_val) ){
            foreach( $inf_val as $ide ){
              if( isset($_atr[$ide]) && isset($_atr[$ide]->var['dat']) && isset($_dat->$ide) ){
                if( is_numeric($_dat->$ide) ){
                  $_ .= app_dat::fic($_atr[$ide]->var['dat'],$_dat->$ide,[ 'opc'=>[ "det" ] ]);
                }// listado de imagenes por valor
                elseif( is_string($_dat->$ide) ){
                  if( !isset($sep) ){
                    $sep = ( preg_match("/, /",$_dat->$ide) ) ? ", " : (  preg_match("/\s*\-\s*/",$_dat->$ide) ? " - " : FALSE );
                  }                  
                  if( $sep ){
                    // valores
                    $ran_lis = [];
                    if( $sep == ', ' ){
                      $ran_lis = explode($sep,$_dat->$ide);
                    }else{
                      $ran_sep = explode($sep,$_dat->$ide);
                      if( isset($ran_sep[0]) && isset($ran_sep[1]) ){
                        $ran_lis = range(api_num::val($ran_sep[0]), api_num::val($ran_sep[1]));
                      }
                    }  
                    foreach( $ran_lis as $atr_val ){
                      $_ .= app_dat::fic($_atr[$ide]->var['dat'],$atr_val,[ 'opc'=>[ "det" ] ]);
                    }
                  }
                }                
              }
            }
          }
          break;
        // listado por atributo(\n) con titulo
        case 'lis': 
          if( is_array($inf_val) ){
            foreach( $inf_val as $lis ){
              if( isset($_atr[$lis]) && isset($_dat->$lis) ){ 
                $_ .= "
                <p class='tit'>{$_atr[$lis]->nom}</p>
                <ul class='tex_ali-izq mar-0 mar_ver-2'>";
                foreach( explode("\n",$_dat->$lis) as $tex_par ){ $_ .= "
                  <li>".app::let($tex_par)."</li>";
                }$_ .= "
                </ul>";
              }
            }
          }
          break;
        // Tablero por identificador
        case 'tab':
          // convierto posibles valores ($)          
          if( isset($inf_val[1]) ) $inf_val[1] = api_obj::var($inf_val[1],$_dat);
          // defino identificador del tablero
          if( !isset($inf_val[1]['ide']) ) $inf_val[1]['ide'] = isset($_dat->ide) ? $_dat->ide : FALSE;
          // armo tablero
          extract( api_dat::ide($inf_val[0]) );
          if( $atr && method_exists("app_tab",$esq) ){
            $_ .= app_tab::$esq( $est, $atr, $inf_val[1], isset($inf_val[2]) ? $inf_val[2] : [] );
          }
          break;
        // Texto por valor: parrafos por \n
        case 'tex':
          foreach( api_lis::ite($inf_val) as $tex ){
            foreach( explode("\n",$tex) as $tex_val ){
              $_ .= "<p>".app::let(api_obj::val($_dat,$tex_val))."</p>";
            }
          }
          break;          
        // Contenido HTML : textual o con objetos
        case 'htm':
          if( is_string($inf_val) ){
            $_ .= api_obj::val($_dat,$inf_val);
          }else{
            foreach( api_lis::ite($inf_val) as $ele ){
              // convierto texto ($), y genero elemento/s
              $_ .= api_ele::val( api_obj::var($ele,$_dat) );
            }
          }
          break;
        // Ejecuciones : por clase::metodo([...parametros])
        case 'eje':
          // convierto valores ($), y ejecuto por identificador
          $_ .= api_eje::val( $inf_val['ide'], isset($inf_val['par']) ? api_obj::var($inf_val['par'],$_dat) : [] );
          break;
        }
        if( $inf_sep ){ $_ .= "
          </section>";
        }
      }
    }
    return $_;
  }
  // Posiciones : listado de atributos con ficha + nombre ~ descripcion ~ posicion
  static function pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];
    foreach( app::dat($esq,$est,'pos') as $ite ){
      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( api_dat::ide($ite['ide']) );
      $ope_atr = api_dat::atr($esq,$est);

      foreach( $ite['atr'] as $atr ){
        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;
        
        $_atr = isset($ope_atr[$atr]->var) ? $ope_atr[$atr]->var : [];
        $_ide = explode('.', $ide = isset($_atr['dat']) ?  $_atr['dat'] : "{$esq}.{$est}_{$atr}" );                
        $_dat = api_hol::_($_ide[1],$val);        
        $_val = app::dat($_ide[0],$_ide[1],'val');
        
        $htm = "";
        if( isset($_val['ima']) ) $htm .=
          api_hol::ima($_ide[1], $_dat, [ 'class'=>"tam-3 mar_der-1" ]);
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".app::let( app_dat::val('nom',$ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".app::let( app_dat::val('des',$ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            app_var::num('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    return app_lis::val($_,$ele);
  }
  // Listado
  static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      api_ele::cla($ele['lis'], "ide-{$ide}",'ini');
      $_ = app_lis::$tip($dat, $ele);
    }
    return $_;
  }
}