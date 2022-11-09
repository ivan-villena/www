<?php

class app_var {

  static string $IDE = "app_var-";
  static string $EJE = "app_var.";

  static function fig( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."fig";
    $_eje = self::$EJE."fig";
    
    switch( $tip ){
    case 'ima': 
      $_ = "<img src=''>";
      break;
    // color
    case 'col':
      $ope['type']='color';
      $ope['value'] = empty($dat) ? $dat : '#000000';
      break;
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".api_ele::atr($ope).">";            
    }
    return $_;
  }

  static function num( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
    $_ = "";
    $_ide = self::$IDE."num";
    $_eje = self::$EJE."num";

    if( $tip == 'val' ){

      if( !isset($ope['htm']) ) $ope['htm'] = $dat;

      $_ = "<n".api_ele::atr($ope).">{$ope['htm']}</n>";
    }
    else{

      if( $tip != 'bit' ){

        if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;

        if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }

        if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
      }

      // controlo valores al actualizar
      api_ele::eje($ope,'inp',"$_eje"."act(this);",'ini');
      // seleccion automática
      api_ele::eje($ope,'foc',"this.select();",'ini');        
      
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
            $ope['value'] = api_num::val($ope['value'],$tam); 
          }
          if( !empty($ope['num_sep']) ){ 
            $ope['value'] = api_num::int($ope['value']); 
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

          if( !isset($ope['step']) ) $ope['step'] = '0.'.api_num::val('1',$dec);

          if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = api_num::val($ope['value'],$tam);

          if( !empty($ope['num_sep']) ) $ope['value'] = api_num::dec($ope['value']);
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
            $ope['id'] = "_num_ran-".app::var_ide('_num-ran');
          }
          $htm_out = "";
          if( !in_array('val-ocu',$opc) ){ $htm_out = "
            <output for='{$ope['id']}'>
              <n class='_val'>{$ope['value']}</n>
            </output>";
          }
          $_ = "
          <div class='app_num ran {$cla}'>
          
            <div class='val'>
              <n class='_min'>{$ope['min']}</n>
              <c class='sep'><</c>
              <input".api_ele::atr($ope).">
              <c class='sep'>></c>
              <n class='_max'>{$ope['max']}</n>
            </div>

            {$htm_out}

