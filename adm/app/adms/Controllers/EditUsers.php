<?php

namespace App\adms\Controllers;

/**
 * Controller da página editar usuário.
 * @author Cesar <cesar@celke.com.br>
 */
class EditUsers
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;
      /** @var array|string|null $id recebeo id do registro */
      private int|string| null $id;


    /**
     * Instantiar a classe responsável em carregar a View e enviar os dados para View..
     * Quando o usuário clicar no botão "cadastrar" do formulário da página novo usuário. Acessa o IF e instância a classe "AdmsAddUsers" responsável em cadastrar o usuário no banco de dados.
     * Usuário cadastrado com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(int|string|null $id): void
    {
        $this->dataForm= filter_input_array(INPUT_POST,FILTER_DEFAULT);
       
        if((!empty($id)) and(empty( $this->dataForm['SendEditUser']))){
            var_dump($id);
            $this->id = (int)$id;
            $viewUser= new \App\adms\Models\AdmsEditUsers();
           $viewUser->viewUser($this->id);
           if($viewUser->getResult()){
            $this->data['form']=$viewUser->getResultBd();
            $this->viewEditUser();
           }else {
            $urlRedirect = URLADM . "list-users/index";
            header("Location: $urlRedirect");
        }
        }else{
           
            $this->editUser();
        }   
       
    }
     /**
     * Instantiar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditUser(): void
    {
        $listSelect = new \App\adms\Models\AdmsEditUsers();
        $this->data['select'] = $listSelect->listSelect();
        
        $loadView = new \Core\ConfigView("adms/Views/users/editUser", $this->data);
        $loadView->loadView();
    }

    private function editUser():void
    {
       
        if(!empty($this->dataForm['SendEditUser'])){
            unset($this->dataForm['SendEditUser']);
            $editUser = new \App\adms\Models\AdmsEditUsers();
            $editUser->update($this->dataForm);
            if($editUser->getResult()){
                $urlRedirect = URLADM . "view-users/index/".$this->dataForm['id'];
            header("Location: $urlRedirect");
            }else{
                $this->data['form'] =$this->dataForm;
                $this->viewEditUser();
            }

        }else{
             $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Usuário não encontrado controller EditUsers!</p>";
            $urlRedirect = URLADM . "list-users/index";
            header("Location: $urlRedirect");
        }
    }
}
