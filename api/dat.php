<?php
// Dato : (api).esq.est[ide].atr
class api_dat {

  static string $IDE = "api_dat-";
  static string $EJE = "api_dat.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = api_app::est('dat',$ide,'dat');
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
    }
    return $_;
  }

  // Variable : div.dat_var > label + (select,input,textarea,button)[name]
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    $_VAR = [ 
      'ico'=>"", 
      'nom'=>"", 
      'des'=>"", 
      'ite'=>[], 
      'eti'=>[], 
      'ope'=>[], 
      'htm'=>"", 
      'htm_pre'=>"", 
      'htm_med'=>"", 
      'htm_pos'=>"" 
    ];
    // identificadores
    $dat_ide = is_string($ide) ? explode('.',$ide) : $ide;
    if( isset($dat_ide[2]) ){
      $esq = $dat_ide[0]; 
      $est = $dat_ide[1];
      $atr = $dat_ide[2];
    }
    elseif( isset($dat_ide[1]) ){
      $est = $dat_ide[0];
      $atr = $dat_ide[1];
    }
    else{
      $atr = $dat_ide[0];
    }

    // por atributi de la base
    if( $tip == 'atr' ){

      if( !empty($_atr = api_app::est($esq,$est,'atr',$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = api_app::var($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = api_ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = api_obj::val_jun($ele,$_var);
    }
    // identificadores
    if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
      $ele['ope']['id'] = $ele['ide'];
    }
    // nombre en formulario
    if( empty($ele['ope']['name']) ){
      $ele['ope']['name'] = $atr;
    }      
    // proceso html + agregados
    $agr = api_ele::htm($ele);

    // etiqueta
    $eti_htm='';
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    if( !in_array('eti',$opc) ){
      // icono o texto
      if( !empty($ele['ico']) ){
        $eti_htm = api_fig::ico($ele['ico']);
      }elseif( !empty($ele['nom']) ){    
        $eti_htm = api_tex::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      // agrego for/id e imprimo
      if( !empty($eti_htm) ){    
        if( isset($ele['id']) ){
          $ele['eti']['for'] = $ele['id'];
        }elseif( isset($ele['ope']['id']) ){
          $ele['eti']['for'] = $ele['ope']['id'];
        }
        $eti_htm = "<label".api_ele::atr($ele['eti']).">{$eti_htm}</label>";
      }
    }

    // contenido medio
    if( !in_array('eti_fin',$opc) ){
      $eti_ini = $eti_htm.( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ); 
      $eti_fin = "";
    }else{
      $eti_ini = ""; 
      $eti_fin = ( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ).$eti_htm;
    }

    // valor: hmtl o controlador
    if( isset($agr['htm']) ){
      $val_htm = $agr['htm'];
    }else{
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }
      $val_htm = api_ele::val($ele['ope']);
    }

    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ) $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';

    return "
    <div".api_ele::atr(api_ele::cla($ele['ite'],"dat_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }
  
  // Informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
  static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
    $_ = "";      
    if( $_inf = isset($ope) ? $ope : api_app::est($esq,$est,'inf') ){      
      // cargo atributos
      $_atr = api_app::est($esq,$est,'atr');
      // cargo datos
      $_dat = api_app::dat($esq,$est,$dat);
      // cargo valores
      $_val = api_app::est($esq,$est,'val');
      // cargo opciones
      $opc = [];
      if( isset($_inf['opc']) ){ 
        $opc = api_lis::val_ite($_inf['opc']); 
        unset($_inf['opc']); 
      }

      // Nombre
      if( in_array('nom',$opc) && isset($_dat->nom) ){
        $_ .= api_tex::var('val',$_dat->nom,['class'=>"tit mar-0"]);
      }// por valor
      elseif( isset($_val['nom'])  ){
        $_ .= api_tex::var('val',api_obj::val($_dat,$_val['nom']),['class'=>"tit mar-0"]);
      }

      // Descripcion
      if( in_array('des',$opc) ){
        if( isset($_val['des']) ){
          $_ .= api_tex::var('val',api_obj::val($_dat,$_val['des']),['class'=>"des"]);
        }elseif( isset($_dat->des) ){
          $_ .= api_tex::var('val',$_dat->des,['class'=>"des"]);
        }        
      }

      // Detalle: imagen + atributos
      if( !empty($_val['ima']) || !empty($_inf['det']) ){ 
        $_ .= "
        <div class='doc_val jus-cen mar_arr-1'>";
          if( !empty($_val['ima']) ){
            $_ .= api_fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
          }
          if( !empty($_inf['det']) ){
            $atr = $_inf['det'];
            unset($_inf['det']);
            $_ .= is_array($atr) ? api_dat::lis_atr($esq,$est,$atr,$_dat) : api_tex::var('val',$_dat->$atr,['class'=>"det"]);
          }$_ .= "
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
          $_ .= api_dat::lis_atr($esq,$est,$inf_val,$_dat);
          break;
        // Atributos : valor c/s titulo
        case 'atr':
          $agr_tit = preg_match("/-tit/",$inf_ide);
          foreach( api_lis::val_ite($inf_val) as $atr ){
            if( isset($_dat->$atr) ){
              // titulo
              if( $agr_tit ) $_ .= api_tex::var('val',$_atr[$atr]->nom,['class'=>"tit"]);
              // contenido
              foreach( explode("\n",$_dat->$atr) as $tex_par ){
                $_ .= api_tex::var('val',$tex_par);
              }
            }
          }
          break;
        // Texto por valor: parrafos por \n
        case 'tex':
          foreach( api_lis::val_ite($inf_val) as $tex ){
            // por contenido
            if( is_string($tex) ){
              foreach( explode("\n",$tex) as $tex_val ){
                $_ .= api_tex::var('val',api_obj::val($_dat,$tex_val));
              }
            }// por elemento {<>}
            else{
              foreach( $tex as &$ele_val ){
                if( is_string($ele_val) ) $ele_val = api_obj::val($_dat,$ele_val);
              }
              $_ .= api_ele::val($tex);
            }
          }
          break;          
        // listados : "\n",
        case 'lis':
          foreach( api_lis::val_ite($inf_val) as $lis ){
            if( isset($_atr[$lis]) && isset($_dat->$lis) ){
              // con atributo-titulo
              $_ .= api_tex::var('val',$_atr[$lis]->nom,['class'=>"tit"]).api_lis::pos($_dat->$lis);
            }
          }
          break;
        // Tablero por identificador
        case 'tab':
          $_ .= api_est::tab($inf_val[0], $_dat, isset($inf_val[1]) ? $inf_val[1] : [], isset($inf_val[2]) ? $inf_val[2] : []);
          break;
        // Fichas : por relaciones con valores(", ") o rangos(" - ")
        case 'fic':
          $agr_tit = preg_match("/-tit/",$inf_ide);
          foreach( api_lis::val_ite($inf_val) as $ide ){
            if( isset($_atr[$ide]) && isset($_atr[$ide]->var['dat']) && isset($_dat->$ide) ){
              $dat_ide = explode('_',$_atr[$ide]->var['dat']);
              $dat_esq = array_shift($dat_ide);
              $dat_est = implode('_',$dat_ide);
              // titulo
              if( $agr_tit ) $_ .= api_tex::var('val',$_atr[$ide]->nom,['class'=>"tit"]);
              // pido ficha/s
              $_ .= api_dat::fic("{$dat_esq}.{$dat_est}", $_dat->$ide);
            }
          }
          break;
        // Contenido HTML : textual o con objetos
        case 'htm':
          if( is_string($inf_val) ){
            $_ .= api_obj::val($_dat,$inf_val);
          }else{
            foreach( api_lis::val_ite($inf_val) as $ele ){
              // convierto texto ($), y genero elemento/s
              $_ .= api_ele::val( api_obj::val_lis($ele,$_dat) );
            }
          }
          break;
        // Ejecuciones : por clase::metodo([...parametros])
        case 'eje':
          // convierto valores ($), y ejecuto por identificadorv
          $_ .= api_eje::val( $inf_val['ide'], isset($inf_val['par']) ? api_obj::val_lis($inf_val['par'],$_dat) : [] );
          break;
        }
        if( $inf_sep ){ $_ .= "
          </section>";
        }
      }
    }
    return $_;
  }

  // abm
  static function abm( string $tip, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."ope_{$tip}";
    $_ope = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    switch( $tip ){
    // Navegador
    case 'nav':
      $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
      if( !empty($url) ){
        $url_agr = "{$url}/0";
        $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
      }
      $_ .= "
      <fieldset class='doc_ope' abm='{$tip}'>    
        ".api_fig::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."

        ".api_fig::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."

        ".api_fig::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
      </fieldset>";
      break;
    // Tabla
    case 'est':
      $_ .= "
      <fieldset class='doc_ope'>    
        ".api_fig::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
        
        ".api_fig::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
      </fieldset>";                  
      break;                
    // Registro
    case 'inf':
      $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
      $_ = "
      <fieldset class='doc_ope mar-2 esp-ara'>

        ".api_fig::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

        if( in_array('eli',$ope['opc']) ){

          $_ .= api_fig::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
        }$_ .= "

        ".api_fig::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

      </fieldset>";
      break;              
    }

    return $_;
  }

  // armo valores ( esq.est ): nombre, descripcion, imagen
  static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_app::est_ide($ide) );
    // cargo valores
    $_val = api_app::est($esq,$est,'val');
    // cargo datos/registros
    if(  $tip != 'ima' ) $_dat = api_app::dat($esq,$est,$dat);

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? api_obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n".api_obj::val($_dat,$_val['des']) : "");
    }
    // por atributos con texto : nom + des + ima 
    elseif( isset($_val[$tip]) ){

      if( $tip == 'ima' ){
        if( is_array($_val[$tip]) ) $tip = 'tab';
      }
      elseif( is_string($_val[$tip]) ){ 
        $_ = api_obj::val($_dat,$_val[$tip]);
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

        $_dat = api_app::dat($esq,$est,$dat_val);

        $ele_ima['data-ide'] = $_dat->ide;
      
        // cargo titulos
        if( !isset($ele_ima['title']) ){
          $ele_ima['title'] = api_dat::val('tit',"$esq.$est",$_dat);
        }
        elseif( $ele_ima['title'] === FALSE  ){
          unset($ele_ima['title']);
        }
        
        // acceso a informe
        if( !isset($ele_ima['onclick']) ){
          if( api_app::est($esq,$est,'inf') ) api_ele::eje($ele_ima,'cli',"api_dat.inf('$esq','$est',".intval($_dat->ide).")");
        }
        elseif( $ele_ima['onclick'] === FALSE ){
          unset($ele_ima['onclick']);
        }
        
        $_ .= api_fig::ima( [ 'style' => api_obj::val($_dat,$_val[$tip]) ], $ele_ima );
      }
    }
    // tablero por imagen
    elseif( $tip == 'tab' ){
      $_dat = api_app::dat($esq,$est,$dat);
      $par = $_val['ima'];
      $ele_ima = $ele;
      $ele = isset($par[2]) ? $par[2] : [];
      $ele['sec'] = api_ele::val_jun($ele_ima,isset($ele['sec']) ? $ele['sec'] : []);
      api_ele::cla($ele['sec'],"ima");
      $_ = api_est::tab($par[0], $_dat, isset($par[1]) ? $par[1] : [], $ele);
    }
    // variable por dato
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos por valor
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = api_tex::let($_);
      $_ = api_ele::val($ele);
    }    

    return $_;
  }// busco valor por seleccion ( esq.est.atr ) : variable, html, ficha, color, texto, numero, fecha
  static function val_ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_app::est_ide($ide) );
    // parametros: "esq.est.atr" 
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = api_app::dat($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }

    if( is_object($dat) && isset($dat->$atr) ){
      
      $_atr = api_app::est($esq,$est,'atr',$atr);
      // variable por tipo
      if( $tip == 'var' ){
        $_var = $_atr->var;
        $_var['val'] = $dat->$atr;
        $_ = api_ele::val($_val);
      }// proceso texto con letras
      elseif( $tip == 'htm' ){

        $_ = api_tex::let($dat->$atr);
      }// color en atributo
      elseif( $tip == 'col' ){
        
        if( $col = api_dat::val_ide('col',$esq,$est,$atr) ){ $_ = "
          <div".api_ele::atr(api_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
        }else{
          $_ = "<div class='err' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// imagen en atributo
      elseif( $tip == 'ima' ){

        if( !empty($_atr->var['dat']) ){
          $_ima_ide = explode('_',$_atr->var['dat']);
          $_ima['esq'] = array_shift($_ima_ide);
          $_ima['est'] = implode('_',$_ima_ide);
        }
        if( !empty($_ima) || !empty( $_ima = api_dat::val_ide('ima',$esq,$est,$atr) ) ){
          
          $_ = api_fig::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
        }
        else{
          $_ = "<div class='err' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// por tipos de dato
      elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

        if( $tip=='tip' ){
          $tip = $_atr->var_dat;
        }
        if( $tip == 'num' ){
          $_ = api_num::var('val',$dat->$atr,$ele);
        }
        elseif( $tip == 'tex' ){
          $_ = api_tex::var('val',$dat->$atr);
          
        }
        elseif( $tip == 'fec' ){
          $ele['value'] = $dat->$atr;
          $_ = "<time".api_ele::atr($ele).">".api_tex::let($dat->$atr)."</time>";
        }
        else{
          $_ = api_tex::let($dat->$atr);
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
        $_ = "<div class='err' title='No existe el atributo {$atr} para el objeto _{$esq}.{$est}[{$ide}]'>{-_-}</div>";
      }      
    }      

    return $_;
  }// busco identificadores por seleccion : imagen, color...
  static function val_ide( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
    // dato
    $_ = [ 'esq' => $esq, 'est' => $est ];
    if( !empty($atr) ){
      // armo identificador
      $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  
      // busco dato en atributos
      $_atr = api_app::est($esq,$est,'atr',$atr);
      if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
        $dat = explode('_',$var_dat);
        $_['esq'] = array_shift($dat);
        $_['est'] = implode('_',$dat);
      }
    }
    // valido dato
    if( !empty( $dat_Val = api_app::est($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['ide'] = "{$_['esq']}.{$_['est']}";
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;
  }// armo selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
  static function val_opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."val_opc(";
    $_eje = self::$EJE."val_opc(";

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
    $ele_val = [ 'eti'=>[ 'name'=>"val", 
      'class'=>"mar_ver-1", 'title'=>"Seleccionar el Regisrto por Estructura",
      'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" 
    ] ];
    if( $opc_esq || $opc_est ){
      // operador por esquemas
      if( $opc_esq ){
        $dat_esq = [];
        $ele_esq = [ 'eti'=>[ 'name'=>"esq", 
          'class'=>"mar_ver-1", 'title'=>"Seleccionar el Esquema de Datos...",
          'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" 
        ] ];
      }
      // operador por estructuras
      $ele_est = [ 'eti'=>[ 'name'=>"est", 
        'class'=>"mar_ver-1", 'title'=>"Seleccionar la Estructura de Datos...",
        'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" 
      ] ];
      
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
    if( is_string($dat) || api_lis::val($dat) ){
      $_ide = is_string($dat) ? explode('.',$dat) : $dat;
      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = api_app::est($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          // cargo atributo
          $_atr = api_app::est($esq,$est,'atr',$atr);
          $atr_nom = $_atr->nom;
          if( $_atr->ide == 'ide' && empty($_atr->nom) && !empty($_atr_nom = api_app::est($esq,$est,'atr','nom')) ){
            $atr_nom = $_atr_nom->nom;
          }
          // armo identificador
          $dat = "{$esq}.".api_app::est_rel($esq,$est,$atr);
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
        
        if( $dat_opc_est = api_app::est($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            // redundancia de esquemas
            $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
            // datos de la estructura relacional
            $_est = api_app::est($esq_ide,$dep_ide);
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
      $_ .= api_opc::lis($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }
    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= api_opc::lis($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }
    // selector de atributo con nombre de variable por operador
    $_ .= api_opc::lis($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = api_ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='doc_val'>
        ".api_opc::lis( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }
    return $_;
  }

  // Ficha : imagen + { nombre + descripcion }
  static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_app::est_ide($ide) );
    $_val = api_app::est($esq,$est,'val');
    // proceso valores
    $val_lis = [];
    if( is_numeric($val) ){
      $val_lis = [ $val ];
    }elseif( is_string($val) ){
      $sep = ( preg_match("/, /",$val) ) ? ", " : (  preg_match("/\s-\s/",$val) ? " - " : FALSE );
      if( $sep == ', ' ){
        $val_lis = explode($sep,$val);
      }else{
        $ran_sep = explode($sep,$val);
        if( isset($ran_sep[0]) && isset($ran_sep[1]) ){
          $val_lis = range(api_num::val($ran_sep[0]), api_num::val($ran_sep[1]));
        }
      }
    }elseif( is_array($val) ){
      $val_lis = $val;
    }
    // armo fichas
    foreach( $val_lis as $val ){
      // cargo datos
      $_dat = ( class_exists($cla = "api_$esq") && method_exists($cla,'_') ) ? $cla::_($est,$val) : [];
      $_ .= "
      <div class='doc_val'>";
  
        if( isset($_val['ima']) ) $_ .= api_fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
  
        if( isset($_val['nom']) || isset($_val['des']) ){ $_.="
          <div class='tex_ali-izq'>";
            if( isset($_val['nom']) ){
              $_ .= api_tex::var('val',api_obj::val($_dat,$_val['nom']),['class'=>"tit"]);
            }
            if( isset($_val['des']) ){
              $_ .= api_tex::var('val',api_obj::val($_dat,$_val['des']),['class'=>"des"]);
            }
            $_ .= "
          </div>";
        }$_ .= "
      </div>";
    }
    return $_;
  }// Valores: .ima => { ...imagen por atributos } 
  static function fic_atr( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    extract( api_app::est_ide($ide) );
    if( ( $_fic = api_app::est($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

      "<div class='doc_val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
      
        ( !empty($val) ? api_fig::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."

      </div>";
      // imágenes de atributos
      if( !empty($_fic[1]) ){ $_ .= "
        <c class='sep'>=></c> 
        ".api_dat::fic_ima($esq,$est,$_fic[1], $val);
      }
    }
    return $_;
  }// Imagenes : listado por => { ... ; ... }
  static function fic_ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
    // Valores
    if( isset($val) ) $val = api_app::dat($esq,$est,$val);
    // Atributos 
    if( empty($atr) ) $atr = api_app::est($esq,$est,'fic.ima');
    // Elementos
    if( !isset($ope['ima']) ) $ope['ima'] = [];
    $_ = "
    <ul class='lis val tam-mov'>
      <li><c>{</c></li>";        
      foreach( $atr as $atr ){
        $_ima = api_dat::val_ide('ima',$esq,$est,$atr); $_ .= "
        <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
          ".( isset($val->$atr) ? api_fig::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
        </li>";
      } $_ .= "
      <li><c>}</c></li>
    </ul>";
    return $_;
  }
  
  // Listado
  static function lis( string | array $dat, string $ide, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      $ele['lis']['data-dat'] = $ide;
      // tipos: pos + ite + tab
      $tip = "dep";
      if( isset($ele['lis_tip']) ){
        $tip = $ele['lis_tip'];
        unset($ele['lis_tip']);
      }
      $_ = api_lis::$tip($dat, $ele);
    }
    return $_;
  }// Glosario : palabras por esquema
  static function lis_ide( string | array $ide, array $ele = [] ) : string {

    $_ = [];
    $_ide = explode('.',$ide);
    
    if( is_array( $tex = api_app::dat('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }
    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return api_lis::pos($_,$ele);

  }// Atributos : item => Atributo: "valor"
  static function lis_atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    // cargo dato
    if( !is_object($dat) ) $dat = api_app::dat($esq,$est,$dat);
    // cargo atributos
    $dat_atr = api_app::est($esq,$est,'atr');      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){       
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ 
        $_atr = $dat_atr[$atr];
        $val = $dat->$atr;
        if( is_numeric($val) && isset($_atr->var['dat']) ){
          // busco nombres /o/ iconos
          $atr_ide = explode('_',$_atr->var['dat']);
          $atr_esq = array_shift($atr_ide);
          $atr_est = implode('_',$atr_ide);
          $atr_dat = api_app::est($atr_esq,$atr_est,'val');
          $atr_obj = [];
          if( class_exists($atr_cla = $atr_esq) && method_exists($atr_cla,'_') ){
            $atr_obj = $atr_cla::_("{$atr_est}", $val);
          }
          if( isset($atr_dat['nom']) ){
            $val = api_tex::let( api_obj::val($atr_obj,$atr_dat['nom']) );
            if( isset($atr_dat['des']) && !$_opc_des ){
              $val .= "<br>".api_tex::let(api_obj::val($atr_obj,$atr_dat['des']));
            }
          }
          $val = str_replace($dat_atr[$atr]->nom,"<b class='let-ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          $val = "<b class='let-ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".api_tex::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = api_lis::pos($ite,$ope);          

    return $_;
  }// Descripciones : imagen + nombre > ...atributos
  static function lis_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    $_ = [];

    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'dep';

    // atributos de la estructura
    $atr = api_lis::val_ite($atr);

    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;

    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    api_ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists($cla = "api_{$esq}") ){

      foreach( $cla::_($est) as $pos => $_dat ){ 
        $htm = 
        api_fig::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".api_obj::val($_dat,$des) : "" )."</dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".api_tex::let($_dat->$ide)."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }
    return api_lis::$tip( $_, $ele, ...$opc );

  }// Posiciones : ficha + nombre ~ descripcion ~ posicion
  static function lis_pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];

    foreach( api_app::est($esq,$est,'pos') as $ite ){
      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( api_app::est_ide($ite['ide']) );
      $ope_atr = api_app::est($esq,$est,'atr');

      foreach( $ite['atr'] as $atr ){
        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;        
        $_atr = isset($ope_atr[$atr]->var) ? $ope_atr[$atr]->var : [];
        
        // identificadores
        $_ide = explode('_', isset($_atr['dat']) ? $_atr['dat'] : "{$esq}_{$est}_{$atr}" );
        $esq_ide = array_shift($_ide);
        $est_ide = implode('_',$_ide);
        $dat_ide = "{$esq_ide}.{$est_ide}";
        
        // datos
        $_dat = api_app::dat($esq_ide,$est_ide,$val);
        $_val = api_app::est($esq_ide,$est_ide,'val');
        
        $htm = isset($_val['ima']) ? api_fig::ima($esq_ide ,$est_ide, $_dat, [ 'class'=>"tam-3 mar_der-1" ]) : "";
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".api_tex::let( api_dat::val('nom',$dat_ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".api_tex::let( api_dat::val('des',$dat_ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            api_num::var('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    $ele['opc'] = [ "tog","ver","not-sep" ];
    $ele['ope'] = [ 'class'=>"mar_arr-1" ];

    return api_lis::dep($_,$ele);
  }
}