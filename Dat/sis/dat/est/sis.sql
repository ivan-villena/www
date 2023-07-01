-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api

  -- Usuario
  DELETE FROM `sis-dat_est` WHERE `esq` = 'sis' AND `ide` LIKE 'usu%'; INSERT INTO `sis-dat_est` VALUES
  ( 'sis', 'usu', '{

      "atr": {
        "key": { "val_ocu":1 },
        "mai": { "type":"email" },
        "pas": { "type":"password" },
        "ubi": { "val_ope":0, "val_ocu":1 }
      }
  }');
--

