<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    /*  header("Location:/"); */
 die("Erro: Página não encontrada!<br>");
 }
/**
 * Controller da página listar usuarios
 * @author Cesar <cesar@celke.com.br>
 */
class ListSitsUsers
{
    
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    public function index(): void
    {
       $listSitsUsers= new \App\adms\Models\AdmsListSitsUsers();
       $listSitsUsers->listSitsUsers();
       if($listSitsUsers->getResult()){
        $this->data['listSitsUsers'] = $listSitsUsers->getResultBd();
        
       }else{
        $this->data['listSitsUsers'] = [];
       }
     
        

       $loadView = new \Core\ConfigView("adms/Views/sitsUser/listSitUser", $this->data);
       $loadView->loadView();

    }
}