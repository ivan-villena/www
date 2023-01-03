// Elementos
var $dom = {
  // página
  doc: {
    bot: 'body > .doc_bot',
    win: 'body > .doc_win',
    pan: 'body > .doc_pan',
    sec: 'body > .doc_sec',
    bar: 'body > .doc_bar',
    pie: 'body > .doc_pie'
  },
  // Estructura
  est: {
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
  },
  // Datos
  dat: {
    var: {}
  }
};

// documento 
for( const $atr in $dom.doc ){
  $dom.doc[$atr] = document.querySelector($dom.doc[$atr]);
}

// Estructura : listado + tablero
['lis','tab'].forEach( $ope => {

  for( const $ide in $dom.est[$ope] ){

    if( typeof($dom.est[$ope][$ide]) == 'string' ){

      $dom.est[$ope][$ide] = document.querySelector($dom.est[$ope][$ide]); 
    }
    else if( typeof($dom.est[$ope][$ide]) == 'object' ){

      for( const $i in $dom.est[$ope][$ide] ){
        $dom.est[$ope][$ide][$i] = document.querySelector($dom.est[$ope][$ide][$i]);
      }
    }
  }
});

$dom.est.tab.cla = ".pos.ope";

