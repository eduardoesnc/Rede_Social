<?php
	
	namespace Classes\Controllers;

	class HomeController{


		public function index(){

			if(isset($_GET['loggout'])){
				session_unset();
				session_destroy();

				\Classes\Utilidades::redirect(INCLUDE_PATH);
			}


			if(isset($_SESSION['login'])){
				//Renderiza a home do usuário.

				//Existe pedido de amizade?

				if(isset($_GET['recusarAmizade'])){
					$idEnviou = (int) $_GET['recusarAmizade'];
					\Classes\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,0);
					\Classes\Utilidades::alerta('Amizade Recusada :(');
					\Classes\Utilidades::redirect(INCLUDE_PATH);
				}else if(isset($_GET['aceitarAmizade'])){
					$idEnviou = (int) $_GET['aceitarAmizade'];
					if(\Classes\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,1)){
					\Classes\Utilidades::alerta('Amizade aceita!');
					\Classes\Utilidades::redirect(INCLUDE_PATH);
					}else{
					\Classes\Utilidades::alerta('Ops.. um erro ocorreu!');
					\Classes\Utilidades::redirect(INCLUDE_PATH);
					}
				}


				//Existe postagem no feed?


				if(isset($_POST['post_feed'])){

					if($_POST['post_content'] == ''){
						\Classes\Utilidades::alerta('Não permitimos posts vázios :(');
						\Classes\Utilidades::redirect(INCLUDE_PATH);
					}

					\Classes\Models\HomeModel::postFeed($_POST['post_content']);
					\Classes\Utilidades::alerta('Post feito com sucesso!');
					\Classes\Utilidades::redirect(INCLUDE_PATH);
				}


				\Classes\Views\MainView::render('home');
			}else{
				//Renderizar para criar conta.

				if(isset($_POST['login'])){
					$login = $_POST['email'];
					$senha = $_POST['senha'];

					

					//Verificar no banco de dados.

					$verifica = \Classes\MySql::connect()->prepare("SELECT * FROM usuarios WHERE email = ?");
					$verifica->execute(array($login));



					
					if($verifica->rowCount() == 0){
						//Não existe o usuário!
						\Classes\Utilidades::alerta('Não existe nenhum usuário com este e-mail...');
						\Classes\Utilidades::redirect(INCLUDE_PATH);
					}else{
						$dados = $verifica->fetch();
						$senhaBanco = $dados['senha'];
						if(\Classes\Bcrypt::check($senha,$senhaBanco)){
							//Usuário logado com sucesso
							
							$_SESSION['login'] = $dados['email'];
							$_SESSION['id'] = $dados['id'];
							$_SESSION['nome'] = explode(' ',$dados['nome'])[0];
							$_SESSION['img'] = $dados['img'];
							\Classes\Utilidades::alerta('Logado com sucesso!');
							\Classes\Utilidades::redirect(INCLUDE_PATH);
						}else{
							\Classes\Utilidades::alerta('Senha incorreta....');
							\Classes\Utilidades::redirect(INCLUDE_PATH);
						}
					}
					

				}

				\Classes\Views\MainView::render('login');
			}

		}

	}

?>