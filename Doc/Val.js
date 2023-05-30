// WINDOW
'use strict';

class Doc_Val {

  /* imagen : .app_ima.$ide */
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    if( $dat[2] !== undefined ){
      $.ele = !!($dat[3]) ? $dat[3] : {};      
      $_ = Doc_Dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $.ele);
    }
    else{
      $ele = !!$dat[1] ? $dat[1] : {};
      $.tip = typeof($dat = $dat[0]);
      // por estilos : bkg
      if( $.tip == 'object' ){
        $ele = Ele.val_jun( $dat, $ele );
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `_img/${$dat}`;
        Ele.css( $ele, Ele.css_fon($.dir,{'tip':$.tip}) );
      }
      // etiqueta
      $.eti = 'span';
      if( !!$ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }// codifico botones
      if( $.eti == 'button' && !$ele['type'] ) $ele['type'] = "button";
      // aseguro identificador
      Ele.cla($ele,`val_ima`,'ini');
      // contenido 
      $.htm = '';
      if( !!($ele['htm']) ){
        Ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
        delete($ele['htm']);
      }
      $_ = `<${$.eti}${Ele.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
  static ima_lis( $arc, $pad ){
    let $={
      'lis':[]
    };  
    Obj.pos_ite($arc).forEach( $_arc => {
      if( typeof($_arc)=='object' ){
        $.ima = document.createElement('img');
        $.ima.src = URL.createObjectURL($_arc);
        $.lis.push($.ima);
      }
    });
    if( !!$pad && $pad.nodeName ){
      dom.eli($pad);
      dom.agr($.lis,$pad);
    }
    return $;
  }

  /* Icono : .val_ico.ide-$ide */
  static ico( $ide, $ele = {} ){

    let $_="<span class='val_ico'></span>", $ = {};

    $.ico = Dat._("sis.tex_ico");

    if( !!($.ico[$ide]) ){

      $.eti = 'span';
      if( $ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }

      if( $.eti == 'button' && !($ele['type']) ) $ele['type'] = "button"; 

      $_ = `
      <${$.eti}${Ele.atr(Ele.cla($ele,`val_ico ide-${$ide}`,'ini'))}>
        ${$.ico[$ide].val}
      </${$.eti}>`;
    }
    return $_;
  }  

  // letras : c - n
  static let( $dat, $ele={} ){

    let $_ = [], 
      $pal = [], 
      $let = [], 
      $num = 0, 
      $tex_let = Dat._("sis.tex_let")
    ;

    if( $dat !== null && $dat !== undefined && $dat !== NaN ){

      if( typeof($dat) != 'string' ) $dat = $dat.toString();

      $dat.split("\n").forEach( $pal_lis => {

        $pal_lis.split(' ').forEach( $pal_val => {
          
          $num = Num.val($pal_val);
          if( !!$num || $num == 0 ){
            $pal.push( `<n>${$pal_val}</n>` );
          }
          else{
            $let = [];
            $pal_val.split('').forEach( $car =>{
              $num = Num.val($car);
              if( !!$num || $num == 0 ){
                $let.push( `<n>${$car}</n>` );
              }
              else if( !!$tex_let[$car] ){
                $let.push( `<c>${$car}</c>` );
              }
              else{
                $let.push( $car );
              }
            });
            $pal.push( $let.join('') );
          }
        });
        $_.push($pal.join(' '));
      });
    }
    return $_.join('<br>');
  }

  /* Selector de Opciones */
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

    Doc_Val.opc_ite($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }// ...<options>
  static opc_ite( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ 
      $.obj_tip = Obj.tip($dat[$ide]); 
      break; 
    }
    $.obj_pos = ( $.obj_tip == 'pos' );

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

}