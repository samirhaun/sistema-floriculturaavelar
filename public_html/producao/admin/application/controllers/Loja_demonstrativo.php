<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_demonstrativo extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/pedidos_model');
        $this->load->model('loja/contas_pagar_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'loja',
                                'submenu' => 'loja-demonstrativo'
                                )
                            );
    }


       

    function demonstrativo(){
        // $data['convenios'] = $this->faturamento_model->get_convenios();
        // $data['procedimentos'] = $this->faturamento_model->get_procedimentos();
        $data['vendedores'] = $this->pedidos_model->get_vendedores();
        $data['entregadores'] = $this->pedidos_model->get_entregadores();
        $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();
        $this->montaTela('pedidos/relatorio',$data);
    }


    function demonstrativo_buscar(){

        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] =  $this->input->post('dia_final');
            $data['filtro_origem'] = $this->input->post('origem');
            $data['filtro_vendedor'] = $this->input->post('vendedor');
            $data['filtro_plano_conta'] = $this->input->post('plano_contas_id');
            $data['filtro_exibir_receitas'] = $this->input->post('exibir_receitas');
            $data['filtro_referencia'] = $this->input->post('referencia');
            $data['filtro_situacao_pgto'] = $this->input->post('situacao_pgto');
            $data['filtro_entregador'] = $this->input->post('entregador');
            $data['filtro_forma_pgto'] = $this->input->post('forma_pgto');
            $data['filtro_exibir_despesas'] = $this->input->post('exibir_despesas');

            //BUSCANDO OS PROCEDIMENTOS REALIZADOS
            if($data['filtro_exibir_receitas'] == 1):

                $data['pedidos'] = $this->pedidos_model
                                            ->get_demonstrativo(inverteData($data['filtro_inicio'],'-')
                                                                ,inverteData($data['filtro_fim'],'-')
                                                                ,$data['filtro_origem']
                                                                ,$data['filtro_vendedor']
                                                                ,$data['filtro_referencia']
                                                                ,$data['filtro_situacao_pgto']
                                                                ,$data['filtro_entregador']
                                                                ,$data['filtro_forma_pgto']);

                                                            //       echo '<pre>';
                                                            // print_r($data['pedidos']);
                                                            // exit;

            endif;

            
          if($data['filtro_exibir_despesas'] == 1):

           $data['contas_pagar'] = $this->pedidos_model
                                          ->get_demonstrativo_pagar(inverteData($data['filtro_inicio'],'-')
                                                            ,inverteData($data['filtro_fim'],'-')
                                                            ,$data['filtro_origem']
                                                            ,$data['filtro_vendedor']
                                                            ,$data['filtro_plano_conta']
                                                            ,$data['filtro_referencia']
                                                            ,$data['filtro_forma_pgto']);


          endif;                          
                                                            // echo '<pre>';
                                                            // print_r($data['contas_pagar']);
                                                            // exit;
        

            $data['vendedores'] = $this->pedidos_model->get_vendedores();

            $data['entregadores'] = $this->pedidos_model->get_entregadores();
            
            $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();

            $data['resultado'] = $this->load->view('pedidos/relatorio_resultado', $data, true);
            

          
            
            $this->montaTela('pedidos/relatorio',$data);


        }

    }


    /* novo demonstrativo*/

    function demonstrativo_novo(){
        $this->set_menu_active(
            array(
                'menu' => 'loja',
                'submenu' => 'loja-demonstrativo-novo'
                )
            );
        // $data['convenios'] = $this->faturamento_model->get_convenios();
        // $data['procedimentos'] = $this->faturamento_model->get_procedimentos();
        $data['vendedores'] = $this->pedidos_model->get_vendedores();
        $data['entregadores'] = $this->pedidos_model->get_entregadores();
        $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();
        $this->montaTela('pedidos/relatorio_novo',$data);
    }


    function demonstrativo_buscar_novo(){
        $this->set_menu_active(
            array(
                'menu' => 'loja',
                'submenu' => 'loja-demonstrativo-novo'
                )
            );
        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] =  $this->input->post('dia_final');
            $data['filtro_origem'] = $this->input->post('origem') ?: 'all';
            $data['filtro_vendedor'] = $this->input->post('vendedor') ?: 'all';
            $data['filtro_plano_conta'] = $this->input->post('plano_contas_id') ?: 'all';
            $data['filtro_exibir_receitas'] = $this->input->post('exibir_receitas');
            $data['filtro_referencia'] = $this->input->post('referencia');
            $data['filtro_situacao_pgto'] = $this->input->post('situacao_pgto') ?: array('all');
            $data['filtro_entregador'] = $this->input->post('entregador') ?: 'all';
            $data['filtro_forma_pgto'] = $this->input->post('forma_pgto') ?: array();
            $data['filtro_exibir_despesas'] = $this->input->post('exibir_despesas');

            $inicio = inverteData($data['filtro_inicio'],'-');
            $fim = inverteData($data['filtro_fim'],'-');
            $page = (int) $this->input->post('page') ?: 1;
            $page_rec = (int) $this->input->post('page_receitas') ?: 1;
            $page_desp = (int) $this->input->post('page_despesas') ?: 1;
            $per_page = 50;

            $data['page'] = $page;
            $data['page_receitas'] = $page_rec;
            $data['page_despesas'] = $page_desp;
            $data['per_page'] = $per_page;

            $pedidos_all = $this->pedidos_model->get_demonstrativo_novo(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_referencia'], $data['filtro_situacao_pgto'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $contas_all = $this->pedidos_model->get_demonstrativo_pagar_novo(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_plano_conta'], $data['filtro_referencia'],
                $data['filtro_forma_pgto'], $data['filtro_situacao_pgto']
            );

            $data['total_receitas_count'] = count($pedidos_all);
            $data['total_despesas_count'] = count($contas_all);

            $merged = array();
            foreach($pedidos_all as $p){ $p->_tipo = 'receita'; $merged[] = $p; }
            foreach($contas_all as $c){ $c->_tipo = 'despesa'; $merged[] = $c; }
            $total_all = count($merged);
            $total_pages = ceil($total_all / $per_page);
            $data['registros'] = array_slice($merged, ($page - 1) * $per_page, $per_page);
            $data['total_all'] = $total_all;
            $data['total_pages'] = $total_pages;

            $total_pages_rec = ceil($data['total_receitas_count'] / $per_page);
            $total_pages_desp = ceil($data['total_despesas_count'] / $per_page);
            $data['pedidos_pag'] = array_slice($pedidos_all, ($page_rec - 1) * $per_page, $per_page);
            $data['contas_pag'] = array_slice($contas_all, ($page_desp - 1) * $per_page, $per_page);
            $data['total_pages_receitas'] = $total_pages_rec;
            $data['total_pages_despesas'] = $total_pages_desp;

            $data['totais_forma_pgto_receitas'] = $this->pedidos_model->get_demonstrativo_totais_por_forma_pgto(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_referencia'], $data['filtro_situacao_pgto'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $data['comparativo_entradas'] = $this->pedidos_model->get_fluxo_entradas_vendido_recebido_por_forma(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $data['totais_forma_pgto_despesas'] = $this->pedidos_model->get_demonstrativo_totais_por_forma_pgto_despesas(
                $inicio, $fim, $data['filtro_plano_conta'], $data['filtro_referencia'],
                $data['filtro_forma_pgto'], $data['filtro_situacao_pgto']
            );

            $data['fechamento_cartao'] = $this->pedidos_model->get_fechamento_cartao(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['card_receitas_abertas'] = $this->pedidos_model->get_receitas_abertas_por_vencimento(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $data['totais_por_categoria'] = $this->pedidos_model->get_demonstrativo_por_categoria(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['detalhe_categoria'] = $this->pedidos_model->get_demonstrativo_detalhe_categoria(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['totais_por_plano_conta'] = $this->pedidos_model->get_demonstrativo_por_plano_conta(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['detalhe_plano_conta'] = $this->pedidos_model->get_demonstrativo_detalhe_plano_conta(
                $inicio, $fim, $data['filtro_referencia']
            );

            $todos_planos = $this->contas_pagar_model->get_todos_planos_conta();
            $data['planos_lookup'] = array();
            if(!empty($todos_planos)){
                foreach($todos_planos as $pl){
                    $data['planos_lookup'][$pl->cod] = $pl->descricao;
                }
            }

            // Duplicata (forma_pgto = 7) e' venda a prazo: fica isolada dos totais de caixa,
            // mas registrada a parte para nao sumir do relatorio.
            $total_receitas_aberto = 0; $total_receitas_pago = 0;
            $card_duplicata_recebido = 0; $card_duplicata_aberto = 0;
            foreach($pedidos_all as $p){
                if(!empty($p->cancelada_relatorio)){
                    continue;
                }
                $eh_duplicata = ((int) $p->forma_pgto_receita === 7);
                if($p->status_receita == 1){
                    if($eh_duplicata) $card_duplicata_recebido += (float) $p->valor_receita;
                    else $total_receitas_pago += (float) $p->valor_receita;
                } else {
                    if($eh_duplicata) $card_duplicata_aberto += (float) $p->valor_receita;
                    else $total_receitas_aberto += (float) $p->valor_receita;
                }
            }
            $card_total_a_receber = 0;
            $card_duplicata_a_receber = 0;
            if(!empty($data['card_receitas_abertas'])){
                foreach($data['card_receitas_abertas'] as $r){
                    if((int) $r->forma_pgto_receita === 7){
                        $card_duplicata_a_receber += (float) $r->valor_receita;
                    } else {
                        $card_total_a_receber += (float) $r->valor_receita;
                    }
                }
            }
            $data['card_total_a_receber'] = $card_total_a_receber;
            $data['card_total_recebido'] = $total_receitas_pago;
            $data['card_duplicata_a_receber'] = $card_duplicata_a_receber;
            $data['card_duplicata_recebido'] = $card_duplicata_recebido;
            $data['card_duplicata_aberto'] = $card_duplicata_aberto;

            $total_desp_aberto = 0; $total_desp_pago = 0;
            foreach($contas_all as $c){
                if($c->status == 1) $total_desp_pago += (float) $c->valor;
                else $total_desp_aberto += (float) $c->valor;
            }
            $data['card_total_a_pagar'] = $total_desp_aberto;
            $data['card_total_pago_desp'] = $total_desp_pago;
            $data['card_saldo'] = $total_receitas_pago - $total_desp_pago;
            $data['card_previsao'] = $card_total_a_receber - $total_desp_aberto;

            $data['resultado'] = $this->load->view('pedidos/relatorio_resultado_novo', $data, true);

            if($this->input->is_ajax_request()){
                $this->output->set_output(json_encode(array(
                    'html' => $data['resultado'],
                    'page' => $page
                )));
                return;
            }

            $data['vendedores'] = $this->pedidos_model->get_vendedores();
            $data['entregadores'] = $this->pedidos_model->get_entregadores();
            $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();

            $this->montaTela('pedidos/relatorio_novo',$data);
        }
    }


    function exportar_contas_pagar_novo(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            show_404();
            return;
        }

        $filtro_inicio = $this->input->post('dia_inicial');
        $filtro_fim = $this->input->post('dia_final');
        $referencia = $this->input->post('referencia') ?: 'pgto';
        $origem = $this->input->post('origem') ?: 'all';
        $vendedor = $this->input->post('vendedor') ?: 'all';
        $plano_conta = $this->input->post('plano_contas_id') ?: 'all';
        $situacaopgto = $this->input->post('situacao_pgto');
        $formapgto = $this->input->post('forma_pgto');

        if(!is_array($situacaopgto) || empty($situacaopgto)){
            $situacaopgto = array('all');
        }
        if(!is_array($formapgto)){
            $formapgto = empty($formapgto) ? array() : array($formapgto);
        }

        $contas = $this->pedidos_model->get_demonstrativo_pagar_novo(
            inverteData($filtro_inicio, '-'),
            inverteData($filtro_fim, '-'),
            $origem,
            $vendedor,
            $plano_conta,
            $referencia,
            $formapgto,
            $situacaopgto
        );

        $nomes_forma_pgto = array(
            1 => 'Dinheiro', 9 => 'Pix', 2 => 'Débito',
            3 => 'Crédito 1x', 4 => 'Crédito 2x', 5 => 'Crédito 3x',
            6 => 'Crédito 4x', 7 => 'Duplicata', 8 => 'Cheque'
        );
        $texto_xml = function($valor){
            $valor = (string) $valor;
            $valor = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $valor);
            return htmlspecialchars($valor, ENT_QUOTES | ENT_XML1, 'UTF-8');
        };
        $celula_texto = function($referencia, $valor, $estilo = null) use ($texto_xml){
            $atributo_estilo = ($estilo === null) ? '' : ' s="'.(int) $estilo.'"';
            return '<c r="'.$referencia.'" t="inlineStr"'.$atributo_estilo.'><is><t xml:space="preserve">'.$texto_xml($valor).'</t></is></c>';
        };
        $data_excel = function($valor){
            $dia = substr((string) $valor, 0, 10);
            $data = DateTime::createFromFormat('!Y-m-d', $dia);
            if(!$data || $data->format('Y-m-d') !== $dia){
                return null;
            }
            $base = new DateTime('1899-12-30');
            return (int) $base->diff($data)->format('%r%a');
        };
        $celula_data = function($referencia, $valor) use ($data_excel, $celula_texto){
            if(empty($valor)){
                return $celula_texto($referencia, '-');
            }
            $serial = $data_excel($valor);
            return ($serial === null)
                ? $celula_texto($referencia, '-')
                : '<c r="'.$referencia.'" s="1"><v>'.$serial.'</v></c>';
        };

        $cabecalhos = array('Código', 'Fornecedor', 'Categoria', 'Subcategoria', 'Vencimento', 'Valor', 'Status', 'Data Pago', 'Forma Pgto');
        $colunas = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
        $linhas_xml = '<row r="1">';
        foreach($cabecalhos as $indice => $cabecalho){
            $linhas_xml .= $celula_texto($colunas[$indice].'1', $cabecalho, 3);
        }
        $linhas_xml .= '</row>';
        $numero_linha = 2;

        foreach($contas as $conta){
            if(!empty($conta->categoria_despesa)){
                $categoria = trim((!empty($conta->codigo_categoria_despesa) ? $conta->codigo_categoria_despesa.' - ' : '').$conta->categoria_despesa);
            } else {
                $categoria = !empty($conta->plano_conta)
                    ? trim((!empty($conta->cod_plano_conta) ? $conta->cod_plano_conta.' - ' : '').$conta->plano_conta)
                    : '-';
            }
            $subcategoria = !empty($conta->categoria_despesa) && !empty($conta->subcategoria_despesa)
                ? trim((!empty($conta->codigo_subcategoria_despesa) ? $conta->codigo_subcategoria_despesa.' - ' : '').$conta->subcategoria_despesa)
                : '-';

            $linhas_xml .= '<row r="'.$numero_linha.'">';
            $linhas_xml .= '<c r="A'.$numero_linha.'"><v>'.(int) $conta->id.'</v></c>';
            $linhas_xml .= $celula_texto('B'.$numero_linha, $conta->fornecedor);
            $linhas_xml .= $celula_texto('C'.$numero_linha, $categoria);
            $linhas_xml .= $celula_texto('D'.$numero_linha, $subcategoria);
            $linhas_xml .= $celula_data('E'.$numero_linha, $conta->data_vencimento);
            $linhas_xml .= '<c r="F'.$numero_linha.'" s="2"><v>'.number_format((float) $conta->valor, 2, '.', '').'</v></c>';
            $linhas_xml .= $celula_texto('G'.$numero_linha, ((int) $conta->status === 0) ? 'Em aberto' : 'Paga');
            $linhas_xml .= $celula_data('H'.$numero_linha, $conta->data_pago);
            $linhas_xml .= $celula_texto('I'.$numero_linha, isset($nomes_forma_pgto[$conta->forma_pgto]) ? $nomes_forma_pgto[$conta->forma_pgto] : '-');
            $linhas_xml .= '</row>';
            $numero_linha++;
        }

        $ultima_linha = max(1, $numero_linha - 1);
        $tipos_conteudo = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
        $relacoes_raiz = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Contas a Pagar" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
        $relacoes_workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
        $estilos = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<numFmts count="2"><numFmt numFmtId="164" formatCode="dd/mm/yyyy"/><numFmt numFmtId="165" formatCode="&quot;R$&quot; #,##0.00"/></numFmts>'
            .'<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            .'<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'
            .'<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="4"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/><xf numFmtId="165" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
            .'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            .'</styleSheet>';
        $planilha = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<dimension ref="A1:I'.$ultima_linha.'"/>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/><selection pane="bottomLeft" activeCell="A2" sqref="A2"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="15"/>'
            .'<cols><col min="1" max="1" width="10" customWidth="1"/><col min="2" max="2" width="32" customWidth="1"/><col min="3" max="4" width="30" customWidth="1"/><col min="5" max="5" width="15" customWidth="1"/><col min="6" max="6" width="14" customWidth="1"/><col min="7" max="7" width="16" customWidth="1"/><col min="8" max="8" width="15" customWidth="1"/><col min="9" max="9" width="18" customWidth="1"/></cols>'
            .'<sheetData>'.$linhas_xml.'</sheetData>'
            .'<autoFilter ref="A1:I'.$ultima_linha.'"/>'
            .'<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>'
            .'</worksheet>';

        $this->load->library('zip');
        $this->zip->add_data(array(
            '[Content_Types].xml' => $tipos_conteudo,
            '_rels/.rels' => $relacoes_raiz,
            'xl/workbook.xml' => $workbook,
            'xl/_rels/workbook.xml.rels' => $relacoes_workbook,
            'xl/styles.xml' => $estilos,
            'xl/worksheets/sheet1.xml' => $planilha
        ));
        $conteudo = $this->zip->get_zip();

        $nome = 'contas-a-pagar_'.date('Ymd_His').'.xlsx';
        $this->output->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->set_header('Content-Disposition: attachment; filename="'.$nome.'"')
            ->set_header('Content-Length: '.strlen($conteudo))
            ->set_output($conteudo);
    }
}
