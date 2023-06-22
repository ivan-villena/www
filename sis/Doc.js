// WINDOW
'use strict';

// Documento
class Doc {

  Uri = {};

  Ope = {};

  Dat = {};

  constructor( $Uri ){

    if( $Uri ) this.Uri = $Uri;

    // Operadores globales del Documento
    this.Ope = {
      bot: 'body > .ope_cab',
      win: 'body > .ope_win',
      pan: 'body > .ope_pan',
      sec: 'body > .ope_sec',
      bar: 'body > .ope_bar',
      pie: 'body > .ope_pie',
      var: null
    }

    // Operadores de datos
    this.Dat = {
      // operadores de valor
      val: {
        // acumulados
        acu : [ "pos", "mar", "ver", "atr" ],
        // filtros
        ver : {
          dat : `form.ide-dat select[name="val"]`,
          fec : `form.ide-fec input[name="ini"]`,
          pos : `form.ide-pos input[name="ini"]`
        }
      },
      // listado por tabla
      lis: {
        // Valores
        val : `.dat_lis`,
        val_acu : `.dat_lis .ide-ver .ide-acu`,
        val_sum : `.dat_lis .ide-ver .ide-sum`,
        val_cue : `.dat_lis .ide-ver .ide-cue`,
        // filtros
        ver : `.dat_lis .ide-ver`,
        // columnas
        atr : `.dat_lis .ide-atr`,
        // Descripciones
        des : `.dat_lis .ide-des`
      },
      // tablero
      tab: {
        ide : null,
        dep : null,
        cla : null,
        // Valores
        val : `.dat_tab`,
        val_acu : `.ide-val .ide-acu`,
        val_sum : `.ide-val .ide-sum`,
        val_cue : `.ide-val .ide-cue`,
        // Seleccion
        ver : `.ide-ver`,
        ver_dat : `.ide-ver form.ide-dat`,
        ver_pos : `.ide-ver form.ide-pos`,
        ver_fec : `.ide-ver form.ide-fec`,
        // atributos
        atr : `.ide-ver section.ide-atr`,
        // opciones:
        sec : `.ide-opc form.ide-sec`,    
        pos : `.ide-opc form.ide-pos`,
        est : `.ide-opc section.ide-est`
      }      
    }

    /* Cargo Eventos */
    
    // - 1: teclado
    document.onkeyup = Doc.htm_ope;

    // - 2: anulo formularios
    Doc.htm_dat();

    // 3- Cargo Operadores del Documento
    for( const $atr in this.Ope ){ 
      
      this.Ope[$atr] = document.querySelector(this.Ope[$atr]); 
    }
  }

  /* DOM */

