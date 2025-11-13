<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TEC_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loja_model');
        }

    public function set_menu_active($menu='')
    {
        $this->active_menu = $menu;
    }

    // public function set_type_user($menu='')
    // {
    //     $this->active_menu = $menu;
    // }

    //recebe a url da pagina para ser adicionada no conteudo
    public function montaTela($paginaConteudo, $data = NULL)
    {   

        $carrinho=$this->session->userdata('carrinho_gury_informatica_1a2s3d4f');
        $contato['sobre'] = $this->loja_model->get_contato();
        if($carrinho) {
          if(isset($_COOKIE['carrinho_gury_informatica_1a2s3d4f'])){
            $carrinho= unserialize($_COOKIE['carrinho_gury_informatica_1a2s3d4f']);
          }
            $produtos = array();
            foreach ($carrinho as $key => $pdc) {
              $prod = $this->loja_model->produtos_carrinho($pdc->produto); // BUSCA O PRODUTO
              $prod->{'quantidade'} = $pdc->quantidade; //ADICIONA A QUANTIDADE
              $produtos[] = $prod;
            }
            $carrinho['carrinho'] =  $produtos;
        }



        $carrinho['contato_empresa'] = $this->loja_model->get_contato();

        $carrinho['categorias_produtos_all'] = $this->loja_model->get_categorias_produtos_all();

        $carrinho['grupos_categorias'] = $this->loja_model->get_grupos_categorias();


        $this->load->view('includes/header', $carrinho);
        $this->load->view($paginaConteudo, $data);
        $this->load->view('includes/footer', $contato);
    }


    public function montaTelaInterna($paginaConteudo, $data = NULL)
    {

        $carrinho=$this->session->userdata('carrinho_gury_informatica_1a2s3d4f');
        $contato['sobre'] = $this->loja_model->get_contato();

        if($carrinho) {
          if(isset($_COOKIE['carrinho_gury_informatica_1a2s3d4f'])){
            $carrinho= unserialize($_COOKIE['carrinho_gury_informatica_1a2s3d4f']);
          }
            $produtos = array();
            foreach ($carrinho as $key => $pdc) {
              $prod = $this->loja_model->produtos_carrinho($pdc->produto); // BUSCA O PRODUTO
              $prod->{'quantidade'} = $pdc->quantidade; //ADICIONA A QUANTIDADE
              $produtos[] = $prod;
            }
            $carrinho['carrinho'] =  $produtos;
        }
        $carrinho['contato_empresa'] = $this->loja_model->get_contato();
        $this->load->view('includes/header-interna', $carrinho);
        $this->load->view($paginaConteudo, $data);
        $this->load->view('includes/footer', $contato);
    }

  
}
