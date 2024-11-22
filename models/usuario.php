<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario
{

	//implementamos nuestro constructor
	public function __construct()
	{

	}

	//metodo insertar regiustro
	public function insertar($nom1, $nom2, $ape1, $ape2, $doc, $rol, $tel, $mail, $login, $clave, $imagen, $permisos)
	{
		$sql = "INSERT INTO usuario (usuprinom, ususegnom, usupriape, ususegape, usunumdoc, rol, usutelefono, usuemail, login, clave, imagen) 
				VALUES ('$nom1', '$nom2', '$ape1', '$ape2', '$doc', '$rol', '$tel', LOWER('$mail'), '$login', '$clave', '$imagen')";
		//return ejecutarConsulta($sql);
		$idusuarionew = ejecutarConsulta_retornarID($sql);
		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($permisos)) {

			$sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) 
							    VALUES('$idusuarionew', '$permisos[$num_elementos]')";

			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos = $num_elementos + 1;
		}
		return $sw;
	}

	public function editar($idusuario, $nom1, $nom2, $ape1, $ape2, $doc, $rol, $tel, $mail, $imagen, $permisos)
	{
		$sql = "UPDATE usuario 
					SET usuprinom = '$nom1', 
						ususegnom = '$nom2', 
						usupriape = '$ape1', 
						ususegape = '$ape2', 
						usunumdoc = '$doc', 
						rol = '$rol', 
						usutelefono = '$tel', 
						usuemail = LOWER('$mail'), 
						imagen = '$imagen'
				WHERE idusuario = '$idusuario'";
		ejecutarConsulta($sql);

		//eliminar permisos asignados
		$sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
		ejecutarConsulta($sqldel);

		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($permisos)) {

			$sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) 
							VALUES('$idusuario', '$permisos[$num_elementos]')";

			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos = $num_elementos + 1;
		}
		return $sw;
	}
	public function desactivar($idusuario)
	{
		$sql = "UPDATE usuario SET estado='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
	public function activar($idusuario)
	{
		$sql = "UPDATE usuario SET estado='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($idusuario)
	{
		$sql = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar()
	{
		$sql = "SELECT u.*, r.rolnombre 
				FROM usuario u
				INNER JOIN rol r ON r.id = u.rol
				ORDER BY u.idusuario DESC";
		return ejecutarConsulta($sql);
	}

	//listar y mostrar registro en select
	public function select()
	{
		$sql = "SELECT * 
				FROM usuario 
				WHERE condicion = 1 
				AND NOT idusuario = 1 
				AND rol = 3
				AND tecsistema = 'SI'";
		return ejecutarConsulta($sql);
	}

	//metodo para listar permmisos marcados de un usuario especifico
	public function listarmarcados($idusuario)
	{
		$sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//funcion que verifica el acceso al sistema

	public function verificar($login, $clave)
	{
		$sql = "SELECT u.idusuario, u.usuprinom, u.ususegnom, u.usupriape, u.ususegape, u.usunumdoc, u.rol, 
						u.usutelefono,  u.usuemail, u.login, u.clave, u.imagen, u.estado, r.rolnombre
				FROM usuario u
				INNER JOIN rol r ON r.id = u.rol
				WHERE login = '$login' AND u.clave = '$clave' AND u.estado = '1'";
		return ejecutarConsulta($sql);

	}

	public function selectInstitucion()
	{
		$sql = "SELECT * FROM institucion WHERE estado = 1";
		return ejecutarConsulta($sql);
	}

	public function selectRol()
	{
		$sql = "SELECT * FROM rol WHERE estado = 1";
		return ejecutarConsulta($sql);
	}
}

?>