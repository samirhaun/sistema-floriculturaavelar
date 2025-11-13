<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_news_letter extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/news_letter_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'conteudo',
                                'submenu' => 'site-news-letter'
                                )
                            );
    }

    public function index()
    {
        $data['news_letters'] = $this->news_letter_model->get_news_letter();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/news_letter/lista', $data);
    }
}