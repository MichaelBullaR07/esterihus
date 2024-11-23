--
-- Base de datos: `dbsame`
--

CREATE DATABASE IF NOT EXISTS `esterihus` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `esterihus`;

DROP TABLE IF EXISTS `ad_logs`;
CREATE TABLE `ad_logs` (
	`logdate` timestamp NOT NULL DEFAULT current_timestamp(),
	`logaction` varchar(100) NOT NULL,
	`logdetails` varchar(200) NOT NULL,
	`logtable` varchar(50) NOT NULL,
	`logstoredprocedure` varchar(50) NOT NULL,
	`logtrigger` varchar(50) NOT NULL,
	`logmodip` varchar(40) NOT NULL DEFAULT '::1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ad_tipodocumento`;
CREATE TABLE `ad_tipodocumento` (
	`tdocid` tinyint(4) NOT NULL,
	`tdocnom` varchar(4) NOT NULL,
	`tdocnomcom` varchar(40) NOT NULL,
	`tdocestado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ad_tipodocumento` (`tdocid`, `tdocnom`, `tdocnomcom`, `tdocestado`) VALUES
(1, 'NIT', 'Número de Identificación Tributaria', 1),
(2, 'CC', 'Cédula de ciudadania', 1),
(3, 'CE', 'Cédula extranjera', 1);

ALTER TABLE `ad_tipodocumento` ADD PRIMARY KEY (`tdocid`);
ALTER TABLE `ad_tipodocumento` MODIFY `tdocid` tinyint(4) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ad_rol`;
CREATE TABLE `ad_rol` (
  `rolid` tinyint(4) NOT NULL,
  `rolnombre` varchar(30) NOT NULL,
  `rolusuario1` varchar(40) DEFAULT NULL,
  `rolfechareg` timestamp NULL DEFAULT current_timestamp(),
  `rolestado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ad_rol` (`rolid`, `rolnombre`, `rolusuario1`, `rolfechareg`, `rolestado`) VALUES
(1, 'Administrador', 'admin', current_timestamp(), 1),
(2, 'Supervisor', 'admin', current_timestamp(), 1),
(3, 'Profesional', 'admin', current_timestamp(), 1),
(4, 'Tecnologo', 'admin', current_timestamp(), 1),
(5, 'Auxiliar', 'admin', current_timestamp(), 1);

ALTER TABLE `ad_rol` ADD PRIMARY KEY (`rolid`);
ALTER TABLE `ad_rol` MODIFY `rolid` int(4) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ad_permiso`;
CREATE TABLE `ad_permiso` (
  `permid` int(11) NOT NULL,
  `permnom` varchar(30) NOT NULL,
  `perestado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ad_permiso` (`permid`, `permnom`, `perestado`) VALUES
(1, 'Dashboard', 1),
(2, 'Registro', 1),
(3, 'Control', 1),
(4, 'Administracion', 1),
(5, 'Informe', 1),
(6, 'Personal', 1),
(7, 'Seguridad', 1);

ALTER TABLE `ad_permiso` ADD PRIMARY KEY (`permid`);
ALTER TABLE `ad_permiso` MODIFY `permid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ad_usuario`;
CREATE TABLE `ad_usuario` (
  `usuid` int(11) NOT NULL,
  `usuprinom` varchar(40) NOT NULL,
  `ususegnom` varchar(40) DEFAULT NULL,
  `usupriape` varchar(40) NOT NULL,
  `ususegape` varchar(40) DEFAULT NULL,
  `usutdocid` tinyint(4) NOT NULL,
  `usunumdoc` varchar(20) NOT NULL,
  `usurol` int(11) NOT NULL,
  `usutelefono` varchar(13) DEFAULT NULL,
  `usuemail` varchar(50) DEFAULT NULL,
  `usulogin` varchar(40) NOT NULL,
  `usuclave` varchar(64) NOT NULL,
  `usuimg` varchar(50) DEFAULT NULL,
  `usuregusuarioid` int(11) NOT NULL,
  `usuregusuario` varchar(50) DEFAULT NULL,
  `usuregfecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `usumodusuarioid` int(11) DEFAULT NULL,
  `usumodusuario` varchar(50) DEFAULT NULL,
  `usumodfecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `usumodip` varchar(40) DEFAULT NULL,
  `usuestado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ad_usuario` (`usuid`, `usuprinom`, `ususegnom`, `usupriape`, `ususegape`, `usutdocid`, `usunumdoc`, `usurol`, `usutelefono`, `usuemail`, `usulogin`, `usuclave`, `usuimg`, `usuregusuario`, `usuregfecha`, `usumodusuario`, `usumodfecha`, `usumodip`, `usuestado`) VALUES
(1, 'Usuario', '', 'Administrador', '', 1, '900006037', 1, 'Extensión 158', 'soportetecnologia@hus.gov.co', 'admin', 'a4cc13d36ce223789e68491c502c9e3ee3e83c753b62f1bbac5b73d2e9a365fe', '1712845679.png', 1, current_timestamp(), NULL, current_timestamp(), NULL, 1);

ALTER TABLE `ad_usuario`
	ADD PRIMARY KEY (`usuid`),
	ADD UNIQUE KEY `login_UNIQUE` (`usulogin`),
	ADD KEY `fk_usuario_rol` (`usurol`),
	ADD KEY `fk_usuario_tdoc` (`usutdocid`),
	ADD KEY `fk_usuario_usureg` (`usuregusuarioid`),
	ADD KEY `fk_usuario_usumod` (`usumodusuarioid`);
ALTER TABLE `ad_usuario` MODIFY `usuid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ad_usuario`
	ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`usurol`) REFERENCES `ad_rol` (`rolid`);
ALTER TABLE `ad_usuario`
	ADD CONSTRAINT `fk_usuario_tdoc` FOREIGN KEY (`usutdocid`) REFERENCES `ad_tipodocumento` (`tdocid`);
-- ALTER TABLE `ad_usuario`
	-- ADD CONSTRAINT `fk_usuario_usureg` FOREIGN KEY (`usuregusuarioid`) REFERENCES `ad_usuario` (`usuid`);
-- ALTER TABLE `ad_usuario`
	-- ADD CONSTRAINT `fk_usuario_usumod` FOREIGN KEY (`usumodusuarioid`) REFERENCES `ad_usuario` (`usuid`);

DROP TABLE IF EXISTS `ad_usuario_traza`;
CREATE TABLE `ad_usuario_traza`(
  usutrazaid INT(13) NOT NULL,
  usutrazausuarioid INT(11) NOT NULL,
  usutrazadatosantiguos TEXT NOT NULL,
  usutrazadatosnuevos TEXT NOT NULL,
  usutrazafecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  usutrazaip VARCHAR(40) NOT NULL,
  usutrazaaccion VARCHAR(50) NOT NULL,
  usutrazaestado TINYINT(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ad_usuario_traza`
	ADD PRIMARY KEY (`usutrazaid`);
ALTER TABLE `ad_usuario_traza` MODIFY `usutrazaid` int(13) NOT NULL AUTO_INCREMENT;


DROP TABLE IF EXISTS `ad_usuario_permiso`;
CREATE TABLE `ad_usuario_permiso` (
  `idusuperm` int(12) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ad_usuario_permiso` (`idusuperm`, `idusuario`, `idpermiso`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7);

ALTER TABLE `ad_usuario_permiso`
  ADD PRIMARY KEY (`idusuperm`),
  ADD KEY `fk_usuperm_usuario` (`idusuario`),
  ADD KEY `fk_usuperm_permiso` (`idpermiso`);
  
ALTER TABLE `ad_usuario_permiso`
  MODIFY `idusuperm` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
  
ALTER TABLE `ad_usuario_permiso`
	ADD CONSTRAINT `fk_usuperm_usuario` FOREIGN KEY (`idusuario`) REFERENCES `ad_usuario` (`usuid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `ad_usuario_permiso`
	ADD CONSTRAINT `fk_usuperm_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `ad_permiso` (`permid`) ON DELETE NO ACTION ON UPDATE NO ACTION;