<?php
session_start();
require_once "../models/usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nom1 = isset($_POST["txtNom1"]) ? limpiarCadena($_POST["txtNom1"]) : "";
$nom2 = isset($_POST["txtNom2"]) ? limpiarCadena($_POST["txtNom2"]) : "";
$ape1 = isset($_POST["txtApe1"]) ? limpiarCadena($_POST["txtApe1"]) : "";
$ape2 = isset($_POST["txtApe2"]) ? limpiarCadena($_POST["txtApe2"]) : "";
$doc = isset($_POST["txtDoc"]) ? limpiarCadena($_POST["txtDoc"]) : "";
$rol = isset($_POST["txtRol"]) ? limpiarCadena($_POST["txtRol"]) : "";
$tel = isset($_POST["txtTel"]) ? limpiarCadena($_POST["txtTel"]) : "";
$mail = isset($_POST["txtMail"]) ? limpiarCadena($_POST["txtMail"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $_POST["imagenactual"];
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}

		//Hash SHA256 para la contraseña
		$clavehash = hash("SHA256", $clave);
		if (empty($idusuario)) {
			$rspta = $usuario->insertar($nom1, $nom2, $ape1, $ape2, $doc, $rol, $tel, $mail, $login, $clavehash, $imagen, $_POST['permiso']);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		} else {
			$rspta = $usuario->editar($idusuario, $nom1, $nom2, $ape1, $ape2, $doc, $rol, $tel, $mail, $imagen, $_POST['permiso']);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
		break;


	case 'desactivar':
		$rspta = $usuario->desactivar($idusuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

	case 'activar':
		$rspta = $usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;

	case 'mostrar':
		$rspta = $usuario->mostrar($idusuario);
		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta = $usuario->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => ($reg->estado) ? '<a href="#" title="Inactivar" class="text-danger" onclick="desactivar(' . $reg->idusuario . ', \'' . $reg->usuprinom . '\', \'' . $reg->usupriape . '\')"><i class="fa fa-toggle-off"></i></a>'
					. ' ' . '<a href="#" title="Actualizar" class="text-warning" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil-square-o"></i></a>'
					: '<a href="#" title="Activar" class="text-primary" onclick="activar(' . $reg->idusuario . ', \'' . $reg->usuprinom . '\', \'' . $reg->usupriape . '\')"><i class="fa fa-toggle-on"></i></a>',
				"1" => $reg->usunumdoc,
				"2" => $reg->usuprinom . ' ' . $reg->ususegnom . ' ' . $reg->usupriape . ' ' . $reg->ususegape,
				"3" => $reg->rolnombre,
				"4" => $reg->usutelefono,
				"5" => $reg->login,
				"6" => $reg->fechareg,
				"7" => "<img src='../files/usuarios/" . $reg->imagen . "' height='25px' width='25px'>",
				"8" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>'
			);
		}

		$results = array(
			"sEcho" => 1,
			//info para datatables
			"iTotalRecords" => count($data),
			//enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data),
			//enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
		break;

	//llenar select institucion
	case 'selectInstitucion':
		//require_once "../modelos/Traslado.php";
		$suario = new Usuario();
		$rspta = $suario->selectInstitucion();
		$htmlOptions = '<option value="">--Seleccionar--</option>'; //Comienza con una opción en blanco
		while ($reg = $rspta->fetch_object()) {
			$htmlOptions .= '<option value=' . $reg->id . '>' . $reg->inscodigo . '</option>'; //Agrega las opciones de la base de datos
		}
		echo $htmlOptions; //Envía la respuesta completa
		break;

	//llenar select rol
	case 'selectRol':
		$suario = new Usuario();
		$rspta = $suario->selectRol();
		$htmlOptions = '<option value="">--Seleccionar--</option>'; //Comienza con una opción en blanco
		while ($reg = $rspta->fetch_object()) {
			$htmlOptions .= '<option value=' . $reg->id . '>' . $reg->rolnombre . '</option>'; //Agrega las opciones de la base de datos
		}
		echo $htmlOptions; //Envía la respuesta completa
		break;

	case 'permisos':
		//obtenemos toodos los permisos de la tabla permisos
		require_once "../models/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();
		//obtener permisos asigandos
		$id = $_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		$valores = array();

		//almacenar permisos asigandos
		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}
		//mostramos la lista de permisos
		while ($reg = $rspta->fetch_object()) {
			$sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
			echo '<li><input type="checkbox" ' . $sw . ' name="permiso[]" value="' . $reg->idpermiso . '">' . ' ' . $reg->nombre . '</li>';
		}
		break;

	case 'verificar':
		//validar si el usuario tiene acceso al sistema
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];

		//Hash SHA256 en la contraseña
		$clavehash = hash("SHA256", $clavea);

		$rspta = $usuario->verificar($logina, $clavehash);

		$fetch = $rspta->fetch_object();
		if (isset($fetch)) {
			# Declaramos las variables de sesion
			$_SESSION['idusuario'] = $fetch->idusuario;
			$_SESSION['usuprinom'] = $fetch->usuprinom;
			$_SESSION['ususegnom'] = $fetch->ususegnom;
			$_SESSION['usupriape'] = $fetch->usupriape;
			$_SESSION['ususegape'] = $fetch->ususegape;
			$_SESSION['rolnombre'] = $fetch->rolnombre;
			$_SESSION['rol'] = $fetch->rol;
			$_SESSION['usutelefono'] = $fetch->usutelefono;
			$_SESSION['usuemail'] = $fetch->usuemail;
			$_SESSION['login'] = $fetch->login;
			$_SESSION['imagen'] = $fetch->imagen;
			# Crear variable de sesión para el nombre completo
			$_SESSION['nombre_completo'] = $fetch->usuprinom . ' ' . $fetch->usupriape . ' ' . $fetch->ususegape;

			//obtenemos los permisos
			$marcados = $usuario->listarmarcados($fetch->idusuario);

			//declaramos el array para almacenar todos los permisos
			$valores = array();

			//almacenamos los permisos marcados en al array
			while ($per = $marcados->fetch_object()) {
				array_push($valores, $per->idpermiso);
			}

			//determinamos los accesos al usuario
			//in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
			in_array(1, $valores) ? $_SESSION['dashboard'] = 1 : $_SESSION['dashboard'] = 0;
			in_array(2, $valores) ? $_SESSION['registro'] = 1 : $_SESSION['registro'] = 0;
			in_array(3, $valores) ? $_SESSION['control'] = 1 : $_SESSION['control'] = 0;
			in_array(4, $valores) ? $_SESSION['administracion'] = 1 : $_SESSION['administracion'] = 0;
			in_array(5, $valores) ? $_SESSION['informe'] = 1 : $_SESSION['informe'] = 0;
			in_array(6, $valores) ? $_SESSION['personal'] = 1 : $_SESSION['personal'] = 0;
			in_array(7, $valores) ? $_SESSION['seguridad'] = 1 : $_SESSION['seguridad'] = 0;

		}
		echo json_encode($fetch);


		break;
	case 'salir':
		//limpiamos la variables de la secion
		session_unset();

		//destruimos la sesion
		session_destroy();
		//redireccionamos al login
		header("Location: ../index.php");
		break;
}
?>