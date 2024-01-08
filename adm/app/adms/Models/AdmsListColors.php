<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
  /*  header("Location:/"); */
  die("Erro: Página não encontrada!<br>");
}
/**
 * listar cores do banco de dados
 */
class AdmsListColors
{


  /**recebe true se executar com sucesso e false se houver erro */
  private bool $result;
  /**Recebe os registros do banco de dados */
  private array|null $resultBd;
  /** @var string|int|null $page Recebe o número página */
  private string|int|null $page;
  /** @var int $page Recebe a quantidade de registros que deve retornar do banco de dados */
  private int $limitResult = 40;
  /** @var string|null $page Recebe a páginação */
  private string|null $resultPg;




  /**
   * 
   *recebe true se executar com sucesso e false se houver erro 
   *
   * @return boolean
   */
  function getResult(): bool
  {
    return $this->result;
  }
  /**
   * retorna os  registros do  BD
   *
   * @return array|null
   */
  function getResultBd(): array|null
  {
    return $this->resultBd;
    // //var_dump($this->result);
  }
  /**
     * @return bool Retorna a paginação
     */
    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

  /**
   
   *
   * @return void
   */
  public function listColors(int $page = null): void
  {
    $this->page = (int) $page ? $page : 1;
    ////var_dump($this->page );
    $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index');
    $pagination->condition($this->page, $this->limitResult);
    $pagination->pagination("SELECT COUNT(col.id) AS num_result FROM adms_colors  As col");
    $this->resultPg = $pagination->getResult();
    ////var_dump($this->resultPg);

    $listSitsUsers = new \App\adms\Models\helper\AdmsRead();
    $listSitsUsers->fullRead("SELECT id, name,
                            color 
                            FROM adms_colors
                            ORDER BY id DESC
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");
    $this->resultBd = $listSitsUsers->getResult();

    if ($this->resultBd) {

      $this->result = true;
    } else {
      $_SESSION['msg'] = "<p style='color:#f00'>Erro - 0054: nenhum usuário encontrado!</p>";
      $this->result = false;
    }
  }
}
