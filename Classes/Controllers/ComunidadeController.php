<?php
	
	namespace Classes\Controllers;

	class ComunidadeController{


		public function index(){
			if(isset($_SESSION['login'])){

				if(isset($_GET['solicitarAmizade'])){
					$idPara = (int) $_GET['solicitarAmizade'];
					if(\Classes\Models\UsuariosModel::solicitarAmizade($idPara)){
						\Classes\Utilidades::alerta('Amizade solicitada com sucesso!');
						\Classes\Utilidades::redirect(INCLUDE_PATH.'comunidade');
					}else{
						\Classes\Utilidades::alerta('Ocorreu um erro ao solicitar a amizade...');
						\Classes\Utilidades::redirect(INCLUDE_PATH.'comunidade');
					}
				}

			\Classes\Views\MainView::render('comunidade');
			}else{
				\Classes\Utilidades::redirect(INCLUDE_PATH);
			}
			
		}

	}

?>