  // imprimo y actualizo eventos
  static htm( $ele, $val ){

    if( $ele && $ele.nodeName && $val ){

      if( typeof($val) == 'string' ){
        
        $ele.innerHTML = $val;
      }
      else{

        Doc.agr( $val, $ele );
      }
      
      // actualizo eventos
      Doc.htm_dat(); 
    }

  }// teclado
  static htm_ope( $eve ){ 
    
    let $ = {};

    if( $eve.keyCode ){
      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // 1- opciones
        if( $.men = document.querySelector(`ul.ope_opc:not(.dis-ocu)`) ){
  
          $.men.classList.add("dis-ocu");
        }
        // 2- operador
        else if( $.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.dis-ocu)`) ){
  
          $.nav = $.ope.parentElement.previousElementSibling;
          if( $.ico = $.nav.querySelector(`.val_ico.fon-sel`) ) $.ico.click();
        }
        // 3- pantalla
        else if( document.querySelector(`body > .ope_win > :not(.dis-ocu)`) ){
          
          // oculto la ultima pantalla
          $.art = document.querySelector('body > .ope_win').children;
  
          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
  
            const $art = $.art[$ide];
  
            if( !$art.classList.contains("dis-ocu") ){
              Doc_Ope.win( $art.querySelector(`header:first-child .val_ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // 4- paneles
        else if( document.querySelector(`.ope_pan > [class*="ide-"]:not(.dis-ocu)`) ){ 
  
          Doc_Ope.pan();
        }
        break;
      }
    }    

  }// formularios
  static htm_dat( $eve ){

    if( !$eve ){
      // buscar todos los forms
      document.querySelectorAll('form').forEach( $eve => {

        // si no tiene evento submit, asociarlo
        Doc.act('eje_agr',$eve,'submit',Doc.htm_dat);
      });
    }
    // recibo un evento: evito submit
    else if( $eve.target  ){
      
      $eve.preventDefault();
    }
  }// actualizo pagina
  static htm_act(){

    window.location.href = window.location.href.split('#')[0];
  }
  
  // operaciones
  static ope( $val, $ele, ...$opc ){

    let $_ = false, $ = {};

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
    if( $ele ){
      $.res = [];
      Obj.pos_ite($ele).forEach( $ele_ite => $.res.push( Doc.act( $ele_ite, $val, ...$opc ) ) );
    }
    // resultados: [<>] => <> // si hay 1 solo, devuelvo único elemento
    $_ = Obj.pos($.res);
    if( !$_.length ){ 
      $_ = false; 
    }
    else if( $_.length == 1 ){ 
      $_ = $_[0]; 
    }
    return $_;
  }

  // actualizo propiedades
  static act( $tip, $ele, $val, $ope ){

    let $_ = [], $ = { tip : $tip.split('_')  };

    if( typeof($ele) == 'string' ) $ele = document.querySelectorAll($ele);

    $.lis = Obj.pos($ele);

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
      case 'eli': $.lis.forEach( $v => { Obj.pos($v.children).forEach( $v_2 => $_.push($v.removeChild($v_2)) ) } ); break;
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
        $.lis.forEach( $v => Obj.pos_ite($val).forEach( $val_cla => $_.push( $v.classList.add($val_cla) ) ) ); 
        break;
      case 'mod': 
        $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); 
        break;
      case 'eli': 
        $.lis.forEach( $v => Obj.pos_ite($val).forEach( $val_cla => $_.push( $v.classList.remove($val_cla) ) ) );
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
  // genero elemetno HTML : {} / "" => {dom}
  static val( $ele, ...$opc ){

    let $_ = false, $={ 'tip':typeof($ele) };
    
    // desde texto : <> 
    if( $.tip == 'string' ){
      
      $_ = document.createElement('span');
      $_.innerHTML = $ele;
      
      // devuelvo nodos: todos o el 1°
      if( $_.children[0] ){        

        $_ = $opc.includes('nod') ? Obj.pos($_.children) : $_.children[0];
      }
    }
    // desde 1 objeto : {}
    else if( $.tip == 'object' && !$ele.nodeName ){
      
      $.ele = Obj.val_dec($ele);
      
      // creo etiqueta
      $.eti = 'span';
      if( $.ele.eti ){
        $.eti = $.ele.eti;
        delete($.ele.eti);
      }
      $_ = document.createElement($.eti);

      // copio contenido : texto | 1-n elementos
      if( $.ele.htm ){
        if( typeof($.ele.htm)=='string' ){ 
          $.ele_doc = document.createElement('span');
          $.ele_doc.innerHTML = $.ele.htm; 
          $.ele.htm = $.ele_doc.children;
        }
        Obj.pos($.ele.htm).forEach( 
          $htm => $_.appendChild($htm)
        );
        delete($.ele.htm);
      }
      // copio atributos
      for( const $i in $val ){ 
        $_.setAttribute($i, $val[$i]); 
      }
    }

    return $_;
  }  
  // busco nodos
  static ver( $ele, $ope={} ){

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

      Obj.pos($ele.children).forEach( $ele => {

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
  static agr( $ele, $pad, ...$opc ){
    
    let $_=[], $={};
    
    $.opc_ini = $opc.includes('ini');
    
    $.val_uni = !Array.isArray($ele);
    
    // recibo 1 o muchos    
    Obj.pos_ite($ele).forEach( $ele => {
      
      if( typeof($ele) == 'string' ){

        $_.push( ...Doc.val($ele,'nod') );
      }
      else{
        $_.push( $ele );
      }
    });

    // agrego o modifico  
    if( typeof($pad)=='string' ) $pad = document.querySelector($pad);

    if( $pad ){

      $_.forEach( $ele => {

        // al inicio
        if( $.opc_ini && $pad.children[0] ){

          $pad.insertBefore( $ele, $pad.children[0] );
        }// antes de un hermano
        else if( $opc[0] && $opc[0].nodeName ){

          $pad.insertBefore( $ele, $opc[0] );
        }
        // al final
        else{

          $pad.appendChild( $ele );
        }
      });

    }
    return ( $.val_uni && $_[0] ) ? $_[0] : $_;
  }  
  // modifico nodo : si no encuentro anterior, puedo agregar
  static mod( $ele, $mod = {}, ...$opc ){
    let $_={},$={};
    $.opc_agr = !$opc.includes('-agr');
    // aseguro valor
    $.eti = Doc.val($ele);
    if( $.eti.nodeName ){
      if( $mod.nodeName ) $mod.parentElement.replaceChild( $.eti, $mod );
    }
    return $_;
  }
  // elimino nodo/s : todos o por seleccion, y los devuelvo
  static eli( $pad, $nod ){
    let $_ = [];

    // elimino todos
    if( $nod === undefined ){
      $nod = $pad.children;
    }
    // por seleccion
    else if( typeof($nod)=='string' ){
      $nod = $pad.querySelectorAll($nod);
    }
    // por elemento
    else if( !$nod.nodeName ){
      $nod = false;
    }

    if( !!$nod ){
      Obj.pos($nod).forEach( $ele => $_.push( $pad.removeChild($ele) ) );
    }
    return $_;
  }  

}