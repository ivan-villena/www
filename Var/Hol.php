<?php

class Hol {

  // busco valores por ciclo
  static function val( mixed $val ) : array | object | string {
    $_=[];
    
    // del sincronario
    if( is_string($val) && preg_match("/\./",$val) ){
        // busco año          
        if( $fec = Hol::val_cod($val) ){

          // convierto fecha
          $_ = Hol::val($fec);

          if( is_string($_) ){ 

            $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
          }
        }
        else{ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
        }
    }
    // del calendario
    elseif( $fec = Fec::dat($val) ){      

      // giro lunar => mes + día
      if( !empty($Psi = Dat::get( Dat::_('hol.psi'), [ 

          'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 
          'opc'=>[ 'uni' ]
        ])) 
      ){

        // cargo fecha con anillo solar y codigo ns
        $_ = Hol::val_dec( $fec );                

        // codifico clave
        $_['ani'] = Num::val($_['ani'],2);

        $_['lun'] = $Psi->lun;

        $_['dia'] = $Psi->lun_dia;

        $_['psi'] = $Psi->ide;

        $_['ani_fam']['cod'] = $Psi->fec_cod;
        
        // giro galáctico => kin
        if( is_object($Kin = Dat::_('hol.kin', Num::ran( $_['ani_fam'][2] + $Psi->fec_cod + $_['Fec']->dia, 260 ) )) ){

          $_['kin'] = $Kin->ide;

          $_['val'] = implode('.',[ $_['sir'], $_['ani'], $_['lun'], $_['dia'] ]);
        }
        else{
          $_ = '{-_-} Error de Cálculo con el Kin...'; 
        }
      }
      else{ 
        $_ = '{-_-} Error de Cálculo con el Psi-cronos...'; 
      }
    }// error
    else{
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }

    return $_;
  }
  
  // convierto NS => d/m/a
  static function val_cod( array | string $val ) : bool | string {

    $_ = $val;

    if( is_string($val) ) $val = explode('.',$val);

    if( isset($val[3]) ){

      $sir = intval($val[0]);
      $ani = intval($val[1]);
      $lun = intval($val[2]);
      $dia = intval($val[3]);

      // mes y día
      $Psi = Dat::get( Dat::_('hol.psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);      
  
      if( isset($Psi->fec_mes) && isset($Psi->fec_dia) ){

        $_ = $Psi->fec_mes.'/'.$Psi->fec_dia;
      
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
  }// convierto d/m/a => NS
  static function val_dec( mixed $val ) : array | string {  

    $_ = [];

    if( !empty( $Fec = !is_object($val) ? Fec::dat($val) : $val ) ){
      
      $_['Fec'] = $Fec;
      $_['fec'] = $Fec->val;

      // Datos iniciales de la base: SE TOMA COMO PUNTO DE REFERENCIA EL AÑO 26/07/1987
      $año = 1987;
      $_['sir'] = 1;
      $_['ani'] = 0; 
      $_['ani_fam'] = [ 2 => 87, 3 => 38, 4 => 34 ];

      // anterior a 1987
      if ( $año < $Fec->año ){

        while( $año < $Fec->año ){ 
          
          // sumo un año
          $año++;
          $_['ani']++;
          $_['ani_fam'][2] = Num::ran($_['ani_fam'][2]+105, 260);
          $_['ani_fam'][3] = Num::ran($_['ani_fam'][3]+105, 260);
          $_['ani_fam'][4] = Num::ran($_['ani_fam'][4]+105, 260);          
          
          // ajusto ciclo
          if ( $_['ani'] > 51 ){ 
            $_['ani'] = 0;
            $_['sir']++;
          }
        }
      }
      // posterior a 1987
      elseif( $año > $Fec->año ){
        
        $_['sir'] = 0;

        while( $Fec->año < $año ){ 
          // resto un año
          $año--;           
          $_['ani']--;
          $_['ani_fam'][2] = Num::ran($_['ani_fam'][2]-105, 260);
          $_['ani_fam'][3] = Num::ran($_['ani_fam'][3]-105, 260);
          $_['ani_fam'][4] = Num::ran($_['ani_fam'][4]-105, 260);   

          // ajusto ciclo
          if ( $_['ani'] < 0 ){ 
            $_['ani'] = 51; 
            $_['sir']--; 
          }
        }
        
        // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
        if( $_['sir'] == 0 ) $_['sir'] = -1;

      }

      // ajusto año por dia fuera del tiempo
      if( $Fec->dia <= 25 && $Fec->mes <= 7 ){
        
        $_['ani']--;
        $_['ani_fam'][3] = Num::ran($_['ani_fam'][3]-105, 260);
        $_['ani_fam'][4] = Num::ran($_['ani_fam'][4]-105, 260);
      }
      
    }
    else{
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }    

    return $_;
  }// sumo o resto dias de un fecha dada
  static function val_ope( mixed $val, int $cue = 1, string $tip = "dia" ) : mixed {
    $_ = "";

    // proceso fecha del sincronario
    $Cic = is_array($val) ? $val : Hol::val( $val );
    
    // calculo valores
    if( isset($Cic['val']) ){
    
      $sir = intval($Cic['sir']);
      $ani = intval($Cic['ani']);
      $lun = intval($Cic['lun']);
      $dia = intval($Cic['dia']);

      switch( $tip ){
      case 'sir':
        if( $tip == '+' ){

          $sir += $cue;
        }
        elseif( $tip == '-' ){

          $sir -= $cue;
        }
        if( $sir == 0 ) $sir = -1;

        break;
      case 'ani': 
        if( $tip == '+' ){

          $ani += $cue;

          if( $ani > 51 ){
            $sir += Num::val_red($ani / 51);
            $ani = Num::ran($ani, 51, 0);
          }
        }
        elseif( $tip == '-' ){

          $ani -= $cue;

          if( $ani < 0 ){
            $sir -= Num::val_red($ani / 51);
            $ani = Num::ran($ani, 51, 0);
          }
        }
        break;
      case 'lun': 
          if( $tip == '+' ){
  
            $lun += $cue;
              
            if( $lun > 13 ){
              $ani += Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani > 51 ){  
                $sir += Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);                
              }
            }
          }
          elseif( $tip == '-' ){
  
            $lun -= $cue;
              
            if( $lun < 1 ){  
              $ani -= Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani < 0 ){
                $sir -= Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);
              }
            }
          }        
          break;
        
      case 'dia':
        if( $tip == '+' ){

          $dia += $cue;        
  
          if( $dia > 28 ){
            $lun += Num::val_red($cue / 28);
            $dia = Num::ran($dia, 28);
            
            if( $lun > 13 ){
              $ani += Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani > 51 ){
                $sir += Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);
              }
            }
          }
        }
        elseif( $tip == '-' ){
  
          $dia -= $cue;        
  
          if( $dia < 1 ){
            $lun -= Num::val_red($cue / 28);
            $dia = Num::ran($dia, 28);
            
            if( $lun < 1 ){    
              $ani -= Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani < 0 ){    
                $sir -= Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);
              }
            }
          }
        }        
        break;
      }

      $_ = "$sir.".Num::val($ani,2).".".Num::val($lun,2).".".Num::val($dia,2);
    }

    return $_;
  }

}