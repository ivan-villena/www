<?php

// Valor : acumulado + sumatoria + filtro + conteos
class app_val {

  static array $OPE = [
    'acu'=>['nom'=>"Acumulados" ], 
    'ver'=>['nom'=>"Selección"  ], 
    'sum'=>['nom'=>"Sumatorias" ], 
    'cue'=>['nom'=>"Conteos"    ]
  ];
  static string $IDE = "app_val-";
  static string $EJE = "app_val.";

  // cargo Valores : absoluto o con dependencias ( api.dat->est ) 
  static function dat( array $ope ) : array {      
    $_ = [];
    // cargo temporal
    foreach( $ope as $esq => $est_lis ){
      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        // recorro dependencias            
        foreach( ( !empty($dat_est = app::dat($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
          // acumulo valores
          if( isset($dat->$ide) ) $_["{$ref}"] = $dat->$ide;
        }
      }
    }
    global $_api;
    $_api->app_val []= $_;

    return $_;
  }
  // abm de la base
  static function abm( string $tip, array $ope = [], array $ele = [] ) : string {
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";
    $_ope = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];      
    switch( $tip ){
    case 'nav':        
      $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
      if( !empty($url) ){
        $url_agr = "{$url}/0";
        $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
      }
      $_ .= "
      <fieldset class='ope' abm='{$tip}'>    
        ".app::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."

        ".app::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."

        ".app::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
      </fieldset>";
      break;
    case 'abm':
      $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
      $_ = "
      <fieldset class='ope mar-2 esp-ara'>

        ".app::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

        if( in_array('eli',$ope['opc']) ){

          $_ .= app::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
        }$_ .= "

        ".app::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

      </fieldset>";
      break;              
    case 'est':
      $_ .= "
      <fieldset class='ope'>    
        ".app::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
        
        ".app::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
      </fieldset>";                  
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

    $_ .= "
    <div class='ren'>";
      foreach( $opc as $ide ){        
        $_ .= app::var('app',"val.acu.$ide", [
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
  }
  // sumatorias
  static function sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
    extract( api_dat::ide($dat) );

    $_ = "";
    $_ide = self::$IDE."sum"." _$esq-$est";

    // estructuras por esquema
    foreach( app::var_dat($esq,'val','sum') as $ide => $ite ){
  
      $_ .= app::var($esq,"val.sum.$ide",[
        'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
        // busco fichas del operador
        'htm_fin'=> !empty($ite['var_fic']) ? app_dat::fic($ite['var_fic'], $val, $ope) : ''
      ]);
    }    

    return $_;
  }
  // filtros : texto + listado + datos
  static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "";
    $_ite = function( $ide, $dat=[], $ele=[] ){

      if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

      // impido tipos ( para fechas )
      if( ( $ide == 'inc' || $ide == 'lim' ) && isset($ele['ope']['_tip']) ) unset($ele['ope']['_tip']);
      
      // combino elementos
      if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = api_ele::jun($ele['ope'],$dat[$ide]);

      return $ele;
    };
    switch( $tip ){
    // dato : estructura => valores 
    case 'dat':
      // selector de estructura.relaciones para filtros
      array_push($opc,'est','val');
      $_ .= app::var('app',"val.ver.dat",[ 
        'ite'=>[ 'class'=>"tam-mov" ],
        'htm'=>app_dat::opc('ver',$dat,$ele,...$opc)
      ]);
      break;
    // listado : desde + hasta + cada + cuantos
    case 'lis': 
      // por defecto
      if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

      // desde - hasta
      foreach( ['ini','fin'] as $ide ){

        if( isset($dat[$ide]) ) $_ .= app::var('app',"val.ver.$ide", $_ite($ide,$dat,$ele));
      }

      // limites : incremento + cuantos ? del inicio | del final
      if( isset($dat['inc']) || isset($dat['lim']) ){
        $_ .= "
        <div class='ren'>";
          // cada
          if( isset($dat['inc']) ){
            $_ .= app::var('app',"val.ver.inc", $_ite('inc',$dat,$ele));
          }
          // cuántos
          if( isset($dat['lim']) ){
            $_eje = "app_ope.var('mar',this,'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
            $ele['htm_fin'] = "
            <fieldset class='ope'>
              ".app::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
              ".app::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
            </fieldset>"; 
            $_ .=
            app::var('app',"val.ver.lim", $_ite('lim',$dat,$ele) );
          }$_ .= "
        </div>";
      }
      break;
    }
    return $_;
  }
  // conteos : por valores de estructura relacionada por atributo
  static function cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."cue";
    $_eje = self::$EJE."cue";

    if( is_string($dat) ){
      extract( api_dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }

    switch( $tip ){        
    case 'dat': 
      $_ = [];
      // -> por esquemas
      foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){
        // -> por estructuras
        foreach( $est_lis as $est_ide ){
          // -> por dependencias ( est_atr )
          foreach( ( !empty($dat_opc_est = app::dat($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
            $est = str_replace("{$esq}_",'',$est);
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = app::dat($esq,$est,'opc.ver') ){
              // nombre de la estructura
              $est_nom = api_dat::est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                // armo relacion por atributo
                $rel = api_dat::rel($esq,$est,$atr);
                // busco nombre de estructura relacional
                $rel_nom = api_dat::est($esq,$rel)->nom;
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='var mar_izq-2 dis-ocu'>
                    ".app_val::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
      $ide = !empty($atr) ? api_dat::rel($esq,$est,$atr) : $est;
      $_ = "
      <!-- filtros -->
      <form class='val'>

        ".app::var('val','ver',[ 
          'nom'=>"Filtrar", 
          'id'=> "{$_ide}-ver {$esq}-{$ide}",
          'htm'=> app::val_ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
        ])."
      </form>

      <!-- valores -->
      <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
        <tbody>";
        foreach( api::dat($esq,$ide) as $ide => $_var ){
        
          $ide = isset($_var->ide) ? $_var->ide : $ide;

          if( !empty($atr) ){
            $ima = !empty( $_ima = api_dat::opc('ima',$esq,$est,$atr) ) ? app::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
          }
          else{
            $ima = app::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
          }$_ .= "
          <tr class='pos' data-ide='{$ide}'>
            <td data-atr='ima'>{$ima}</td>
            <td data-atr='ide'>".app::let($ide)."</td>
            <td data-atr='nom'>".app::let(isset($_var->nom) ? $_var->nom : '')."</td>
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
}