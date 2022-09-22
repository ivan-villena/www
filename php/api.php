<?php
  // SISTEMA : accesos
    define('SYS_DIR', "C:\\xampp\\htdocs" );
    define('SYS_NAV', "http://localhost/" );
    define('SYS_REC', "http://localhost/_/" );

  // OPERACIONES : clases
    define('DIS_OCU', "dis-ocu" );
    define('BOR_SEL', "bor-sel" );
    define('FON_SEL', "fon-sel" );

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  // Interfaces del sistema 
  class _api {

    // documento
    public array
      $ico = [],
      $let = [],
      $var = [], 
      $var_tip = [], // tipos
      $var_dat = [], // tipos de dato
      $var_val = [], // tipos de valor
      $var_ope = [], // tipos de operaciones
      $val_opc = [], // opciones por operadores
      $var_ide = []  // identificadores de controladores
    ;
    // Interfaces
    public array
      // numeros
      $num = [],
      // fechas
      $fec = [],
      // holon
      $hol = []
    ;
    // Aplicacion
    public array
      // datos
      $dat = [],
      $dat_atr = [], // atributos de la base
      $dat_est = [], // estructuras de la base
      // tablas : valor por esquemas.estructrua/s
      $est = [],
      // tableros : estructura con valores por esquemas.estructrua/s
      $tab = []    
    ;

    function __construct(){
      
      // documento : iconos + letras
      $this->ico = _dat::var('_api.ico', [ 'niv'=>['ide'] ]);
      $this->let = _dat::var('_api.let', [ 'niv'=>['ide'] ]);

      // variable: tipos + operaciones
      $this->var_tip = _dat::var('_api.var_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
      $this->var_ope = _dat::var('_api.var_ope', [ 'niv'=>['ide'] ]);
      
      // fechas : mes + semana + dias
      foreach( ['mes','sem','dia'] as $ide ){

        $this->{"_fec_$ide"} = _dat::var("_api.fec_$ide");
      }
    }

    // get : estructura-objetos
    static function _( string $ide, $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];

      // aseguro carga      
      if( !isset($_api->$ide) ){
        $_api->$ide = _dat::ini('api',$ide);
      }
      
      // cargo datos
      $_dat = $_api->$ide;
      
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

    // estructuras
    static function _dat_est( string $esq, string $ide = NULL, string $ope = NULL ) : array | object {      
      global $_api;
      $_ = [];
      // cargo estructuras de un esquema por operadores
      if( empty($ide) ){

        if( !isset( $_api->dat_est[$esq] ) ){
          
          foreach( _dat::var("_api.dat_est",[ 'ver'=>"`esq`='{$esq}'", 'niv'=>['ide'], 'obj'=>"ope", 'red'=>"ope" ]) as $est => $_ope ){

            $_api->dat_est[$esq][$est] = _sql::est("_{$esq}",'ver',$est,'uni');

            $_api->dat_est[$esq][$est]->ope = $_ope;
          }
        }
        $_ = $_api->dat_est[$esq];
      }
      else{

        if( !isset($_api->dat_est[$esq][$ide]) ){ 

          if( is_object( $_api->dat_est[$esq][$ide] = _sql::est("_{$esq}",'ver',$ide,'uni') ) ){
            // busco operadores
            $_api->dat_est[$esq][$ide]->ope = _dat::var("_api.dat_est",[
              'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni"
            ]);
          }    
        }
        
        $_ = $_api->dat_est[$esq][$ide];        
      }
      // devuelvo operador
      if( !empty($ope) ){

        $_ = isset( $_->ope->$ope ) ? $_->ope->$ope : FALSE;
      }

      return $_;
    }// atributos 
    static function _dat_atr( string $esq, string $est, string | array $ide = NULL ) : bool | array | object {      
      global $_api;

      if( !isset($_api->dat_atr[$esq]) ) $_api->dat_atr[$esq] = [];

      if( !isset($_api->dat_atr[$esq][$est]) ){

        $_api->dat_atr[$esq][$est] = !empty( _sql::est("_{$esq}",'lis',"_{$est}",'uni') ) ? _sql::atr("_{$esq}","_{$est}") : _sql::atr("_{$esq}",$est);
        
        // cargo operadores del atributo
        $_atr = &$_api->dat_atr[$esq][$est];
        foreach( _dat::var("_api.dat_atr",['ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'ele'=>'var' ]) as $_api_atr ){

          if( !empty($_api_atr->var) && isset($_atr[$i = $_api_atr->ide]) ){

            $_atr[$i]->var = _ele::jun($_atr[$i]->var, $_api_atr->var);
          }
        }
      }
      // todos
      if( empty($ide) ){
        $_ = $_api->dat_atr[$esq][$est];
      }// uno
      elseif( is_string($ide) ){
        $_ = isset($_api->dat_atr[$esq][$est][$ide]) ? $_api->dat_atr[$esq][$est][$ide] : FALSE;
      }// muchos
      else{
        $_ = [];
        foreach( $ide as $atr ){
          $_ []= isset($_api->dat_atr[$esq][$est][$atr]) ? $_api->dat_atr[$esq][$est][$atr] : FALSE;
        }
      }
      return $_;
    }// valores
    static function _dat_val( string $esq, string $est = NULL, string $ide = NULL ) : bool | array | object {      
      global $_api;
      
      if( !isset($_api->dat_val[$esq]) ) $_api->dat_val[$esq] = [];

      if( empty($est) ){
        
        $_ = $_api->dat_val[$esq] = _dat::var("_api.dat_val",[ 
          'ver'=>"`esq`='{$esq}'", 'niv'=>["est"], 'obj'=>"ope", 'red'=>"ope" 
        ]);
      }
      else{

        if( !isset($_api->dat_val[$esq][$est]) ){

          $_api->dat_val[$esq][$est] = _dat::var("_api.dat_val",[ 
            'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni" 
          ]);
        }

        if( empty( $ide ) ){
        
          $_ = $_api->dat_val[$esq][$est];
        }
        elseif( isset($_api->dat_val[$esq][$est]->$ide) ){
  
          $_ = $_api->dat_val[$esq][$est]->$ide;
        }
        else{
  
          $_ = FALSE;
        }
      }
      return $_;
    }

    // controladores de valores variables
    static function _var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_var = &$_api->var;
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
    }// incremendo del identificador para operadores-variable
    static function _var_ide( string $ope ) : string {
      global $_api;

      if( !isset($_api->var_ide[$ope]) ) $_api->var_ide[$ope] = 0;

      $_api->var_ide[$ope]++;

      return $_api->var_ide[$ope];

    }// operaciones : ver, ...
    static function _var_ope( string $tip, mixed $dat, mixed $ope = [], ...$opc ) : mixed {
      global $_api;
      $_ = [];
      switch( $tip ){
      case 'opc':
              
        if( !isset($_api->var_ope_opc[$tip][$dat[0]][$dat[1]]) ){

          $_dat = _dat::var( _api::_('var_ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );
    
          $_api->var_ope_opc[$tip][$dat[0]][$dat[1]] = _doc_opc::val( $_dat, $ope, ...$opc);
        }
    
        $_ = $_api->var_ope_opc[$tip][$dat[0]][$dat[1]];
        
        break;
      }
      return $_;
    }    

    // cargo valores de un proceso : absoluto o con dependencias ( _api.dat->est ) 
    static function _val( string | array $ope, mixed $dat = NULL ) : array {
      global $_api;
      $_ = [];
      if( is_array($ope) ){
        // cargo temporal
        foreach( $ope as $esq => $est_lis ){
          // recorro estructuras del esquema
          foreach( $est_lis as $est => $dat ){
            // recorro dependencias
            $dat_est = _app::dat_est($esq,$est,'est');
            
            foreach( ( !empty($dat_est) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
              // acumulo valores
              if( isset($dat->$ide) ){
                
                $_["{$esq}-{$ref}"] = $dat->$ide;
              }
            }                            
          }
        }
        $_api->dat []= $_;
      }
      return $_;
    }    

    // tablero de la aplicacion
    static function _tab( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->tab[$esq][$est]) ){
        $_api->tab[$esq][$est] = _dat::var("_api.tab",[ 'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'opc'=>'uni', 'ele'=>['ele','ope','opc'] ]);
      }
      // devuelvo tablero : ele + ope + opc
      $_ = $_api->tab[$esq][$est];

      // combino elementos
      if( isset($ele) ){
        $_ = $ele;
        if( !empty($_api->tab[$esq][$est]->ele) ){

          foreach( $_api->tab[$esq][$est]->ele as $eti => $atr ){
            
            $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
          }
        }
      }
      return $_;
    }

    // tabla de la base 
    static function _est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;

      if( !isset($_api->est[$esq][$est]) || isset($ope) ){

        // combinado        
        $_est = _dat::var("_api.est",[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 'obj'=>'ope', 'red'=>'ope',  'opc'=>'uni' ]);

        // cargo atributos por estructura de la base      
        $_atr = _dat::atr($esq,$est);

        // reemplazo atributos por defecto
        if( isset($ope['atr']) ){
          $_est->atr = _lis::ite($ope['atr']);
          // descarto columnas ocultas
          if( isset($_est->atr_ocu) ) unset($_est->atr_ocu);
        }
        if( empty($_est->atr) ){
          $_est->atr = !empty($_atr) ? array_keys($_atr) : [];
        }
        if( isset($ope['atr_ocu']) ){
          $_est->atr_ocu = _lis::ite($ope['atr_ocu']);
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
            
              $_est->{"{$i}_{$e}"} = _lis::ite($ope["{$i}_{$e}"]);
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

        $_api->est[$esq][$est] = $_est;
      }

      return $_api->est[$esq][$est];
    }    

  }

  // Código sql 
  class _sql {

    // ejecuto codigo sql 
    static function dec( ...$val ){  
      $_ = []; 
      $err=[];
      $eje=[];
      
      $_ses = $_SESSION['sql'];

      $_sql = new mysqli( $_ses['ser'], $_ses['usu'], $_ses['pas'], $_ses['esq'] );
    
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
    static function cod( string $ide = '', array $ope = [], string $tip='ver' ) : array {
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
    
      ];
      // solo agregar 1 registro :: ( ``,`` ) values( '','' )
      if( $tip=='agr' ){     
        $_['atr'] = [];
        $_['val'] = [];    
        foreach( _lis::ite($ope['val']) as $pos=>$ite ){
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
      }
      // solo modificar 1 o más campos de 1 o más registros :: set `atr` = 'val', 
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
    
      }
      // consultar :: campos, juntar, agrupar, ordenar, limitar
      elseif( $tip == 'ver' ){
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
        
      }
      // ++ filtros: where `atr` ope val/var/expr
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
        }
        elseif( is_string($ope['ver']) && !empty($ope['ver']) ){ 
          $_['ver']=" {$ope['ver']}";
        }
        if( !empty($_['ver']) ) $_['ver']=" WHERE{$_['ver']}";
      }
      return $_;
    }
    // esquemas
    static function esq( string $ope, string $ide='', ...$opc ) : string | array | object {
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
          $_sql []= "DROP SCHEMA `{$ide}`";
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
        $_var = _api::_('var_tip');
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
            $var['dat'] = $var_cue = _obj::dec($var_cue);
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

  // Código html 
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
          $ele = _obj::dec($ele,[],'nom');
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
          foreach( _lis::ite($htm) as $ele ){
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

  // usuario : sesion + tránsitos  
  class _usu {

    public int $ide = 0;

    public string $pas = "";

    public string $nom = "Usuario";

    public string $ape = "Público";

    public string $eda = "";

    public string $fec = "";

    public string $sin = "";

    public string $kin = "";
    
    public string $psi = "";

    public string $mai = "";

    public string $tel = "";

    public string $ubi = "";

    public function __construct( int $ide = NULL ){

      if( !empty($ide) ){

        foreach( _dat::var("_api.usu", [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

          $this->$atr = $val;
        }

        // calculo edad actual
        $this->eda = _fec::cue('eda',$this->fec);
      }      
    }

    // genero transitos
    static function cic_act( string $tip = NULL, mixed $val = NULL ) : string {
      global $_usu;
      $_ = "";
      if( empty($tip) ){
      }
      else{
        switch( $tip ){
        // genero tránsitos anuales > lunares
        case 'ani':          
          // elimino previos
          $_ .= "DELETE FROM `_api`.`usu_cic_ani` WHERE usu = $_usu->ide;<br>";
          $_ .= "DELETE FROM `_api`.`usu_cic_lun` WHERE usu = $_usu->ide;<br>";

          // pido tránsitos
          foreach( _hol::cic( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

            $_ .= "INSERT INTO `_usu`.`cic_ani` VALUES( 
              $_usu->ide, 
              $_cic_ani->ide, 
              $_cic_ani->eda, 
              $_cic_ani->arm, 
              $_cic_ani->ond, 
              $_cic_ani->ton, 
              '"._fec::var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
            );<br>";

            if( empty($_cic_ani->lun) ) continue;

            foreach( $_cic_ani->lun as $_cic_lun ){

              $_ .= "INSERT INTO `_usu`.`cic_lun` VALUES( 
                $_usu->ide, 
                $_cic_lun->ani, 
                $_cic_lun->ide, 
                '"._fec::var($_cic_lun->fec,'dia')."', 
                '$_cic_lun->sin', $_cic_lun->kin 
              );<br>";
            }
          }

          break;
        // genero transito diario por ciclo lunar
        case 'dia':
          
          break;
        }
      }
      return $_;
    }
    // calculo tránsito actual
    static function cic_dat( string $fec = '' ) : array {
      global $_usu;

      // valido fecha
      if( empty($fec) ) $fec = date( 'Y/m/d' );

      // cargo holon por fecha
      $_['hol'] = _hol::val( $fec );

      // busco anillo actual
      $_['ani'] = _dat::var("_api.usu_cic_ani",[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
      ]);

      // busco transito lunar
      $_['lun'] = _dat::var("_api.usu_cic_lun",[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = {$_['ani']->ide} AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
      ]);

      // calculo diario
      $_['dia'] = new stdClass;
      $_['dia']->kin = _hol::_('kin', intval($_['hol']['kin']) + intval($_usu->kin) );

      return $_;
    }
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // Dato : esq.est[ide].atr
  class _dat {
    
    // listado : devuelvo estructura - objeto
    static function var( $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

      // objeto->propiedad 
      if( is_string($ope) ){

        $esq = $dat;
        $est = $ope;

        $_ = isset($val) ? $val : new stdClass;
        
        if( !isset($val) || !_obj::tip($val) ){
          
          if( class_exists($_cla = "_$esq") ){

            if(  method_exists($_cla,'_') ) $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);

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

    // estructura : datos + operadores
    static function est( string $esq, string $ide = NULL, mixed $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      if( !isset($ide) ){

        $_ = _app::dat_est($esq);
      }
      elseif( !isset($tip) ){
        
        $_ = _app::dat_est($esq,$ide);
      }
      else{
        switch( $tip ){
        case 'ope': 
          $_ = _dat::est($esq,$ide);
          if( !empty($_->$ope) ) $_ = $_->ope;
          break;
        }
      }
      return $_;
    }// operadores de la estructura : relaciones + ficha + filrtos + colores + imagenes + numeros + textos
    static function est_ope( string $esq, string $est, string $atr = NULL ) : mixed {
      $_ = FALSE;
      $_val = _app::dat_est($esq,$est)->ope;
      if( empty($atr) ){
        $_ = $_val;
      }
      elseif( isset($_val->$atr) ){
        $_ = $_val->$atr;
      }
      return $_;
    }// cuento columnas totales
    static function est_atr( string | array $dat, array $ope=[] ) : int {
      $_ = 0;
      if( isset($ope['atr']) ){
        
        $_ = count($ope['atr']);
      }
      // 1 estructura de la base
      elseif( !( $obj_tip = _obj::tip($dat) ) ){

        $ide = _dat::ide($dat);

        $dat_est = _app::est($ide['esq'],$ide['est']);

        $_ = isset($dat_est->atr) ? count($dat_est->atr) : 0;

      }
      // n estructuras de la base
      elseif( $obj_tip == 'nom' ){

        foreach( $dat as $esq => $est_lis ){
  
          foreach( $est_lis as $est ){

            $dat_est = _app::est($esq,$est);

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

    // atributo : datos + tipo + variable
    static function atr( string $esq, string $est, mixed $ide = NULL, string $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      if( !isset($ide) ){

        $_ = _app::dat_atr($esq,$est);
      }
      elseif( !isset($tip) ){
        $_atr = _app::dat_atr($esq,$est);
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
    }// identificador por relaciones : esq.est_atr | _api.dat_atr[ide].dat
    static function atr_est( string $esq, string $est, string $atr ) : string {
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

    // valores : nombre, descripcion, titulo, imagen, color...
    static function val( string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : mixed {
      $_ = FALSE; 
      
      $_val = _app::dat_val($esq,$est);
      
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
    }// ver valores : imagen, color...
    static function val_ver( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
      // dato
      $_ = [ 'esq' => $esq, 'est' => $est ];

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
    }// proceso abm : alta , modificacion y baja de registro-objeto
    static function val_ope( string $esq, string $est, string $tip, object $dat ) : string {
      $_="";
      $_sql = [];
      if( $esq=='usu' ){
        
      }
      // ejecuto transacciones    
      $var_eve = [];
      foreach( $_sql as $est=>$ope ){ 
        $eje []= _dat::val( $tip, $est, $ope) ; 
      }
      if( !empty($eje) ){
        $_ = _sql::dec( ...$eje );
      }
      return $_;
    }
  }
  // Valor : tip + ver
  class _val {

    // objeto por tipo de variable['dat','val']
    static function tip( mixed $val ) : bool | object {
      $_ = FALSE;
      $ide = strtolower(gettype($val));    
      // vacios
      if( is_null($val) ){
        $ide = "null";
      }
      // logicos
      elseif( is_bool($val) ){
        $val = "bool";
      }
      // funciones
      elseif( is_callable($val) ){ 
        $ide = "function"; 
      }
      // listados
      elseif( is_array($val) && array_keys($val) !== range( 0, count( array_values($val) ) - 1 ) ){
        $ide = "asoc"; 
      }
      // numericos
      elseif( is_numeric($val) ){ 
        $ide="int";      
        if( is_nan($val) ){ 
          $ide = "nan";
        }// evaluar largos
        else{
          if( is_integer($val) || is_long($val) ){          
            $ide = "integer";
            if( $val >= -128 && $val <= 127 ){ 
              $ide = "tinyint";
            }elseif( $val >= -32768 && $val <= 32767 ){ 
              $ide = "smallint";
            }elseif( $val >= -8388608 && $val <= 8388607 ){ 
              $ide = "mediumint";
            }elseif( $val >= -2147483648 && $val <= 2147483647 ){ 
              $ide = "int";
            }elseif( $val >= -92233720368547 && $val <= 92233720368547 ){ 
              $ide = "bigint";
            }else{
              $ide = "long";
            }
          }else{
            $ide="decimal";
            if( is_double($val) ){ 
              $ide = "double";
            }
            elseif( is_float($val) ){ 
              $ide = "float";
            }
          }
        }
      }
      // textos
      elseif( is_string($val) ){
        $tam = strlen($val);
        $ide = "varchar";
        if( $tam <= 50 ){
          if( preg_match("/^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/",$val) ){ 
            $ide = "datetime";
          }elseif( preg_match("/^\d{4}([\-\/\.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/",$val) ){ 
            $ide = "date";              
          }elseif( preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/",$val) ){ 
            $ide = "time";                  
          }
        }
        elseif( $tam <= 255 && $tam >= 100 ){
          $ide = "tinytext";
        }
        elseif( $tam <= 65535 ){
          $ide = "text";
        }
        elseif( $tam <= 16777215 ){
          $ide = "mediumtext";
        }
        elseif( $tam <= 4294967295 ){
          $ide = "longtext";
        }
        else{ 
          $ide = "string";
        }
      }
      
      if( !empty( $tip = _api::_('var_tip',$ide) ) ){
        $_ = $tip;
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

  }
  // listado : []
  class _lis {

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
    static function val( array | object $dat ) : array {

      $_ = $dat;

      if( _obj::tip($dat) ){

        $_ = [];

        foreach( $dat as $v ){

          $_[] = $v;
        }
      }
      return $_;
    }
  }
  // Estructura : [ ...{} ] 
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

        $ope['opc'] = _lis::ite($ope['opc']);

        if( in_array('uni',$ope['opc']) ) _est::uni($dat, ...$opc );
      }

      return $dat;
    }
    // decodifica : "" => {} , []
    static function dec( array &$dat, string | array $atr, ...$opc ) : array {

      $atr = _lis::ite($atr);

      foreach( $dat as &$ite ){

        if( is_object($ite) ){

          foreach( $atr as $ide ){

            if( isset($ite->$ide) ){

              $ite->$ide = _obj::dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);            
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
          $ide = _dat::ide($dat);
          $_ = _dat::atr($ide['esq'],$ide['est']);
        }
        // del entorno 
        else{
          
          foreach( $dat as $ite ){

            foreach( $ite as $ide => $val ){ 
              $atr = new stdClass;
              $atr->ide = $ide;
              $atr->nom = $ide;
              $atr->var = _val::tip($val);
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
          $_[ implode('(.)',$i) ] = $val; 
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

                      foreach( $n_4 as &$n_5 ){

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
              $val_ite []= _val::ver( $val, $ver[1], $ver[2] );
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

        if( !_val::ver( $opc_ide ? $i : $v, $ope, $val ) ) 
        
          $_[ $lis_tip ? $pos : $i ] = $v;

        $pos++;
      }
      
      return $dat = $_;
    }
    
  }
  // Ejecucion : ( ...par ) => { ...cod } : val 
  class _eje {

    // ejecucion del entorno : funcion() |o| [namespace/]clase(...par).objeto->método(...par)
    static function val( mixed $ide, mixed $par=[], array $ini=[] ) : mixed {
      // php.fun(,)par(,)... || rec/cla.met(,)par(,)...      
      if( is_string($ide) ){

        if( preg_match("/^\[.+\]$/",$ide) ){
          // FALSE : convierto en objetos stdClass
          $var_eve = _obj::dec($ide);
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
        $_ = _eje::fun( $ide, ..._lis::ite($par) );      
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

              $_ = empty($par) ? $cla::$met() : $cla::$met( ..._lis::ite($par) ) ;
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

              $_ = empty($par) ? $obj->$met() : $obj->$met( ..._lis::ite($par) ) ;
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
  // Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
  class _ele {

    // devuelvo elemento : [ atr => "val" ]
    static function val( string | array | object $ele, array | object $dat = NULL ) : array {

      $_ = $ele;
      
      // convierto "" => []
      if( is_string($ele) ){
        $_ = _obj::dec($ele,$dat,'nom');
      }
      // convierto {} => []
      elseif( is_object($ele) ){
        $_ = _obj::nom($ele);
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
    static function jun( string | array $ele, array $ope, array | object $dat = NULL, array $opc = [] ) : array {
      // si es "", convierto a []
      $_ = _ele::val($ele,$dat);
      // proceso opciones
      $opc_eje = isset($opc['eje']) ? $opc['eje'] : [];
      $opc_cla = isset($opc['cla']) ? $opc['cla'] : [];
      $opc_css = isset($opc['css']) ? $opc['css'] : [];

      // recorro elementos
      foreach( _lis::ite($ope) as $ele ){
        
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
        'cli'=>"onclick",
        'cam'=>"onchange",
        'inp'=>"oninput",
        'foc'=>"onfocus",
        'hov'=>"onhover",
        'tec'=>"onkeypress"
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
    
        $ele = _obj::dec($dat,[],'nom');
    
        if( isset($ele['class']) ){
    
          foreach( explode(' ',$ele['class']) as $val ){ 
    
            if( !empty($val) ) $_[] = trim($val);
          }
        }
      }// operaciones
      else{
        
        if( in_array('eli',$opc) ){

          foreach( _lis::ite($val) as $v ){
            
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
  // Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
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
    // convierto a string: {} => ""
    static function cod( object | array | string $dat ) : string {
      $_ = [];
      
      if( is_array($dat) || is_object($dat) ){
        // https://www.php.net/manual/es/function.json-encode.php
        // https://www.php.net/manual/es/json.constants.php
        $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
      }
      return $_;
    }
    // convierto a objeto : "" => {}/[]
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
      elseif( is_object($dat) ){

        $_ = get_class($dat) == 'stdClass' ? 'atr' : 'atr';
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
          $ope = _lis::ite($ope);
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
          $ope = _lis::ite($ope);
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
  // Archivo : fichero + texto + imagen + audio + video + app + ...tipos
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
  // Texto : caracter + letra + oracion + parrafo
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
  // Numero : separador + operador + entero + decimal + rango
  class _num {

    // datos del objeto
    static function dat( int | float | string $ide, string $atr='' ) : object | string {
      $_num_int = _api::_('num_int');
      
      // aseguro valor
      if( is_string($ide) ) $ide = _num::val($ide);
      
      // busco datos
      $_ = isset($_num_int[$ide]) ? $_num_int[$ide] : new stdClass();
      
      // devuelvo atributo
      if( !empty($atr) ) $_ = isset($_->$atr) ? $_->$atr : "";

      return $_;
    }
    // devuelvo valor con "0-" o numérico : int | float
    static function val( mixed $dat, int $tot = 0 ) : string | int | float {
      $_ = $dat;
      if( !empty($tot) ){

        $_ = _tex::agr( abs( $dat = _num::val($dat) ), $tot, "0");

        if( $dat < 0 ) $_ = "-".$_;
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
    // redondeos
    static function red( mixed $val, int $dec = 0, string $tip = 'min' ) : mixed {
      // https://www.php.net/manual/es/function.round.php
      return round($val, $dec, $tip == 'min' ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP );
    }

  }
  // Fecha : aaaa-mm-dia hh:mm:ss utc
  class _fec {  

    // codifico fecha : [ año, mes, dia ]
    static function cod( string $val, string $sep = NULL ) : array {
      $_ = [];

      $val = explode(' ',str_replace('T','',$val))[0];

      if( empty($sep) ){
        $sep = preg_match("/-/",$val) ? '-' : ( preg_match("/\//",$val) ? '/' : '.' );
      }

      $dat = array_map( function($v){ return intval($v); }, explode($sep,$val) );

      if( strlen( strval($dat[0]) ) == 4 || $dat[0] > 31 ){

        $_ = [ $dat[0], $dat[1], $dat[2] ];
      }
      else{        
        $_ = [ $dat[2], $dat[1], $dat[0] ];
      }
      return $_;
    }
    // objeto: DateTime
    static function dec( int | string | object | array $dat = NULL ) : DateTime | string {
      $_ = $dat;
      if( empty($dat) ){
        $_ = new DateTime('NOW');
      }
      else{
        if( is_numeric($dat) && preg_match("/^\d+$/",strval($dat)) ){
          try{ 
            $_ = new DateTime( intval($dat) );
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }
        elseif( is_string($dat) ){ 
          try{ 
            $_ = _fec::dat($dat);
            $_ = !! $_ ? new DateTime( "{$_->año}-{$_->mes}-{$_->dia}" ) : new DateTime('NOW');
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }
        elseif( is_object($dat) ){

          if( get_class($dat)=='stdClass' ){
            $_ = new DateTime( "{$dat->año}-{$dat->mes}-{$dat->dia}" );
          }
          else{
            $_ = $dat;
          }
        }
        elseif( is_array($dat) ){
          $_ = new DateTime( "{$dat[0]}-{$dat[1]}-{$dat[2]}" );
        }
      }
      return $_;
    }
    // devuelvo por tipo
    static function tip( mixed $dat, $tip = NULL ) : bool | string {
      $_ = FALSE;

      if( empty($tip) ){
        $_ = $dat;
        if( is_string($dat) ) $_ = _fec::dat($dat);
      }
      else{
        $_fec = $dat;
        // aseguro objeto nativo
        if( !is_object($dat) || get_class($dat)=='stdClass' ) $_fec = _fec::dec($dat); 
        // busco tipo
        switch( $tip ){
        case 'dyh': $_ = $_fec->format('Y/m/d H:i:s');  break;
        case 'hor': $_ = $_fec->format('H:i:s');        break;
        case 'sem': $_ = $_fec->format('w');            break;
        case 'dia': $_ = $_fec->format('Y/m/d');        break;
        }
      }
      return $_;
    }
    // objeto fecha: { val, año, mes, dia, sem, hor, min, seg, ubi }
    static function dat( string $val, ...$opc ) : object {

      $_ = new stdClass();
      $_->val = "";
      $_->dia = 0;
      $_->mes = 0;
      $_->año = 0;
      $_->tie = "";
      $_->hor = 0;
      $_->min = 0;
      $_->seg = 0;
      $_->ubi = "";

      // separo valores
      $val = explode( preg_match("/T/i",$val) ? 'T' : ' ', $val );

      if( isset($val[0]) ){

        $fec = explode( preg_match("/-/",$val[0]) ? '-' : '/', $val[0] );

        if( isset($fec[2]) ){
          // mes
          $_->mes = intval($fec[1]);
          // año
          if( strlen($fec[0]) > 2 ){
            $_->año = intval($fec[0]);    
            $_->dia = intval($fec[2]);    
          }else{
            $_->año = intval($fec[2]);    
            $_->dia = intval($fec[0]);
          }  
          // valido fecha resultante
          if( $_->val = _fec::val($_,...$opc) ){
            // busco valor semanal
            $_->sem = _fec::tip($_,'sem');
            // proceso horario
            if( isset($val[1]) ){
              $hor = explode(':', $_->tie = $val[1]);
              // segundos
              if( isset($hor[2]) ) $_->seg = intval($hor[2]);
              // minutos
              if( isset($hor[1]) ) $_->min = intval($hor[1]);
              // horas
              $_->hor = intval($hor[0]);
            }        
          }
        }
      }    
      return $_;
    }
    // validor de fecha : "año/mes/dia" | "dia/mes/año"
    static function val( object $dat, ...$opc ) : bool | string {
      $_ = FALSE;
    
      $año = !empty($dat->año) ? $dat->año : 1900;
      $mes = !empty($dat->mes) ? $dat->mes : 1;
      $dia = !empty($dat->dia) ? $dat->dia : 1;
  
      if( checkdate($mes, $dia, $año) ){
        
        $_ = !in_array('año',$opc) ? _num::val($dia,2).'/'._num::val($mes,2).'/'._num::val($año,4) : _num::val($año,4).'/'._num::val($mes,2).'/'._num::val($dia,2);
      }

      return $_;
    }
    // formateo para input : "aaa-mm-ddThh:mm:ss"
    static function var( string $val = NULL, string $tip = 'dia' ) : string {
      $_ = "";
  
      if( !empty($val) ){

        if( ( $tip == 'dia' || $tip == 'dyh' ) ){

          $_fec = _fec::dat($val,'año');

          $val = $_fec->val;
        }

        $_ = str_replace(' ','T',str_replace('/','-',$val));
      }
      
      return $_;
    }
    // operaciones numericas por tipos
    static function ope( string | object $dat, int $val = 0, string $ope = '+', string $tip = 'dia' ){
      $_ = $dat;
      
      if( is_object($dat) ){

        $_ = "{$dat->año}-{$dat->mes}-{$dat->dia}";
      }
      elseif( is_string($dat) ){

        $_ = str_replace('/','-',$dat);
      }

      if( !!$_ ){
        $tie = '';
        switch( $tip ){
        case 'seg': $tie = 'second'; break;
        case 'min': $tie = 'minute'; break;
        case 'hor': $tie = 'hour';   break;
        case 'dia': $tie = 'day';    break;
        case 'sem': $tie = 'week';   break;
        case 'mes': $tie = 'month';  break;
        case 'año': $tie = 'year';   break;
        } 
        if( $val > 1 ) $tie .= "s";
        // strtotime devuelve un timestamp        
        $_ = date( 'd-m-Y', strtotime( $ope.strval($val)." $tie", strtotime($_) ) );
      }
      return $_;
    }
    // cuento dias pos periodo : mes | año
    static function cue( string $tip, string | object $val ) : mixed {

      $_ = is_string($val) ? _fec::dat($val) : $val;

      switch( $tip ){
      case 'mes':
        if( !!$_ ){
          $_ = cal_days_in_month( CAL_GREGORIAN, $_->mes, $_->año );
        }
        break;
      case 'año':
        $lis = [];
        $tot = 0; 
        $num = 0; 
        $mes = 0;
        for( $i = 1; $i <= 12; $i++ ){

          $lis[$i] = _fec::cue('mes',"{$_->año}/$i/1");
          $tot += $lis[$i]; 
        }
        if( $_->mes == 1 ){ 
          $num = $_->dia;
        }
        else{
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
      case 'eda':
        $ano_dif = date("Y") - $_->año;
        $mes_dif = date("m") - $_->mes;
        $dia_dif = date("d") - $_->dia;
        if( $dia_dif < 0 || $mes_dif < 0 ) $ano_dif--;
        $_ = $ano_dif;
        break;
      }
      return $_;
    }
    // defino valor por rangos : AC - DC
    static function ran( $ini, $fin ){

      $_ = "";

      if( $ini < 0 && $fin < 0  ){

        $_ = _num::int( $ini * - 1 )." - "._num::int( $fin * - 1). " A.C.";
      }
      elseif( $ini > 0 && $fin > 0 ){

        $_ = _num::int( $ini )." - "._num::int( $fin ). " D.C.";
      }
      else{
        $_ = _num::int( $ini * - 1 )." A.C. - "._num::int( $fin ). " D.C.";
      }

      return $_;
    }
    // año bisciesto ?
    static function año_bis( string | object $fec ) : bool {

      if( is_string($fec) ) $fec = _fec::dat($fec);

      return date('L', strtotime("$fec->año-01-01"));
    }

  }
  // holon del sincronario
  class _hol {

    public function __construct(){
    }

    // estructuras por posicion
    static function _( string $ide, mixed $val = NULL ) : string | array | object {
      $_ = [];

      global $_api;
      
      $est = "hol_$ide";

      // aseguro carga
      if( !isset($_api->$est) ) $_api->$est = _dat::ini('hol',$ide);

      // busco dato
      if( !empty($val) ){        
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $fec = _api::_('fec',$val);
            if( isset($fec->dia)  ) $_ = _dat::var( _hol::_('psi'), [ 'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 'opc'=>['uni'] ]);
            break;
          case 'kin':
            $_ = $_api->$est[ _num::ran( _num::sum($val), 260 ) - 1 ];
            break;
          default:
            if( isset($_api->$est[ $val = intval($val) - 1 ]) ) $_ = $_api->$est[$val]; 
            break;
          }
        }
      }
      // devuelvo toda la lista
      else{
        $_ = $_api->$est;
      }
      return $_;    
    }

    // consults sql
    static function dat( string $ide ) : string {
      $_ = "";
      switch( $ide ){
      case 'kin': 
        foreach( _hol::_('kin') as $kin => $_kin ){
          $sel = _hol::_('sel',$_kin->arm_tra_dia);
          $cel = _hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = _hol::_('ton',$_kin->nav_ond_dia);      
          // poder del sello x poder del tono
          if( preg_match("/(o|a)$/i",$ton->nom) ){
            $pod = explode(' ',$sel->pod);
            $art = $pod[0];
            if( preg_match("/agua/i",$pod[1]) ){ 
              $art = 'la';
            }
            $pod = "{$sel->pod} ".( ( strtolower($art) == 'la' ) ? substr($ton->nom,0,-1).'a' : substr($ton->nom,0,-1).'o' );
          }else{
            $pod = "{$sel->pod} {$ton->nom}";
          }
          // encantamiento del kin
          $enc = "Yo ".($ton->pod_lec)." con el fin de ".ucfirst($sel->acc).", \n".($ton->acc_lec)." {$sel->car}. 
            \nSello {$cel->nom} "._tex::art_del($sel->pod)." con el tono {$ton->nom} "._tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = _hol::_('sel', _hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder "._tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin->val_est) ){
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = _hol::_('kin_cro_ond',_hol::_('ton',$_ele['ton'])->ond_arm);
            $enc .= "\nSoy un Kin Polar, {$_arm->enc} el Espectro Galáctico {$_est->col}. ";
          }
          if( in_array($kin+1, $_kin->val_pag) ){
            $enc .= "\nSoy un Portal de Activación Galáctica, entra en mí.";
          }
          $_ .= "
          <p>
            UPDATE `_hol`.`kin` SET 
              `pod` = '{$pod}', 
              `des` = '{$enc}'
            WHERE 
              `ide` = '{$_kin->ide}';
          </p>";
        }
        break;
      case 'kin_fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $fac_ini = -3113;
        foreach( _hol::_('kin') as $_kin ){    
    
          $fac_fin = $fac_ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){
            $fac_fin ++;
          }
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `fac_ini` = $fac_ini, `fac_fin` = $fac_fin, `fac_ran` = '"._fec::ran($fac_ini,$fac_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $fac_ini = $fac_fin;
    
        }
        break;
      case 'kin_enc':
    
        $enc_ini = -26000;    
        foreach( _hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '"._fec::ran($enc_ini,$enc_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'kin_cro_ele':
        foreach( _hol::_('kin_cro_ele') as $_ele ){
          $_cas = _hol::_('cas',$_ele->ide);
          $_est = _hol::_('kin_cro_est',$_cas->arm);
          $_ton = _hol::_('ton',$_ele->ton);
          $_ .= "
          UPDATE `_hol``.`kin_cro_ele` 
            SET `des` = '$_ton->des del Espectro Galáctico "._tex::let_ora($_est->col)."'
          WHERE `ide` = $_ele->ide;<br>";
        }
        break;
      }
      return $_;
    }
    // sumo o resto dias de un fecha dada
    static function ope( string $tip, string $val, int $cue = 1, string $opc = 'dia' ) : string {

      $_ = $val;

      if( isset($val[3]) ){

        $val = explode('.',$val);
        $sir = intval($val[0]);
        $ani = intval($val[1]);
        $lun = intval($val[2]);
        $dia = intval($val[3]);
  
        switch( $opc ){
        case 'dia':
          if( $tip == '+' ){
  
            $dia += $cue;        
    
            if( $dia > 28 ){
              $lun += _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun > 13 ){
                $ani += _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani > 51 ){
                  $sir += _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }
          elseif( $tip == '-' ){
    
            $dia -= $cue;        
    
            if( $dia < 1 ){
              $lun -= _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun < 1 ){    
                $ani -= _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani < 0 ){    
                  $sir -= _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }        
          break;
        case 'lun': 
          if( $tip == '+' ){
  
            $lun += $cue;
              
            if( $lun > 13 ){
              $ani += _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani > 51 ){  
                $sir += _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);                
              }
            }
          }
          elseif( $tip == '-' ){
  
            $lun -= $cue;
              
            if( $lun < 1 ){  
              $ani -= _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani < 0 ){
                $sir -= _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);
              }
            }
          }        
          break;
        case 'ani': 
          if( $tip == '+' ){
  
            $ani += $cue;
  
            if( $ani > 51 ){
              $sir += _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
            }
          }
          elseif( $tip == '-' ){
  
            $ani -= $cue;
  
            if( $ani < 0 ){
              $sir -= _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
            }
          }
          break;
        case 'sir':
          if( $tip == '+' ){
  
            $sir += $cue;
          }
          elseif( $tip == '-' ){
  
            $sir -= $cue;
          }
          if( $sir == 0 ) $sir = -1;
  
          break;
        }

        $_ = "$sir."._num::val($ani,2)."."._num::val($lun,2)."."._num::val($dia,2);
      }
      return $_;
    }
    // convierto NS => d/m/a
    static function cod( array | string $val ) : bool | string {
      $_ = FALSE;

      if( is_string($val) ) $val = explode('.',$val);

      if( isset($val[3]) ){

        $sir = intval($val[0]);
        $ani = intval($val[1]);
        $lun = intval($val[2]);
        $dia = intval($val[3]);

        // mes y día
        $_psi = _dat::var( _hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
    
        if( isset($_psi->fec_mes) && isset($_psi->fec_dia) ){

          $_ = $_psi->fec_mes.'/'.$_psi->fec_dia;
        
          $ini_sir = 1;
          $ini_ani = 0;
          $año = 1987;
          // ns.
          if( $sir != $ini_sir ){

            $año = $año + ( 52 * ( $sir - $ini_sir ) );
          }
          // ns.ani.        
          if( $ani != $ini_ani ){          

            $año = $año + ( $ani - $ini_sir ) + 1;
          }
          // ajusto año
          if( $año == 1987 && ( $lun == 6 && $dia > 19 ) || $lun > 6 ){
            $año ++;
          }
          $_ = $año.'/'.$_;
        }
      }
      return $_;
    }
    // convierto d/m/a => NS
    static function dec( mixed $val ) : object | string {

      $_ = !is_object($val) ? _fec::dat($val) : $val ;

      if( !!$_ ){
        // SE TOMA COMO PUNTO DE REFERENCIA EL AÑO 26/07/1987
        $año      = 1987; 
        $_->sir   = 1;
        $_->ani   = 0; 
        $_->fam_2 = 87;
        $_->fam_3 = 38;
        $_->fam_4 = 34;

        if ($año < $_->año ){

          while( $año < $_->año ){ 

            $año++;

            $_->ani++;

            foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 

              $_->$atr = _num::ran($_->$atr+105, 260); 
            }

            if ($_->ani > 51){ 
              $_->ani = 0; 
              $_->sir++; 
            }
          }
        }
        elseif( $año > $_->año ){
          
          $_->sir = 0;
          while( $_->año < $año ){ 

            $año--; 
            
            $_->ani--;

            foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 
              
              $_->$atr = _num::ran($_->$atr-105, 260); 
            }

            if ($_->ani < 0){ 
              $_->ani = 51; 
              $_->sir--; 
            }
          } 
          // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
          if( $_->sir == 0 ) $_->sir = -1;
        }      
        if( $_->dia <= 25 && $_->mes <= 7){
          
          $_->ani--;
          
          foreach( ['fam_3','fam_4'] as $atr ){ 

            $_->$atr = _num::ran($_->$atr-105, 260); 
          }
        }
      }
      else{
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
      }
      return $_;
    }
    // busco valores : fecha - sincronario - tránsitos
    static function val( mixed $val, string $tip = '', array $ope = [] ) : array | object | string {    
      $_=[];
      // por tipo
      if( !empty($tip) ){
        // proceso fecha
        if( $tip == 'fec' ){
          $fec = $val;
          $_ = _hol::val($fec);
          if( is_string($_) ){ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
          }
        }
        // decodifico N.S.( cod.ani.lun.dia:kin )
        elseif( $tip == 'sin' ){
          // busco año          
          if( $_fec = _hol::cod($val) ){

            $_ = _hol::val($_fec);

            if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
          }
          else{ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
          }
        }
      }
      // armo datos de una fecha
      elseif( $fec = _fec::dat($val) ){
        // giro solar => año
        $_['fec'] = $fec->val;

        $_fec = _hol::dec($fec);

        // giro lunar => mes + día
        if( $_psi = _hol::_('fec',$_['fec']) ){

          $_['psi'] = $_psi->ide;

          $_['sin'] = "{$_fec->sir}."._num::val($_fec->ani,2).".{$_psi->lun}.{$_psi->lun_dia}";

          // giro galáctico => kin
          $_kin = _hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

          if( is_object($_kin) ){

            $_['kin'] = $_kin->ide;
          }
          else{
            $_ = '{-_-} Error de Cálculo con la fecha galáctica...'; 
          }
        }
        else{ 
          $_ = '{-_-} Error de Cálculo con la fecha solar...'; 
        }
      }
      // error
      else{ 
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
      }
      return $_;
    }
    // genero transitos por fecha del sincronario
    static function cic( string $val, ...$opc ) : array {
      $_ = [];
      $ver_lun = !in_array('not-lun',$opc);
      
      // recorro el castillo anual
      for( $cic_año = 1 ; $cic_año <= 52; $cic_año++ ){
        
        $_val = _hol::val($val,'sin');

        $_cas = _hol::_('cas',$cic_año);
        
        // creo el transito anual
        $_cic_año = new stdClass;
        $_cic_año->ide = $cic_año;
        $_cic_año->eda = $cic_año-1;
        $_cic_año->arm = $_cas->arm;
        $_cic_año->ond = $_cas->ond;
        $_cic_año->ton = $_cas->ton;
        $_cic_año->fec = $_val['fec'];
        $_cic_año->sin = $_val['sin'];
        $_cic_año->kin = $_val['kin'];
        // genero transitos lunares
        if( $ver_lun ){
          $_cic_año->lun = [];
          
          $val_lun = $val;
  
          for( $cic_mes = 1 ; $cic_mes <= 13; $cic_mes++ ){

            $_val_lun = _hol::val($val_lun,'sin');
  
            $_cic_lun = new stdClass;  
            $_cic_lun->ani = $cic_año;
            $_cic_lun->ide = $cic_mes;
            $_cic_lun->fec = $_val_lun['fec'];
            $_cic_lun->sin = $_val_lun['sin'];
            $_cic_lun->kin = $_val_lun['kin'];
            
            $_cic_año->lun []= $_cic_lun;
            // incremento 1 luna
            $val_lun = _hol::ope('+',$val_lun,1,'lun');            
          }
        }        
        $_ []= $_cic_año;
        // incremento 1 anillo      
        $val = _hol::ope('+',$val,1,'ani');
      }

      return $_;
    }
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // documento 
  class _doc {
    
    // icono
    static function ico( string $ide, array $ele=[] ) : string {
      $_="";      
      global $_api;
      $_ico = $_api->ico;
      $_ico_gru = [
        [ 'nom'=>'material-icons',					'url'=>'https://fonts.googleapis.com/css?family=Material+Icons' ],
        [ 'nom'=>'material-icons-outlined', 'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Outlined' ],
        [ 'nom'=>'material-icons-two-tone', 'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Two+Tone' ],
        [ 'nom'=>'material-icons-round',		'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Round' ],
        [ 'nom'=>'material-icons-sharp',		'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Sharp' ]
      ];
      if( isset($_ico[$ide]) ){
        if( preg_match("/\//",$_ico[$ide]->val) ){
          
        }
        else{
          $ele['eti'] = isset($ele['eti']) ? $ele['eti'] : 'span';
          if( $ele['eti'] == 'button' ){
            if( empty($ele['type']) ) $ele['type'] = "button";
          }
          $ele['ide'] = $ide;
          $htm = $_ico[$ide]->val;
          $_ = "
          <{$ele['eti']}"._htm::atr(_ele::cla($ele,"ico material-icons-outlined",'ini')).">
            {$htm}
          </{$ele['eti']}>";
        }    
      }
      return $_;
    }
    // letra : ( n, c )
    static function let( string $dat, array $ele=[] ) : string {
      global $_api;
      $_let = $_api->let;
      $_pal = [];
      foreach( explode(' ',$dat) as $pal ){
        // numero completo
        if( is_numeric($pal) ){
          if( preg_match("/,/",$pal) ) $pal = str_replace(",","<c>,</c>",$pal);
          if( preg_match("/\./",$pal) ) $pal = str_replace(".","<c>.</c>",$pal);
          $_pal []= "<n>{$pal}</n>";
        }// caracteres por palabra
        else{
          $let = [];
          foreach( _tex::let($pal) as $car ){
            $ele_let = $ele;
            if( is_numeric($car) ){
              $let []= "<n"._htm::atr($ele_let).">{$car}</n>";
            }elseif( isset($_let[$car]) ){
              _ele::cla($ele_let,"{$_let[$car]->tip} _{$_let[$car]->var}",'ini');
              $let []= "<c"._htm::atr($ele_let).">{$car}</c>";        
            }else{            
              $let []= $car;
            }
          }
          $_pal []= implode('',$let);
        }
      }
      return implode(' ',$_pal);
    }
    // imagen : (span,button)[ima]
    static function ima( ...$dat ) : string {    
      $_ = "";
      // por aplicacion
      if( isset($dat[2]) ){

        $ele = isset($dat[3]) ? $dat[3] : [];

        // if( preg_match("/_ide$/",$dat[1]) ) $dat[1] = preg_replace("/_ide$/",'',$dat[1]);
        
        $ele['ima'] = "{$dat[0]}.{$dat[1]}";

        $_ = _doc_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
      }
      // por directorio: localhost/_/esquema/image
      else{
        $ele = isset($dat[1]) ? $dat[1] : [];
        $dat = $dat[0];

        if( is_array($dat) ){

          $ele = _ele::jun( $dat, $ele );
        }
        // por valor
        elseif( is_string($dat)){

          $ima = explode('.',$dat);
          $dat = $ima[0];
          $tip = isset($ima[1]) ? $ima[1] : 'png';
          $dir = SYS_NAV."_/{$dat}";
          $fic_ide ="{$dir}.{$tip}";
          _ele::css( $ele, _ele::fon($dir,['tip'=>$tip]) );
        }
        
        $ele['eti'] = isset($ele['eti']) ? $ele['eti'] : 'span';

        if( $ele['eti'] == 'button' ){
          if( empty($ele['type']) ) $ele['type'] = "button";
        }
        
        if( empty($ele['ima']) ){
          $ele['ima'] = $fic_ide;
        }

        $htm = "";
        if( !empty($ele['htm']) ){
          _ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
          $htm = $ele['htm'];
        }

        $_ = "<{$ele['eti']}"._htm::atr($ele).">{$htm}</{$ele['eti']}>";
      }
      return $_;
    }
  }
  // contenedor
  class _doc_val {

    static string $IDE = "_doc_val-";
    static string $EJE = "_doc_val.";

    // pestaña con secciones : nav + * > ...[nav="ide"]
    static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['nav','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav";
      $_ = "";
      
      $val_sel = isset($ele['sel']) ? $ele['sel'] : '';

      // navegador 
      _ele::cla($ele['nav'], $tip, 'ini');
      $_ .= "
      <nav"._htm::atr($ele['nav']).">";    
      foreach( $dat as $ide => $val ){

        if( is_object($val) ) $val = _obj::nom($val);

        if( isset($val['ide']) ) $ide = $val['ide'];

        $ele_nav = isset($val['nav']) ? $val['nav'] : [];

        $ele_nav['eti'] = 'a';

        if( !isset($val['ico']) && !isset($ele_nav['htm']) && !empty($val['nom']) ) $ele_nav['htm'] = $val['nom'];

        _ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

        if( $val_sel == $ide ) _ele::cla($ele_nav,FON_SEL);
    
        $_ .= _htm::val($ele_nav);
      }$_.="
      </nav>";
      // contenido
      $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'section';
      $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'div';
      $_ .= "
      <$eti_sec"._htm::atr($ele['sec']).">";
        foreach( $dat as $ide => $val ){
          $ele_ite = $ele['ite'];
          $ele_ite['data-ide'] = $ide;
          if( $val_sel != $ide ) _ele::cla($ele_ite,DIS_OCU); 
          $_ .= "
          <$eti_ite"._htm::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? _ele::val($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
        }$_.="
      </$eti_sec>";

      return $_;
    }
    // variable : div.atr > label + (input,textarea,select,button)[name]
    static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
      $_var_ope = [ 
        'ico'=>"", 'nom'=>"", 'des'=>"", 
        'ite'=>[], 'eti'=>[], 'ope'=>[], 
        'htm'=>"", 'htm_pre'=>"", 'htm_med'=>"", 'htm_pos'=>"" 
      ];
      $_ = "";

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

      // por formulario de la base
      if( $tip == 'atr' ){        

        if( !empty($_atr = _dat::atr($esq,$est,$atr)) ){

          $_var = [ 'nom'=>$_atr->nom, 'ope'=>$_atr->var ];
        }
      }
      // carga operadores: esquema - dato - valor
      elseif( $tip != 'val' ){ 

        $_var = _app::var($tip,$esq,$est,$atr);
      }

      // combino operadores
      if( !empty($_var) ){

        if( !empty($_var['ope']) ){
          $ele['ope'] = _ele::jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
          unset($_var['ope']);
        }
        $ele = _obj::jun($ele,$_var);
      }
      // identificadores
      if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
        $ele['ope']['id'] = $ele['ide'];
      }
      // aseguro valor
      if( isset($ele['val']) && !isset($ele['ope']['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      // nombre en formulario
      if( empty($ele['ope']['name']) ){
        $ele['ope']['name'] = $atr;
      }      
      // agregados
      $agr = _htm::dat($ele);

      // etiqueta
      if( !isset($ele['eti']) ) $ele['eti'] = [];
      $eti_htm='';
      if( !in_array('eti',$opc) ){
        if( !empty($ele['ico']) ){

          $eti_htm = _doc::ico($ele['ico']);
        }
        elseif( !empty($ele['nom']) ){
    
          $eti_htm = _doc::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
        }
        if( !empty($eti_htm) ){
    
          if( isset($ele['ope']['id']) ) $ele['eti']['for'] = $ele['ope']['id']; 
    
          $eti_htm = "<label"._htm::atr($ele['eti']).">{$eti_htm}</label>";
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
      // valor            
      if( isset($agr['htm']) ){
        $val_htm = $agr['htm'];
      }
      else{
        if( isset($ele['val']) ){
          $ele['ope']['val'] = $ele['val'];
        }
        if( empty($ele['ope']['name']) && isset($ele['ide']) ){
          $ele['ope']['name'] = $ele['ide'];
        }
        $val_htm = _htm::val($ele['ope']);
      }
      // contenedor
      if( !isset($ele['ite']) ) $ele['ite']=[];      
      if( !isset($ele['ite']['title']) ){
        $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';
      }    
      return "
      <div"._htm::atr(_ele::cla($ele['ite'],"atr",'ini')).">
        ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
        {$eti_ini}
        {$val_htm}
        {$eti_fin}
        ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
      </div>
      ";   
    }
    // contenido visible/oculto
    static function tog( string | array $dat = NULL, array $ele = [] ) : string {
      $_ide = self::$IDE."tog";
      $_eje = self::$EJE."tog";
      foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
      
      // contenido textual
      if( is_string($dat) ) $dat = [
        'eti'=>"p", 'class'=>"let-tit", 'htm'=>_doc::let($dat) 
      ];

      // contenedor : icono + ...elementos          
      _ele::eje( $dat,'cli',"$_eje(this);",'ini');

      return "
      <div"._htm::atr( _ele::cla( $ele['val'],"val tog",'ini') ).">
      
        "._doc_val::tog_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

        ".( isset($ele['htm_ini']) ? _htm::val($ele['htm_ini']) : '' )."
        
        "._htm::val( $dat )."

        ".( isset($ele['htm_fin']) ? _htm::val($ele['htm_fin']) : '' )."

      </div>";
    }
    // icono de toggle
    static function tog_ico( array $ele = [] ) : string {

      $_eje = self::$EJE."tog";

      return _doc::ico('ope_tog', _ele::eje($ele,'cli',"$_eje(this);",'ini'));
    }
    // operadores: expandir / contraer
    static function tog_ope( array $ele = [], ...$opc ) : string {
      $_ide = self::$IDE."tog";
      $_eje = self::$EJE."tog";      

      if( !isset($ele['ope']) ) $ele['ope'] = [];
      _ele::cla($ele['ope'],"ope",'ini');

      $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
      return "
      <fieldset"._htm::atr($ele['ope']).">
        "._doc::ico('ope_tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
        "._doc::ico('ope_nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');" ] )."        
      </fieldset>";
    }
    // filtros : texto
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";

      switch( $tip ){
      // textuales
      case 'tex': 
        $dat['eje'] = !empty($dat['eje']) ? $dat['eje'] : NULL;
        // opciones de filtro por texto
        $_ .= _app::var_ope('opc',['ver','tex'],[
          'eti'=>[ 
            'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
            'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
          ],
          'ite'=>[ 'dat'=>"()($)dat()" ]
        ]);
        // ingreso de valor a filtrar
        $_ .= _doc_tex::ope('ora', isset($dat['val']) ? $dat['val'] : '', [ 'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
          'name'=>"val", 'title'=>"Introducir un valor de búsqueda...", 
          'oninput'=>$dat['eje'],
          'class'=>isset($dat['ele_val']['class']) ? $dat['ele_val']['class'] : NULL,
          'style'=>isset($dat['ele_val']['style']) ? $dat['ele_val']['class'] : NULL
        ]);
        if( isset($dat['cue']) ){ $_ .= "
          <c class='sep'>(</c><n name='tot' title='Items totales'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c class='sep'>)</c>";
        }
        break;        
      }
      
      return $_;
    }
  }
  // datos : valores, opciones, variables, abm, cuentas, acumulados, sumatorias, filtros
  class _doc_dat {

    static string $IDE = "_doc_dat-";
    static string $EJE = "_doc_dat.";

    static array $OPE = [
      'acu'=>['nom'=>"Acumulados" ], 
      'ver'=>['nom'=>"Selección"  ], 
      'sum'=>['nom'=>"Sumatorias" ], 
      'cue'=>['nom'=>"Conteos"    ]
    ];
    static array $BOT = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];

    // abm de la base
    static function abm( string $tip, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";
      $_ope = self::$BOT;
      switch( $tip ){
      case 'nav':
        $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
        if( !empty($url) ){
          $url_agr = "{$url}/0";
          $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
        }
        $_ .= "
        <fieldset class='ope' abm='{$tip}'>    
          "._doc::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']->nom, 'onclick'=>"{$_eje}('ver');"])."
  
          "._doc::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']->nom, 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."
  
          "._doc::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');"])."
        </fieldset>";
        break;
      case 'abm':
        $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
        $_ = "
        <fieldset class='ope mar-2 esp-ara'>

          "._doc::ico('dat_val', [ 'eti'=>"button", 'title'=>$_ope[$tip]->nom, 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

          if( in_array('eli',$ope['opc']) ){

            $_ .= _doc::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');" ]);
          }$_ .= "

          "._doc::ico('dat_act', [ 'eti'=>"button", 'title'=>$_ope['fin']->nom, 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

        </fieldset>";
        break;              
      case 'est':
        $_ .= "
        <fieldset class='ope'>    
          "._doc::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
          
          "._doc::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
        </fieldset>";                  
        break;                
      }

      return $_;
    }
    // armo valores ( esq.est ): nombre, descripcion, imagen
    static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );

      $dat_var = _dat::var($esq,$est,$dat);
      $dat_val = _dat::val($esq,$est);

      // armo titulo : nombre <br> detalle
      if( $tip == 'ver' ){

        $_ = ( isset($dat_val->nom) ? _obj::val($dat_var,$dat_val->nom) : "" ).( isset($dat_val->des) ? "\n"._obj::val($dat_var,$dat_val->des) : "");
      }
      // por atributos con texto : nom + des + ima 
      elseif( isset($dat_val->$tip) ){

        if( is_string($dat_val->$tip) ) $_ = _obj::val($dat_var,$dat_val->$tip);
      }
      // armo imagen
      if( $tip == 'ima' ){

        // cargo titulo
        $tit = _doc_dat::val('ver',"$esq.$est",$dat);
        
        $_ = _doc::ima( [ 'style'=>$_, 'title'=>$tit ], $ele );
      }
      // armo variable
      elseif( $tip == 'var' ){
        
        $_ = "";

      }
      // armo textos
      elseif( !!$ele ){  

        if( empty($ele['eti']) ) $ele['eti'] = 'p';
        $ele['htm'] = _doc::let($_);
        $_ = _htm::val($ele);
      }    

      return $_;
    }
    // valor por seleccion ( esq.est.atr ) : texto, variable, icono, ficha, colores, html
    static function var( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // parametros: "esq.est.atr" 
      $ide = 'NaN';
      if( !is_object($dat) ){

        $ide = $dat;
        $dat = _dat::var($esq,$est,$dat);
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
          $_ = _htm::val($_val);
        }// proceso texto con letras
        elseif( $tip == 'htm' ){

          $_ = _doc::let($dat->$atr);
        }// color en atributo
        elseif( $tip == 'col' ){
          
          if( $col = _dat::val_ver('col',$esq,$est,$atr) ){
            $_ = "<div"._htm::atr(_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
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
          if( !empty($_ima) || !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ){
            
            $_ = _doc::ima( $_ima['esq'], $_ima['est'], $dat->$atr, $ele );
          }
          else{
            $_ = "<div class='err fon-roj' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }
        elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

          if( $tip=='tip' ){
            $tip = $_atr->var_dat;
          }
          if( $tip == 'num' ){
            $_ = "<n"._htm::atr($ele).">{$dat->$atr}</n>";
          }
          elseif( $tip == 'tex' ){
            $_ = "<p"._htm::atr($ele).">"._doc::let($dat->$atr)."</p>";
          }
          elseif( $tip == 'fec' ){
            $ele['value'] = $dat->$atr;
            $_ = "<time"._htm::atr($ele).">"._doc::let($dat->$atr)."</time>";
          }
          else{
            $_ = _doc::let($dat->$atr);
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
    // selector por operador : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
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
      if( empty($ope['ope']['title']) ) $ope['ope']['title'] = "Seleccionar el Atributo de la Estructura...";
      // valor seleccionado
      if( isset($ope['val']) ) $_val = explode('-',$ope['val']);
      // cargo selector de estructura
      $ele_eje = isset($ope['ope']['onchange']) ? $ope['ope']['onchange'] : FALSE;
      $ele_val = [ 
        'eti'=>[ 'name'=>"val", 'title'=>"Seleccionar el valor buscado...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] 
      ];
      if( $opc_esq || $opc_est ){
        // operador por esquemas
        if( $opc_esq ){
          $dat_esq = [];
          $ele_esq = [ 
            'eti'=>[ 'name'=>"esq", 'title'=>"Seleccionar el Esquema de Datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] 
          ];
        }// operador por estructuras
        $ele_est = [ 
          'eti'=>[ 'name'=>"est", 'title'=>"Seleccionar la Estructura de datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] 
        ];
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
        if( ( $dat_opc_ide = _dat::est_ope($esq,$est,$ide) ) && is_array($dat_opc_ide) ){  
          // recorro atributos + si tiene el operador, agrego la opcion      
          foreach( $dat_opc_ide as $atr ){
            // cargo atributo
            $_atr = _dat::atr($esq,$est,$atr);
            // identificador
            $dat = "{$esq}.";
            if( !empty($_atr->var['dat']) ){ $dat = $_atr->var['dat']; }else{ $dat .= _dat::atr_est($esq,$est,$atr); }  
            $_ []= [                 
              'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat, 'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 'htm'=>$_atr->nom 
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
          if( $dat_opc_est = _dat::est_ope($esq_ide,$est_ide,'est') ){
            // recorro dependencias de la estructura
            foreach( $dat_opc_est as $dep_ide ){
              // datos de la estructura relacional
              $_est = _dat::est($esq_ide,$dep_ide);
              $ite_val = "{$esq_ide}.{$dep_ide}";
              // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
              if( 
                !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) 
              ){
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
        $_ .= _doc_opc::val($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
      }
      // selector de estructura [opcional]
      if( $opc_esq || $opc_est ){
        $_ .= _doc_opc::val($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
      }
      // selector de atributo con nombre de variable por operador
      $_ .= _doc_opc::val($dat_ope,$ele_ope,'nad');
      
      // selector de valor por relacion
      if( $opc_val ){
        // copio eventos
        if( $ele_eje ) $ele_val['eti'] = _ele::eje($ele_val['eti'],'cam',$ele_eje);
        $_ .= "
        <div class='val'>
          <c class='sep'>:</c>
          "._doc_opc::val( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
          <span class='ico'></span>
        </div>";
      }

      return $_;
    }
    // conteos : por valores de estructura relacionada por atributo
    static function cue( string $tip, string | array $dat, array $ope = [], ...$opc ) : string | array {
      $_ = "";
      $_ide = self::$IDE."cue";
      $_eje = self::$EJE."cue";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      switch( $tip ){        
      case 'dat': 
        $_ = [];
        
        // -> por esquemas
        foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){

          // -> por estructuras
          foreach( $est_lis as $est_ide ){

            // -> por dependencias ( est_atr )
            foreach( ( !empty($dat_opc_est = _dat::est_ope($esq,$est_ide,'est')) ? $dat_opc_est : [ $est_ide ] ) as $est ){

              // armo listado para aquellos que permiten filtros
              if( $dat_opc_ver = _dat::est_ope($esq,$est,'ver') ){
                // nombre de la estructura
                $est_nom = _dat::est($esq,$est)->nom;                
                $htm_lis = [];
                foreach( $dat_opc_ver as $atr ){
                  // armo relacion por atributo
                  $rel = _dat::atr_est($esq,$est,$atr);
                  // busco nombre de estructura relacional
                  $rel_nom = _dat::est($esq,$rel)->nom;
                  // armo listado : form + table por estructura
                  $htm_lis []= [ 
                    'ite'=>$rel_nom, 'htm'=>"
                    <div class='var mar_izq-2 dis-ocu'>
                      "._doc_dat::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
                    </div>"
                  ];
                }
                $_[] = [ 'ite'=> $est_nom, 'lis'=> $htm_lis ];
              }
            }
          }
        }
        break;
      case 'est':
        if( isset($ope['ide']) ) $_ide = $ope['ide'];
        // armo relacion por atributo
        $ide = !empty($atr) ? _dat::atr_est($esq,$est,$atr) : $est;
        $_ = "
        <!-- filtros -->
        <form class='val'>

          "._doc_val::var('val','ver',[ 
            'nom'=>"Filtrar", 
            'id'=>"{$_ide}-ver {$esq}-{$ide}",
            'htm'=>_doc_val::ver('tex', [ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
          ])."
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::var($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ? _doc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
            }
            else{
              $ima = _doc::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
            }$_ .= "
            <tr data-ide='{$ide}'>
              <td data-atr='ima'>{$ima}</td>
              <td data-atr='ide'>"._doc::let($ide)."</td>
              <td data-atr='nom'>"._doc::let(isset($_var->nom) ? $_var->nom : '')."</td>
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
    // filtros : dato + atributo + posicion + texto
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";

      $_ite = function( $ide, $dat=[], $ele=[] ){

        if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

        // impido tipos ( para fechas )
        if( ( $ide == 'inc' || $ide == 'cue' ) && isset($ele['ope']['_tip']) ) unset($ele['ope']['_tip']);
        
        // combino elementos
        if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = _ele::jun($ele['ope'],$dat[$ide]);

        return $ele;
      };
      switch( $tip ){
      // datos : estructura => valores 
      case 'dat':
        // selector de estructura.relaciones para filtros
        array_push($opc,'est','val');
        $_ .= _doc_val::var('doc',"val.ver.dat",[ 
          'ite'=>[ 'class'=>"tam-mov" ], 
          'htm'=>_doc_dat::opc('ver',$dat,$ele,...$opc)
        ]);
        break;
      // atributo : nombre : valor por tipo ( bool, numero, texto, fecha, lista, archivo, figura )
      case 'atr':
        break;
      // listados : desde + hasta
      case 'lis': 
        // por defecto
        if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

        // desde - hasta
        foreach( ['ini','fin'] as $ide ){

          if( isset($dat[$ide]) ) $_ .= _doc_val::var('doc', "val.ver.$ide", $_ite($ide,$dat,$ele));
        }
        $_ .= _doc_dat::ver('lim',$dat,$ele);
        break;
      // limites : incremento + cuantos ? del inicio | del final
      case 'lim':
        // cada
        if( isset($dat['inc']) ){
          $_ .= _doc_val::var('doc', "val.ver.inc", $_ite('inc',$dat,$ele));
        }
        // cuántos
        if( isset($dat['cue']) ){
          $_eje = "_doc.var('mar', this, 'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
          $ele['htm_fin'] = "
          <fieldset class='ope'>
            "._doc::ico('nav_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>"; 
          $_ .=
          _doc_val::var('doc', "val.ver.cue", $_ite('cue',$dat,$ele) );
        }
        break;
      }

      return $_;
    }
    // acumulado : posicion + marcas + seleccion
    static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."acu";
      $_eje = self::$EJE."acu";

      if( empty($opc) ) $opc = array_keys($dat);

      $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

      if( !empty($ope['ide']) ) $_ide = $ope['ide'];

      foreach( $opc as $ide ){        
        $_ .= _doc_val::var('doc',"val.acu.$ide", [
          'ope'=> [ 
            'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
          ],
          'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
        ]);
      }
      if( !empty($ope['htm_fin']) ){
        $_ .= $ope['htm_fin'];
      }
      return $_;
    }
    // sumatorias
    static function sum(  string | array $dat, array $ope = [] ) : string {

      $_ = "";
      $_ide = self::$IDE."sum";
      $_eje = self::$EJE."sum";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }
      if( isset($ope['ide']) ) $_ide = $ope['ide'];

      // operadores por esquemas
      $_val = []; // -> obtener valor de la ficha
      foreach( _app::var($esq,'val','sum') as $ide => $ite ){
    
        $_ .= _doc_val::var($esq, ['val','sum',$ide], [          
          'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
          'htm_fin'=> !empty($ite['var_fic']) ? _doc_dat::fic($ite['var_fic'],$_val) : ''
        ]);
      }    

      return $_;
    }    
    // ficha : valor[ima] => { ...imagenes por atributos } 
    static function fic( string $ide, mixed $val, ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."fic";
      $_eje = self::$EJE."fic";      
      // proceso estructura
      extract( _dat::ide($ide) );

      $_fic = _dat::est_ope($esq,$est,'fic');

      if( isset($_fic->ide) ){
        
        if( !empty($val) ) $val = _dat::var($esq,$est,$val);
      
        $ima = _dat::val_ver('ima',$esq,$est,$atr = $_fic->ide);
        
        $_ .= "
        <div class='val' data-ima='{$ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";

          if( !empty($val) ) $_ .= _doc::ima($esq,$est,$val); $_ .= "

        </div>

        <c class='sep'>=></c>

        "._doc_dat::atr_ima($esq,$est,isset($_fic->atr) ? $_fic->atr : [],$val);
      }     

      return $_;
    }
    // devuelvo listado por estructura
    static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {

      $_ = $dat;

      if( is_array($dat) ){

        $ele['lis']['est'] = $ide;

        _ele::cla($ele['lis'], "anc_max-fit mar-aut mar_ver-3");        
        
        $_ = _doc_lis::$tip($dat, $ele);
      }

      return $_;
    }
    // devuelvo Tabla por Estructura
    static function est( string | array $dat, array $ope, array $ele = [], ...$opc ) : string {

      if( empty($opc) ) $opc = ['htm','cab_ocu'];

      if( !isset($ele['tab']) ){ $ele['tab'] = []; }

      if( !isset($ele['tab']['class']) ) _ele::cla($ele['tab'],"anc-100 mar-aut mar_aba-2");

      return _doc_est::lis($dat, $ope, $ele, ...$opc);
    }
    // listado de : ...atributo: valor
    static function atr( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      $_ = "";

      $_ide = $_ide;
      if( is_string($dat) ){
        $var_dat = _dat::ide($dat);        
        $esq = $var_dat['esq'];
        $est = $var_dat['est'];
        $_ide = "{$_ide} _$esq.$est";
        // datos
        $dat = _dat::var($dat,$ope);
      }

      // estructura
      $ide = _dat::ide($ope['est']);
      unset($ope['est']);
      
      // atributos
      $dat_atr = _dat::atr($ide['esq'],$ide['est']);
      if( isset($ope['atr']) ){
        $lis_atr = $ope['atr'];
        unset($ope['atr']);
      }
      else{
        $lis_atr = array_keys($dat_atr);
      }

      // dato: objeto
      if( !is_object($dat) ){
        $dat = _dat::var($ide['esq'],$ide['est'],$dat);
      }
      $ite = [];
      foreach( $lis_atr as $atr ){
        if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ $ite []= "
          <b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> "._doc::let($dat->$atr);
        }
      }

      $_ = _doc_lis::ite($ite,$ope);          

      return $_;
    }// propiedades con <dl> : imagen + nombre > ...atributos
    static function atr_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
      
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
          _doc::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
          <dl>
            <dt>
              ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " "._obj::val($_dat,$des) : "" )."
            </dt>";
            foreach( $atr as $ide ){ 
              if( isset($_dat->$ide) ){ $htm .= "
                <dd>".( preg_match("/_des/",$ide) ? "<q>"._doc::let($_dat->$ide)."</q>" : _doc::let($_dat->$ide) )."</dd>";
              }
            }$htm .= "
          </dl>";
          $_ []= $htm;
        }
      }

      return _doc_lis::$tip( $_, $ele, ...$opc );
    }// imagenes : { ...[atr:ima] ; ...[atr:ima] }
    static function atr_ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {

      if( isset($val) ){
        $_dat = _dat::var($esq,$est,$val);
      }
      // busco atributos en : _dat.est.ope
      if( empty($atr) ){
        $atr = _dat::est_ope($esq,$est,'atr_ima');
      }
      $_lis = [];
      $_ = "
      <ul class='val'>  
        <li><c class='sep'>{</c></li>";        
        foreach( $atr as $atr ){
          // $_ima = _dat::val_ver('ima',$esq,$est,$atr);
          if( isset($_dat->$atr) ) $_lis []= _doc::ima($esq,"{$est}_{$atr}",$_dat->$atr,[ 
            'eti'=>"li", 'class'=>"tam-4 mar_hor-1", 'data-esq'=>$esq, 'data-est'=>$est, 'data-atr'=>$atr
            //, 'data-ima'=>$_ima['ide']
          ]);
        } $_ .= 
        implode("<c class='sep'>;</c>",$_lis)."
        <li><c class='sep'>}</c></li>
      </ul>";
      return $_;
    }
  }
  // tablero : opciones + posiciones + secciones
  class _doc_tab {

    static string $IDE = "_doc_tab-";
    static string $EJE = "_doc_tab.";

    static array $OPE = [
      'val' => ['nom'=>"Valores"  ],
      'opc' => ['nom'=>"Opciones" ],
      'pos' => ['nom'=>"Posición" ],
      'ver' => ['nom'=>"Selección"]
    ];

    // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
    static function ope( string $tip, string $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;

      // proceso datos del tablero
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";

      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];

      switch( $tip ){
      // todos
      case 'tod':
        $ele['sec']['class'] = "mar_izq-2";
        foreach( $_ope as $ide => $_dat ){
          $_lis []= [
            'ite'=>[ 'eti'=>"h3", 'class'=>"mar-0", 'htm'=>$_dat['nom'] ],
            'htm'=>_doc_tab::ope($ide,$dat,$ope,$ele,...$opc)
          ];
        }
        $_ = "
        <h2>Operadores</h2>

        "._doc_lis::val($_lis,[ 'opc'=>['tog'], 'ope'=>[ 'class'=>"mar_hor-1" ] ]);

        break;
      // valores : totales + acumulados + sumatorias
      case 'val':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='acu'>
            <fieldset class='inf ren'>
              <legend>Acumulados</legend>";

              $_ .= _doc_val::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ]);
              
              $_ .= _doc_dat::acu($ope[$tip],[ 
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
              ]);
              $_ .="
            </fieldset>
          </form>

          <form ide='sum'>

            <fieldset class='inf ren' data-esq='hol' data-est='kin'>
              <legend>Sumatorias del Kin</legend>

              "._doc_dat::sum('hol',[ 'ide'=>$_ide ])."

            </fieldset>
          </form>

        </section>";
        break;
      // Filtros : estructuras/db + posiciones + fechas
      case 'ver':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='dat'>
            <fieldset class='inf ren'>
              <legend>por Datos</legend>

              "._doc_dat::ver('dat', $ope['est'], [ 'ope'=>[ 'onchange'=>"$_eje('dat',this);" ] ], 'ope_tam')."

            </fieldset>
          </form>

          <form ide='pos'>
            <fieldset class='inf ren'>
              <legend>por Posiciones</legend>

              "._doc_dat::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [
                'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ] 
              ])."
            </fieldset>
          </form>

          <form ide='fec'>
            <fieldset class='inf ren'>
              <legend>por Fechas</legend>

              "._doc_dat::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [ 
                'ope'=>[ '_tip'=>"fec_dia", 'id'=>"{$_ide}-fec", 'onchange'=>"$_eje('fec',this);" ] 
              ])."
            </fieldset>
            
          </form>

        </section>";          
        break;
      // Opciones : sección + posición
      case 'opc':
        if( !empty($ope['sec']) || !empty($ope['pos']) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">";
            // secciones
            $tip = 'sec';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">
                <fieldset class='inf ren'>
                  <legend>Secciones</legend>";
                  // operadores globales
                  if( !empty($tab_sec = _app::var('doc','tab',$tip)) ){ $_ .= "
                    <div class='val'>";
                    foreach( _app::var('doc','tab',$tip) as $ide => $ite ){
                      if( isset($ope[$tip][$ide]) ){ 
                        $_ .= _doc_val::var('doc', "tab.$tip.$ide", [ 
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ] 
                        ]); 
                      }
                    }$_ .= "
                    </div>";
                  }
                  // operadores por aplicación
                  $_ .= _doc_tab::opc($tip,$dat,$ope[$tip])."

                </fieldset>
              </form>";          
            }
            // posiciones
            $tip = 'pos';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){ 
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">    
                <fieldset class='inf ren'>
                  <legend>Posiciones</legend>";
                  // bordes            
                  $ide = 'bor';
                  $_ .= _doc_val::var('doc', "tab.$tip.$ide",[
                    'val'=>isset($ope[$tip][$ide]) ? $ope[$tip][$ide] : 0,
                    'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
                  ]);                
                  // color de fondo - numero - texto - fecha
                  foreach( ['col','num','tex','fec'] as $ide ){                  
                    if( isset($ope[$tip][$ide]) ){
                      $_ .= _doc_val::var('doc', "tab.{$tip}.{$ide}", [
                        'id'=>"{$ele_ide}-{$ide}",
                        'htm'=>_doc_dat::opc($ide, $ope['est'], [
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                        ])
                      ]);                      
                    }
                  }
                  // imagen de fondo - ( ficha )
                  if( isset($ope[$tip][$ide = 'ima']) ){ $_ .= "
                    <div class='ren'>";
                      if( isset($ope[$tip][$ide]) ){
                        $_ .= _doc_val::var('doc',"tab.{$tip}.{$ide}",[
                          'id'=>"{$ele_ide}-{$ide}",
                          'htm'=>_doc_dat::opc($ide, $ope['est'], [ 
                            'val'=>$ope[$tip][$ide], 
                            'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                          ])
                        ]);
                      }
                      if( isset($ope['val']) ){ $_ .= "
                        <div class='val' ide='acu'>";
                          foreach( array_keys($ope['val']) as $ite ){        
                            $_ .= _doc_val::var('doc', "tab.$tip.{$ide}_{$ite}", [
                              'val'=>isset($ope[$tip]["{$ide}_{$ite}"]) ? $ope[$tip]["{$ide}_{$ite}"] : FALSE,
                              'ope'=>[ 'id'=>"{$ele_ide}-{$ide}_{$ite}", 'onchange'=>$ele_eve ]
                            ]);
                          }$_.="
                        </div>";
                        }
                      $_ .= "
                    </div>";
                  }    
                  // operadores por aplicaciones                  
                  $_ .= _doc_tab::opc($tip,$dat,$ope[$tip])."
                </fieldset>    
              </form>";          
            }$_ .= "
          </section>";
        }
        break;
      // Posicion principal : atributos por aplicación
      case 'pos':
        if( !empty($ope[$tip]) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">

            "._doc_tab::pos($dat,$ope,$ele,...$opc)."

          </section>";
        }
        break;

      }  

      return $_;
    }
    // por opciones : seccion + posicion
    static function opc( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc";
      // proceso estructura de la base
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";        
      $_eje = "_{$esq}_app_tab.opc";

      // solo muestro las declaradas en el operador
      $atr = array_keys($ope);

      foreach( _app::var($esq,'tab',$tip) as $ide => $_dat ){

        if( in_array($ide,$atr) ){

          $_ .= _doc_val::var($esq, "tab.$tip.$ide", [
            'ope'=>[
              'id'=>"{$_ide}-{$ide}", 
              'val'=>!empty($ope[$ide]) ? !empty($ope[$ide]) : NULL, 
              'onchange'=>"$_eje('$tip',this);"
            ]
          ]);
        }
      }

      return $_;
    }
    // por posicion : valor principal
    static function pos( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."pos";      
      // proceso estructura
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";
      $_eje = "_{$esq}_app_tab.pos";

      foreach( $opc as $atr ){
        $htm = "";
        foreach( _app::var($esq,'pos',$atr) as $ide => $val ){
          $var = [
            'ope'=>[ 
              'id'=>"{$_ide}-{$atr}-{$ide}", 
              'dat'=>$atr, 
              'onchange'=>"$_eje('$atr',this)" ]
          ];
          if( isset($ope[$atr][$ide]) ){
            $var['var']['val'] = $ope[$atr][$ide];
          }
          $htm .= _doc_val::var($esq, "pos.$atr.$ide", $var);
        }        
        // busco datos del operador 
        if( !empty($htm) && !empty($_ope = _app::var($esq,'val','pos',$atr)) ){
          $ele['ope']['pos'] = $atr; $_ .= "
          <form"._htm::atr($ele['ope']).">
            <fieldset class='inf ren'>
              <legend>{$_ope['nom']}</legend>
                {$htm}
            </fieldset>
          </form>";          
        }
      }

      return $_;
    }
    
  }
  // Tabla : atributos + valores + filtros + columnas + titulo + detalle
  class _doc_est {

    static string $IDE = "_doc_est-";
    static string $EJE = "_doc_est.";    

    static array $OPE = [
      'val' => [ 'nom'=>"Valores"       , 'des'=>"", 'htm'=>"" ], 
      'ver' => [ 'nom'=>"Filtros"       , 'des'=>"", 'htm'=>"" ], 
      'col' => [ 'nom'=>"Columnas"      , 'des'=>"", 'htm'=>"" ], 
      'des' => [ 'nom'=>"Descripciones" , 'des'=>"", 'htm'=>"" ],      
      'cue' => [ 'nom'=>"Cuentas"       , 'des'=>"", 'htm'=>"" ]
    ];
    static array $VAL = [
      'atr'=>[],// columnas del listado
      'atr_tot'=>0,// columnas totales
      'atr_ocu'=>[],// columnas ocultas
      'atr_dat'=>[],// datos de las columnas
      'est'=>[],// estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est_dat'=>[],// datos y operadores por estructura
      'dat'=>[],// filas: valores por estructura [...{...$}]
      'val'=>[],// dato por columna: valor por objeto {...$}
      'tit'=>[],// titulos: por base {'cic','gru','des'} o por operador [$pos]
      'tit_cic'=>[],// titulos por ciclos
      'tit_gru'=>[],// titulos por agrupaciones
      'det'=>[],// detalles: por base {'cic','gru','des'} o por operador [$pos]
      'det_cic'=>[],// detalle por ciclos
      'det_gru'=>[],// detalle por agrupaciones
      'det_des'=>[]// detalle por descripciones
    ];
    static array $OPC = [ 
      'cab_ocu',  // ocultar titulo de columnas
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ];
    static array $ATR = [
      'opc'=>[ "Opción", 0 ], 
      'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
      'tex'=>[ "Texto",  0 ], 
      'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
      'obj'=>[ "Objeto",  0 ] 
    ];

    // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
    static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{        
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];      
      // todos
      switch( $tip ){      
      case 'tod':
        $ope_ver = '';
        $ope['var']['var'] = "est";
        // cargo operadores
        $_ope = _obj::nom($_ope,'ver',$ope_lis = ['ver','col','des','cue']);
        foreach( $ope_lis as $ide ){
          $_ope[$ide]['htm'] = _doc_est::ope($ide,$dat,$ope,$ele);
        }
        $_ = "
  
        "._doc_est::ope('val',$dat,$ope,$ele)."
  
        "._doc_val::nav('pes', $_ope, [ 
          'nav'=>['class' => "mar_arr-2" ]
        ],'tog')."        

        <div"._htm::atr($ope['var']).">

          "._doc_est::lis($dat,$ope,$ele,...$opc)."

        </div>
        ";          
        break;
      // atributos y tipos de dato con filtros
      case 'atr':
        foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
        // tipos de dato
        $_cue = self::$ATR;
        // cuento atributos por tipo
        foreach( $_est->atr as $atr ){
          $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
          if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
        }
        // operador : toggles + filtros
        $_ .= "
        <form class='val jus-ini' dat='atr'>
  
          <fieldset class='ope'>
            "._doc::ico('ope_ocu',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
            "._doc::ico('ope_ver',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
          </fieldset>
  
          "._doc_val::var('val','ver',[ 'nom'=>"Filtrar", 'htm'=>_doc_val::ver('tex',[ 'eje'=>"{$_eje}_ver(this);" ]) ])."
  
          <fieldset class='ite'>";
          foreach( $_cue as $atr => $val ){ $_ .= "
            <div class='val'>
              "._doc::ico($atr,[
                'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
              ])."
              <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
            </div>";
          }$_ .= "
          </fieldset>
  
        </form>";
        // listado
        $pos = 0; $_ .= "
        <table"._htm::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
        foreach( $_est->atr as $atr ){
          $pos++;
          $_atr = _dat::atr($esq,$est,$atr);
          $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

          $var_tip = explode('_',$_atr->ope['_tip'])[0];
          if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
          if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
          if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
          if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
          $htm = "
          <form class='ren esp-bet'>
          
            "._doc_dat::ver('lis', isset($_cue[$var_tip][2]) ? $_cue[$var_tip][2] : [], [ 'ope'=>$_var ] )."
  
          </form>";
          $_ .= "
          <tr data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}' pos='{$pos}'>
            <td data-atr='val'>
              "._doc::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
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
      // valores : cantidad + acumulado + operaciones
      case 'val': 
        $_ = "
        <h3 class='dis-ocu'>Valores</h3>";
        // acumulados
        if( isset($ope['val']) ){
          $_ .= "
          <form ide='acu'>

            <fieldset class='inf ren'>
              <legend>Valores</legend>
              
              "._doc_val::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ])."
              
              "._doc_val::var('doc', "val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
              
              "._doc_dat::acu($ope['val'],[
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this); ".self::$EJE."ver('val',this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c> <n>0</n> <c class='sep'>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }// por datos de la base ?
        else{
        }
        break;
      // filtros : datos + posicion + atributos
      case 'ver':
        $dat_tot = count($ope['dat']);
        $_ = "
        <h3 class='dis-ocu'>Filtros</h3>

        <form ide='dat'>
          <fieldset class='inf ren'>
            <legend>por Datos</legend>
            
            "._doc_dat::ver('dat', $ope['est'], [ 'ope'=>[ 'max'=>$dat_tot, 'onchange'=>"$_eje('dat',this);" ] ])."

          </fieldset>
        </form>

        <form ide='pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>
            "._doc_dat::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [                  
              'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ]
            ])."
          </fieldset>
        </form>";
        break;
      // columnas : ver/ocultar
      case 'col':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
              
          foreach( $est_lis as $est ){
            // estrutura por aplicacion
            $_est = _app::est($esq,$est);
            // datos de la estructura
            $est_nom = _dat::est($esq,$est)->nom;
            // contenido : listado de checkbox en formulario
            $htm = "
            <form ide='{$tip}' class='ren jus-ini mar_izq-2'>";
              foreach( $_est->atr as $atr ){
                $htm .= _doc_val::var('val',$atr,[
                  'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                  'val'=>!isset($_est->atr_ocu) || !in_array($atr,$_est->atr_ocu),
                  'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                  ] 
                ]);
              } $htm.="
            </form>";
            
            $lis_val []= [
              'ite'=>$est_nom,
              'htm'=>$htm
            ];
          }              
        }        
        $_ = "        
        <h3 class='dis-ocu'>Columnas</h3>

        "._doc_lis::val($lis_val,[
          'htm_fin'=>[ 'eti'=>"ul", 'class'=>"ope mar_izq-1", 'htm'=>"
            "._doc::ico('ope_ver',['eti'=>"li", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
            "._doc::ico('ope_ocu',['eti'=>"li", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])
          ],
          'opc'=>['tog']
        ]);
        break;
      // descripciones : titulo + detalle
      case 'des':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
            
          foreach( $est_lis as $est ){
            
            $_est =  _app::est($esq,$est,$ope);

            // ciclos, agrupaciones y lecturas
            if( !empty($_est->tit_cic) || !empty($_est->tit_gru) || !empty($_est->det_des) ){              

              $lis_dep = [];
              foreach( ['cic','gru','des'] as $ide ){

                $pre = $ide == 'des' ? 'det' : 'tit';
                
                if( !empty($_est->{"{$pre}_{$ide}"}) ){
                  $htm = "
                  <form ide='{$ide}' data-esq='{$esq}' data-est='{$est}' class='ren jus-ini mar_izq-2'>";
                  foreach( $_est->{"{$pre}_{$ide}"} as $atr ){
                    $htm .= _doc_val::var('val',$atr,[ 
                      'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                      'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                    ]);
                  }$htm .= "
                  </form>";
                  
                  $lis_dep[] = [ 
                    'ite'=>_app::var('doc','est','ver',$ide)['nom'], 
                    'htm'=>$htm
                  ];
                }
              }
              $lis_val[] = [
                'ite'=>_dat::est($esq,$est)->nom,
                'lis'=>$lis_dep
              ];
            }
          }
        }
        $_ = "
        <h3 class='dis-ocu'>Descripciones</h3>

        "._doc_lis::val($lis_val,[ 'opc'=>['tog'] ]);

        break;
      // cuentas : total + porcentaje
      case 'cue':
        $_ = "
        <h3 class='dis-ocu'>Cuentas</h3>

        "._doc_lis::val( _doc_dat::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[ 'class'=>"dis-ocu" ], 'opc'=>['tog', 'ver', 'cue'] ] );

        break;
      }

      return $_;
    }

    // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
    static function lis( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      foreach( [ 'lis','cue','tit_ite','tit_val','dat_ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
      $_ = "";
      $_ide = self::$IDE."lis";
      $_eje = self::$EJE."lis";
      
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];            
      
      // identificadores de la base        
      if( isset($esq) ){
        $ele['lis']['data-esq'] = $esq;
        $ele['lis']['data-est'] = $est;
      }
      $_ = "
      <table"._htm::atr($ele['lis']).">";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';

        // columnas:
        if( $dat_val_lis = is_array($dat) ){
          // datos de atributos
          if( !isset($ope['atr_dat']) ){
            $ope['atr_dat'] = _est::atr($dat);
          }
          // listado de columnas
          if( !isset($ope['atr']) ){
            $ope['atr'] = array_keys($ope['atr_dat']);
          }
        }
        // caclulo total de columnas
        $ope['atr_tot'] = _dat::est_atr($dat,$ope);

        // cabecera
        if( !in_array('cab_ocu',$opc) ){ 
          foreach( [ 'cab','cab_ite','cab_val' ] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
          $_ .= "
          <thead"._htm::atr($ele['cab']).">
            "._doc_est::col($dat,$ope,$ele,...$opc)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody"._htm::atr($ele['cue']).">";          
          // recorro: por listado $dat = []
          if( $dat_val_lis ){

            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              if( !empty($ope['tit'][$pos]) ) $_ .= _doc_est::pos('tit',$pos,$ope,$ele);

              // fila-columnas
              $ope['val'] = $_dat; $_.="
              <tr"._htm::atr($ele['dat_ite']).">
                "._doc_est::ite($dat,$ope,$ele,...$opc)."
              </tr>";

              // detalles
              if( !empty($ope['det'][$pos]) ) $_ .= _doc_est::pos('det',$pos,$ope,$ele);                    
            }
          }
          // estructuras de la base esquema
          else{

            // valido item por objeto-array
            foreach( $ope['dat'] as $_dat ){ $_val_dat_obj = is_object($_dat); break; }
            
            // valido contenido : titulos y detalles por estructura de la base
            $ele_ite = [];
            foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
              $_val[$i] = isset($ope[$i]);
              foreach( $v as $e ){

                if( !is_numeric($e) && ( $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]) ) || ( $_val[$i] && in_array($e,$ope[$i]) ) ){

                  $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
                }
              }            
            }

            // contenido previo : titulos por agrupaciones
            if( isset($ele_ite['tit_gru']) ){

              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  $_ .= _doc_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru'], ...$opc);
                }
              }
            }

            // recorro datos
            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              foreach( $ope['est'] as $esq => $est_lis ){
                // recorro referencias
                foreach( $est_lis as $est){
                  // cargo relaciones                  
                  if( $dat_opc_est = _dat::est_ope($esq,$est,'est') ){

                    foreach( $dat_opc_est as $atr => $ref ){

                      $ele['ite']["{$esq}-{$ref}"] = $_val_dat_obj ? $_dat->$atr : $_dat["{$esq}-{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos                
                  if( $_val['tit'] || $_val['tit_cic'] ){

                    $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                    $_ .= _doc_est::tit('cic', "{$esq}.{$est}", $ope, $ele_ite['tit_cic'], ...$opc);
                  }
                }
              }
              // cargo item por esquema.estructuras
              $ele['ite']['pos'] = $pos; $_ .= "
              <tr"._htm::atr($ele['ite']).">";
              foreach( $ope['est'] as $esq => $est_lis ){
      
                foreach( $est_lis as $est ){
                  
                  $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                  $_ .= _doc_est::ite("{$esq}.{$est}", $ope, $ele, ...$opc);
                } 
              }$_ .= "
              </tr>";
              $opc []= "det_cit";
              // cargo detalles
              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  foreach( ['des','cic','gru'] as $ide ){

                    if( isset($ele_ite["det_{$ide}"]) ){

                      $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                      $_ .= _doc_est::det($ide, "{$esq}.{$est}", $ope, $ele_ite["det_{$ide}"], ...$opc );
                    }
                  }                  
                } 
              }                    
            }
          }$_ .= "              
        </tbody>";
        // pie
        if( !empty($ope['pie']) ){
          foreach( ['pie','pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
          $_.="
          <tfoot"._htm::atr($ele['pie']).">";
            // fila de operaciones
            $_.="
            <tr"._htm::atr($ele['pie_ite']).">";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>";
      return $_;
    }

    // columnas : por atributos
    static function col( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";

      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      
      // por muchos      
      if( isset($ope['est']) ){

        $ope_est = $ope['est'];
        unset($ope['est']);

        foreach( $ope_est as $esq => $est_lis ){

          foreach( $est_lis as $est ){

            $_ .= _doc_est::col("{$esq}.{$est}",$ope,$ele,...$opc);
          }
        }
      }
      // por 1: esquema.estructura
      else{
        $_val['dat'] = isset($esq);

        $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
        // cargo datos
        $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _est::atr($dat) );
        // ocultos por estructura
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : [];
        // genero columnas
        foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est->atr) ? $_est->atr : array_keys($ope['atr_dat']) ) ) as $atr ){
          $e = [];
          if( $_val['dat'] ){
            $e['data-esq'] = $esq;
            $e['data-est'] = $est;
          } 
          $e['data-atr'] = $atr;
          if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
          // poner enlaces
          $htm = _doc::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
          if( !$ope_nav ){
            $htm = "<p>{$htm}</p>";
          }else{
            $htm = "<a href='' target='_blank'>{$htm}</a>";
          }$_ .= "
          <th"._htm::atr($e).">
            {$htm}
          </th>";
        }         
      }   

      return $_;
    }

    // posicion : titulo + detalle
    static function pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {              
      $_ = "";
      if( isset($ope[$tip][$ide]) ){

        foreach( _lis::ite($ope[$tip][$ide]) as $val ){ 
          $_.="
          <tr"._htm::atr($ele["{$tip}_ite"]).">
            <td"._htm::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
              ".( is_array($val) ? _htm::val($val) : "<p class='{$tip} tex_ali-izq'>"._doc::let($val)."</p>" )."
            </td>
          </tr>";
        }        
      }
      return $_;
    }

    // titulo : posicion + ciclos + agrupaciones
    static function tit( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."tit";
      $_eje = self::$EJE."tit";
      // proceso estructura de la base
      if( is_string($dat) ){

        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
        $_ide .= " _$esq.$est";
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 titulo : nombre + detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = is_object($ope[2]) ? $ope[2]->ide : $ope[2];
        $htm = "";
        if( !empty($htm_val = _doc_dat::val('nom',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <p class='tit'>"._doc::let($htm_val)."</p>";
        }
        if( !empty($htm_val = _doc_dat::val('des',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <q class='mar_arr-1'>"._doc::let($htm_val)."</q>";
        }
        $_ .="
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">{$htm}</td>
        </tr>";
      }
      // por ciclos : secuencias
      elseif( $tip == 'cic' ){
        
        // acumulo posicion actual, si cambia -> imprimo ciclo        
        if( isset($_est->cic_val) ){
          
          $val = _dat::var($esq,$est,$ope['val']);            
  
          foreach( $_est->cic_val as $atr => &$pos ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr) ) && $pos != $val->$atr ){
              if( !empty($val->$atr) ){
                $ite_ele = $ele;

                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');

                $_ .= _doc_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ite_ele);
              }
              $pos = $val->$atr;
            }
          }
        }        
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($_est->$tip) ){
          foreach( $_est->$tip as $atr ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr)) ){

              foreach( _dat::var($esq,$ide) as $val ){                
                $ite_ele = $ele;
                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
                $_ .= _doc_est::tit('pos',$dat,[$atr,$ide,$val],$ite_ele);
              }
            }
          }
        }
      }
      return $_;
    }

    // item : datos de la estructura
    static function ite( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      
      $dat = $ope['val'];
      $opc_ima = !in_array('ima',$opc);
      $opc_var = in_array('var',$opc);
      $opc_htm = in_array('htm',$opc);

      // identificadores
      if( $_val['dat'] = isset($esq) ){
        $ele['dat_val']['data-esq'] = $esq;
        $ele['dat_val']['data-est'] = $est;
      }
      // datos de la base
      if( isset($_est) ){

        $_atr    = _dat::atr($esq,$est);
        $est_ima = _dat::est_ope($esq,$est,'ima');  
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : FALSE;
        
        foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est->atr ) as $atr ){
          $ele_dat = $ele['dat_val'];
          $ele_dat['data-atr'] = $atr;         
          //ocultos
          if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
          // contenido
          $ele_val = $ele['val'];
          
          if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
            _ele::cla($ele_val,"tam-3");
            $ide = 'ima';
          }
          // variables
          else{
            $ide = $opc_var ? 'var' : 'tip';
            // adapto estilos por tipo de valor
            if( !empty($_atr[$atr]) ){
              $var_dat = $_atr[$atr]->var_dat;
              $var_val = $_atr[$atr]->var_val;
            }elseif( !empty( $_var = _val::tip( $dat ) ) ){
              $var_dat = $_var->dat;
              $var_val = $_var->val;
            }else{
              $var_dat = "val";
              $var_val = "nul";
            }
            // - limito texto vertical
            if( $var_dat == 'tex' ){
              if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem; overflow-y:scroll");
            }
          }$_ .= "
          <td"._htm::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
            "._doc_dat::var($ide,"{$esq}.{$est}.{$atr}",$dat,$ele_val)."
          </td>";
        }
      }
      // por listado del entorno
      else{
        $_atr = $ope['atr_dat'];
        $_val_dat_obj = is_object($dat);
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_dat_obj ? $dat->{$ide} : $dat[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// variable por tipo
          elseif( $opc_var ){
            $htm = "";
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _htm::val($dat_val);
          }// textos
          else{
            $htm = _doc::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td"._htm::atr($ele['dat_val']).">
            {$htm}
          </td>";
        }
      }      

      return $_;
    }

    // detalles : posicion + descripciones + lecturas
    static function det( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."det";
      $_eje = self::$EJE."det";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = isset($ope[1]->ide) ? $ope[1]->ide : ( isset($ope[1]->pos) ? $ope[1]->pos : '' ); $_ .= "
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">
            ".( in_array('det_cit',$opc) ? "<q>"._doc::let($ope[1]->{$ope[0]})."</q>" : _doc::let($ope[1]->{$ope[0]}) )."
          </td>
        </tr>";
      }
      // por tipos : descripciones + ciclos + agrupaciones
      elseif( isset($_est->{"det_$tip"}) ){
        $val = _dat::var($esq,$est,$ope['val']);
        foreach( $_est->{"det_$tip"} as $atr ){
          $ite_ele = $ele;
          if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
          $_ .= _doc_est::det('pos',$dat,[$atr,$val],$ite_ele,...$opc);
        }
      }

      return $_;
    }    
  }
  // listados : ite + val + nav + bar + est + tab
  class _doc_lis {

    static string $IDE = "_doc_lis-";
    static string $EJE = "_doc_lis.";

    // operadores : tog + filtro
    static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      
      $_ = "";

      $tod = empty($opc);
      
      if( $tod || in_array('tog',$opc) ){        
        
        $_ .= _doc_val::tog_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
      }
      if( $tod || in_array('ver',$opc) ){
        $_ .= _doc_val::var('val','ver',[ 
          'des'=>"Filtrar...",
          'htm'=>_doc_val::ver('tex',[ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
        ]);
      }

      if( !empty($_) ){ $_ = "
        <form"._htm::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
          {$_}
        </form>";        
      }      
      return $_;
    }

    // posicion : dl, ul, ol
    static function ite( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      $_ = "";

      // operador
      if( isset($ope['opc']) ){
        $_ .= _doc_lis::ope('ite', $ope['opc'] = _lis::ite($ope['opc']), $ope);
      }

      // por punteo o numerado
      if( _obj::pos($dat) ){
        $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
        $_ .= "
        <{$eti}"._htm::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= _doc_lis::ite_pos( 1, $pos, $val, $ope, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        // agrego toggle del item
        _ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
        $_ .= "
        <dl"._htm::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 

            $ope_ite = $ope['ite'];

            if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt"._htm::atr($ope_ite).">
              "._doc::let($nom)."
            </dt>";
            foreach( _lis::ite($val) as $ite ){ $_ .= "
              <dd"._htm::atr($ope['val']).">
                "._doc::let($ite)."
              </dd>";
            }
          }$_.="
        </dl>";
      }

      return $_;
    }
    static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
      $_ = "
      <li"._htm::atr($ope['ite']).">";
        if( is_string($val) ){ 
          $_ .= $val;
        }// sublistas
        else{
          $niv++;
          $_.="
          <$eti data-niv='$niv'>";
          if( isset($ope['opc']) ){
            $opc = [];
            if( in_array('tog_dep',$ope['opc']) ) $opc []= "tog";
            $_ .= "<li>"._doc_lis::ope('ite',$opc,$ope)."</li>";
          }
          foreach( $val as $ide => $val ){
            $_ .= _doc_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
          }$_.="
          </$eti>";
        }
        $_ .= "
      </li>";
      return $_;
    }

    // valores : ul > ...li > .val(.ico + tex-tit) + lis/htm
    static function val( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";
      $_ = "";

      // elementos        
      _ele::cla($ope['lis'],"lis",'ini');
      _ele::cla($ope['ite'],"ite",'ini');
      _ele::cla($ope['dep'],"lis",'ini');
      _ele::cla($ope['ope'],"ite",'ini');
      
      // operadores
      if( isset($ope['opc']) ){
        $_ .= _doc_lis::ope('val', _lis::ite($ope['opc']), $ope);
      }

      // listado
      $_ .= "
      <ul"._htm::atr($ope['lis']).">";
      foreach( $dat as $ide => $val ){

        $_ .= _doc_lis::val_pos( 1, $ide, $val, $ope );
      }$_ .= "
      </ul>";      

      return $_;
    }    
    static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
    
      $ope_ite = $ope['ite'];      
      // con dependencia : evalúo rotacion de icono
      if( ( $val_lis = is_array($val) ) ){         
        $ope_ico = $ope['ico'];
        $ele_dep = isset($ope["lis-$niv"]) ? _ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
        if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) _ele::cla($ope_ico,"ocu");
      }// sin dependencias : separo item por icono vacío
      else{
        _ele::cla($ope_ite,"sep");
      }
      $_ = "
      <li"._htm::atr( isset($ope["ite-$niv"]) ? _ele::jun($ope_ite  ,$ope["ite-$niv"]) : $ope_ite  ).">

        ".( $val_lis ? _doc_val::tog( isset($val['ite']) ? $val['ite'] : $ide, isset($val['ite_ope']) ? $val['ite_ope'] : ['ico'=>$ope_ico] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
            $ope['dep']['data-niv'] = $niv;
            $_ .= "
            <ul"._htm::atr($ele_dep).">";

            if( is_array($val['lis'])  ){
              // operador por dependencias : 1° item de la lista
              if( isset($ope['opc'])){
                $opc = [];
                foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
                if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
                if( !empty($opc) ) $_ .= "
                <li>"._doc_lis::ope('val',$opc,$ope)."</li>";
              }
              foreach( $val['lis'] as $i => $v ){

                $_ .= _doc_lis::val_pos( $niv+1, $i, $v, $ope );
              }
            }// listado textual
            elseif( is_string($val['lis']) ){

              $_ .= $val['lis'];
            }$_ .= "
            </ul>";
          }// contenido html directo ( asegurar elemento único )
          elseif( isset($val['htm']) ){

            $_ .= is_string($val['htm']) ? $val['htm'] : _ele::val($val['htm']);
          }
        }$_ .= "          
      </li>";        
      return $_;
    }

    // indices : a[href] > ...a[href]
    static function nav( array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav_";// val | ver
      $_ = "";

      // operador
      _ele::cla( $ele['ope'], "ite", 'ini' );
      $_ .= "
      <form"._htm::atr($ele['ope']).">

        "._doc_val::tog_ope()."

        "._doc_val::ver('tex',[ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."

      </form>";
      // dependencias
      $tog_dep = FALSE;
      if( in_array('tog_dep',$opc) ){
        _ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
        <form"._htm::atr($ele['ope_dep']).">
  
          "._doc_val::tog_ope()."
  
        </form>";
      }
      // armo listado de enlaces
      $_lis = [];
      $opc_ide = in_array('ide',$opc);
      _ele::cla( $ele['lis'], "nav", 'ini' );
      foreach( $dat[1] as $nv1 => $_nv1 ){
        $ide = $opc_ide ? $_nv1->ide : $nv1;
        $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv1->nom}") ];
        if( !isset($dat[2][$nv1]) ){
          $_lis []= _htm::val($eti_1);
        }
        else{
          $_lis_2 = [];
          foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
            $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
            $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv2->nom}") ];
            if( !isset($dat[3][$nv1][$nv2])  ){
              $_lis_2 []= _htm::val($eti_2);
            }
            else{
              $_lis_3 = [];              
              foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
                $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
                $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv3->nom}") ];
                if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                  $_lis_3 []= _htm::val($eti_3);
                }
                else{
                  $_lis_4 = [];                  
                  foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                    $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                    $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv4->nom}") ];
                    if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                      $_lis_4 []= _htm::val($eti_4);
                    }
                    else{
                      $_lis_5 = [];                      
                      foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                        $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                        $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv5->nom}") ];
                        if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                          $_lis_5 []= _htm::val($eti_5);
                        }
                        else{
                          $_lis_6 = [];
                          foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                            $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                            $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv6->nom}") ];
                            if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                              $_lis_6 []= _htm::val($eti_6);
                            }
                            else{
                              $_lis_7 = [];
                              // ... continuar ciclo
                              $_lis_6 []= [ 'ite'=>$eti_6, 'lis'=>$_lis_7 ];                              
                            }
                          }
                          $_lis_5 []= [ 'ite'=>$eti_5, 'lis'=>$_lis_6 ];
                        }
                      }
                      $_lis_4 []= [ 'ite'=>$eti_4, 'lis'=>$_lis_5 ];
                    }
                  }
                  $_lis_3 []= [ 'ite'=>$eti_3, 'lis'=>$_lis_4 ];
                }
              }
              $_lis_2 []= [ 'ite'=>$eti_2, 'lis'=>$_lis_3 ];  
            }
          }
          $_lis []= [ 'ite'=>$eti_1, 'lis'=>$_lis_2 ];
        }
      }
      // pido listado
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [];
      $_ .= _doc_lis::val($_lis,$ele);
      return $_;
    }

    // horizontal con barra de desplazamiento por item
    static function bar( array $dat, array $ope = [] ) : string {
      $_ide = self::$IDE."bar";
      $_eje = self::$EJE."bar";      
      $_ = "";

      $pos = 0;
      $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
      if( !isset($ope['lis']) ) $ope['lis']=[];
      $_.="
      <ul"._htm::atr(_ele::cla($ope['lis'],"bar",'ini')).">";
        foreach( $dat as $ite ){ 
          $pos++;
          $ope['ite']['data-pos'] = $pos;
          $ope['ite']['class'] = ( $pos == $pos_ver ) ? "" : DIS_OCU;
          $_.="
          <li"._htm::atr($ope['ite']).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= _ele::val($ite);
            }// modelo : titulo + detalle + imagen
            elseif( is_object($ite) ){

            } $_.= "
          </li>";
        }$_.="
      </ul>";
      // operadores
      $min = $pos == 0 ? 0 : 1;
      $max = $pos;
      $_eje .= "_ite";
      $_ .= "
      <form class='ope anc-100 jus-cen mar_arr-1'>

        "._doc_num::ope('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
                
        "._doc::ico("nav_pre",['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

        "._doc_num::ope('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

        "._doc::ico("nav_pos",['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

        "._doc_num::ope('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

      </form>";
      return $_;
    }

  }
  // Opción : vacio + binario + unico + multiple + colores
  class _doc_opc {

    static string $IDE = "_doc_opc-";
    static string $EJE = "_doc_opc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      // vacíos : null
      case 'vac':
        $ope['type'] = 'radio'; 
        $ope['disabled'] = '1';
        if( is_nan($dat) ){ 
          $ope['val']="non";
        }elseif( is_null($dat) ){ 
          $ope['val']="nov";
        }                    
        break;
      // binarios : input checkbox = true/false
      case 'bin':
        $ope['type']='checkbox';
        if( !empty($dat) ){ $ope['checked']='checked'; }
        break;
      // opcion unica : input radio
      case 'uni':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_uni'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){
            $_ .= "
              <div class='val'>
                <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
                <input id='{$ope_ide}-{$ide}' type='radio' name='{$ide}' value='{$ide}'>
              </div>";
          }$_ .= "
          </div>";
        }
        break;
      // opcion múltiple : select[multiple]
      case 'mul':
        if( isset($ope['dat']) ){
          $val = $dat;
          $dat = $ope['dat'];
          unset($ope['dat']);
          $ope['multiple'] = '1';
          $_ = _doc_opc::val( $dat, $ope, ...$opc);
        }
        break;          
      // medidas
      case 'col':
        $ope['type']='color';
        $ope['value'] = empty($dat) ? $dat : '#000000';
        break;
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";            
      }

      return $_;
    }
    // selector : <select>
    static function val( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      $ope_eti = !empty($ope['eti']) ? _obj::dec($ope['eti'],[],'nom') : [];

      if( isset($ope_eti['data-opc']) ){
        $opc = array_merge($opc,is_array($ope_eti['data-opc']) ? $ope_eti['data-opc'] : explode(',',$ope_eti['data-opc']) );
      }

      // etiqueta del contenedor
      $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';

      // aseguro valor
      $val = NULL;
      if( isset($ope['val']) ){
        $val = $ope['val'];
      }
      elseif( isset($ope_eti['val']) ){
        $val = $ope_eti['val'];
        unset($ope_eti['val']);
      }
      
      $_ = "
      <{$eti}"._htm::atr($ope_eti).">";

        if( in_array('nad',$opc) ){ $_ .= "
          <option default value=''>{-_-}</option>"; 
        }
        // items
        $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
        if( !empty($ope['gru']) ){

          foreach( $ope['gru'] as $ide => $nom ){ 

            if( isset($dat[$ide]) ){ $_.="
              <optgroup data-ide='{$ide}' label='{$nom}'>
                "._doc_opc::lis( $dat[$ide], $val, $ope_ite, ...$opc )."                
              </optgroup>";
            }
          }
        }
        else{                        
          $_ .= _doc_opc::lis( $dat, $val, $ope_ite, ...$opc );
        }
        $_ .= "
      </{$eti}>";

      return $_;
    }
    // opciones : <option value>
    static function lis( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);

      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = _obj::tip($v);
        break;
      }

      foreach( $dat as $i => $v){ 
        $atr=''; 
        $htm=''; 
        $e = $ope;

        // literal
        if( !$obj_tip ){  
          $e['value'] = $i;
          $htm = !!$opc_ide ? "{$i}: ".strval($v) : strval($v) ;
          $atr = _htm::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = _ele::jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = _htm::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = _obj::val($v,$e['value']); 
          }else{ 
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
          // titulo con descripcion
          if( !isset($e['title']) ){ 
            if( isset($v->des) ){ 
              $e['title'] = $v->des; 
            }elseif( isset($v->tit) ){ 
              $e['title'] = $v->tit; 
            }
          }
          // contenido
          if( isset($e['htm']) ){
            $htm = _obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = _htm::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = _htm::atr($e);
        }
        // agrego atributo si está en la lista
        if( $val_ite ){ 
          if( $val_arr ){
            if( in_array($e['value'],$val) ) $atr .= " selected";
          }
          elseif( $val == $e['value'] ){

            $atr .= " selected";
          }
        }
        $_ .= "<option{$atr}>{$htm}</option>";
      }   
      return $_;
    }    
  }
  // Numero : codigo + separador + entero + decimal + rango
  class _doc_num {

    static string $IDE = "_doc_num-";
    static string $EJE = "_doc_num.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      if( $tip == 'val' ){

        if( !isset($ope['htm']) ) $ope['htm'] = $dat;

        $_ = "<n"._htm::atr($ope).">{$ope['htm']}</n>";
      }
      else{

        if( $tip != 'bit' ){
  
          if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;
  
          if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }
  
          if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
        }
  
        // controlo valores al actualizar
        _ele::eje($ope,'inp',"$_eje"."act(this);",'ini');
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');        
        
        switch( $tip ){
        case 'bit':
  
          $ope['type']='text';  
          break;          
        case 'int':
  
          if( !isset($ope['step']) ) $ope['step']='1'; 
  
          if( !empty($ope['value']) ){
  
            if( !empty($ope['num_pad']) ){
              
              if( !empty($ope['maxlength']) ){ 
                $tam = $ope['maxlength']; 
              }
              elseif( !empty($ope['max']) ){ 
                $tam = count(explode('',$ope['max'])); 
              }
            }
            if( !empty($tam) ){ 
              $ope['value'] = _num::val($ope['value'],$tam); 
            }
            if( !empty($ope['num_sep']) ){ 
              $ope['value'] = _num::int($ope['value']); 
            }
          }
          break;
        case 'dec':
          if( !empty($ope['value']) ){
  
            $ope['value'] = floatval($ope['value']);
  
            if( !empty($ope['maxlength']) ){
              $tam = explode(',',$ope['maxlength']); 
              $int = $tam[0]; 
              $dec = isset($tam[1]) ? $tam[1] : 0;
            }
            else{
  
              if( !empty($ope['num_dec']) ){ 
  
                $dec = $ope['num_dec']; 
              }
              elseif( isset($ope['step']) ){ 
                $dec = explode('.',$ope['step']); 
                $dec = isset($dec[1]) ? strlen($dec[1]) : 0;
              }
              if( isset($ope['num']) ){ 
  
                $int = strlen($ope['num']); 
              }
              elseif( isset($ope['max']) ){ 
  
                $int = strlen($ope['max']); 
              }
            }
            $tam = intval($int) + 1;
  
            if( empty($dec) ) $dec = 2;
  
            $ope['num_dec'] = $dec;
  
            if( !isset($ope['step']) ) $ope['step'] = '0.'._num::val('1',$dec);
  
            if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = _num::val($ope['value'],$tam);
  
            if( !empty($ope['num_sep']) ) $ope['value'] = _num::dec($ope['value']);
          }
          break;
        case 'ran':
          $ope['type']='range'; 
          if( !isset($ope['step']) ) $ope['step']=1; 
          if( !isset($ope['min']) )  $ope['min']=0; 
          if( !isset($ope['max']) )  $ope['max']=$ope['step'];
          // armo bloques : min < --- val --- > max / output
          if( !in_array('ver',$opc) ){            
            $cla = "";
            if( isset($ope['class']) ){ 
              $cla = "{$ope['class']}"; 
              unset($ope['class']); 
            }
            if( !isset($ope['id']) ){ 
              $ope['id'] = "_num_ran-"._app::var_ide('_num-ran');
            }
            $htm_out = "";
            if( !in_array('val-ocu',$opc) ){ $htm_out = "
              <output for='{$ope['id']}'>
                <n class='_val'>{$ope['value']}</n>
              </output>";
            }
            $_ = "
            <div var='num_ran' class='{$cla}'>
            
              <div class='val'>
                <n class='_min'>{$ope['min']}</n>
                <c class='sep'><</c>
                <input"._htm::atr($ope).">
                <c class='sep'>></c>
                <n class='_max'>{$ope['max']}</n>
              </div>

              {$htm_out}

            </div>";
          }
          break;
        }

        if( empty($_) && !empty($ope['type']) ){
          $_ = "<input"._htm::atr($ope).">";
        }
      }        

      return $_;
    }
  }
  // Fecha : aaaa-mm-dd hh::mm:ss
  class _doc_fec {

    static string $IDE = "_doc_fec-";

    static string $EJE = "_doc_fec.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      

      switch( $tip ){
      case 'val':
        $ope['value'] = $dat; $_ = "
        <time"._htm::atr($ope).">
          "._doc::let(_fec::var($dat))."
        </time>";
      case 'tie': 
        $ope['value'] = intval($dat);
        $ope['type']='numeric';
        break;
      case 'dyh': 
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='datetime-local';
        break;
      case 'hor':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='time';
        break;
      case 'dia':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='date';
        break;
      case 'sem':
        $ope['value'] = intval($dat);
        $ope['type']='week';
        break;
      case 'mes':
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      case 'año': 
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');
        $_ = "<input"._htm::atr($ope).">";
      }      

      return $_;
    }        
  }  
  // Texto : letra + palabra + párrafo + libro
  class _doc_tex {

    static string $IDE = "_doc_tex-";

    static string $EJE = "_doc_tex.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      // valor
      if( $tip == 'val' ){

        $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'font';

        $ope['htm'] = _doc::let( is_null($dat) ? '' : strval($dat) );

        $_ = _htm::val($ope);

      }// por tipos
      else{

        if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? _obj::cod($dat) : $dat );

        $ope['value'] = str_replace('"','\"',$dat);

        if( $tip == 'par' ){

          if( empty($ope['rows']) ) $ope['rows']="2";      
        }
        else{
          $ope['type']='text';
        }
        if( isset($ope['type']) ){
          $lis_htm = "";
          if( isset($ope['lis']) || isset($ope['dat']) ){
            if( isset($ope['lis']) ){
              $dat_lis = _obj::dec($ope['lis']);
              unset($ope['lis']);          
            }else{
              $dat_lis = [];
            }        
            if( empty($ope['id']) ){ 
              $ope['id']="_tex-{$tip}-"._app::var_ide("_tex-{$tip}-");
            }
            $ope['list'] = "{$ope['id']}-lis";
            $lis_htm = "
            <datalist id='{$ope['list']}'>";
              foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
                <option data-ide='{$pos}' value='{$ite}'></option>";
              }$lis_htm .="
            </datalist>";
          }
          // seleccion automática
          _ele::eje($ope,'foc',"this.select();",'ini');  
          $_ = "<input"._htm::atr($ope).">".$lis_htm;
        }
        else{
          $_ = "<textarea"._htm::atr($ope).">{$dat}</textarea>";
        }
      }      

      return $_;
    }
  }
  // Archivo del directorio: ./ "." tex ima mus vid app
  class _doc_arc {

    static string $IDE = "_doc_arc-";

    static string $EJE = "_doc_arc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      if( isset($ope['tip']) ) $ope['accept'] = _arc::tip($ope['tip']);

      switch( $tip ){
      case 'val':
        $ope['type']='file';
        if( isset($ope['multiple']) ) unset($ope['multiple']);
        break;
      case 'lis':
        $ope['type']='file';
        $ope['multiple'] = '1';
        break;
      case 'url':
        $ope['type']='url';
        break;
      // ima - vid - mus
      default:
        $ope['type']='file';
        $ope['accept'] = _arc::tip($tip);
        break;      
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";
      }
      return $_;
    }    
    
  }  
  // Obejeto : {}, []
  class _doc_obj {

    static string $IDE = "_doc_obj-";

    static string $EJE = "_doc_obj.";

    // operadores 
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      // texto : json
      if( !isset($dat) || is_string($dat) ){
        $ope['value'] = strval($dat); $_ = "
        <textarea"._htm::atr($ope).">{$dat}</textarea>";
      }
      // por tipos: pos - nom - atr
      elseif( $tip = _obj::tip($dat) ){
        $cue = 0; 
        $htm = '';
        $cla_agr = ''; 
        $cla_tog = ' dis-ocu';
        if( in_array('ocu',$opc) ){ 
          $cla_agr = ' dis-ocu'; 
          $cla_tog = ''; 
        }
        $atr_agr = in_array('dat',$opc) ? '' : " disabled";
        // separadores
        if( $tip=='pos' ){ $ini='('; $fin=')'; }elseif( $tip=='nom' ){ $ini='['; $fin=']'; }else{ $ini='{'; $fin='}'; }
        // conteido
        if( is_object($dat) ){
          // ... incluir metodos
        }
        foreach( $dat as $i=>$v ){ 
          $cue++; 
          $htm .= _doc_obj::ite( $i, $v, $tip, ...$opc);
        }
        $ope['var']= "obj_{$tip}";
        $_ = "
        <div"._htm::atr($ope).">
          <div class='jus-ini mar_ver-1'>
            <p>
              <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
            </p>
            "._doc::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
            <ul class='ope _tog{$cla_agr}'>"; 
              if( empty($atr_agr) ){ $_.="
              "._doc::ico('ope_mar',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
              "._doc::ico('ope_des',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
              "._doc::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
              "._doc::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
              ";
              }$_.="
            </ul>
            <p class=' _tog{$cla_tog}'>
              <c class='sep _lis-fin'>{$fin}</c>
            </p>
          </div>
          <ul class='lis _atr ali-ini _tog{$cla_agr}'> 
            {$htm}
          </ul>
          <p class='_tog{$cla_agr}'>
            <c class='_lis-fin'>{$fin}</c>
          </p>
        </div>";
      }

      return $_;
    }
    // item por tipo
    static function ite( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      $ope['ent']=isset($ope['ent'])?$ope['ent']:'alm';
      
      $ope['eti']=isset($ope['eti'])?$ope['eti']:[]; 
      $ope['ite']=isset($ope['ite'])?$ope['ite']:[];      

      if( is_null($dat) ){ 
        $dat=''; 
        $tip_dat='val'; 
        $tip_val='vac'; 
        $_="<input type='radio' disabled>";
      }
      else{ 
        $tip = _val::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }

      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "_doc_{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){

        if( $ite != "" ){          
          $_ = $cla_ide::ope( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = _doc_opc::val( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{

        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p"._htm::atr($ope['ite']).">{$dat}</p>";
      }
      $ide='';
      if( !empty($ite) ){ 
        $agr = "";
        if( $tip == 'pos' ){
          $agr = " tam='2'";
          $tip = "number";
        }else{
          $tip = "text";
        }
        $ide="<input class='ide' type='{$tip}'{$agr} value='{$ide}' title='{$ide}'>";
      }
      else{ 
        $ide="<c class='sep'>[</c><n>{$ide}</n><c class='sep'>]</c>";
      }
      if( $tip == 'pos' ){
        $sep='='; 
      }else{ 
        $sep=( $tip == 'nom' ) ? '=>' : ':' ; 
      }  
      $sep = "<c class='sep'>{$sep}</c>"; 

      return "
      <li class='atr' data-ide='{$ide}'>
        {$ite}{$ide}{$sep}{$_}
      </li>";  
    }
  }
  // Ejecucion : () => {} : $
  class _doc_eje {

    static string $IDE = "_doc_eje-";

    static string $EJE = "_doc_eje.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }  
  // Elemento : <>...</>
  class _doc_ele {

    static string $IDE = "_doc_ele-";

    static string $EJE = "_doc_ele.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }