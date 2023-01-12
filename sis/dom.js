
// Modelo de Objetos del Documento
class sis_dom {

  // Aplicacion
  app = {
    bot: 'body > .app_bot',
    win: 'body > .app_win',
    pan: 'body > .app_pan',
    sec: 'body > .app_sec',
    bar: 'body > .app_bar',
    pie: 'body > .app_pie',
    var: null
  };// - Estructura
  app_est = {

    ope: {
      // acumulados
      acu : [ "pos", "mar", "ver", "opc" ],
      // filtros
      ver : {
        dat : `form.ide-dat select[name="val"]`,
        fec : `form.ide-fec input[name="ini"]`,
        pos : `form.ide-pos input[name="ini"]`
      }    
    },

    lis: {
      // Valores
      val :     `.ide-lis div.est.lis`,
      // Operador
      val_acu : `.ide-lis .ide-ver .ide-acu`,      
      val_sum : `.ide-lis .ide-ver .ide-sum`,
      val_cue : `.ide-lis .ide-ver .ide-cue`,    
      // filtros
      ver : `.ide-lis .ide-ver`,
      // Descripciones
      des : `.ide-lis .ide-des`    
    },
    
    tab: {
      ide : null,
      dep : null,
      cla : null,
      // Valores
      val : `main > article > .est.tab`,
      // operador
      val_acu : `.doc_pan > .ide-val .ide-acu`,
      val_sum : `.doc_pan > .ide-val .ide-sum`,
      val_cue : `.doc_pan > .ide-val .ide-cue`,
      // Seleccion
      ver : `.doc_pan > .ide-ver`,
      // seccion + posicion
      sec : `.doc_pan > .ide-opc .ide-sec`,    
      pos : `.doc_pan > .ide-opc .ide-pos`,
      // ...opciones
      opc : `.doc_pan > .ide-opc .ide-opc`
    }
  }

  constructor(){

    // Aplicacion
    for( const $atr in this.app ){
      if( $atr ) this.app[$atr] = document.querySelector(this.app[$atr]);
    }
    
    // Estructura : listado + tablero
    ['lis','tab'].forEach( $ope => {

      for( const $ide in this.app_est[$ope] ){

        if( typeof(this.app_est[$ope][$ide]) == 'string' ){

          this.app_est[$ope][$ide] = document.querySelector(this.app_est[$ope][$ide]); 
        }
        else if( typeof(this.app_est[$ope][$ide]) == 'object' ){

          for( const $i in this.app_est[$ope][$ide] ){
            this.app_est[$ope][$ide][$i] = document.querySelector(this.app_est[$ope][$ide][$i]);
          }
        }
      }
    });

    this.app_est.tab.cla = ".pos.ope";
  }
  
  // operaciones
  ope( $val, $ope, ...$opc ){
    let $_=false,$={};
    // busco elementos
    if( typeof($val) == 'string' ){ 
      $val = document.querySelectorAll($val);
    }
    else if( Array.isArray($val) ){
      $val[0] = ( !$val[0] || typeof($val[0]) == 'string' ) ? document.querySelector( $val[0] ? $val[0] : 'body' ) : $val[0];
      $val = $val[0].querySelectorAll($val[1] ? $val[1] : '*');
    }
    // ejecuto operaciones
    $.res = $val;
    if( $ope ){
      $.res = [];
      api_lis.val_ite($ope).forEach( $ope_ite => $.res.push( this.act( $ope_ite, $val, ...$opc ) ) );
    }
    // resultados: [<>] => <> // si hay 1 solo, devuelvo único elemento
    $_ = api_lis.val_cod($.res);
    if( !$_.length ){ 
      $_ = false; 
    }else if( $_.length == 1 ){ 
      $_ = $_[0]; 
    }
    return $_;
  }
  // actualizo propiedades
  act( $tip, $ele, $val, $ope ){

    let $_ = [], $ = { tip : $tip.split('_')  };

    if( typeof($ele) == 'string' ) $ele = document.querySelectorAll($ele);

    $.lis = api_lis.val_cod($ele);

    switch( $.tip[0] ){
    case 'nod':
      if( !$.tip[1] ){             
        $.htm = { 'main':'app', 'article':'art', 'form':'for', 'fieldset':'fie', 'div':'div' };
        $_ = {};
        $.her = $ele;
        // armo ascendencia
        while( $.her.parentElement ){ 
          $.her = $.her.parentElement; 
          $.ele.nod = $.her.nodeName.toLowerCase();         
          if( $.htm[$.ele.nod] ){ 
            $_[ $.htm[$.ele.nod] ] = $.her; 
          }
        }
      }
      break;
    case 'htm': 
      switch( $.tip[1] ){
      // actualizacion creando elemento
      case 'val': $.lis.forEach( $v => $_.push( $v.innerHTML = $val ) ); break;
      case 'eli': $.lis.forEach( $v => { api_lis.val_cod($v.children).forEach( $v_2 => $_.push($v.removeChild($v_2)) ) } ); break;
      }
      break;
    case 'atr': 
      switch( $.tip[1] ){
      case 'act': $.lis.forEach( $v => $_.push( $v.setAttribute($val,$ope) ) ); break;
      case 'val': $.lis.forEach( $v => $_ = $v.getAttribute($val) ); break;
      }    
      break;
    case 'cla':
      switch( $.tip[1] ){
      case 'val': 
        $.lis.forEach( $v => $_.push( $v.classList.contains($val) ) ); 
        break;
      case 'pos': 
        $.lis.forEach( $v => $_.push( $v.classList.item($val) ) ); 
        break;
      case 'tog': 
        $.lis.forEach( $v => $_.push( $v.classList.toggle($val) ) ); 
        break;
      case 'agr': 
        $.lis.forEach( $v => api_lis.val_ite($val).forEach( $val_cla => $_.push( $v.classList.add($val_cla) ) ) ); 
        break;
      case 'mod': 
        $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); 
        break;
      case 'eli': 
        $.lis.forEach( $v => api_lis.val_ite($val).forEach( $val_cla => $_.push( $v.classList.remove($val_cla) ) ) );
        break;    
      }
      break;
    case 'eje':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': $.lis.forEach( $v => $_.push( $v.addEventListener( $val, eval($ope) ) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.removeEventListener( $val, $ope ) ) ); break;
      }
      break;    
    case 'css':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': break;
      case 'mod': break;
      case 'eli': break;
      }
      break;
    }

    return $_;
  }
  // busco nodos
  ver( $ele, $ope={} ){

    let $_ = false, $ = {};
    $.opc = $ope.opc ? $ope.opc : []      
    // ejecuto valicaciones : etiqueta | clases | atributos
    $._ele_ver = ( $ele, $ope ) =>{
      let $_ = true;
      // etiqueta
      if( $ope.eti && $ope.eti != $ele.nodeName.toLowerCase() ) $_ = false;
      // clases
      if( $_ && $ope.cla ){
        $ope.cla.forEach( $v =>{ 
          if( !$ele.classList.contains($v) ) return $_ = false;
        });
      }
      // atributo = valor
      if( $_ && $ope.atr ){
        $ope.atr.forEach( ($v,$i) =>{ 
          if( !($.atr_val = $ele.getAttribute($i)) || ( $v && $.atr_val != $v ) ) return $_ = false;
        });
      }
      return $_;

    };
    // proceso filtros
    $.val = [];
    $.opc_mul = $.opc.includes('mul');
    
    // por nodos descendentes
    if( $.opc.includes('nod') ){

      api_lis.val_cod($ele.children).forEach( $ele => {

        if( $._ele_ver($ele,$ope) ){           
          $.val.push($ele);
          if( !$.opc_mul ){ return; }
        }
      });
    }// por ascendentes
    else{
      while( $ele.parentElement ){

        $ele = $ele.parentElement;

        if( $._ele_ver($ele,$ope) ){ 
          $.val.push($ele); 
          if( !$.opc_mul ){ break; }
        }
      }
    }
    // devuelvo 1 o muchos
    if( $.val.length > 0 ){
      $_ = $.val.length == 1 ? $.val[0] : $.val;
    }
    return $_;
  }
  // agrego nodo/s al inicio o al final
  agr( $ele, $pad, ...$opc ){
    let $_=[], $={};
    $.opc_ini = $opc.includes('ini');
    $.val_uni = !Array.isArray($ele);
    // recibo 1 o muchos    
    api_lis.val_ite($ele).forEach( $ele => {
      if( typeof($ele) == 'string' ){
        $_.push( ...api_ele.val_cod($ele,'nod') );
      }else{
        $_.push( $ele );
      }
    });
    // agrego o modifico  
    if( typeof($pad)=='string' ) $pad = document.querySelector($pad);

    if( $pad ){      
      $_.forEach( $ele => {
        if( $.opc_ini && $pad.children[0] ){
          $pad.insertBefore( $ele, $pad.children[0] );
        }else{
          $pad.appendChild( $ele );
        }
      });

    }
    return ( $.val_uni && $_[0] ) ? $_[0] : $_;
  }
  // modifico nodo : si no encuentro anterior, puedo agregar
  mod( $ele, $mod = {}, ...$opc ){
    let $_={},$={};
    $.opc_agr = !$opc.includes('-agr');
    // aseguro valor
    $.eti = api_ele.val_cod($ele);
    if( $.eti.nodeName ){
      if( $mod.nodeName ) $mod.parentElement.replaceChild( $.eti, $mod );
    }
    return $_;
  }
  // elimino nodo/s : todos o por seleccion, y los devuelvo
  eli( $pad, $nod ){
    let $_ = [];
    // elimino todos
    if( $nod === undefined ){
      $nod = $pad.children;
    }// por seleccion
    else if( typeof($nod)=='string' ){
      $nod = $pad.querySelectorAll($nod);
    }// por elemento
    else if( !$nod.nodeName ){
      $nod = false;
    }
    if( !!$nod ){
      api_lis.val_cod($nod).forEach( $ele => $_.push( $pad.removeChild($ele) ) );
    }
    return $_;
  }  

}

