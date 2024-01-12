<?php

namespace App\adms\Models;
if(!defined('C8L6K7E')){
  /*  header("Location:/"); */
die("Erro: Página não encontrada!<br>");
}

/**
 * EDITAR o usuários do banco de dados
 */
class AdmsEditUsers
{


  /**recebe true se executar com sucesso e false se houver Erro - */
  private bool $result = false;
  /**Recebe os registros do banco de dados */
  private array|null $resultBd;
  /** @var array|string|null $id recebeo id do registro */
  private int|string| null $id;
  /**
   * 
   * // @var array|string|null $data Recebe a sinformações do formulário 
   */
  private array|string|null $data;
/**
 * $dataExitVal recebe os campos que devem ser retirados da validação
 *
 * @var array|null
 */
  private array|null $dataExitVal;

  
  private array $listRegistryAdd;






  /**
   * retorna true quando eecutar o processo com sucesso ou false quando houver erro
   *
   * @return boolean
   */
  function getResult(): bool
  {
    return $this->result;
  }
  /**
   * retorna os detalhes do registro
   *
   * @return array|null
   */
  function getResultBd(): array|null
  {
    return $this->resultBd;
  }

  public function viewUser(int $id): void
  {
    $this->id = $id;

    $viewUser = new \App\adms\Models\helper\AdmsRead();
    $viewUser->fullRead(
      "SELECT id, name, nickname, email, user,adms_sits_user_id
                            FROM adms_users
                            WHERE id=:id
                            LIMIT :limit",
      "id={$this->id}&limit=1"
    );

    $this->resultBd = $viewUser->getResult();
    if ($this->resultBd) {
      $this->result = true;
    } else {
      $_SESSION['msg'] =  "<p class='alert-danger'>Erro - 0047: Usuário não encontrado!</p>";
      $this->result = false;
    }
  }

  public function update(array $data = null): void
  {

    $this->data = $data;
    /* //var_dump($this->data); */
    $this->dataExitVal['nickname']=$this->data['nickname'];
   /*  $this->dataExitVal['name']=$this->data['name']; */
    unset($this->data['nickname']);
    /* //var_dump($this->data);
    //var_dump( $this->dataExitVal); */

    $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
    $valEmptyField->valField($this->data);
    if ($valEmptyField->getResult()) {
      $this->valInput();
      
     
    } else {
      $this->result = false;
    }
  }
  /** 
   * Instanciar o helper "AdmsValEmail" para verificar se o e-mail válido
   * Instanciar o helper "AdmsValEmailSingle" para verificar se o e-mail não está cadastrado no banco de dados, não permitido cadastro com e-mail duplicado
   * Instanciar o helper "validatePassword" para validar a senha
   * Instanciar o helper "validateUserSingleLogin" para verificar se o usuário não está cadastrado no banco de dados, não permitido cadastro com usuário duplicado
   * Instanciar o método "add" quando não houver nenhum Erro - de preenchimento 
   * Retorna FALSE quando houve algum erro
   * 
   * @return void
   */
  private function valInput(): void
  {

    $valEmail = new \App\adms\Models\helper\AdmsValEmail();
    $valEmail->validateEmail($this->data['email']);

    $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
    $valEmailSingle->validateEmailSingle($this->data['email'], true, $this->data['id']);

    $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
    $valUserSingle->validateUserSingle($this->data['user'], true, $this->data['id']);
    // se o email for valido e não tiver email e usuario no banco de dados ja cadastrado segue o procesamento para editar
    if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valUserSingle->getResult())) {
      $this->edit();
    } else {
      $this->result = false;
    }
  }
  private function edit(): void
  {
    // //var_dump($this->data);
    $this->data['modified'] = date("y-m-d H:i:s");
    $this->data['nickname'] = $this->dataExitVal['nickname'];
    // $this->data['name'] =$this->dataExitVal['name'];
   
    // //var_dump($this->data);
    $upUser = new \App\adms\Models\helper\AdmsUpdate();
    $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id={$this->data['id']}");
    if ($upUser->getResult()) {
      $_SESSION['msg'] = "<p class='alert-success'>Usuário editado com sucesso!</p>";
      $this->result = true;
    } else {
      $_SESSION['msg'] = "<p class='alert-danger'>Erro - 0048: Usuário não editado com sucesso!</p>";
      $this->result = false;
    }
  }
  /**
    * instancia a helper que faz a leitura dos registros no BD , atribui a um objeto com uma posicao sit e atribui chave sit e valor objeto criado ao atributo $this->listRegistryAdd
    *
    * @return array
    */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_users ORDER BY name ASC");
        $registry['sit'] = $list->getResult();
       /*  //var_dump($registry['sit']); */
        $this->listRegistryAdd = ['sit' => $registry['sit']];
        /* //var_dump($this->listRegistryAdd); */
        return $this->listRegistryAdd;
    }
}
