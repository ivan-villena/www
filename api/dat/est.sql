-- Active: 1670107173962@@127.0.0.1@3306@c1461857_api
--
-- Calendario
  DELETE FROM `dat_est` WHERE `esq`='fec';
-- Holon
  DELETE FROM `dat_est` WHERE `esq`='hol';
--
  UPDATE `dat_est` SET ope = REPLACE(ope,'localhost','www.icpv.com.ar');