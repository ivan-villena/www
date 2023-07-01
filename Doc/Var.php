<?php

class Doc_Var {

  static string $IDE = "Doc_Var-";
  static string $EJE = "Doc_Var.";
  static string $DAT = "doc_var";

  // Opciones
  static function opc( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."opc";
    $_eje = self::$EJE."opc";
    
    switch( $tip ){
    // Binario
    case 'bin':
      $ope['type'] = 'checkbox';
      if( !empty($dat) ){
        $ope['checked'] = 'checked'; 
      }
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
          <div class='-val'>
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
        if( is_string($_dat) ){
          $_dat = Dat::get($_dat);
        }
        $_ .= "
        <div class='Opc mul'>";
        $ope_dat = Obj::pos_ite($dat);
        $ope_ide = isset($ope['id']) ? $ope['id'] : "_opc_mul-".Doc::ide('opc_mul');
        $ope_nom = isset($ope['name']) ? $ope['name'] : FALSE;
        foreach( $_dat as $ide => $dat ){
          $ide = isset($dat->ide) ? $dat->ide : $ide;
          $val = isset($dat->nom) ? $dat->nom : $ide;
          $ope_var = $ope;
          $ope_var['eti']   = "input";
          $ope_var['type']  = "checkbox";
          $ope_var['id']    = "{$ope_ide}-{$ide}";
          $ope_var['name']  = $ope_nom ? $ope_nom : $ide;
          $ope_var['value'] = $ide;
          if( in_array($ide,$ope_dat) ) $ope_var['checked'] = 1;
          $_ .= "
          <div class='-val'>
            <label for='{$ope_var['id']}'>{$val}<c>:</c></label>
            ".Ele::eti($ope_var)."
          </div>";
        }$_ .= "
        </div>";
      }
      break;          
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".Ele::atr($ope).">";            
    }
    return $_;
  }
  
  // numero
  static function num( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."num";
    $_eje = self::$EJE."num";

    // sistema decimal
    if( $tip != 'bit' ){

      if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;

      if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }

      if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
    }        

    // controlo valores al actualizar
    Ele::eje($ope,'inp',"$_eje"."_act(this);",'ini');
    
    // seleccion automática
    Ele::eje($ope,'foc',"this.select();",'ini');     
    
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
          $ope['value'] = Num::val($ope['value'],$tam); 
        }
        if( !empty($ope['num_sep']) ){ 
          $ope['value'] = Num::int($ope['value']); 
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

        if( !isset($ope['step']) ) $ope['step'] = '0.'.Num::val('1',$dec);

        if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = Num::val($ope['value'],$tam);

        if( !empty($ope['num_sep']) ) $ope['value'] = Num::dec($ope['value']);
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
          $cla = " {$ope['class']}"; 
          unset($ope['class']); 
        }
        if( !isset($ope['id']) ){ 
          $ope['id'] = "num_ran-".Doc::ide('num_ran');
        }
        $htm_out = "";
        if( !in_array('val-ocu',$opc) ){ $htm_out = "
          <output for='{$ope['id']}'>
            <n class='_val'>{$ope['value']}</n>
          </output>";
        }
        $_ = "
        <div class='var_num ran{$cla}'>
        
          <div class='-val'>
            <n class='_min'>{$ope['min']}</n>
            <c class='sep'><</c>
            <input".Ele::atr($ope).">
            <c class='sep'>></c>
            <n class='_max'>{$ope['max']}</n>
          </div>

          {$htm_out}

        </div>";
      }
      break;
    }

    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".Ele::atr($ope).">";
    }    

    return $_;
  }

  // texto
  static function tex( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string { 
    $_ = "";
    $_ide = self::$IDE."tex";
    $_eje = self::$EJE."tex";

    if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? Obj::val_cod($dat) : $dat );

    $ope['value'] = str_replace('"','\"',$dat);

    if( $tip == 'par' ){

      if( empty($ope['rows']) ) $ope['rows']="2";      

    }
    elseif( !isset($ope['type']) ){

      $ope['type'] = 'text';
    }
    
    if( isset($ope['type']) ){

      $lis_htm = "";

      if( isset($ope['lis']) || isset($ope['dat']) ){

        if( isset($ope['lis']) ){

          $dat_lis = Obj::val_dec($ope['lis']);
          unset($ope['lis']);          
        }
        else{
          $dat_lis = [];
        }

        if( empty($ope['id']) ){ 
          $ope['id']="_tex-{$tip}-".Doc::ide("_tex-{$tip}-");
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
      Ele::eje($ope,'foc',"this.select();",'ini');  

      $_ = "<input".Ele::atr($ope).">".$lis_htm;

    }
    else{
      $_ = "<textarea".Ele::atr($ope).">{$dat}</textarea>";
    }  

    return $_;
  }

  // Fechas
  static function fec( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."fec";
    $_eje = self::$EJE."fec";  

    switch( $tip ){
    case 'tie': 
      $ope['type'] = 'datetime-local';
      $ope['value'] = Fec::val_var($dat,$tip);
      break;
    case 'dia':
      $ope['type'] = 'date';
      $ope['value'] = Fec::val_var($dat,$tip);
      break;      
    case 'hor':
      $ope['type'] = 'time';
      $ope['value'] = Fec::val_var($dat,$tip);
      break;
    case 'sem':
      $ope['type'] = 'week';
      $ope['value'] = intval($dat);
      break;
    case 'mes':
      $ope['type'] = 'number';
      $ope['min'] = 1;
      $ope['max'] = 12;
      $ope['value'] = intval($dat);
      break;
    case 'año': 
      $ope['type'] = 'number';
      $ope['value'] = intval($dat);
      $ope['min'] = -9999;
      $ope['max'] = 9999;
      break;
    }

    if( empty($_) && !empty($ope['type']) ){
      // seleccion automática
      Ele::eje($ope,'foc',"this.select();",'ini');
      $_ = "<input".Ele::atr($ope).">";
    }      

    return $_;
  }
  
  // Figuras
  static function fig( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."fig";
    $_eje = self::$EJE."fig";
    
    switch( $tip ){
    // color
    case 'col':
      $ope['type'] = 'color';
      $ope['value'] = empty($dat) ? $dat : '#000000';
      break;
    // dibujos
    case 'pun':
      break;
    case 'lin':
      break;
    case 'pol':
      break;
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".Ele::atr($ope).">";            
    }
    return $_;
  }
  
  // objeto
  static function obj( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."obj";
    $_eje = self::$EJE."obj";

    $_ite = function( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
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
        $tip = Dat::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }
  
      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){
  
        if( $ite != "" ){          
          $_ = $cla_ide::var( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = Doc_Val::opc( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{
  
        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p".Ele::atr($ope['ite']).">{$dat}</p>";
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
    };
    // texto : json
    if( !isset($dat) || is_string($dat) ){
      $ope['value'] = strval($dat); $_ = "
      <textarea".Ele::atr($ope).">{$dat}</textarea>";
    }
    // por tipos: pos - nom - atr
    elseif( $tip = Obj::tip($dat) ){
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
        $htm .= $_ite($i,$v,$tip,...$opc);
      }
      Ele::cla($ope,"Obj {$tip}",'ini');
      $_ = "
      <div".Ele::atr($ope).">
        <div class='jus-ini mar_ver-1'>
          <p>
            <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
          </p>
          ".Doc_Val::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
          <ul class='ope_bot _tog{$cla_agr}'>"; 
            if( empty($atr_agr) ){ $_.="
            ".Doc_Val::ico('dat_tod',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
            ".Doc_Val::ico('dat_nad',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
            ".Doc_Val::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
            ".Doc_Val::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
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
  
  // Directorio
  static function arc( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."arc";
    $_eje = self::$EJE."arc";

    // Validacion de tipos para formularios
    $_val_tip = function( $ide ){
      $_ = "";
      switch( $ide ){
        case 'ima': $_ = ""; break;
        case 'vid': $_ = ""; break;
        case 'mus': $_ = ""; break;
      }
      return $_;
    };

    switch( $tip ){
    case 'dir':
      $ope['type'] = 'file';
      if( isset($ope['tip']) ) $ope['accept'] = $_val_tip($ope['tip']);
      $ope['multiple'] = '1';
      break;

    case 'fic':
      $ope['type'] = 'file';
      if( isset($ope['tip']) ) $ope['accept'] = $_val_tip($ope['tip']);
      if( isset($ope['multiple']) ) unset($ope['multiple']);
      break;

    case 'url':
      $ope['type']='url';
      break;

    // ima - vid - mus
    default:
      $ope['type']='file';
      $ope['accept'] = $_val_tip($tip);
      break;
      
    }

    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".Ele::atr($ope).">";
    }
    return $_;
  }
}