          </div>";
        }
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input".api_ele::atr($ope).">";
      }
    }        

    return $_;
  }

  static function tex( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";

    // valor
    if( $tip == 'val' ){

      $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'font';

      $ope['htm'] = app::let( is_null($dat) ? '' : strval($dat) );

      $_ = api_ele::val($ope);

    }// por tipos
    else{

      if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? api_obj::cod($dat) : $dat );

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
            $dat_lis = api_obj::dec($ope['lis']);
            unset($ope['lis']);          
          }else{
            $dat_lis = [];
          }        
          if( empty($ope['id']) ){ 
            $ope['id']="_tex-{$tip}-".app::var_ide("_tex-{$tip}-");
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
        api_ele::eje($ope,'foc',"this.select();",'ini');  
        $_ = "<input".api_ele::atr($ope).">".$lis_htm;
      }
      else{
        $_ = "<textarea".api_ele::atr($ope).">{$dat}</textarea>";
      }
    }      

    return $_;
  }

  static function fec( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      

    switch( $tip ){
    case 'val':
      $ope['value'] = $dat; $_ = "
      <time".api_ele::atr($ope).">
        ".app::let(api_fec::var($dat))."
      </time>";
    case 'tie': 
      $ope['value'] = intval($dat);
      $ope['type']='numeric';
      break;
    case 'dyh': 
      $ope['value'] = api_fec::var($dat,$tip);
      $ope['type']='datetime-local';
      break;
    case 'hor':
      $ope['value'] = api_fec::var($dat,$tip);
      $ope['type']='time';
      break;
    case 'dia':
      $ope['value'] = api_fec::var($dat,$tip);
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
      api_ele::eje($ope,'foc',"this.select();",'ini');
      $_ = "<input".api_ele::atr($ope).">";
    }      

    return $_;
  }
  
  static function arc( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";

    if( isset($ope['tip']) ) $ope['accept'] = api_arc::tip($ope['tip']);

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
      $ope['accept'] = api_arc::tip($tip);
      break;      
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".api_ele::atr($ope).">";
    }
    return $_;
  }      
  
  static function ele( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";

    return $_;
  }  
  
  static function eje( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";

    return $_;
  }   

  static function obj( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."obj";
    $_eje = self::$EJE."obj";

    // texto : json
    if( !isset($dat) || is_string($dat) ){
      $ope['value'] = strval($dat); $_ = "
      <textarea".api_ele::atr($ope).">{$dat}</textarea>";
    }
    // por tipos: pos - nom - atr
    elseif( $tip = api_obj::tip($dat) ){
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
        $htm .= app_var::obj_ite( $i, $v, $tip, ...$opc);
      }
      api_ele::cla($ope,"app_obj {$tip}",'ini');
      $_ = "
      <div".api_ele::atr($ope).">
        <div class='jus-ini mar_ver-1'>
          <p>
            <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
          </p>
          ".app::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
          <ul class='ope _tog{$cla_agr}'>"; 
            if( empty($atr_agr) ){ $_.="
            ".app::ico('dat_tod',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
            ".app::ico('dat_nad',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
            ".app::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
            ".app::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
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
  static function obj_ite( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
    $_ = "";

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
      $tip = api_dat::tip($dat); 
      $tip_dat = $tip['dat']; 
      $tip_val = $tip['val']; 
    }

    $ite = "";
    if( in_array('dat',$opc) && $tip != 'val' ){ 
      $ite = "<input type='checkbox'>"; 
    }
    // items de lista -> // reducir dependencias
    $cla_ide = "app_{$tip_dat}";
    if( in_array($tip_dat,[ 'lis' ]) ){

      if( $ite != "" ){          
        $_ = $cla_ide::ope( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
      }
      else{
        $_ = app_var::opc_val( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
      }
    }// controladores
    else{

      $dat = is_string($dat) ? $dat : strval($dat); 
      $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p".api_ele::atr($ope['ite']).">{$dat}</p>";
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

  static function opc( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."opc";
    $_eje = self::$EJE."opc";
    
    switch( $tip ){
    // vacío : null
    case 'vac':
      $ope['type'] = 'radio'; 
      $ope['disabled'] = '1';
      if( is_nan($dat) ){ 
        $ope['val']="non";
      }elseif( is_null($dat) ){ 
        $ope['val']="nov";
      }                    
      break;
    // binario : input[checkbox]
    case 'bin':
      $ope['type']='checkbox';
      if( !empty($dat) ){ $ope['checked']='checked'; }
      break;
    // único : div > input[radio]
    case 'uni':
      if( isset($ope['dat']) ){
        $_dat = $ope['dat'];
        unset($ope['dat']); 
        $_ .= "
        <div var='opc_uni'>";
        $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
        foreach( $_dat as $ide => $val ){ $_ .= "
          <div class='val'>
            <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
            <input id='{$ope_ide}-{$ide}' type='radio' name='{$ide}' value='{$ide}'>
          </div>";
        }$_ .= "
        </div>";
      }
      break;
    // múltiple : div > ...input[checkbox]
    case 'mul':
      if( isset($ope['dat']) ){
        $_dat = $ope['dat'];
        unset($ope['dat']); 
        $_ .= "
        <div var='opc_mul'>";
        $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
        foreach( $_dat as $ide => $val ){ $_ .= "
          <div class='val'>
            <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
            <input id='{$ope_ide}-{$ide}' type='checkbox' name='{$ide}' value='{$ide}'>
          </div>";
        }$_ .= "
        </div>";
      }
      break;          
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".api_ele::atr($ope).">";            
    }
    return $_;
  }
  static function opc_val( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";

    $ope_eti = !empty($ope['eti']) ? api_obj::dec($ope['eti'],[],'nom') : [];

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
    <{$eti}".api_ele::atr($ope_eti).">";

      if( in_array('nad',$opc) ){ $_ .= "
        <option default value=''>{-_-}</option>"; 
      }
      // items
      $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
      if( !empty($ope['gru']) ){

        foreach( $ope['gru'] as $ide => $nom ){ 

          if( isset($dat[$ide]) ){ $_.="
            <optgroup data-ide='{$ide}' label='{$nom}'>
              ".app_var::opc_lis( $dat[$ide], $val, $ope_ite, ...$opc )."                
            </optgroup>";
          }
        }
      }
      else{                        
        $_ .= app_var::opc_lis( $dat, $val, $ope_ite, ...$opc );
      }
      $_ .= "
    </{$eti}>";

    return $_;
  }
  static function opc_lis( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
    $_ = "";
    
    $val_ite = !empty($val);
    $val_arr = $val_ite && is_array($val);
    $opc_ide = in_array('ide',$opc);

    $obj_tip = FALSE;
    foreach( $dat as $i => $v){ 
      $obj_tip = api_obj::tip($v);
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
        $atr = api_ele::atr($e);
      }
      // elemento
      elseif( $obj_tip == 'nom' ){
        $e = api_ele::jun($e,$v);
        if( !isset($e['value']) ) $e['value'] = $i;
        $htm = isset($e['htm']) ? $e['htm'] : $i;
        $atr = api_ele::atr($e);
      }
      // objeto ( ide + nom + des + tit )
      elseif( $obj_tip == 'atr' ){
        $_ide = isset($v->ide) ? $v->ide : FALSE ;
        $_htm = isset($v->nom) ? $v->nom : FALSE ;
        // valor
        if( isset($e['value']) ){ 
          $e['value'] = api_obj::val($v,$e['value']); 
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
          $htm = api_obj::val($v,$e['htm']);
        }else{
          if( !!$opc_ide && $_ide && $_htm ){
            $htm = "{$_ide}: {$_htm}";
          }elseif( $_htm ){
            $htm = $_htm;
          }else{
            $htm = $_ide; 
          }
        }
        $atr = api_ele::atr($e,$v);            
      }// por posiciones
      else{
        $htm = "( \"".implode( '", "', $v )."\" )" ;
        $atr = api_ele::atr($e);
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