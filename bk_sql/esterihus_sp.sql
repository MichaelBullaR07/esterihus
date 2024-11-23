-- PROCEDIMIENTO ALMACENA QUE CREA UN REGISTRO EN LA TABLA DE ad_logs
DROP PROCEDURE IF EXISTS `sp_ad_logs_crear`;
DELIMITER $$
CREATE PROCEDURE `sp_ad_logs_crear`(
    IN in_logaction VARCHAR(100),
    IN in_logdetails VARCHAR(200),
    IN in_logtable VARCHAR(50),
    IN in_logstoredprocedure VARCHAR(50),
    IN in_logtrigger VARCHAR(50),
    IN in_logmodip VARCHAR(40)
)
BEGIN
    -- MANEJO DE ERROR CON TRY CATCH
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SELECT CONCAT('ERROR: ', @errno, ' - ', @text) AS error;
    END;
    -- INICIO DE TRANSACCION
    START TRANSACTION;
    -- INSERTAR REGISTRO
    INSERT INTO ad_logs (logdate, 
                        logaction, 
                        logdetails, 
                        logtable, 
                        logstoredprocedure, 
                        logtrigger, 
                        logmodip)
    VALUES (current_timestamp(),
            in_logaction,
            in_logdetails,
            in_logtable,
            in_logstoredprocedure,
            in_logtrigger,
            in_logmodip);
    -- FIN DE TRANSACCION
    COMMIT;
    SELECT 'OK' AS error;
END$$
DELIMITER ;

-- PROCEDIMIENTO ALMACENADO QUE CREA UN REGISTRO EN LA TABLA DE ad_usuario
DROP PROCEDURE IF EXISTS `sp_ad_usuario_crear`;
DELIMITER $$
CREATE PROCEDURE `sp_ad_usuario_crear`(
    IN in_usuprinom VARCHAR(40),
    IN in_ususegnom VARCHAR(40),
    IN in_usupriape VARCHAR(40),
    IN in_ususegape VARCHAR(40),
    IN in_usutdocid TINYINT,
    IN in_usunumdoc VARCHAR(20),
    IN in_usurol INT,
    IN in_usutelefono VARCHAR(13),
    IN in_usuemail VARCHAR(50),
    IN in_usulogin VARCHAR(40),
    IN in_usuclave VARCHAR(64),
    IN in_usuimg VARCHAR(50),
    IN in_usuregusuarioid int(11),
    IN in_usuregusuario int(11),
    IN in_usumodusuario VARCHAR(50),
    IN in_usumodip VARCHAR(40),
    IN out_usuid VARCHAR(40),
    OUT out_error VARCHAR(100)
)
BEGIN
    -- MANEJO DE ERROR CONTRY CATCH
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET out_error = CONCAT('ERROR: ', @errno, ' - ', @text);
    END;
    -- INICIO DE TRANSACCION
    START TRANSACTION;
    -- VERIFICA SI EL USUARIO EXISTE
    IF EXISTS(SELECT usuid FROM ad_usuario WHERE usulogin = in_usulogin) THEN
    SET out_error = 'ERROR: 1062 - El usuario ya existe';
    ROLLBACK;
    -- LLAMA AL PROCEDIMIENTO ALMACENADO QUE REGISTRA EL LOG
    CALL sp_ad_logs_crear('CREAR', 'El usuario ya existe', 'ad_usuario', 'sp_ad_usuario_crear', '', in_usumodip);
    LEAVE sp_ad_usuario_crear;
    END IF;
    -- INSERTAR REGISTRO
    INSERT INTO ad_usuario
        (usuprinom,
            ususegnom,
            usupriape,
            ususegape,
            usutdocid,
            usunumdoc,
            usurol,
            usutelefono,
            usuemail,
            usulogin,
            usuclave,
            usuimg,
            usuregusuarioid,
            usuregusuario,
            usumodusuario,
            usumodip)
    VALUES (in_usuprinom,
                in_ususegnom,
                in_usupriape,
                in_ususegape,
                in_usutdocid,
                in_usunumdoc,
                in_usurol,
                in_usutelefono,
                in_usuemail,
                in_usulogin,
                in_usuclave,
                in_usuimg,
                in_usuregusuarioid,
                in_usuregusuario,
                in_usumodusuario,
                in_usumodip);
    -- CAPTURA EL ID DEL USUARIO QUE SE ACABA DE CREAR
    SELECT LAST_INSERT_ID() INTO out_usuid;
    SET out_error = 'OK';
END$$
DELIMITER ;

-- PROCEDIMIENTO ALMACENADO QUE ACTUALIZA UN REGISTRO EN LA TABLA DE ad_usuario CON MANEJO DE ERROR Y PARAMETRO DE SALIDA EN DEVUELVE EL ERROR EN CASO DE QUE EXISTA
DROP PROCEDURE IF EXISTS `sp_ad_usuario_actualizar`;
DELIMITER $$
CREATE PROCEDURE `sp_ad_usuario_actualizar`(
    IN in_usuid INT,
    IN in_usuprinom VARCHAR(40),
    IN in_ususegnom VARCHAR(40),
    IN in_usupriape VARCHAR(40),
    IN in_ususegape VARCHAR(40),
    IN in_usutdocid TINYINT,
    IN in_usunumdoc VARCHAR(20),
    IN in_usurol INT,
    IN in_usutelefono VARCHAR(13),
    IN in_usuemail VARCHAR(50),
    IN in_usulogin VARCHAR(40),
    IN in_usuclave VARCHAR(64),
    IN in_usuimg VARCHAR(50),
    IN in_usumodusuarioid INT,
    IN in_usumodusuario VARCHAR(50),
    IN in_usumodip VARCHAR(40),
    OUT out_error VARCHAR(100)
)
BEGIN
-- MANEJO DE ERROR CONTRY CATCH
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET out_error = CONCAT('ERROR: ', @errno, ' - ', @text);
    END;
    -- INICIO DE TRANSACCION
    START TRANSACTION;
    -- VERIFICA SI EXISTE EL USUARIO, DE NO EXISTIR RETORNA UN ERROR
    IF NOT EXISTS(SELECT usuid FROM ad_usuario WHERE usuid = in_usuid) THEN
    SET out_error = 'ERROR: 1062 - El usuario no existe';
    -- LLAMA AL PROCEDIMIENTO ALMACENADO QUE REGISTRA EL LOG
    CALL sp_ad_logs_crear('ACTUALIZAR', 'El usuario no existe', 'ad_usuario', 'sp_ad_usuario_actualizar', '', in_usumodip);
    ROLLBACK;
    LEAVE sp_ad_usuario_actualizar;
    END IF;
    -- ACTUALIZAR REGISTRO
    UPDATE ad_usuario SET
        usuprinom = in_usuprinom,
        ususegnom = in_ususegnom,
        usupriape = in_usupriape,
        ususegape = in_ususegape,
        usutdocid = in_usutdocid,
        usunumdoc = in_usunumdoc,
        usurol = in_usurol,
        usutelefono = in_usutelefono,
        usuemail = in_usuemail,
        usulogin = in_usulogin,
        usuclave = in_usuclave,
        usuimg = in_usuimg,
        usumodusuarioid = in_usumodusuarioid,
        usumodusuario = in_usumodusuario,
        usumodfecha = current_timestamp(),
        usumodip = in_usumodip
    WHERE usuid = in_usuid;
    SET out_error = 'OK';
END$$
DELIMITER;

