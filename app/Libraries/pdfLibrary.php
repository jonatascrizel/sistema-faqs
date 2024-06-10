<?php

namespace App\Libraries;

use \Mpdf\Mpdf;

class pdfLibrary
{
  public $mpdf;

  public function __construct()
  {

    $this->defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $this->defaultConfig['fontDir'];

    $this->defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $this->defaultFontConfig['fontdata'];

    $this->mpdf = new Mpdf(
      [
        'fontDir' => array_merge($fontDirs, [
          base_url('assets/webfonts'),
        ]),
        'fontdata' => $fontData + [
          'OpenSans' => [
            'R' => "OpenSans-Medium.ttf",
            'B' => "OpenSans-Bold.ttf",
            'I' => "OpenSans-Italic.ttf",
            'BI' => "OpenSans-BoldItalic.ttf",
          ]
        ],
        'mode' => 'utf-8',
        'orientation' => 'P',
        'tempDir' => __DIR__ . '/tmp',
        'setAutoTopMargin' => false,
        'margin_top' => 45,
        'margin_left' => 20,
        'margin_right' => 20,
        'margin_bottom' => 25,
        'margin_header' => 15,
      ]
    );

    $this->mpdf->AddFontDirectory(base_url('assets/webfonts'));

    $this->mpdf->allow_charset_conversion = true;

    $this->mpdf->SetAuthor(session()->get('SEO_title'));
    $this->PDFX = true;

    if (ENVIRONMENT == 'development')
      $this->PATH_IMG = ROOTPATH . 'public\assets\img\\';
    else
      $this->PATH_IMG = ROOTPATH . 'public/assets/img/';
    
    $arrContextOptions = array(
      'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false
      )
    );
    $stylesheet = file_get_contents(base_url('assets/css/pdf.css'),false,stream_context_create($arrContextOptions));
    $this->mpdf->WriteHTML($stylesheet,1);

    $this->mpdf->SetHTMLHeader('
        <div style="display: block;">
          <div style="width: 65%; float:left;">
            <img src="' . $this->PATH_IMG . 'logo.png" alt="'.session()->get('SEO_title').'" height="2cm" />
          </div>
          <div style="width: 35%; float:rigth; font-size: 12px">
            CNPJ: '.session()->get('cnpj').'<br />
            E-mail: '.session()->get('emlContato').'<br />
            Fone: '.session()->get('telefone').'<br />
            Endereço: '.session()->get('endereco').'<br />
          </div>         
        </div>
        <hr />');

    $this->mpdf->SetHTMLFooter('');
  }

  public function downloadPDF($filename)
  {
    $this->mpdf->Output($filename, 'D');
  }

  public function assinaturaOrcamento()
  {
    $html = '
    <table width="100%" border="0" class="no-border">
            <tr>
                <td width="10%"></td>
                <td width="45%"></td>
                <td width="10%"></td>
                <td width="45%" style="height: 80px;"></td>
                <td width="10%"></td>
            </tr>
            <tr>
                <td width="10%"></td>
                <td width="45%" style="border-top:2px solid #000; text-align: center; font-size: 12px; vertical-align:top;"><br />
                  [NOME_CLIENTE]<br />
                </td>
                <td width="10%"></td>
                <td width="45%" style="border-top:2px solid #000; text-align: center; font-size: 12px; height: 65px;"><br />
                  JHilgert LTDA<br />
                </td>
                <td width="10%"></td>
            </tr>
        </table>
    ';

    return $html;
  }


}
