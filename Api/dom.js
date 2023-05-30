
class dom {

  // operaciones
  static ope( $val, $ope, ...$opc ){

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
    if( $ope ){
      $.res = [];
      Obj.pos_ite($ope).forEach( $ope_ite => $.res.push( Ele.act( $ope_ite, $val, ...$opc ) ) );
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
        $_.push( ...dom.val($ele,'nod') );
      }else{
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
    $.eti = dom.val($ele);
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