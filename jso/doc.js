// WINDOW
'use strict';

// operativas
const DIS_OCU = "dis-ocu";
const FON_SEL = "fon-sel";
const BOR_SEL = "bor-sel";


// documento
class _doc {

  // telcas : escape
  static inp( $dat ){
    let $={};

    switch( $dat.keyCode ){
    // Escape => ocultar modales
    case 27: 
      // menú contextual
      if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
        $.men.classList.add(DIS_OCU);
      }// pantallas
      else if( $.win = document.querySelector(`#win:not(.${DIS_OCU}) article header .ico[ide="eje_fin"]`) ){ 
        _app_ope.bot('win');
      }// navegacion
      else if( $.nav = document.querySelector(`aside.nav > [ide]:not(.${DIS_OCU})`) ){ 
        _app_ope.bot('nav');
      }
      break;
    }
  }

  // iconos
  static ico( $ide, $ele={} ){
    let $_="", $={
      '_ico':$_api._('ico')
    };
    if( !!($._ico[$ide]) ){
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }
      $ele['ide'] = $ide;
      $htm = $._ico[$ide].val;
      $_ = `
      <${$ele['eti']}${_htm.atr(_ele.cla($ele,"ico material-icons-outlined",'ini'))}>
        ${$htm}
      </${$ele['eti']}>`;
    }
    return $_;
  }

  // letras : c - n
  static let( $dat, $ele={} ){

    let $_="", $pal, $_pal=[], $let=[], $_let = $_api._('let'), $num = 0;
    
    if( $dat !== null && $dat !== undefined && $dat !== NaN ){      

      $dat.toString().split(' ').forEach( $pal => {
        
        $num = _num.val($pal);
        if( !!$num || $num == 0 ){
          if( /,/.test($pal) ) $pal.replaceAll(",","<c>,</c>");
          if( /\./.test($pal) ) $pal.replaceAll(".","<c>.</c>");
          $_pal.push( `<n>${$pal}</n>` );
        }
        else{
          $let = [];
          $pal.split('').forEach( $car =>{
            $ele = $ele;
            $num = _num.val($car);
            if( !!$num || $num == 0 ){
              $let.push( `<n>${$car}</n>` );
            }
            else if( $_let[$car] ){
              $let.push( `<c${_htm.atr(_ele.cla($ele,`${$_let[$car].tip} _${$_let[$car].cla}`,'ini'))}>${$car}</c>` );
            }
            else{
              $let.push( $car );
            }
          });
          $_pal.push( $let.join('') );
        }
      });
      $_ = $_pal.join(' ');
    }
    return $_;
  }

  // imagen : (span,button)[ima]
  static ima( ...$dat ){  
    let $_="",$={},$ele={};

    if( $dat[2] !== undefined ){

      if( /_ide$/.test($dat[1]) ){ $dat[1] = $dat[1].replace(/_ide$/,''); }

      $dat[3] = !!($dat[3]) ? $dat[3] : {};
      $dat[3]['ima'] = `${$dat[0]}.${$dat[1]}`;

      $_ = _app_dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $dat[3]);
    }
    else{
      $ele = $dat[1] ? $dat[1] : {};
      $dat = $dat[0];
      $.tip = typeof($dat);
      $.fic_ide = '';
      if( $.tip == 'object' ){
    
        $ele = _ele.val( $dat, $ele );
      }
      else if( $.tip == 'string' ){
    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `_/${$dat}/ima`;
        $.fic_ide = $.dir+"."+$.tip;
        _ele.css( $ele, _ele.fon($.dir,{'tip':$.tip}) );
      }
    
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }      
      $.eti = $ele['eti'];
      $.htm='';
      if( !!($ele['htm']) ){
        _ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
      }
      if( !$ele['ima'] && $.fic_ide ){ $ele['ima'] = $.fic_ide; }
      $_ = `<${$.eti}${_htm.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
  
  // variable : div.atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};
    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $_app.var = _ele.ver($dat,{'eti':'form'});
      $.var_ide = '';
      if( $dat.name ){
        $.var_ide = $dat.name;
      }
    }else{
      switch( $tip ){
      case 'mar':
        if( $ope ){
          $dat.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $dat.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $dat.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $dat && $e.classList.remove($ope) );
          $dat.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }

  // pestañas por contenido
  static nav( $dat, $ope, ...$opc ){
    let $={};
    // capturo contenedor
    $.nav = $dat.parentElement;
    // con toggles
    $.val_tog = $opc.includes('tog');
    // seleccion anterior
    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){
      // capturo contenido
      $.lis = $.nav.nextElementSibling;
      // recorro items
      _lis.val( $.lis.children ).forEach( $e => {
        // coincide con el seleccionado
        if( $e.dataset.ide == $ope ){
          // hago toogles
          if( $.val_tog ){
            
            $e.classList.toggle(DIS_OCU);
            $dat.classList.toggle(FON_SEL);
          }
          // muestro y selecciono
          else if( $e.classList.contains(DIS_OCU) ){

            $e.classList.remove(DIS_OCU);
            $dat.classList.add(FON_SEL);
          }              
        }// oculto los no coincidentes
        else if( !$e.classList.contains(DIS_OCU) ){ 

          $e.classList.add(DIS_OCU); 
        }
      });
    }      
  }
}

// contenido
class _doc_val {

  // visibilidad de bloques
  static tog( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){

      $.ite = $dat.parentElement;

      $.bot = $.ite.querySelector('.ico[ide="ope_tog"]');

      $.sec = $.ite.nextElementSibling;
      
      if( $.bot.classList.contains('ocu') ){
        $.bot.classList.remove('ocu');
        $.sec.classList.remove(DIS_OCU);
      }
      else{
        $.bot.classList.add('ocu');
        $.sec.classList.add(DIS_OCU);
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $_app.var = _ele.ver($dat,{'eti':"form"}) ){        

        $.lis = !!$_app.var.nextElementSibling ? $_app.var.nextElementSibling : $_app.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .ico[ide="ope_tog"]${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  // filtros : texto
  static ver(){    
  }

}

// Lista
class _doc_lis {
  
  // opciones
  static opc( $dat, $ope, ...$opc ){
    let $_, $={};

    if( $ope && !$ope.nodeName ){
      $.opc = document.createElement('select');
      for( const $atr in $ope ){ $.opc.setAttribute($atr,$_[$atr]); }
      $_ = $.opc;
    }else{
      $_ = $ope;
    }
    $.val = '';
    if( $ope.value ){
      $.val = $ope.value;
    }else if( $ope.val ){
      $.val = $ope.val;
    }

    _doc_lis.opc_ite($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }
  static opc_ite( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ $.obj_tip = _obj.tip($dat[$ide]); break; }
    $.obj_pos = $.obj_tip == 'pos';

    for( const $ide in $dat ){ const $ite = $dat[$ide];

      $.ite = document.createElement('option');
      $.val = $ide;
      $.htm = "";
      if( !$.obj_tip ){        
        $.htm = $ite;
      }
      else if( $.obj_pos ){        
        $.htm = $ite.join(', ');
      }
      else{
        // valor
        if( $ite.ide ){ $.val = $ite.ide; }else if( $ite.pos ){ $.val = $ite.pos; }
        // titulo
        if( !!$ite.des || !!$ite.tit ) $.ite.setAttribute('title', !! $ite.des ? $ite.des : $ite.tit );
        // contenido        
        if( !!$.val_ide && !!$ite.ide ) $.htm += `${$ite.ide}: `;
        if( !!$ite.nom ) $.htm += $ite.nom;
      }      
      $.ite.setAttribute('value',$.val);
      if( $val == $.val ) $.ite.setAttribute('selected',"");
      $.ite.innerHTML = $.htm;
      $_.push($.ite);
    }
    return $_;
  }

  // punteos, numeraciones, y términos
  static ite(){
  }
  static ite_val( $dat, $ope ){
    let $=_doc.var($dat);
    
    if( !$ope ){
      // toggles
      if( $dat.nodeName == 'DT' ){

        $.dd = $dat.nextElementSibling;

        while( $.dd && $.dd.nodeName == 'DD' ){
          $.dd.classList.toggle(DIS_OCU);
          $.dd = $.dd.nextElementSibling;
        }
      }
    }else{
      switch( $ope ){

      }
    }
  }
  static ite_tog( $dat, $ope ){

    let $=_doc.var($dat);

    if( !$dat || !$ope ){
      _ele.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      _lis.val($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              _doc_lis.ite_val($ite);
            }
          }
        }
      } );
    }
  }  
  static ite_ver( $dat, $ope ){
    let $=_doc.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $_app.var.querySelector('[name="val"]').value ){
        // oculto todos
        _ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $_app.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = _val.ver($e.innerHTML,$.ope,$.val) ){
              $e.classList.remove(DIS_OCU);
            }else{
              $e.classList.add(DIS_OCU);                 
            }
            $.dd = $e.nextElementSibling;
            while( $.dd && $.dd.nodeName == 'DD' ){
              if( $.ope_val ){ 
                $.dd.classList.remove(DIS_OCU); 
              }else{ 
                $.dd.classList.add(DIS_OCU); 
              }
              $.dd = $.dd.nextElementSibling;
            }
          });
        }
        else{
          _lis.val($.lis.children).forEach( $e => 
            _val.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        _ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': _ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': _ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $_app.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  // valores con titulo
  static val(){      
  }
  static val_ver( $dat, $ope = 'p:first-of-type', $cla = 'let-luz' ){

    let $=_doc.var($dat);

    // busco listado
    if( $_app.var ){
      $.lis = !! $_app.var.nextElementSibling ? $_app.var.nextElementSibling : $_app.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }

    // ejecuto filtros
    if( $.lis && ( $.ope = $_app.var.querySelector(`[name="ope"]`) ) && ( $.val = $_app.var.querySelector(`[name="val"]`) ) ){
      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.ite ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.ite ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = _ele.ver($ite,{'eti':'li'});

        // ejecuto comparacion por elemento selector ( p / a ), y oculto/mustro item
        if( !$.val.value || _val.ver($ite.innerText, $.ope.value, $.val.value) ){
          $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
          !!$.val.value && $ite.classList.add($cla);// agrego brillo
        }
        else if( !$.ite.classList.contains(DIS_OCU) ){
          $.ite.classList.add(DIS_OCU);
        }
      } );      
      // por cada item mostrado, muestro ascendentes
      $.tot = 0;
      if( $.val.value ){
        $.lis.querySelectorAll(`li.ite:not(.${DIS_OCU})`).forEach( $ite => {
          $.tot ++;
          $.val = $ite;
          while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('ite') ){
            $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
            $.val = $.ite;
          }
        } );
        $.tog = ['ver','tod'];
      }
      else{
        $.tog = ['ocu','nad'];
      }
      
      // actualizo toggle
      if( $.tog[1] && ( $.ico = $_app.var.querySelector(`.ico[ide="ope_${$.tog[1]}"]`) ) ){
        
        _doc_val.tog($.ico,$.tog[1]);
      }            
      
      // actualizo total
      if( $.tot_val = $_app.var.querySelector(`[name="tot"]`) ){

        $.tot_val.innerHTML = $.tot;
      }            
    }      
  }

  // indice : enlaces del navegador
  static nav(){      
  }
  // hago toogle por item
  static nav_tog( $dat ){
    let $={ ide : location.toString().split('#') };

    if( $dat && $.ide[1] ){

      if( $.nav = _doc_lis.nav_mar($dat) ){

        // hago toogles ascendentes
        while( 
          ( $.lis = $.nav.parentElement.parentElement.parentElement ) 
          && ( $.val = $.lis.previousElementSibling ) && ( $.val.nodeName == 'DIV' &&  $.val.classList.contains('val') )
          && ( $.nav = $.val.querySelector('a[href^="#"]') )
        ){
          if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('ico') ){                
            _doc_val.tog($.ico);
          }                
        }
      }
    }
  }
  // click en item
  static nav_val( $dat, $cla = FON_SEL ){
    let $ = {
      nav : _ele.ver($dat,{'eti':'nav'})
    };
    // elimino marcas previas
    $.nav && $.nav.querySelectorAll(`.val.${$cla}`).forEach( $e => $e.classList.remove($cla) );

    // controlo el toggle automatico por dependencias
    if( 
      ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
      &&
      ( $dat.classList.contains('ico') || $.dep.classList.contains(DIS_OCU) ) 
    ){
      _doc_val.tog($dat);
    }

    // pinto fondo
    if( !( $.bot = $dat.parentElement.querySelector('.ico') ) || !$.bot.classList.contains('ocu') ){

      $dat.parentElement.classList.add($cla);
    }

  }
  // marco valor seleccionado
  static nav_mar( $dat ){
    let $={
      ide : location.toString().split('#')
    };

    // hago toogle por item
    if( $.nav = $dat.querySelector(`a[href="#${$.ide[1]}"]`) ){
        
      _doc_lis.nav_val($.nav);
    }

    return $.nav;
  }
  // ejecuto filtros
  static nav_ver( $dat, $ope = 'a[href]' ){
    let $=_doc.var($dat);

    // ejecuto filtros
    _doc_lis.val_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    _doc_lis.nav_tog($_app.var.nextElementSibling);

  }  

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $=_doc.var($dat);

    if( $tip == 'val' ){

      $.lis = $_app.var.previousElementSibling;

      $.val = $_app.var.querySelector('[name="val"]');
      $.pos = _num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = _num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > _num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < _num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = _num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      _ele.act('cla_agr',$.lis.querySelectorAll(`li[pos]:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li[pos="${$.pos}"]`) ) $.ite.classList.remove(DIS_OCU);
    }
  }

  // tabla
  static tab( $dat, $ele, ...$opc ){
    let $ = {};

    // 1- cabecera
    $.tab_cab = document.createElement('thead');
    $.cab_lis = document.createElement('tr');

    if( $dat[0] ){
      for( const $atr in $dat[0] ){
        $.cab_ide = document.createElement('th');
        $.cab_ide.innerHTML = $atr;
        $.cab_lis.appendChild($.cab_ide);
      }
      $.tab_cab.appendChild($.cab_lis);
    }

    // 2-cuerpo
    $.tab_dat = document.createElement('tbody');
    $dat.forEach( $dat => {
      $.lis = document.createElement('tr');
      for( const $atr in $dat ){
        $.dat_ite = document.createElement('td');
        $.dat_ite.innerHTML = $dat[$atr];
        $.lis.appendChild($.dat_ite);
      }
      $.tab_dat.appendChild($.lis);
    });
    // 3-pie
    // ...

    $.tab = document.createElement('table');
    $.tab.appendChild($.tab_cab);
    $.tab.appendChild($.tab_dat);

    return $.tab;
  } 
}

// Opcion
class _doc_opc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $={};

    switch( $tip ){
    case 'val': 
      if( !$ope.nodeName ){
        $.opc = document.createElement('select');
        for( const $atr in $ope ){
          $.opc.setAttribute($atr,$ope[$atr]);
        }
        $ope = $.opc;
      }
      if( $opc.includes('nad') ){
        $.ite = document.createElement('option');
        $.ite.value = ''; $.ite.innerHTML = '{-_-}';
        $ope.appendChild($.ite);
      }
      $.val_ide = $opc.includes('ide');
      $dat.forEach( ($dat,$ite) => {
        $.ite = document.createElement('option');
        // identificador
        if( $dat.ide ){ $.ide = $dat.ide; }else if( $dat.pos ){ $.ide = $dat.pos; }else{ $.ide = $ite; }
        // valor
        $.ite.setAttribute('value',$.ide);
        // titulo
        if( !!$dat.des || !!$dat.tit ) $.ite.setAttribute('title', !! $dat.des ? $dat.des : $dat.tit );
        // contenido
        $.htm = "";
        if( !!$.val_ide && !!$dat.ide ){ $.htm += `${$dat.ide}: `; }
        if( !!$dat.nom ){ $.htm += $dat.nom; }
        $.ite.innerHTML = $.htm;
        $ope.appendChild($.ite);
      });
      $_ = $ope;
      break;
    }
    return $_;      
  }
}

// Número
class _doc_num {
  
  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  // al actualizar
  static act( $dat ){

    let $={};

    $.val = _num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = _num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = _num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = _num.val($.val,$.num_cue);

      // actualizo valores por rango
      if( $dat.type == 'range' ){

        if( $.val = $dat.parentElement.nextElementSibling ) $.val.innerHTML = $.num_pad ? $.num_pad : $dat.value;
      }
      // por entero o decimales
      else{

        if( $.num_pad ) $dat.value = $.num_pad;
      }      
    }
  }
}

// Fecha
class _doc_fec {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}

// Texto
class _doc_tex {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}

// Archivo
class _doc_arc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}

// Objeto
class _doc_obj {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = _val.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += _doc.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }
  // 
}

// Ejecucion
class _doc_eje {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}

// Elemento
class _doc_ele {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    case 'her':
      $.her = [];
      $.ele = $dat; 
      while( $.ele ){ 
        $.nom = $.ele.nodeName.toLowerCase();
        if( $.nom=='#document' || $.nom=='html' ){
          break;
        }
        $.tex=`<font class='ide'>${$.nom}</font>`; 
        if( $.ele.id ){ 
          $.tex += `<c>#</c><font class='val'>${$.ele.id}</font>`; 
        }
        else if( $.ele.name ){ 
          $.tex += `<c>[</c><font class='val'>${$.ele.name}</font><c>]</c>`; 
        }
        if( $.ele.className){
          $.cla_lis = $.ele.className.split(' ').map( $ite => `<c>.</c><font class='val'>${$ite}</font>` );
          $.tex += `${$.cla_lis.join('')}`;
        }
        $.her.push($.tex);
        $.ele = ( $.ele.parentNode) ? $.ele.parentNode : false;
      }
      $_ = $.her.reverse().join(`<c class='sep'>></c>`);
      break;            
    case 'eti':
      $.nom = $dat.nodeName.toLowerCase();
      $.fin=`
      <c class='lis'><</font>
      <c>/</c>
      <font class='ide'>${$.nom}</font>
      <c class='lis'>></c>`;
      $.eti_htm=''; 
      if( $dat.childNodes.length>0 ){ 
        _lis.val($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${this.ele('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${_doc.let($e.textContent)}</li>`;
          }
        });
      }
      $.eti_atr=[];
      $dat.getAttributeNames().forEach( $atr => $.eti_atr.push(`
        <li class='mar_hor-1'>
          <font class='val'>${$atr}</font>
          <c class='sep'>=</c>
          <q>${$dat.getAttribute($atr)}</q>
        </li>`)
      );
      $_ =`
      <div var='ele_eti' class='dat'>

        <div class='ite ini'>

          <c class='lis sep'><</font>
          <font class='ide' onclick='_doc_ele.act(this,'ver');'>${$.nom}</font>
          <ul class='val _atr'>
            ${$.eti_atr.join('')}
          </ul>
          <c class='lis sep'>></c>          

          <p class='tog dis-ocu'>
            c onclick='_doc_ele.act(this,'ver');' class='tog dis-ocu'>...</>
            ${$.fin}
          </p>
        </div>`;
        if( !['input','img','br','hr'].includes($.nom) ){
          $_ += `
          <ul class='val _nod mar_hor-3'>
            ${$.eti_htm}
          </ul>

          <div class='ite tog fin'>
            ${$.fin}
          </div>  `;
        }$_ += `
      </div>`;
      break;
    }

    return $_;      
  }
  // ...
  static act( $dat ){
    let $={};

    $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )

    $dat.nextElementSibling.classList.add(FON_SEL);

    $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');
    // resultado
    $.res = $dat.parentElement.parentElement.previousElementSibling.querySelector('div.ele');

    _ele.eli($.res);

    $.res.innerHTML = _doc.ele('eti',document.querySelector($.ver));
  }
  // ...
  static val( $dat ){
    let $={};

    $.ope = document.createElement('form');

    $.lis = document.createElement('ul');

    $.lis.classList.add('lis');

    $dat.forEach( $e => {
      $.ite = document.createElement('li');            
      $.ite.classList.add('mar_ver-2');            
      $.ite.innerHTML = `${_doc.ico('val_ver',{'class':"tam-1 mar_der-1",'onclick':"_doc_ele.act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = _doc.ele('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });

    return [ $.ope, $.lis ];     
  }
}