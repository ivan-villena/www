<?php
  // SISTEMA : accesos
  define('SYS_DIR', "C:\\xampp\\htdocs" );
  define('SYS_NAV', "http://localhost/" );
  define('SYS_REC', "http://localhost/_/" );

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Interfaces del sistema //////////////////////////////////////////////////////////////////////////////////
  class _api {

    public object 
      $_uri,
      $_doc,
      $_ses
    ;

    public array

      // documento
      $_ico = [],
      $_let = [],
      $_var = [], // controladores
      $_var_tip = [], // tipos
      $_var_dat = [], // tipos de dato
      $_var_val = [], // tipos de valor
      $_var_ope = [], // tipos de operaciones
      $_val_opc = [], // opciones por operadores
      $_var_ide = [], // identificadores de controladores            

      // datos      
      $_dat = [],
      $_dat_atr = [], // atributos de la base
      $_dat_est = [], // estructuras de la base            
      
      // estructuras-tablas
      $_est = [], 

      // elementos de los tableros
      $_tab = [],

      // numeros
      $_num_int = [],

      // fechas
      $_fec = [],
      $_fec_año = [],
      $_fec_mes = [],
      $_fec_sem = [],
      $_fec_dia = [],
      $_fec_hor = [],
      $_fec_min = [],
      $_fec_seg = []
    ;

    function __construct(){

      $this->_uri = new stdClass;

      $this->_ses = new stdClass;

      $this->_doc = new stdClass;
      
      $this->ses('api');
      
      // documento : iconos + letras
      $this->_ico = _dat::var('_api.ico', [ 'niv'=>['ide'] ]);
      $this->_let = _dat::var('_api.let', [ 'niv'=>['ide'] ]);

      // variable: tipos + operaciones
      $this->_var_tip = _dat::var('_api.var_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
      $this->_var_ope = _dat::var('_api.var_ope', [ 'niv'=>['ide'] ]);

      // fechas : mes + semana + dias
      foreach( ['mes','sem','dia'] as $ide ){

        $this->{"_fec_$ide"} = _dat::var("_api.fec_$ide");
      }
    }

    // peticion : hhtp://esq/cab/art/...val
    function uri( string $esq = 'api' ) : object {      

      // peticion
      $this->_uri->url = !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '';

      // actualizo ultimo valor
      $_SESSION['api-uri'] = $this->_uri->url;
      
      // por separaciones
      $dat = explode('/',$this->_uri->url);
      $this->_uri->esq = isset($dat[0]) ? $dat[0] : $esq;
      $this->_uri->cab = isset($dat[1]) ? $dat[1] : FALSE;
      $this->_uri->art = isset($dat[2]) ? $dat[2] : FALSE;
      $this->_uri->val = isset($dat[3]) ? $dat[3] : FALSE;

      return $this->_uri;
    }

    // directorio : accesos por aplicacion
    function dir( object $uri = NULL ) : object {  

      if( !isset($uri) ){
        $uri = $this->uri();
      }

      $_ = new stdClass();

      $_->rec = SYS_NAV."_/";
      
      $_->esq = SYS_NAV."{$uri->esq}";
        
      $_->cab = "{$uri->esq}/{$uri->cab}";

      $_->ima = SYS_NAV."_/{$_->cab}/";

      if( !empty($uri->art) ){

        $_->art = $_->cab."/{$uri->art}";
      
        $_->ima .= "{$uri->art}/";
      }

      return $_;
    }

    // sesion : datos por esquemas
    function ses( string $esq ) : array {      

      $this->_ses->$esq = [];      

      foreach( $_REQUEST as $i => $v ){

        if( preg_match("/^$esq-/",$i) ){

          $this->_ses->$esq[$i] = $v;
        }
      }

      return $this->_ses->$esq;      
    }

    // página : esquema / cabecera / articulo / valor=dato 
    function doc() : object {
      
      // objeto página
      $this->_doc->css = ['api'];
      $this->_doc->htm = "";
      $this->_doc->jso = ['api','doc'];
      $this->_doc->cod = "";
      
      // cabecera
      $this->_doc->cab_ini = "";// inicio
      $this->_doc->cab_nav = "";// menu : pantallas, secciones
      $this->_doc->cab_fin = "";// fin
            
      // articulo      
      $this->_doc->art_ini = "";// panel izquierdo
      $this->_doc->art_nav = "";// menu : paneles
      $this->_doc->art_fin = "";// panel derecho

      // modales
      $this->_doc->win = "";

      // petición    
      $esq = $this->_uri->esq;
      $cab = $this->_uri->cab;
      $art = $this->_uri->art;
      $val = $this->_uri->val;

      // esquema
      $this->_doc->esq = _dat::var("_api.doc_esq",[
        'ver'=>"`ide`='{$esq}'",
        'opc'=>'uni'
      ]);
      // menú
      if( !empty($cab) ){
        $this->_doc->cab = _dat::var("_api.doc_cab",[
          'ver'=>"`esq`='{$esq}' AND `ide`='{$cab}'",
          'ele'=>'ope',
          'opc'=>'uni'
        ]);
        // artículo
        if( !empty($art) ){
          $this->_doc->art = _dat::var("_api.doc_art",[
            'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}' AND `ide`='{$art}'",
            'ele'=>'ope',
            'opc'=>'uni'
          ]);
          // seccion/valor
          if( !empty($val) ){

            $this->_doc->val = [];

            foreach( explode(';',$val) as $_val ){

              $_val = explode('=',$_val);

              $this->_doc->val[$_val[0]] = isset($_val[1]) ? $_val[1] : NULL;
            }

          }
        }
      }
      // titulo      
      if( !empty($this->_doc->art->nom) ){
        $this->_doc->nom = $this->_doc->art->nom;
      }
      elseif( !empty($this->_doc->cab->nom) ){
        $this->_doc->nom = $this->_doc->cab->nom;
      }
      elseif( !empty($this->_doc->esq->nom) ){
        $this->_doc->nom = $this->_doc->esq->nom; 
      }
      else{
        $this->_doc->nom = "{-_-}";
      }
      return $this->_doc;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
    // get : estructura-objetos
    static function _( string $ide, $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];
      // aseguro carga
      if( !isset($_api->{"_$ide"}) ) $_api->{"_$ide"} = _dat::ini('api',$ide);
      // cargo datos
      $_dat = $_api->{"_$ide"};
      if( !empty($val) ){
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $_ = _fec::dat($val);
            break;
          default:
            if( is_numeric($val) ){
              $ide = intval($val)-1;
              if( isset($_dat[$ide]) ) $_ = $_dat[$ide];
            }
            elseif( isset($_dat[$val]) ){ 
              $_ = $_dat[$val];
            }
            break;
          }
        }
      }// toda la lista
      elseif( isset($_dat) ){
        $_ = $_dat;
      }
      return $_;
    }
    // datos de un proceso : absoluto o con dependencias ( _api.dat->est ) 
    static function dat( string | array $ope, mixed $dat = NULL ){
      global $_api;
      $_ = [];
      if( is_array($ope) ){      
        // cargo temporal
        foreach( $ope as $esq => $est_lis ){
          // recorro estructuras del esquema
          foreach( $est_lis as $est => $dat ){
            // recorro dependencias
            $dat_est = _api::dat_est($esq,$est,'est');
            
            foreach( ( !empty($dat_est) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
              // acumulo valores
              if( isset($dat->$ide) ){
                
                $_["{$esq}-{$ref}"] = $dat->$ide;
              }
            }                            
          }
        }
        $_api->_dat []= $_;
      }
      return $_;
    }     
    // estructuras
    static function dat_est( string $esq, string $ide = NULL, string $ope = NULL ) : array | object {      
      global $_api;
      $_ = [];
      // cargo estructuras de un esquema por operadores
      if( empty($ide) ){

        if( !isset( $_api->_dat_est[$esq] ) ){
          
          foreach( _dat::var("_api.dat_est",[ 'ver'=>"`esq`='{$esq}'", 'niv'=>['ide'], 'obj'=>"ope", 'red'=>"ope" ]) as $est => $_ope ){

            $_api->_dat_est[$esq][$est] = _sql::est("_{$esq}",'ver',$est,'uni');

            $_api->_dat_est[$esq][$est]->ope = $_ope;
          }
        }
        $_ = $_api->_dat_est[$esq];
      }
      else{

        if( !isset($_api->_dat_est[$esq][$ide]) ){ 

          if( is_object( $_api->_dat_est[$esq][$ide] = _sql::est("_{$esq}",'ver',$ide,'uni') ) ){
            // busco operadores
            $_api->_dat_est[$esq][$ide]->ope = _dat::var("_api.dat_est",[
              'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni"
            ]);
          }    
        }
        
        $_ = $_api->_dat_est[$esq][$ide];        
      }
      // devuelvo operador
      if( !empty($ope) ){

        $_ = isset( $_->ope->$ope ) ? $_->ope->$ope : FALSE;
      }

      return $_;
    }
    // atributos 
    static function dat_atr( string $esq, string $est, string | array $ide = NULL ) : array | object | bool {      
      global $_api;

      if( !isset($_api->_dat_atr[$esq]) ) $_api->_dat_atr[$esq] = [];

      if( !isset($_api->_dat_atr[$esq][$est]) ){

        $_api->_dat_atr[$esq][$est] = !empty( _sql::est("_{$esq}",'lis',"_{$est}",'uni') ) ? _sql::atr("_{$esq}","_{$est}") : _sql::atr("_{$esq}",$est);
        
        // cargo operadores del atributo
        $_atr = &$_api->_dat_atr[$esq][$est];
        foreach( _dat::var("_api.dat_atr",['ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'ele'=>'var' ]) as $_api_atr ){

          if( !empty($_api_atr->var) && isset($_atr[$i = $_api_atr->ide]) ){

            $_atr[$i]->var = _ele::jun($_atr[$i]->var, $_api_atr->var);
          }
        }
      }
      // todos
      if( empty($ide) ){
        $_ = $_api->_dat_atr[$esq][$est];
      }// uno
      elseif( is_string($ide) ){
        $_ = isset($_api->_dat_atr[$esq][$est][$ide]) ? $_api->_dat_atr[$esq][$est][$ide] : FALSE;
      }// muchos
      else{
        $_ = [];
        foreach( $ide as $atr ){
          $_ []= isset($_api->_dat_atr[$esq][$est][$atr]) ? $_api->_dat_atr[$esq][$est][$atr] : FALSE;
        }
      }
      return $_;
    }
    // valores
    static function dat_val( string $esq, string $est = NULL, string $ide = NULL ) : array | object | bool {      
      global $_api;
      
      if( !isset($_api->_dat_val[$esq]) ) $_api->_dat_val[$esq] = [];

      if( empty($est) ){
        
        $_ = $_api->_dat_val[$esq] = _dat::var("_api.dat_val",[ 
          'ver'=>"`esq`='{$esq}'", 'niv'=>["est"], 'obj'=>"ope", 'red'=>"ope" 
        ]);
      }
      else{

        if( !isset($_api->_dat_val[$esq][$est]) ){

          $_api->_dat_val[$esq][$est] = _dat::var("_api.dat_val",[ 
            'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni" 
          ]);
        }

        if( empty( $ide ) ){
        
          $_ = $_api->_dat_val[$esq][$est];
        }
        elseif( isset($_api->_dat_val[$esq][$est]->$ide) ){
  
          $_ = $_api->_dat_val[$esq][$est]->$ide;
        }
        else{
  
          $_ = FALSE;
        }
      }
      return $_;
    }  

    // tablero de la aplicacion
    static function tab( string $esq, string $est, array $ele = [] ) : array {
      global $_api;

      if( !isset($_api->_tab[$esq][$est]) ){
        $_api->_tab[$esq][$est] = _dat::var("_api.tab",[ 
          'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'opc'=>'uni', 'ele'=>['ope'], 'red'=>'ope'
        ]);
      }

      $_ = $ele;
      if( !empty($_api->_tab[$esq][$est]) ){
        foreach( $_api->_tab[$esq][$est] as $eti => $atr ){
          $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
        }
      }

      return $_;
    }

    // tabla de la base 
    static function est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;

      if( !isset($_api->_est[$esq][$est]) || isset($ope) ){

        // combinado        
        $_est = _dat::var("_api.est",[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 'obj'=>'ope', 'red'=>'ope',  'opc'=>'uni' ]);

        // cargo atributos por estructura de la base      
        $_atr = _dat::atr($esq,$est);

        // reemplazo atributos por defecto
        if( isset($ope['atr']) ){
          $_est->atr = _var::ite($ope['atr']);
          // descarto columnas ocultas
          if( isset($_est->atr_ocu) ) unset($_est->atr_ocu);
        }
        if( empty($_est->atr) ){
          $_est->atr = !empty($_atr) ? array_keys($_atr) : [];
        }
        if( isset($ope['atr_ocu']) ){
          $_est->atr_ocu = _var::ite($ope['atr_ocu']);
        }

        // calculo totales
        $_est->atr_cue = count($_est->atr);
            
        // descripciones
        $_val['tit'] = isset($ope['tit']);      
        $_val['det'] = isset($ope['det']);      

        // reemplazo e inicializo
        foreach( ['tit'=>['cic','gru'], 'det'=>['des','cic','gru']] as $i => $v ){

          foreach( $v as $e ){
            if( isset($ope["{$i}_{$e}"]) ){
            
              $_est->{"{$i}_{$e}"} = _var::ite($ope["{$i}_{$e}"]);
            }
            elseif( ( !$_val[$i] || !in_array($e,$ope[$i]) ) && isset($_est->{"{$i}_{$e}"}) ){
              unset($_est->{"{$i}_{$e}"});
            }
          }
        }

        // ciclos y agrupaciones: busco descripciones + inicio de operadores      
        foreach( ['cic','gru'] as $ide ){

          if( isset($_est->{"tit_{$ide}"}) ){

            foreach( $_est->{"tit_{$ide}"} as $atr ){
              
              // inicio ciclo
              if( $ide == 'cic' ) $_est->cic_val[$atr] = -1;

              // busco descripciones
              if( isset( $_atr["{$atr}_des"] ) ){
                if( !isset($_est->{"det_{$ide}"}) ) $_est->{"det_{$ide}"}=[]; 
                $_est->{"det_{$ide}"} []= "{$atr}_des";
              }
            }
          }
        }

        $_api->_est[$esq][$est] = $_est;
      }

      return $_api->_est[$esq][$est];
    }
    // cuento columnas totales
    static function est_atr( string | array $dat, array $ope=[] ) : int {
      global $_api;
      $_ = 0;
      if( isset($ope['atr']) ){
        
        $_ = count($ope['atr']);
      }
      // 1 estructura de la base
      elseif( !( $obj_tip = _obj::tip($dat) ) ){

        $ide = _var::ide($dat);

        $dat_est = _api::est($ide['esq'],$ide['est']);

        $_ = isset($dat_est->atr) ? count($dat_est->atr) : 0;

      }
      // n estructuras de la base
      elseif( $obj_tip == 'nom' ){

        foreach( $dat as $esq => $est_lis ){
  
          foreach( $est_lis as $est ){

            $dat_est = _api::est($esq,$est);

            $_ += count($dat_est->atr);
          }
        }
      }
      // por listado                    
      elseif( $obj_tip == 'pos' ){

        foreach( $dat as $ite ){

          foreach( $ite as $val ){ 
            $_ ++; 
          }
          break;
        }
      }
      return $_;
    }

    // controladores de valores variables
    static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_var = &$_api->_var;
      $_ = [];
      
      if( empty($dat) ){
        if( !isset($_var[$esq]) ){
          $_var[$esq] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }elseif( empty($val) ){
        if( !isset($_var[$esq][$dat]) ){
          $_var[$esq][$dat] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }else{
        if( !isset($_var[$esq][$dat][$val]) ){
          $_var[$esq][$dat][$val] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }
      if( !empty($ide) ){
        $_ = isset($_var[$esq][$dat][$val][$ide]) ? $_var[$esq][$dat][$val][$ide] : [];
      }elseif( !empty($val) ){
        $_ = isset($_var[$esq][$dat][$val]) ? $_var[$esq][$dat][$val] : [];
      }elseif( !empty($dat) ){      
        $_ = isset($_var[$esq][$dat]) ? $_var[$esq][$dat] : [];
      }else{
        $_ = isset($_var[$esq]) ? $_var[$esq] : [];
      }

      return $_;
    }
    // incremendo del identificador para operadores-variable
    static function var_ide( string $ope ) : string {
      global $_api;

      if( !isset($_api->_ide[$ope]) ) $_api->_ide[$ope] = 0;

      $_api->_ide[$ope]++;

      return $_api->_ide[$ope];

    }
    // operaciones : ver, ...
    static function var_ope( string $tip, mixed $dat, mixed $ope = [], ...$opc ) : mixed {
      global $_api;
      $_ = [];
      switch( $tip ){
      case 'opc':
              
        if( !isset($_api->_var_ope_opc[$tip][$dat[0]][$dat[1]]) ){

          $ope['dat'] = _dat::var( _api::_('var_ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );
    
          $_api->_var_ope_opc[$tip][$dat[0]][$dat[1]] = _doc_opc::ope('val', '', $ope, ...$opc);
        }
    
        return $_api->_var_ope_opc[$tip][$dat[0]][$dat[1]];
        
        break;
      }
      return $_;
    } 
    
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // código sql //////////////////////////////////////////////////////////////////////////////////////////////
  class _sql {

    // ejecuto codigo sql 
    static function dec( ...$val ){  
      $_ = []; 
      $err=[];
      $eje=[];
      
      $_sql = new mysqli( $_SESSION['ser'], $_SESSION['usu'], $_SESSION['pas'], $_SESSION['esq'] );
    
      $_sql->set_charset("utf8");
    
      if( $_sql->connect_errno ){ 
    
        $err[] = $_sql->connect_error;
      }
      // ejecuto consulta/s
      else{    
        if( count($val) > 1 ){ 
          array_unshift($val,"START TRANSACTION"); 
          array_push($val,"COMMIT"); 
        }

        foreach( $val as $dat ){
    
          if( $sql_res = $_sql->query($dat) ){
            
            if( is_object($sql_res) ){

              while( $row = $sql_res->fetch_object() ){ 

                $_ []= $row; 
              }
            }
            else{
              $_ []= $dat;
            }
          }
          else{
            $var_eve[] = $dat;
            $err[] = $_sql->error;
          }
        }
      }
      // proceso errores
      if( !empty($err) ){
        $_['_err'] = "
        <ul class='lis'>";
        foreach( $err as $i => $v ){ $_['_err'] .= "
          <li>
            ".( isset($var_eve[$i]) ? "<p class='err'>"._doc::let($v)."</p><c class='sep'>=></c><q>"._doc::let($var_eve[$i])."</q>" : "<p class='err'>"._doc::let($v)."</p>" )."
          </li>";
        }$_['_err'] .= "
        </ul>";
      }// resultados
      elseif( isset($sql_res) && is_object($sql_res) ){
        unset($sql_res);
      }
      // cierro conexion
      $_sql->close();

      return $_;
    }
    // codifica instrucciones de consultas
    static function cod( string $ide, mixed $ope = NULL, string $tip='ver' ) : array {
      $ide = explode('.',$ide);
      $_=[
        'est'=>"`{$ide[0]}`.`{$ide[1]}`",
        'atr'=>'',// * / fun(expr) / esq.obj.atr [ TOP num]
        'ver'=>'',// WHERE atr (ope) 'val'/`atr`/expr
        'jun'=>'',// INNER/LEFT/RIGTH JOIN p.tab_2 ON tab_1.atr = tab_2.atr
        'gru'=>'',// GROUP BY esq.obj.atr, atr_2
        'div'=>'',// HAVING 
        'ord'=>'',// ORDER BY esq.obj.atr [ ASC/DESC ], atr_2
        'lim'=>'',// LIMIT 'num'
        // insert, update
        'val'=>''// VALUES ('v_1','2','3', ...)
    
      ];// solo agregar 1 registro :: ( ``,`` ) values( '','' )
      if( $tip=='agr' ){     
        $_['atr'] = [];
        $_['val'] = [];    
        foreach( _var::ite($ope['val']) as $pos=>$ite ){
          $_['ite'] = [];
          foreach( $ite as $i=>$v  ){
            if( $pos==0 )
              $_['atr'] []= "`{$i}`";
            if( is_string($v) ){
              $_['ite'] []= "'".str_replace("'","\'",$v)."'"; 
            }elseif( is_null($v) ) {
              $_['ite'] []= "NULL"; 
            }elseif( is_bool($v) ) {
              $_['ite'] []= !! $v ? "1" : "0";
            }else{
              $_['ite'] []= strval($v);
            }
          }
          $_['val'] []= "( ".implode(',',$_['ite'])." )";
        }
        $_['atr'] = implode(', ',$_['atr']);
        $_['val'] = implode(', ',$_['val']);
      }// solo modificar 1 o más campos de 1 o más registros :: set `atr` = 'val', 
      elseif( $tip=='mod' ){
        $_['val'] = [];
        foreach( $ope['val'] as $i=>&$v ){ 
          if( is_string($v) ){ 
            $val = "'".str_replace("'","\'",$v)."'"; 
          }elseif( is_bool($v) ) {
            $val = !! $v ? "1" : "0";
          }elseif( !is_null($v) ) { 
            $val = strval($v); 
          }else{ 
            $val = "NULL"; 
          } 
          $_['val'] []= " `{$i}` = {$val}";
        }
        $_['val'] = implode(', ', $_['val'])." ";
    
      }// consultar :: campos, juntar, agrupar, ordenar, limitar
      if( $tip == 'ver' ){
        // selecciono campos
        $_['atr']='*';
        if( isset($ope['atr']) ){
          if( is_string($ope['atr']) ){
            $_['atr'] = $ope['atr'];
          }else{          
            $atr = [];
            foreach( $ope['atr'] as $v ){ 
              $atr []= ( substr($v,0,1) == '$' ) ? substr($v,1) : "`{$v}`";
            }
            $_['atr'] = implode(', ',$atr);
          }
        }// joins
        if( isset($ope['jun']) ){
          $_['jun'] .= $ope['jun'];
        }// agrupamiento
        if( isset($ope['gru']) ){
          $_['gru'] = " GROUP BY ".( is_array($ope['gru']) ? implode(', ',$ope['gru']) : $ope['gru'] );
          // condicion de agrupamiento        
          if( isset($ope['div']) ){
            $_['div']=" HAVING ".( is_array($ope['div']) ? implode(', ',$ope['div']) : $ope['div'] );
          }
        }// ordenamiento
        if( isset($ope['ord']) ){ 
          $_['ord'] = " ORDER BY ".( is_array($ope['ord']) ? implode(', ',$ope['ord']) : $ope['ord'] );
        }
        // junto condiciones + limite
        $_['ord'] = $_['ord'].$_['gru'].$_['div'].( isset($ope['lim']) ? " LIMIT ".intval($ope['lim']) : '' );
        
      }// filtros: where `atr` ope val/var/expr
      if( isset($ope['ver']) && $tip!='agr' ){
        if( is_array($ope['ver']) ){
          foreach( $ope['ver'] as $i=>$v ){
            switch( $v[1] ){
            case '==': $v[1]=' = ';                           break;
            case '!=': $v[1]=' <> ';                          break;
            case 'is': $v[1]=' IS ';                          break;// casos nulos y funciones
            case '!s': $v[1]=' NOT IS ';                      break;// casos nulos y funciones
            case '^^': $v[1]=' LIKE ';     $v[2]="{$v[2]}%";  break;
            case '!^': $v[1]=' NOT LIKE '; $v[2]="{$v[2]}%";  break;
            case '**': $v[1]=' LIKE ';     $v[2]="%{$v[2]}%"; break;
            case '!*': $v[1]=' NOT LIKE '; $v[2]="%{$v[2]}%"; break;
            case '$$': $v[1]=' LIKE ';     $v[2]="%{$v[2]}";  break;
            case '!$': $v[1]=' NOT LIKE '; $v[2]="%{$v[2]}";  break;
            default:
              if( $v[1]=='[]' || $v[1]=='!]' ){
                $v[1] = ( $v[1]=='[]' ) ? 'IN(' : 'NOT IN('; 
                if( is_string($v[2]) ){ 
                  $v[2] = explode(',,',$v[2]); 
                } 
                $a['v']='';
                foreach( $v[2] as $dat ){ 
                  $a['v'] .= ( substr($dat,0,3)=='$$' ) ? substr($dat,3).", " : "'{$dat}', " ; 
                } 
                $v[2] = substr($a['v'],0,-2)." )"; 
              }
              break;
            }
            if( $i==0 ){
              $bin=''; 
            }else{ 
              $bin=( isset($v[3]) && ( $v[3]=='OR' || $v[3]=='||' ) )  ? 'OR ' : 'AND ' ; 
            }
            $v[0] = ( substr($v[0],0,1)=='$' ) ? substr($v[0],1) : "`{$v[0]}`";
            if( !isset($a['v']) ){
              $v[2] = ( substr($v[2],0,1)=='$' ) ? substr($v[2],1) : "'{$v[2]}'" ; 
            }
            $_['ver'].=" {$bin}{$v[0]}{$v[1]}{$v[2]}";
          }
        }elseif( is_string($ope['ver']) && !empty($ope['ver']) ){ 
          $_['ver']=" {$ope['ver']}";
        }
        if( !empty($_['ver']) ) $_['ver']=" WHERE{$_['ver']}";
      }
      return $_;
    }
    // esquemas
    static function esq( string $ope='', string $ide, ...$opc ) : string | array | object {
      $_ = [];
      if( empty($ope) ){
        foreach( _sql::dec("SHOW DATABASES") as $esq ){
          $_[] = $esq->Database;
        }
      }else{
        switch( $ope ){
        case 'cop':
          // copio estructuras
          // elimino base inicial
          $_sql []= "DROP SCHEMA `{$esq}`";
          $_ = implode(';<br>',$_sql);
          break;
        }
      }
      return $_;
    }
    // estructuras
    static function est( string $esq, string $ope, $ide='', ...$opc ) : bool | string | array | object {
      $_=[];      
      // valido si existe una vista o una tabla por $ide
      if( $ope == 'val' ){

        $_ = FALSE;
        foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}` WHERE `Name` = '{$ide}'") as $v ){
    
          $_ = ( $v->Comment == 'VIEW' ) ? 'vis' : 'tab';
        }
      }
      else{
        $ver = [];
        // proceso ides
        if( !empty($ide) ){
          if( in_array('uni',$opc) ){
            $ver []= "`Name` = '{$ide}'"; 
          }else{
            $ver []= "`Name` LIKE '".( in_array('ini',$opc) ? "%{$ide}" : ( in_array('tod',$opc) ? "%{$ide}%" : "{$ide}%" ) )."'"; 
          }
        }
        // muestro listado de nombres
        if( $ope == 'lis' ){
      
          if( in_array('vis',$opc) ){ $ver []= "`Comment` = 'VIEW'";  }
      
          if( in_array('tab',$opc) ){ $ver []= "`Comment` <> 'VIEW'"; }
      
          $ver = !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '';
      
          foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}`{$ver}") as $v ){
      
            $_[] = $v->Name;
          }
        }
        // armo datos por estructura/s
        elseif( $ope == 'ver' ){
      
          if( in_array('vis',$opc) ){ 
            $ver []= "`Comment` = 'VIEW'"; 
          }
          elseif( in_array('tab',$opc) ){ 
            $ver []= "`Comment` <> 'VIEW'"; 
          }    
          $ver = !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '';
      
          foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}`{$ver}") as $v ){ 
            $_est = new stdClass();
            $_est->esq = $esq;
            $_est->ide = $v->Name;
            $_est->nom = $v->Comment;
            $_est->fec = $v->Create_time;
            $_[$_est->ide] = $_est;
          }
          // devuelvo uno solo
          if( !empty($ide) && isset($_est) ){
            $_ = $_est;
          }
        }
      }
      return $_;
    }
    // atributos
    static function atr( string $esq, string $est, string $ope='ver', ...$opc ) : array | object | string {
      $_=[];
      $dat_lis = _sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
      if( isset($dat_lis['_err']) ){
        $dat_lis = _sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
      }
      if( $ope == 'lis' ){
        foreach( $dat_lis as $atr ){
          $_[] = $atr->Field;
        }
      }
      elseif( $ope == 'ver' ){
        global $_api;
        $_var = $_api->_var_tip;
        $pos = 0;    
        // si existe una vista, veo esas columnas...
        foreach( $dat_lis as $i => $atr ){
          $pos++;      
          $_tip = explode('(',$atr->Type);      
          if( isset($_tip[1]) ) $var_cue = explode(')',$_tip[1])[0]; 
          $_var_atr = $_var[$sql_tip = $_tip[0]];
          $var_dat = $_var_atr->dat;
          $var_val = $_var_atr->val;
          $var_tip = "{$var_dat}_{$var_val}";
          // operador automático
          $var = !empty($_var_atr->ope) ? $_var_atr->ope : [];
          // tipo de variable
          $var['_tip'] = $var_tip;
          if( empty($var_cue) && isset($var['maxlength']) ){
            $var_cue = $var['maxlength'];
          }
          // tipo de dato
          if( $var_dat == 'num' ){
            // booleano
            if( $atr->Type == 'tinyint(1)' ){
              if( isset($var['min']) ) unset($var['min']);
              if( isset($var['max']) ) unset($var['max']);
              if( isset($var['step']) ) unset($var['step']);
              if( isset($var['maxlength']) ) unset($var['maxlength']);              
              $var_tip = $var['_tip'] = 'opc_bin';              
              $var_dat = "opc";
              $var_val = "bin";
              $var_cue = NULL;
            }// autoincremental
            elseif( preg_match("/auto_increment/",$atr->Extra) ){ 
              $var['num_inc'] = 1;
            }
            else{
              // solo positivos y con relleno izquierdo 000-
              if( preg_match("/unsigned/",$atr->Type) ){
                
                if( $var['min'] < 0 ) $var['min'] = 0;
                if( $var['max'] < 0 ) $var['max'] = 0;
                // duplico valores maximos
                if( !empty($var['max']) ){
                  $var['max'] = ( $var['max'] * 2 ) + 1;
                }
                // rellenar con 000-
                if( preg_match("/zerofill/",$atr->Type) ){ 
                  $var['num_pad'] = 1; 
                }
              }
              // enteros
              if( $var_val == 'int' ){
                // limito minimos y maximos por longitud
                if( !empty($var_cue) ){
                  $tot_val = ''; 
                  for( $i=1; $i <= intval($var_cue); $i++ ){ 
                    $tot_val .= '9'; 
                  }
                  $tot_val = intval($tot_val);
                  if( isset($var['max']) && $var['max'] > $tot_val  ){
                    $var['max'] = $tot_val;
                  }
                  if( isset($var['min']) && $var['min'] < -$tot_val  ){
                    $var['min'] = -$tot_val;
                  }
                }
              }// decimales
              elseif( $var_val == 'dec' ){
                if( !empty($var_cue) ){
                  $tot_dec = explode(',',$var_cue);
                }
              }              
            }
          }      
          elseif( $var_dat == 'tex' ){
            if( preg_match("/_bin/", $atr->Collation) ){ 
              $var['_tip'] = "dir_bit"; 
            }
          }
          elseif( $var_dat == 'fec' ){
            // valor por defecto
            if( preg_match("/CURRENT_TIMESTAMP/",$atr->Extra) ){ 
              $var['fec_ini'] = 1; 
              // actualizar al modificar
              if( preg_match("/on update/",$atr->Extra) ){ 
                $var['fec_act'] = 1; 
              }
            }
          }
          elseif( $var_dat == 'opc' ){
            $var['dat'] = $var_cue = _dat::dec($var_cue);
          }
          // valores
          if( !is_null($atr->Default) ){
            $var['val'] = $atr->Default;
          }
          if( isset($var_cue) && !is_array($var_cue) ){
            $var['maxlength'] = $var_cue;
          }
          if( isset($var['fec_ini']) || isset($var['fec_act']) || isset($var['num_inc']) ){
            $var['val_ope'] = 0;
            $var['disabled'] = 1;
          }
          elseif( $atr->Null == 'NO' ){
            $var['val_req'] = 1;
          }
          $_atr = new stdClass();
          $_atr->esq = $esq;
          $_atr->est = $est;
          $_atr->ide = $atr->Field;
          $_atr->pos = $pos;
          $_atr->nom = $atr->Comment;
          $_atr->var = $var;
          $_atr->var_ide = $sql_tip;
          $_atr->var_tip = $var_tip;
          $_atr->var_dat = $var_dat;
          $_atr->var_val = $var_val;
          // ...
          $_[$_atr->ide] = $_atr;
        }
      }
      return $_;
    }
    // indice
    static function ind( string $ide, string $ope = 'ver', ...$opc ) : array | object | string {
      $_ = [];
      $ide = explode('.',$ide);
      $esq = $ide[0];
      $est = $ide[1];

      switch( $opc ){
      case 'ver': 
        if( !empty($ide = $opc[0]) ){

          foreach( _sql::dec("SHOW KEYS FROM `$esq`.`$est` WHERE `Key_name` = '".( $ide == 'pri' ? "PRIMARY" : $ide )."'") as $key ){

            $_[] = $key->Column_name;
          }
        }      
        break;
      case 'lis': 
        break;
      case 'agr': 
        break;
      }

      return $_;
    }
    // elementos
    static function val( string $tip, string $ide, mixed $ope=[] ) : array | object | string {
      $_ = [];

      $e = _sql::cod($ide,$ope, ( $tip == 'cue' ) ? 'ver' : $tip );

      switch( $tip ){
      case 'ver': 
        $_ = _sql::dec("SELECT {$e['atr']} FROM {$e['est']}{$e['jun']}{$e['ver']}{$e['ord']}");
        break;
      case 'cue':  
        $_ = _sql::dec("SELECT COUNT( {$e['atr']} ) AS `cue` FROM {$e['est']}{$e['ver']}")[0]['cue'];
        break;
      case 'agr': 
        $_ = "INSERT INTO {$e['est']} ( {$e['atr']} ) VALUES {$e['val']}";
        break;
      case 'mod': 
        $_ = "UPDATE {$e['est']} SET {$e['val']}{$e['ver']}";
        break; 
      case 'eli': 
        $_ = "DELETE FROM {$e['est']}{$e['ver']}";
        break;
      }
      return $_;
    }

  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // código html /////////////////////////////////////////////////////////////////////////////////////////////
  class _htm {

    // contenido: htm + htm_ini + htm_med + htm_fin
    static function dat( array &$dat ) : array {
      $_=[];
      foreach( ['htm','htm_ini','htm_med','htm_fin'] as $tip ){
        if( isset($dat[$tip]) ){
          if( is_string($dat[$tip]) ){
            $_[$tip] = $dat[$tip];
          }else{
            $_[$tip] = _htm::val($dat[$tip]);
          }
          unset($dat[$tip]);
        }
      }
      return $_;
    }    

    // convierto : []/{} => "<>"
    static function val( ...$dat ) : string {
      $_ = "";
      foreach( $dat as $ele ){
          
        if( is_string($ele) ){
    
          $_ .= $ele;
        }
        else{
          $ele = _dat::dec($ele,[],'nom');
          // operador
          if( isset($ele['_let']) ){
            $htm = $ele['_let'];
            unset($ele['_let']);
            $_ .= _doc::let($htm,$ele);
          }
          // por icono
          elseif( isset($ele['ico']) ){
            $_ .= _doc::ico($ele['ico'],$ele);
          }
          // por ficha
          elseif( isset($ele['fic']) ){
            $est = explode('.',$ele['fic']);
            array_push($est,!empty($ele['ide'])?$ele['ide']:0,$ele);
            $_ .= _doc::ima(...$est);
          }
          // por tipo de valor
          elseif( isset($ele['_tip']) ){
            $tip = explode('_',$ele['_tip']);
            unset($ele['_tip']);
            // valores
            $val = NULL;
            if( isset($ele['val']) ){
              $val = $ele['val'];
              unset($ele['val']);
            }
            // funciones
            if( class_exists( $cla_ide = "_doc_".array_shift($tip) ) ){

              $_ = $cla_ide::ope( empty($tip) ? 'val' : implode('_',$tip), $val, $ele );
            }
            else{
              $_ = "<font class='err' title='no existe el operador $cla_ide'></font>";
            }                    
          }
          // por etiqueta
          else{
            $_ .= _htm::eti($ele);
          }
        }
      }
      return $_;
    }

    // atributos : "< ...atr="">"
    static function atr( $val, $dat = NULL ) : string {
      $_=''; 
      foreach( ['eti','htm','htm_ini','htm_med','htm_fin'] as $atr ){ 
        if( isset($val[$atr]) ){ unset($val[$atr]); }
      }
      if( isset($dat) ){
        $dat_arr = is_array($dat);
        foreach( $val as $i=>$v ){ 
          $tex=[];
          foreach( explode(' ',$v) as $pal ){ 
            $let=[];
            foreach( explode('()',$pal) as $cad ){ 
              $res = $cad;
              if( substr($cad,0,3)=='($)' ){ 
                $atr = substr($cad,3);
                if( $dat_arr ){
                  if( isset($dat[$atr]) ){ $res = $dat[$atr]; }
                }else{
                  if( isset($dat->$atr) ){ $res = $dat->$atr; }
                }
              }$let[] = $res;
            }$tex[] = implode('',$let);
          }// junto por espacios
          $_.=" {$i} = \"".str_replace('"','\'',implode(' ',$tex))."\"";
        }
      }
      else{
        foreach( $val as $i=>$v ){
          $_ .= " {$i} = \"".str_replace('"','\'',strval($v))."\"";
        }
      }
      return $_;
    }

    // etiqueta : <eti ...atr > htm </eti>
    static function eti( array $ele, $tip='' ) : string | array {
      $_ = "";
      if( empty($tip) ){
        $eti = 'span';
        if( isset($ele['eti']) ){
          $eti = $ele['eti'];
          unset($ele['eti']);
        }
        $htm = '';
        if( isset($ele['htm']) ){
          $htm = $ele['htm'];
          unset($ele['htm']);
        }
        if( !is_string($htm) ){
          $_htm = "";
          foreach( _var::ite($htm) as $ele ){
            $_htm .= is_string($ele) ? $ele : _htm::val($ele);
          }
          $htm = $_htm;
        }
        $_ = "
        <{$eti}"._htm::atr($ele).">
          ".( !in_array($eti,['input','img','br','hr']) ? "{$htm}
        </{$eti}>" : '' );
      }
      else{
        switch( $tip ){
        }
      }
      return $_;
    }

  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // datos ///////////////////////////////////////////////////////////////////////////////////////////////////
  class _dat {
    
    // listado : devuelvo estructura - objeto
    static function var( $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

      // objeto->propiedad 
      if( is_string($ope) ){

        $_ = new stdClass;

        global 
          $_api, $_hol, $_usu
        ;
        $var_lis = get_defined_vars();

        $esq = $dat;
        $est = $ope;

        $_ = isset($val) ? $val : new stdClass;
        
        if( !isset($val) || !_obj::tip($val) ){

          if( empty($var_lis["_{$esq}"]) ){
            $var_lis["_{$esq}"] = _ele::cla("_{$esq}");
          }

          if( !empty($var_lis["_{$esq}"]) ){

            $obj = $var_lis["_{$esq}"];

            if( is_object($obj) && method_exists($obj,'_') ){

              $_ = !isset($val) ? $obj->_($est) : $obj->_($est,$val);
            }
          }
          elseif( function_exists($_fun = "_{$est}_dat") ){

            $_ = !isset($val) ? $_fun($est) : $_fun($est,$val);
          }
        }
      }// estructuras
      else{
        $_ = $dat;      
        // datos de la base 
        if( is_string($ide = $dat) ){

          // ejecuto consulta
          $_ = _sql::val('ver',$ide,isset($ope) ? $ope : []);

          if( isset($ope) ){
            // elimino marcas
            foreach( ['ver','jun','gru','ord','lim'] as $i ){

              if( isset($ope[$i]) ) unset($ope[$i]);
            }
            // busco clave primaria
            if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){
              
              $ope['niv'] = _sql::ind($ide,'ver','pri');
            }
          }
        }
        // resultados y operaciones
        if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) )
          _est::ope($_,$ope);
      }
      return $_;
    }
    // inicio estructura: busco datos por vista o tabla
    static function ini( string $esq, string $est, array $ope = [] ) : string | array {
      $_ = [];

      $val_est = _sql::est("_{$esq}",'val',$est);

      $vis = "_{$est}";

      $val_vis = _sql::est("_{$esq}",'val',$vis);

      if( $val_est || $val_vis ){

        $ide = ( $val_vis == 'vis' ) ? $vis : $est;

        $_ = _dat::var( "_{$esq}.{$ide}",$ope);
      }

      return $_;
    }
    // {}/[] => ""
    static function cod( object | array | string $dat ) : string {
      $_ = [];
      // convierto : {} => ""
      if( is_array($dat) || is_object($dat) ){
        // https://www.php.net/manual/es/function.json-encode.php
        // https://www.php.net/manual/es/json.constants.php
        $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
      }
      return $_;
    }
    // "" => {}/[]
    static function dec( object | array | string $dat, array | object $ope = NULL, ...$opc ){
      $_ = $dat;
      // convierto : "" => {}
      if( is_string($dat) ){  
        // busco : ()($)atributo-valor()
        if( !empty($ope) && preg_match("/\(\)\(\$\).+\(\)/",$dat) ){
          $dat = _obj::val($ope,$dat);
        }
        // json : { "atr": val, ... } || [ val, val, ... ]
        if( preg_match("/^({|\[).*(}|\])$/",$dat) ){ 
          // https://www.php.net/manual/es/function.json-decode
          // https://www.php.net/manual/es/json.constants.php
          $_ = json_decode($dat, in_array('nom',$opc) ? TRUE : FALSE, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK );
    
        }
        // valores textuales : ('v_1','v_2','v_3')
        elseif( preg_match("/^\('*.*'*\)$/",$dat) ){
          
          $_ = preg_match("/','/",$dat) ? explode("','",substr($dat,1,-1 )) : [ trim(substr($dat,1,-1 )) ] ;
    
        }
        // elemento del documento : "a_1(=)v_1(,,)a_2(=)v_2"
        elseif( preg_match("/\(,,\)/",$dat) && preg_match("/\(=\)/",$dat) ){
    
          foreach( explode('(,,)',$dat) as $v ){ 
    
            $eti = explode('(=)',$v);
    
            $_[$eti[0]] = $eti[1];
          }
        }
        // esquema.estructura : tabla de la base
        elseif( preg_match("/[A-Za-z0-9_]+\.[A-Za-z0-9_]+$/",$dat) ){
    
          $_ = _dat::var($dat,$ope);
          
        }
      }// convierto : {} => []
      elseif( in_array('nom',$opc) && is_object($dat) && get_class($dat)=='stdClass' ){    
        $_ = _obj::nom($dat);
      }
      return $_;
    }
    // identificador por relaciones : esq.est_atr | _api.dat_atr[ide].dat
    static function ide( string $esq, string $est, string $atr ) : string {
      $_ = '';      
      // busco relacion en atributo
      $_atr = _dat::atr($esq,$est,$atr);
      
      if( !empty($_atr->var['dat']) ){
        $_ = explode('.',$_atr->var['dat'])[1];
      }
      // armo identificador por nombre de estructura + atributo
      elseif( $atr == 'ide' ){
        $_ = $est;
      }
      elseif( !!_sql::est("_{$esq}",'val',"{$est}_{$atr}") ){ 
        $_ = "{$est}_{$atr}";
      }
      else{
        $_ = $atr;
      }
      return $_;
    }
    // estructura : datos + operadores
    static function est( string $esq, string $ide = NULL, mixed $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      if( !isset($ide) ){

        $_ = _api::dat_est($esq);
      }
      elseif( !isset($tip) ){
        
        $_ = _api::dat_est($esq,$ide);
      }
      else{
        switch( $tip ){
        case 'ope': 
          $_ = _dat::est($esq,$est);
          if( !empty($_->$ope) ) $_ = $_->ope;
          break;
        }
      }
      return $_;
    }
    // atributo : datos + tipo + variable
    static function atr( string $esq, string $est, mixed $ide = NULL, string $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      if( !isset($ide) ){

        $_ = _api::dat_atr($esq,$est);
      }
      elseif( !isset($tip) ){
        $_atr = _api::dat_atr($esq,$est);
        // uno
        if( is_string($ide) ){
          if( isset($_atr[$ide]) ) $_ = $_atr[$ide];
        }// muchos
        else{
          foreach( $ide as $atr ){
            if( isset($_atr[$atr]) ) $_[$atr] = $_atr[$atr];
          }
        }
      }
      else{
        switch( $tip ){
          
        }
      }
      return $_;
    }
    // valores : nombre, descripcion, titulo, imagen, color...
    static function val( string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : mixed {
      $_ = FALSE; 
      
      $_val = _api::dat_val($esq,$est);
      
      if( empty($atr) ){

        $_ = $_val;
      }
      elseif( isset($_val->$atr) ){

        $_ = $_val->$atr;

        // valores variables ()($)...()
        if( isset($dat) ){

          $_ = _obj::val( _dat::var($esq,$est,$dat), $_val->$atr );
        }
      }
      return $_;
    }
    // operadores de la estructura : relaciones + ficha + filrtos + colores + imagenes + numeros + textos
    static function ope( string $esq, string $est, string $atr = NULL ) : mixed {
      $_ = FALSE;
      $_val = _api::dat_est($esq,$est)->ope;
      if( empty($atr) ){
        $_ = $_val;
      }
      elseif( isset($_val->$atr) ){
        $_ = $_val->$atr;
      }
      return $_;
    }
    // ver valores : imagen, color...
    static function ver( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
      // dato
      $_ = [
        'esq' => $esq,
        'est' => $est
      ];
      // armo identificador
      if( !empty($atr) ){        
        $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  

        // busco dato en atributos
        $_atr = _dat::atr($esq,$est,$atr);
        
        if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
          $dat = explode('.',$var_dat);
          $_['esq'] = $dat[0];
          $_['est'] = $dat[1];
        }
      }
      // valido dato
      if( !empty( $dat_Val = _dat::val($_['esq'],$_['est'],$tip,$dat) ) ){
        $_['ide'] = "{$_['esq']}.{$_['est']}";
        $_['val'] = $dat_Val;
      }
      else{
        $_ = [];
      }
      return $_;
    }
    // proceso abm : alta , modificacion y baja de registro-objeto
    static function abm( string $esq, string $est, string $tip, object $dat ) : string {
      $_="";
      $_sql = [];
      if( $esq=='usu' ){
        $_usu = new _usu();
        $_sql = $_usu->dat($est,$tip,$dat);
      }
      // ejecuto transacciones    
      $var_eve = [];
      foreach( $_sql as $est=>$ope ){ 
        $eje []= _val_dat( $tip, $est, $ope) ; 
      }
      if( !empty($eje) ){
        $_ = _sql::dec( ...$eje );
      }
      return $_;
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // valores-variables ///////////////////////////////////////////////////////////////////////////////////////
  class _var {

    // objeto por tipo de variable['dat','val']
    static function tip( mixed $val ) : bool | object {
      $_ = FALSE;
      $ide = strtolower(gettype($val));    
      if( is_null($val) ){
        $ide = "null";
      }// logicos
      elseif( is_bool($val) ){
        $val = "bool";
      }// funciones
      elseif( is_callable($val) ){ 
        $ide="function"; 
      }// listados
      elseif( is_array($val) ){
        if( array_keys($val) !== range( 0, count( array_values($val) ) - 1 ) ){ 
          $ide="asoc"; 
        }elseif( count($val)>1 ){ 
          $val=[]; $a['d']=[];
          foreach( $val as $i=>$v ){ 
            if(isset($val[$i-1])){ 
              $a['d'][$v-$val[$i-1]] = $v-$val[$i-1]; 
            } 
            array_push($val, is_numeric($v) ? TRUE : FALSE );
          }
          if( !in_array(FALSE,$val) && count($a['d'])==1 ){ 
            $ide = 'range'; 
          }
        }
      }// numericos
      elseif( is_numeric($val) ){ 
        $ide="int";      
        if( is_nan($val) ){ $ide = "nan";
        }// evaluar largos
        else{
          if( is_integer($val) || is_long($val) ){          
            $ide="integer";
            if(      $val >= -128 && $val <= 127 ){ 
              $ide="tinyint";
            }elseif( $val >= -32768 && $val <= 32767 ){ 
              $ide="smallint";
            }elseif( $val >= -8388608 && $val <= 8388607 ){ 
              $ide="mediumint";
            }elseif( $val >= -2147483648 && $val <= 2147483647 ){ 
              $ide="int";
            }elseif( $val >= -92233720368547 && $val <= 92233720368547 ){ 
              $ide="bigint";
            }else{
              $ide="long";
            }
          }else{
            $ide="decimal";
            if( is_double($val) ){ $ide="double";
            }elseif( is_float($val) ){ $ide="float";
            }
          }
        }
      }// textos
      elseif( is_string($val) ){
        $tam=strlen($val);
        $ide="varchar";
        if( $tam <= 50 ){
          if( preg_match("/^#[a-zA-Z0-9]{6}$/",$val) || preg_match("/^rgb\(/",$val) ){ 
            $ide="color";
          }elseif( preg_match("/^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/",$val) ){ 
            $ide="datetime";
          }elseif( preg_match("/^\d{4}([\-\/\.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/",$val) ){ 
            $ide="date";              
          }elseif( preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/",$val) ){ 
            $ide="time";                  
          }
        }elseif( $tam <= 255 && $tam >= 100 ){
          $ide="tinytext";
        }elseif( $tam <= 65535 ){
          $ide="text";
        }elseif( $tam <= 16777215 ){
          $ide="mediumtext";
        }elseif( $tam <= 4294967295 ){
          $ide="longtext";
        }else{ 
          $ide="string";
        }      
      }
      
      if( !empty( $tip = _api::_('var_tip',$ide) ) ){
        $_ = $tip;
      }
      return $_;
    }
    // identificadores
    static function ide( $dat, array $ope=[] ) : array {
    
      if( is_string($dat) ) 
        $dat = explode('.',$dat);

      $_ = array_merge($ope,[ 
        'esq'=>$dat[0], 
        'est'=>FALSE, 
        'atr'=>FALSE 
      ]);

      if( isset( $dat[1] ) ){
        
        $_['est'] = $dat[1];

        if( isset($dat[2]) ){

          $_['atr'] = $dat[2];
        }
      }
      return $_;
    }
    // comparaciones de valores
    static function ver( $dat, string $ide, $val ) : bool {
      $_ = FALSE;
      switch( $ide ){
      case '===': $_ = ( $dat === $val );  break;
      case '!==': $_ = ( $dat !== $val );  break;
      case '=':   $_ = ( $dat ==  $val );  break;
      case '<>':  $_ = ( $dat !=  $val );  break;
      case '==':  $_ = ( $dat ==  $val );  break;
      case '!=':  $_ = ( $dat !=  $val );  break;          
      case '>':   $_ = ( $dat  >  $val );  break;
      case '>>':  $_ = ( $dat  >  $val );  break;
      case '<<':  $_ = ( $dat  <  $val );  break;
      case '<':   $_ = ( $dat  <  $val );  break;
      case '>=':  $_ = ( $dat >=  $val );  break;
      case '<=':  $_ = ( $dat <=  $val );  break;
      case '^^':  $_ =  preg_match("/^".$val."/",$dat); break;
      case '!^':  $_ = !preg_match("/^".$val."/",$dat); break;    
      case '$$':  $_ =  preg_match("/".$val."$/",$dat); break;
      case '!$':  $_ = !preg_match("/".$val."$/",$dat); break;
      case '**':  $_ =  preg_match("/".$val."/",$dat);  break;
      case '!*':  $_ = !preg_match("/".$val."/",$dat);  break;
      }
      return $_;
    }
    // aseguro iteraciones 
    static function ite( mixed $dat, mixed $ope = NULL ) : array {

      $_ = [];

      if( empty($ope) ){

        $_ = _obj::pos($dat) ? $dat : [ $dat ];
      }
      // ejecuto funciones
      elseif( is_array($dat) && is_callable($ope) ){
        
        foreach( $dat as $pos => $val ){

          $_ []= $ope( $val, $pos );
        }
      }  
      return $_;
    }
    // convierto a listado : [ ...$$ ]
    static function lis( mixed $dat, string $tip = '', array $ope = [] ) : array {

      $_ = $dat;
      if( empty($tip) ){

        if( _obj::tip($dat) ){
          $_ = [];
          foreach( $dat as $v ){
            $_[] = $v;
          }
        }
      }
      else{
        switch( $tip ){
        }
      }
      return $_;
    }

  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // estructuras de datos : [ {}, {} ] ///////////////////////////////////////////////////////////////////////
  class _est {

    // proceso estructura
    static function ope( array &$dat, array $ope=[], ...$opc ) : array | object {
      

      // junto estructuras
      if( isset($ope['jun']) ){
        _est::jun($dat, $ope['jun'], ...$opc);
      }

      // ejecuto filtro
      if( isset($ope['ver']) ){
        _est::ver($dat, $ope['ver'], ...$opc);
      }
      
      // genero elementos
      if( isset($ope['ele']) ){
        _est::dec($dat, $ope['ele'], 'nom');
      }
      // genero objetos  
      if( isset($ope['obj']) ){
        _est::dec($dat, $ope['obj'] );
      }

      // nivelo estructura
      if( isset($ope['niv']) ){
        _est::niv($dat, $ope['niv'] );
      }// o por indice
      elseif( isset($ope['nav']) && is_string($ope['nav']) ){
        _est::nav($dat, $ope['nav'] );
      }

      // reduccion por atributo
      if( isset($ope['red']) && is_string($ope['red']) ){
        _est::red($dat, $ope['red'] );
      }
      
      // devuelvo unico objeto
      if( isset($ope['opc']) ){

        $ope['opc'] = _var::ite($ope['opc']);

        if( in_array('uni',$ope['opc']) ) _est::uni($dat, ...$opc );
      }

      return $dat;
    }
    // decodifica : "" => {} , []
    static function dec( array &$dat, string | array $atr, ...$opc ) : array {

      $atr = _var::ite($atr);

      foreach( $dat as &$ite ){

        if( is_object($ite) ){

          foreach( $atr as $ide ){

            if( isset($ite->$ide) ){

              $ite->$ide = _dat::dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);            
            }
          }
        }
      }
      
      return $dat;
    }
    // genero atributos
    static function atr( string | array $dat, string $ope = "" ) : array {
      $_ = [];
      if( empty($ope) ){
        // de la base
        if( is_string($dat) ){        
          $ide = _var::ide($dat);
          $_ = _dat::atr($ide['esq'],$ide['est']);
        }
        // del entorno 
        else{
          
          foreach( $dat as $ite ){

            foreach( $ite as $ide => $val ){ 
              $atr = new stdClass;
              $atr->ide = $ide;
              $atr->nom = $ide;
              $atr->var = _var::tip($val);
              // cargo atributo
              $_ [$ide] = $atr;
            }
            break;
          }        
        }
      }
      return $_;
    }
    // nivelar indice
    static function niv( array &$dat, mixed $ide ) : array {

      $_ = [];
      // numérica => pos
      if( is_numeric($ide) ){
        $k = intval($ide);
        foreach( $dat as $val ){ 
          $_[$k++]=$val; 
        }
      }
      // Literal => nom
      elseif( is_string($ide) ){
        $k = explode('(.)',$ide);
        foreach( $dat as $i => $val ){ 
          $i=[]; 
          foreach( $k as $ide ){ $i []= $val[$ide]; }
          $_[ implode('(.)',$ide) ] = $val; 
        }
      }
      // Clave Múltiple => keys-[ [ [ [],[ {-_-} ],[], ] ] ]
      elseif( is_array($ide) ){
        $k = array_values($ide);
        switch( count($k) ){
        case 1: foreach( $dat as $v ){ $_[$v->{$k[0]}]=$v; } break;
        case 2: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}]=$v; } break;
        case 3: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}]=$v; } break;
        case 4: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}]=$v; } break;
        case 5: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}]=$v; } break;
        case 6: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}]=$v; } break;
        case 7: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}][$v->{$k[6]}]=$v; } break;
        }
      }
      return $dat = $_;
    }
    // armar navegacion por posicion : xx-xx-xx
    static function nav( array &$dat, string $atr = 'pos' ) : array {
      $_ = [];      
      // creo subniveles
      for( $nav=1; $nav<=7; $nav++ ){
        $_[$nav] = [];
      }
      // cargo por subnivel
      foreach( 
        $dat as $val 
      ){
        switch( 
          $cue = count( $niv = explode('-', is_object($val) ? $val->$atr : $val[$atr] ) ) 
        ){
        case 1: $_[$cue][$niv[0]] = $val; break;
        case 2: $_[$cue][$niv[0]][$niv[1]] = $val; break;
        case 3: $_[$cue][$niv[0]][$niv[1]][$niv[2]] = $val; break;
        case 4: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]] = $val; break;
        case 5: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]] = $val; break;
        case 6: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]] = $val; break;
        case 7: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]][$niv[6]] = $val; break;
        }
      }  
      return $dat = $_;
    }
    // reducir elemento a un atributo
    static function red( array &$dat, string $atr ) : array {    
      
      foreach( $dat as &$n_1 ){

        if( is_object($n_1) ){
          if( isset($n_1->$atr) ) $n_1 = $n_1->$atr;
        }
        elseif( is_array($n_1) ){

          foreach( $n_1 as &$n_2 ){

            if( is_object($n_2) ){
              if( isset($n_2->$atr) ) $n_2 = $n_2->$atr;
            }
            elseif( is_array($n_2) ){

              foreach( $n_2 as &$n_3 ){

                if( is_object($n_3) ){
                  if( isset($n_3->$atr) ) $n_3 = $n_3->$atr;
                }
                elseif( is_array($n_3) ){

                  foreach( $n_3 as &$n_4 ){

                    if( is_object($n_4) ){
                      if( isset($n_4->$atr) ) $n_4 = $n_4->$atr;
                    }
                    elseif( is_array($n_4) ){

                      foreach( $n_5 as &$n_5 ){

                        if( is_object($n_5) ){
                          if( isset($n_5->$atr) ) $n_5 = $n_5->$atr;
                        }
                        elseif( is_array($n_5) ){

                          foreach( $n_5 as &$n_6 ){

                            if( is_object($n_6) ){
                              if( isset($n_6->$atr) ) $n_6 = $n_6->$atr;
                            }
                            elseif( is_array($n_6) ){

                              foreach( $n_6 as &$n_7 ){

                                if( is_object($n_7) ){
                                  if( isset($n_7->$atr) ) $n_7 = $n_7->$atr;
                                }
                                elseif( is_array($n_7) ){                        
                                  // ...
                                }
                              }                      
                            }
                          }                   
                        }
                      }              
                    }
                  }  
                }
              }    
            }
          }
        }
      }
      return $dat;
    }
    // filtrar elementos
    static function ver( array &$dat, array $ope = [], ...$opc ) : array {
      $_ = [];
      foreach( $dat as $pos => $ite ){ 
        $val_ite = [];           
        foreach( $ite as $atr => $val ){ 

          foreach( $ope as $ver ){ 

            if( $atr == $ver[0] ) 
              $val_ite []= _var::ver( $val, $ver[1], $ver[2] );
          }
        }
        // evaluo resultados
        if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) )
          $_[] = $ite;
      }
      return $dat = $_;
    }
    // juntar estructuras
    static function jun( array &$dat, array $ope = [], ...$opc ) : array {

      return $dat = array_merge($ope, $dat);
    }
    // agrupar elementos por atributo con funcion
    static function gru( array &$dat, array $ope = [], ...$opc ) : array {
      return $dat;
    }
    // ordenar elementos
    static function ord( array &$dat, array $ope = [], ...$opc ) : array {
      return $dat;
    }
    // limitar resultados
    static function lim( array &$dat, array $ope = [], ...$opc ) : array {
      return $dat;
    }
    // transforma a unico elemento
    static function uni( array &$dat, ...$opc ) : array | object {
      $_ = new stdClass;

      if( in_array('fin',$opc) ){ $dat = array_reverse($dat); }

      foreach( $dat as $i => $v ){
        $_ = $dat[$i];
        break;
      }

      return $dat = $_;
    }
    // elimina elementos
    static function eli( array &$dat, string $ope, mixed $val, ...$opc ) : array {
      $_ = [];
      $pos = 0;
      $opc_ide = in_array('ide',$opc);
      $lis_tip = _obj::pos($dat);
      
      foreach( $dat as $i => $v ){

        if( !_var::ver( $opc_ide ? $i : $v, $ope, $val ) ) 
        
          $_[ $lis_tip ? $pos : $i ] = $v;

        $pos++;
      }
      
      return $dat = $_;
    }
    
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // ejecuciones : ( ...par ) => { ...cod } : $val ///////////////////////////////////////////////////////////
  class _eje {

    // ejecucion del entorno : funcion() |o| [namespace/]clase(...par).objeto->método(...par)
    static function val( string | array $ide, mixed $par=[], array $ini=[] ) : mixed {
      // php.fun(,)par(,)... || rec/cla.met(,)par(,)...      
      if( is_string($ide) ){

        if( preg_match("/^\[.+\]$/",$ide) ){
          // FALSE : convierto en objetos stdClass
          $var_eve = _dat::dec($ide);
          $ide = $var_eve[0];
          if( isset($var_eve[1]) ) $par = $var_eve[1];
        }
      }// por codificacion
      else{
        $ope = explode('-',$ide);
        $var_eve = explode('.',$ope[0]);
        $ide = empty($var_eve[1]) ? $var_eve[0] : "{$var_eve[0]}.{$var_eve[1]}";
        if( !empty($ope[1]) ) array_unshift($par,$ope[1]);
      }
      // metodos de clase
      if( preg_match("/\./",$ide) || preg_match("/::/",$ide) ){
        $_ = _eje::met( $ide, $par, $ini );
      }
      // funcion del entorno
      else{
        $_ = _eje::fun( $ide, ..._var::ite($par) );      
      }
      return $_;
    }
    // ejecuto funciones
    static function fun( string $ide, ...$par ) : mixed {
      $_=FALSE;
      if( function_exists($ide) ){
        $_ = empty($par) ? $ide() : $ide( ...$par );
      }else{ 
        $_ = ['_err'=>"{-_-}.err: No existe la función '{$ide}' en el entorno php..."];
      }
      return $_;
    }
    // instancio objeto de clase
    static function cla( string $ide, ...$ini ) : object | array {

      $_ide = explode('/',$ide);

      if( isset($_ide[1]) ){
        $dir = $_ide[0];
        $ide = $_ide[1];
      }
      else{
        $dir = 'php';
        $ide = $_ide[0];
      }
      if( is_string($ide) && file_exists($rec = "{$dir}/".substr($ide,1).".php") ){ 

        $val = explode('/',$ide);

        if( !class_exists( $cla = array_pop($val) ) ){

          require_once $rec; 
        }
        // instancio
        if( class_exists($cla) ){

          $_ = empty($ini) ? new $cla() : new $cla( ...$ini );
        }
        else{ 
          $_ = ['_err'=>"{-_-}.err: No existe la clase solicitada en el directorio '{$ide}'..."];
        }
      }
      else{ 
        $_ = ['_err'=>"{-_-}.err: No existe el directorio '{$ide}' en este servidor..."];
      }  
      return $_;
    }
    // ejecuto método de clase
    static function met( string $ide, mixed $par=[], array $ini=[] ) : mixed {
      $_ = FALSE;
      // por clase
      if( preg_match("/::/",$ide) ){
        $_ide = explode('::',$ide);
        $cla = $_ide[0];
        $met = $_ide[1];
        
        if( class_exists($cla) ){       

          if( in_array($met,get_class_methods($cla)) ){

            try{

              $_ = empty($par) ? $cla::$met() : $cla::$met( ..._var::ite($par) ) ;
            }
            catch( Exception $e ){

              $_ = ['_err' => $e->getMessage()];
            }
          }
          else{
            $_ = ['_err'=>"{-_-}.err: No existe el método estático '$met' de la clase '$cla'..."];
          }        
        }
        else{
          $_ = ['_err'=>"{-_-}.err: No existe la clase '$cla'..."];
        }
      }// por objeto
      else{
        $_ide = explode('.',$ide);
        $cla = $_ide[0];
        $met = $_ide[1];
        // instancio
        $obj = _eje::cla( $cla, ...$ini );
        // ejecuto      
        if( is_object($obj) ){

          if( method_exists($obj, $met) ){ 

            try{

              $_ = empty($par) ? $obj->$met() : $obj->$met( ..._var::ite($par) ) ;
            }
            catch( Exception $e ){

              $_ = ['_err' => $e->getMessage()];
            }
          }
          else{
            $_ = ['_err'=>"{-_-}.err: No existe el método '$met' en el objeto '$cla'..."];
          }
        }else{
          $_ = [ '_err'=>$obj ];
        }
      }
      return $_;
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // elementos html : <>...</> ///////////////////////////////////////////////////////////////////////////////
  class _ele {

    // devuelvo elemento : [ atr => "val" ]
    static function val( string | array | object $ele, array | object $dat = NULL ) : array {

      $_ = $ele;
      
      // convierto "" => []
      if( is_string($ele) ){
        $_ = _dat::dec($ele,$dat,'nom');
      }
      // convierto {} => []
      elseif( is_object($ele) ){
        $_ = _obj_nom($ele);
      }
      // proceso atributos con variables : ()($)nom()
      elseif( is_array($_) && isset($dat) ){

        foreach( $_ as &$atr ){                    

          if( is_string($atr) ){ // && preg_match("/\(\)\(\$\).*\(\)/",$atr) 

            $atr = _obj::val($dat,$atr);            
          }
        }
      }
      return $_;
    }
    // combino elementos
    static function jun( stirng | array $ele, array $ope, array | object $dat = NULL, array $opc = [] ) : array {
      // si es "", convierto a []
      $_ = _ele::val($ele,$dat);
      // proceso opciones
      $opc_eje = isset($opc['eje']) ? $opc['eje'] : [];
      $opc_cla = isset($opc['cla']) ? $opc['cla'] : [];
      $opc_css = isset($opc['css']) ? $opc['css'] : [];

      // recorro elementos
      foreach( _var::ite($ope) as $ele ){
        
        // recorro atributos
        foreach( _ele::val($ele,$dat) as $atr => $val ){

          if( !isset($_[$atr]) ){
            $_[$atr] = $val;
          }
          else{
            switch($atr){
            case 'class': _ele::cla($_,$val,...$opc_cla); break;// agrego con separador: " "
            case 'style': _ele::css($_,$val,...$opc_css); break;// agrego con separador: ";"
            default:// reemplazo
              $_[$atr] = $val;
              break;
            }
          }
        }
      }
      return $_;
    }

    // ejecuciones
    static function eje( array &$dat, string $ide, string $val = NULL, ...$opc ) : array {
      $_ = $dat;
      $_eve = [ 
        'cli'=>"onclick", 'cam'=>"onchange", 'inp'=>"oninput" 
      ];
      if( isset($_eve[$ide]) ){

        $ide = $_eve[$ide];

        if( !isset($val) ){

          $_ = [];
  
          if( isset($dat[$ide]) ){
            $_ = explode(';',$dat[$ide]);
          }

        }// operaciones por valor
        else{
          // eliminar
          if( in_array('eli',$opc) ){
  
          }// modificar
          elseif( in_array('mod',$opc) ){
  
          }// agregar
          else{
            if( in_array('ini',$opc) ){
              $dat[$ide] = $val.( !empty($dat[$ide]) ? ' '.$dat[$ide] : '' );
            }
            elseif( !empty($dat[$ide]) ){
              $dat[$ide] .= " ".$val;
            }
            else{
              $dat[$ide] = $val;
            }
          }
          $_ = $dat;
        }
      }
      return $_;
    }
    // clases
    static function cla( array &$dat, $val = NULL, ...$opc ) : array {

      $_ = $dat;
      
      if( !isset($val) ){

        $_ = [];
    
        $ele = _dat::dec($dat,[],'nom');
    
        if( isset($ele['class']) ){
    
          foreach( explode(' ',$ele['class']) as $val ){ 
    
            if( !empty($val) ) $_[] = trim($val);
          }
        }
      }// operaciones
      else{
        
        if( in_array('eli',$opc) ){

          foreach( _var::ite($val) as $v ){
            
          }    
        }
        elseif( in_array('mod',$opc) ){

          if( is_array($val) ){

          }
        }
        else{
          if( !isset($dat['class']) ) $dat['class']='';
    
          if( in_array('ini',$opc) ){

            $dat['class'] = $val.( !empty($dat['class']) ? " {$dat['class']}" : "" );
          }// agrego
          elseif( !empty($dat['class']) ){ 
            $dat['class'] .= " ".$val; 
          }// inicializo
          else{
            $dat['class'] = $val; 
          }
        }
        $_ = $dat;
      }
      return $_;
    }
    // estilos
    static function css( array &$dat, $val = NULL, ...$opc ) : array {

      $_ = $dat;
      // listado
      if( !isset($val) ){

        $_ = [];

        if( isset($ele['style']) ){

          foreach( explode(';',$ele['style']) as $art ){

            if( !empty($art) ){

              $val = explode(':',$art);

              $_[ trim($val[0]) ] = trim($val[1]);
            }
          }
        }
      }// operaciones
      else{        
        // por atributos
        if( is_array($val) ){

          $css = _ele::css($dat);

          if( in_array('eli',$opc) ){

            foreach( $val as $v ){

              if( isset($css[$v]) ) unset($css[$v]);
            }
          }// agrego, actualizo o modifico
          else{
            foreach( $val as $i => $v ){

              if( isset($css[$i]) && in_array('mod',$opc) ){
                $css[$i] .= $v;
              }
              else{
                $css[$i] = $v;
              }
            }
          }
          $css_val = [];
          foreach( $css as $i => $v ){

            $css_val []= "{$i} : {$v}";
          }

          $dat['style'] = implode('; ',$css_val);
        }
        // por texto
        else{

          if( in_array('eli',$opc) ){

          }
          else{

            if( in_array('ini',$opc) ){
              $dat['style'] = $val.( !empty($dat['style']) ? " {$dat['style']}" : "" );
            }
            elseif( !empty($dat['style']) ){
              $dat['style'] .= " ".$val;
            }else{
              $dat['style'] = $val;
            }
          }
        }
        $_ = $dat;
      }
      return $_;
    }
    // fondos
    static function fon( string $val, array $ope=[] ) : string {
      if( empty($ope['tip']) ) $ope['tip']='png';
      if( empty($ope['ali']) ) $ope['ali']='center';
      if( empty($ope['tam']) ) $ope['tam']='contain';
      if( empty($ope['rep']) ) $ope['rep']='no-repeat';
      return "background: {$ope['rep']} {$ope['ali']}/{$ope['tam']} url('{$val}.{$ope['tip']}');";
    }
    
  }  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // objetos : {} ////////////////////////////////////////////////////////////////////////////////////////////
  class _obj {

    // valor : ()($)atr_ide()
    static function val( object | array $dat, string $val='' ) : string {
      $_ = [];
      $val_arr = _obj::tip($dat) == 'nom';
      foreach( explode(' ',$val) as $pal ){ 
        $let=[];
        foreach( explode('()',$pal) as $cad ){ 
          $sep = $cad;
          if( substr($cad,0,3)=='($)' ){ $sep='';
            $ide=substr($cad,3);
            if( $val_arr ){
              if( isset($dat[$ide]) ){ $sep = $dat[$ide]; }
            }else{
              if( isset($dat->$ide) ){ $sep = $dat->$ide; }
            }
          }
          $let[]=$sep;
        }
        $_[] = implode('',$let);
      }
      $_ = implode(' ',$_);
      return $_;
    }

    // combino
    static function jun( array | object $dat, array | object $ope ) : array | object {
            
      $val_obj = is_object($_ = $dat);      

      foreach( $ope as $i => $v ){

        if( $val_obj ? isset($_->$i) : isset($_[$i]) ){

          $val_ite = $val_obj ? $_->$i : $_[$i];

          $val = _obj::tip($v) ? _obj::jun($v,$val_ite) : $val_ite;

          if( $val_obj ){ $_->$i = $val; }else{ $_[$i] = $val; }

        }
        else{

          if( $val_obj ){ $_->$i = $v; }else{ $_[$i] = $v; }          
        }
      }
      return $_;
    }

    // tipos : pos | nom | atr
    static function tip( mixed $dat ) : bool | string {
      
      $_ = FALSE;

      if( _obj::pos($dat) ){
        $_ = 'pos';
      }
      elseif( is_array($dat) ){
        $_ = 'nom';
      }
      elseif( is_object($dat) ){ // && get_class($dat) == 'stdClass'
        $_ = 'atr';
      }

      return $_;
    }

    // posicion : [ # => $$ ]
    static function pos( mixed $dat, string $tip = NULL, mixed $val = NULL ) : bool | array {
      $_ = [];
      if( !isset($tip) ){
        // valido tipo : []
        $_ = is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );
      }
      else{
        switch( $tip ){
        }
      }
      return $_;
    }    

    // nombre : [ ..."" => $$ ]
    static function nom( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
      $_ = $dat;
      if( empty($tip) ){
        if( is_object($dat) && get_class($dat)=='stdClass' ){
          $_ = [];
          foreach( $dat as $atr => $val ){
            $_[$atr] = $val;
          }
        }
      }else{
        switch( $tip ){
        case 'ver':
          $_ = [];
          $ope = _var::ite($ope);
          $ope_val = empty($ope);
          foreach( $dat as $atr => $val ){
            if( $ope_val || in_array($atr,$ope) ){
              $_[$atr] = $val;
            }
          }
          break;
        }
      }
      return $_;
    }

    // objeto : { ..."" : $$ }
    static function atr( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
      $_ = $dat;

      if( !isset($tip) ){
        // listado de objetos
        if( _obj::pos($dat) ){
          
          $_ = array_map( function($i){ return clone $i; }, $dat );
        }
        // creo un objeto desde un array
        elseif( is_array($dat) ){
          $_ = new stdClass();
          foreach( $dat as $atr => $val ){
            $_->$atr = $val;
          }
        }
        // copio objeto
        elseif( is_object($dat) ){
          $_ = clone $dat;
        }
      }
      else{
        switch( $tip ){
        case 'ver':
          $_ = new stdClass();
          $ope = _var::ite($ope);
          $ope_val = empty($ope);   
          foreach( $dat as $atr => $val ){
            if( $ope_val || in_array($atr,$ope) ){
              $_->$atr = $val;
            }
          }
          break;
        }
      }
      return $_;
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // archivos del directorio /////////////////////////////////////////////////////////////////////////////////
  class _arc {  

    static function val( mixed $dat ) : mixed {
      $_=FALSE;
      // remoto
      if( is_link($dat) ){
        header("Location: {$dat}"); // -> llama a la pagina
      }
      // local: carpeta
      elseif( is_dir($dat) ){ 
        $_ = scandir($dat); 
      }
      // local: archivo
      elseif( file_exists($dat) ){ 
        $_=[]; 
        $val = opendir($dat); 
        while( $tex = readdir($val) ){ 
          $_[] = $tex; 
        } 
        closedir($val);
      }
      return $_;
    }

    static function tip( $tip ){
      $_ = "";
      switch( $tip ){
      case 'ima': $_ = ""; break;
      case 'mus': $_ = ""; break;
      case 'vid': $_ = ""; break;
      case 'tex': $_ = ""; break;
      case 'tab': $_ = ""; break;
      case 'eje': $_ = ""; break;
      }
      return $_;
    }

    static function rec( string $ide ) : string {
      $_ = '';
      foreach( ['html','php'] as $tip ){

        if( file_exists( $rec = "{$ide}.{$tip}" ) ){
          $_ = $rec;
          break;
        }        
      }
      return $_;
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // fecha + calendario //////////////////////////////////////////////////////////////////////////////////////
  class _fec {  

    static function dec( $dat ){
      $_ = $dat;
      if( empty($dat) ){
        $_ = new DateTime('NOW');
      }
      else{
        if( is_numeric($dat) && preg_match("/^\d+$/",$dat) ){
          try{ 
            $_ = new DateTime( intval($dat) );
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }elseif( is_string($dat) ){ 
          try{ 
            $_ = _fec::dat($dat); 
            $_ = !! $_ ? new DateTime( "{$_->año}-{$_->mes}-{$_->dia}" ) : new DateTime('NOW') ;
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }elseif( is_object($dat) ){
          if( get_class($dat)=='stdClass' ){
            $_ = new DateTime( "{$dat->año}-{$dat->mes}-{$dat->dia}" );
          }else{
            $_ = $dat;
          }
        }elseif( is_array($dat) ){
          $_ = new DateTime( "{$dat['año']}-{$dat['mes']}-{$dat['dia']}" );
        }
      }
      return $_;
    }

    static function cod( string $val, string $sep='' ){
      $_ = [];
      $val = explode(' ',str_replace('T','',$val))[0];
      if( empty($sep) ){
        $sep = preg_match("/-/",$val) ? '-' : ( preg_match("/\//",$val) ? '/' : '.' );
      }
      $dat = array_map( function($v){ return intval($v); }, explode($sep,$val) );
      if( strlen(strval($dat[0])) == 4 || $dat[0] > 31 ){
        $_ = [ $dat[0], $dat[1], $dat[2] ];
      }else{
        $_ = [ $dat[2], $dat[1], $dat[0] ];
      }
      return $_;
    }

    static function dat( $val, string $sep='/' ){
      $_ = new stdClass();
    
      if( !is_string($val) ){ 
        $val = _fec::val($val);
        if( !$val ){
          return $val;
        }
      }
    
      $tie = explode(' ',str_replace('T',' ',$val));  
      $fec = explode('-',str_replace($sep,'-',$tie[0]));
      $hor = isset($tie[1]) ? $tie[1] : FALSE;
      
      $_->mes = intval($fec[1]);
      if( strlen($fec[0]) > 2 ){
        $_->año = intval($fec[0]);    
        $_->dia = intval($fec[2]);    
      }else{
        $_->año = intval($fec[2]);    
        $_->dia = intval($fec[0]);
      }  
    
      if( $hor ){
        $_->tie = $hor;
        $hor = explode(':',$hor);
        if( isset($hor[2]) ){
          $_->seg = intval($hor[2]);
        }
        if( isset($hor[1]) ){
          $_->min = intval($hor[1]);
        }
        $_->hor = intval($hor[0]);
      }
    
      if( _fec::val( $_->val = implode($sep,[$_->dia,$_->mes,$_->año]) ) ){    
        $_->sem = _fec::tip($_,'sem');
      }else{
        $_ = FALSE;
      }
    
      return $_;
    }

    static function tip( $dat, $tip='' ){
      $_ = FALSE;
      if( empty($tip) ){
        $_ = $dat;
        if( !is_object($dat) ){ 
          $_ = _fec::dat($dat);
        }
      }
      else{
        if( !is_object($dat) || get_class($dat)=='stdClass' ){
          $dat = _fec::dec($dat); 
        }
        switch( $tip ){
        case 'dyh':    
          $_ = $dat->format('Y/m/d H:i:s');
          break;
        case 'hor':    
          $_ = $dat->format('H:i:s');
          break;
        case 'sem':    
          $_ = $dat->format('w');
          break;
        case 'dia':    
          $_ = $dat->format('Y/m/d');
          break;
        }
      }
      return $_;
    }

    static function val( $dat, ...$opc ){
      $_ = $dat;
    
      if( is_string( $dat ) ){
        $dat = explode(' ',$dat)[0];
        $dat = preg_match("/-/",$dat) ? explode('-',$dat) : explode('/',$dat);
      }
    
      if( ( $obj_tip = _obj::tip($dat) ) || _obj::pos($dat) ){
    
        if( $obj_tip == 'nom' ){
          $año = !empty($dat['año']) ? $dat['año'] : 1900;
          $mes = !empty($dat['mes']) ? $dat['mes'] : 1;
          $dia = !empty($dat['dia']) ? $dat['dia'] : 1;
        }
        elseif( $obj_tip == 'atr' ){
          $año = !empty($dat->año) ? $dat->año : 1900;
          $mes = !empty($dat->mes) ? $dat->mes : 1;
          $dia = !empty($dat->dia) ? $dat->dia : 1;
        }
        else{
          $mes = $dat[1];
          if( strlen($dat[0]) == 4 ){ 
            $año = $dat[0]; 
            $dia = $dat[2];
          }else{ 
            $año = $dat[2]; 
            $dia = $dat[0];
          }
        }
    
        if( checkdate($mes, $dia, $año) ){
          $_ = !in_array('año',$opc) ? _num::val($dia,2).'/'._num::val($mes,2).'/'._num::val($año,4) : _num::val($año,4).'/'._num::val($mes,2).'/'._num::val($dia,2);
        }else{
          $_ = FALSE;
        }    
      }  
      return $_;
    }

    static function ope( $dat, $val=0, string $ope='+', string $tip='dia' ){
      $_ = $dat;
      if( is_object($dat) ){
        $_ = "{$dat->año}/{$dat->mes}/{$dat->dia}";
      }elseif( is_string($dat) ){
        $_ = str_replace('/','-',$dat);
      }
      if( !!$_ ){
        $tie='';
        switch( $tip ){
        case 'seg': $tie='second'; break;
        case 'min': $tie='minute'; break;
        case 'hor': $tie='hour';   break;
        case 'dia': $tie='day';    break;
        case 'sem': $tie='week';   break;
        case 'mes': $tie='month';  break;
        case 'año': $tie='year';   break;
        }
        if( $val > 1 ) $tie.="s";
        $val = strval($val);// strtotime devuelve un timestamp
        $_ = date( 'd-m-Y', strtotime( "{$ope}{$val} {$tie}", strtotime($_) ) );
      }
      return $_;
    }

    static function cue( $tip, $val ){
      $_= _fec::dat($val);
      switch( $tip ){
      case 'mes':
        if( !!$_ ){ 
          $_ = cal_days_in_month( CAL_GREGORIAN, $_->mes, $_->año );
        }
        break;
      case 'año':    
        $lis=[]; $tot=0; $num=0; $mes=0;
        for( $i=1; $i<=12; $i++ ){ 
          $lis[$i] = _fec::cue('mes',"{$_->año}/$i/1"); $tot += $lis[$i]; 
        }
        if( $_->mes == 1 ){ 
          $num = $_->dia;
        }else{
          $mes++;
          while( $mes < $_->mes ){ 
            $num += $lis[$mes]; 
            $mes++; 
          }
          $num += $_->dia;
        }
        $_=[ 
          'tex'=>"{$num} de {$tot}", 
          'val'=>$num, 
          'cue'=>$tot
        ];
        break;    
      }
    }

    static function ran( $ini, $fin ){
      $_ = "";
      if( $ini < 0 && $fin < 0  ){
        $_ = _num::int( $ini * - 1 )." - "._num::int( $fin * - 1). " A.C.";
      }elseif( $ini > 0 && $fin > 0 ){
        $_ = _num::int( $ini )." - "._num::int( $fin ). " D.C.";
      }else{
        $_ = _num::int( $ini * - 1 )." A.C. - "._num::int( $fin ). " D.C.";
      }
      return $_;
    }

  }  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // textos + escritura //////////////////////////////////////////////////////////////////////////////////////
  class _tex {
    
    // salvo caracteres con "\"
    static function cod( string $val, string $cod = ".*+\-?^{}()|[\]\$\\", string $opc = "g" ) : string {
    
      return preg_replace("/[{$cod}]/{$opc}",$val,'\\$&');// $& significa toda la cadena coincidente
    }
    // devuelvo longitud
    static function cue( string $val, string $cod = "UTF-8") : int {
    
      return mb_strlen($val, $cod);
    }
    // extraigo subcadena
    static function ver( string $val, int $ini, int $fin, string $cod="UTF-8" ) : string {
    
      return mb_substr($val, $ini, $fin, $cod);
    }
    // relleno por lado con valor repetitivo
    static function agr( int | float | string $val, int $cue = 1, string $rep = ' ', string $lad = 'izq' ) : string {
    
      return str_pad( $val, $cue, $rep, $lad == 'izq' ? STR_PAD_LEFT : STR_PAD_RIGHT );
    }
    // modifico subcadena
    static function mod( string $dat, string $ver, string $val ) : string {

      return str_replace($ver,$val,$dat);
    }
    // separador con acentos y caracteres especiales
    static function let( string $val, int $gru = 0, string $cod = "UTF-8" ) : array {
      // => https://diego.com.es/extraccion-de-strings-en-php    
      if( $gru > 0 ) {
        $_ = []; $len = mb_strlen($val, $cod);
        for ( $i = 0; $i < $len; $i += $gru ) { $_[] = mb_substr($val, $i, $gru, $cod); }
        return $_;
      }
      return preg_split("//u", $val, -1, PREG_SPLIT_NO_EMPTY);
    }
    // todo a minúsculas
    static function let_min( string $val, string $cod = "UTF-8" ) : string {

      return mb_strtolower($val, $cod);
    }
    // todo a mayúsculas
    static function let_may( string $val, string $cod = "UTF-8" ) : string {

      return mb_strtoupper($val, $cod);
    }
    // capitalizar todas las palabras
    static function let_pal( string $val, string $cod = "UTF-8" ) : string {    

      return ucwords( mb_strtolower($val, $cod) );
    }
    // Capitalizar primer palabra
    static function let_ora( string $val, string $cod = "UTF-8" ) : string {
    
      return ucfirst( mb_strtolower($val, $cod) );
    }
    // extraigo : de - el/a
    static function art( string $val ) : string {
      $_ = "";

      $pal = explode(' ',$val);

      if( in_array($pal[0],['de','del']) ) array_shift($pal);

      if( in_array($pal[0],['la','las','el','lo','los','ellos','ella','ellas']) ) array_shift($pal);

      if( isset($pal[0]) ) $_ = $pal[0];

      return $_;
    }
    // agrego : de - de la
    static function art_del( string $val ) : string {
      
      $_ = explode(' ',$val);

      $_[0] = ( strtolower($_[0]) == 'la' ) ? 'de la' : 'del';

      $_ = implode(' ',$_);

      return $_;
    }
    // convierto género : artículo - terminacion
    static function art_gen( string $val, string $pal = 'o' ) : string {
      $_ = trim($val);
      $pal = trim($pal);
      $art = explode(' ',$pal);
      // por articulo
      if( isset($art[1]) ){

        if( $art[0]=='de' ) array_shift($art);

        $pal = $art[0];

        if( ( in_array($pal,['el','del','lo','los','él','ellos']) ) && preg_match("/as?$/", $_) ){

          $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
        }
        elseif( ( in_array($pal,['la','las','ella','ellas']) ) && preg_match("/[oe]s?$/", $_) ){

          $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
        }
      }// por palabra
      elseif( preg_match("/[oe]s?$/",trim($pal)) && preg_match("/as?$/", $_) ){

        $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
      }
      elseif( preg_match("/os?$/",trim($pal)) && preg_match("/as?$/", $_) ){

        $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
      }
      return $_;
    }
  }  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // numeros + cálculos //////////////////////////////////////////////////////////////////////////////////////
  class _num {

    // datos del objeto
    static function dat( int | float | string $ide, string $atr='' ) : object | string {
      global $_api;
      $_num_int = $_api->_('num_int');
      // aseguro valor
      if( is_string($ide) ) $ide = _num::val($ide);
      // busco datos
      $_ = isset($_num_int[$ide]) ? $_num_int[$ide] : new stdClass();
      // devuelvo atributo
      if( !empty($atr) ){
        $_ = isset($_->$atr) ? $_->$atr : "";
      }
      return $_;
    }
    // devuelvo valor con "0-" o numérico : int | float
    static function val( mixed $dat, int $tot = 0 ) : string | int | float {
      $_ = $dat;
      if( !empty($tot) ){

        $_ = _tex::agr($_,$tot,"0");
      }
      // parse-int o parse-float
      elseif( is_string($dat) ){

        $_ = preg_match("/\d+,\d+$/",$dat) ? floatval($dat) : intval($dat);
      }
      return $_;
    }
    // formato de bits
    static function bit( $dat, $ini='KB', $fin='MB' ) : string {
      $_ = floatval($dat); 
      $ini = strtoupper($ini); 
      $fin = strtoupper($fin);
      $_bit = [
        'B' =>1, 
        'KB'=>1024, 
        'MB'=>1048576, 
        'GB'=>1073741824, 
        'TB'=>1099511627776, 
        'PB'=>1125899906842624, 
        'EB'=>1152921504606846976, 
        'ZB'=>1180591620717411303424, 
        'YB'=>1208925819614629174706176
      ];
      if( is_numeric($_) && isset($_bit[$ini]) && isset($_bit[$fin]) ){      
        $_ = $_ * $_bit[$ini];// normalizo a bytes : <-
        $_ = $_ / $_bit[$fin];// convierto a valor final : ->
        if( is_numeric($_) && $_ >= 0 ){ 
          $_ = "<n class='bit'>".number_format($_,2,',','.')."</n><font class='ide'>{$fin}</font>";
        }else{ $_ = "
          <p><font class='err'>Error de conversión</font><c>:</c> {$_}</p>";
        }
      }// error de conversion
      else{ $_ = "
        <p>
          <font class='err'>Error de tipos</font><c>:</c>{$_}
          <br>
          <c class='sep'>-></c>{$ini}<c class='sep'>-></c>{$fin}
        </p>";
      }
      return $_;
    }
    // formato entero : ...mil.num
    static function int( string | float | int $dat, string $mil = '.' ) : string {
      
      return number_format( intval($dat), 0, ( $mil == '.' ) ? ',' : '.', $mil );
    }
    // formato decimal : ...num,dec
    static function dec( string | float | int $val, int $dec = 2, string $sep = ',' ) : string {
      
      return number_format( floatval($val), intval($dec), $sep, ( $sep == ',' ) ? '.' : ','  );
    }
    // reduzco a valor dentro del rango
    static function ran( string | float | int $num, int | float $max, int | float $min = 1 ) : int | float {
      
      $_ = is_string($num) ? _num::val($num) : $num;
      
      while( $_ > $max ){ $_ -= $max; } 
      
      while( $_ < $min ){ $_ += $max; } 
      
      return $_;
    }
    // sumatorias
    static function sum( int | float | string | array $val ) : int | float {

      if( !is_array($val) ) $val = explode(',', is_string($val) ? $val : strval($val) );

      return array_reduce( $val,
      
        function( $acu, $ite ){
          
          return $acu += _num::val($ite); 
        }
      );
    }

  }

