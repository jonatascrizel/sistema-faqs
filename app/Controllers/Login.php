<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\CaptchaLibrary;
use App\Libraries\MailcontrolLibrary;

class login extends BaseController
{
    public function index()
    {
        //die(password_hash('', PASSWORD_DEFAULT));

        helper(['form', 'uteis']);
        $data['session'] = $this->session->get();
        $data['gsitekey'] = $this->session->get('reCaptcha_site');
        echo view("site/header");
        echo view('adm/login', $data);
        echo view("site/footer");
    }

    public function auth()
    {
        $captcha = new CaptchaLibrary();
        $status = $captcha->verifyResponse($this->request->getVar('g-recaptcha-response'));
        $session = session();

        if (isset($status) && $status['success']) {
            $model = new UserModel();
            $email = $this->request->getVar('InputForEmail');
            $password = $this->request->getVar('InputForPassword');
            $data = $model->where(['user_email' => $email, 'ativo' => 1])->first();
            //echo '<pre>'; var_dump($data); die;
            if ($data) {
                $pass = $data->user_password;
                $verify_pass = password_verify($password, $pass);
                if ($verify_pass) {
                    $ses_data = [
                        'user_id'       => $data->id,
                        'user_name'     => $data->user_name,
                        'user_email'    => $data->user_email,
                        'logged_in'     => TRUE
                    ];
                    $session->set($ses_data);
                    return redirect()->to(base_url('/dashboard'));
                    exit();
                } else {
                    $session->setFlashdata('msg', 'Senha inválida');
                    return redirect()->to(base_url('/login'));
                }
            } else {
                $session->setFlashdata('msg', 'E-mail não encontrado');
                return redirect()->to(base_url('/login'));
            }
        } else {
            log_message('error', 'Erro de captcha no acesso à área administrativa.');
            $session->setFlashdata('msg', 'Erro na validação do reCaptcha. Tente novamente.');
            return redirect()->to(base_url('/login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/login'));
    }

    public function forget()
    {
        $eml = $this->request->getVar('InputForEmail');
        if ($eml != '') {
            $session = session();
            $mailControl = new MailcontrolLibrary();
            $model = new UserModel();

            $data = $model->where(['user_email' => $eml, 'ativo' => 1])->first();
            if ($data) {
                $senha_pa = gerarSenha(10);

                $dt = [
                    'user_password'  => password_hash($senha_pa, PASSWORD_DEFAULT),
                ];
                $model->update($data->id, $dt);


                $email = [
                    'html' => '<p>Prezado,<br>Segue sua nova senha conforme solicitado pelo site:</p>
                    <p>
                        <strong>Senha: </strong> ' . $senha_pa . '<br>
                        <strong>IP: </strong> ' . $_SERVER['REMOTE_ADDR'] . '<br>
                        <strong>Data: </strong> ' . date('d/m/Y H:i') . '
                    </p>',
                    'subject' => 'Lembrete de senha!',
                    'to' => array(
                        array(
                            'email' => $eml,
                        ),
                    ),
                    'from' => array(
                        'email' => $this->session->get('SENDER_MAIL'),
                        'name' => $this->session->get('SENDER_NAME')
                    ),
                ];

                $mailControl->sendEmail($email);

                $session->setFlashdata('msg', 'Lembrete enviado para o e-mail informado');
                return redirect()->to(base_url('/login'));
            } else {
                $session->setFlashdata('msg', 'E-mail não encontrado');
                return redirect()->to(base_url('/login'));
            }
        }
    }

    public function teste(){
        echo password_hash('metallica69', PASSWORD_DEFAULT);
    }
}
