<?php
class Members extends Trongate {

    public function login(): void {
        $data['view_module'] = 'members';
        $data['view_file'] = 'login';
        $this->templates->public($data);
    }

    public function register(): void {
        $data['view_module'] = 'members';
        $data['view_file'] = 'register';
        $this->templates->public($data);
    }

    public function submit_registration(): void {
        $submit = post('submit');

        if ($submit !== 'Registrieren') {
            redirect('register');
        }

        // Set validation rules
        $this->validation->set_rules('username', 'Benutzername', 'required|min_length[2]|max_length[65]|callback_username_check');
        $this->validation->set_rules('email', 'E-Mail Adresse', 'required|valid_email|callback_email_check');
        $this->validation->set_rules('password', 'Passwort', 'required|min_length[8]');

        $result = $this->validation->run();

        if ($result === true) {
            // Success
            $data = [
                'username' => post('username', true),
                'email' => post('email', true),
                'password' => post('password')
            ];

            $this->model->create_new_member($data);
            set_flashdata('Konto erfolgreich erstellt. Bitte melde dich an.');
            redirect('login');
        } else {
            // Validation failed
            $this->register();
        }
    }

    public function submit_login(): void {
        $submit = post('submit');

        if ($submit !== 'Anmelden') {
            redirect('login');
        }

        $this->validation->set_rules('email', 'E-Mail Adresse', 'required|valid_email|callback_login_check');
        $this->validation->set_rules('password', 'Passwort', 'required');

        $result = $this->validation->run();

        if ($result === true) {
            // Log user in
            $email = post('email', true);
            $this->model->log_user_in($email);
            redirect('dashboard');
        } else {
            $this->login();
        }
    }

    public function logout(): void {
        $this->module('trongate_tokens');
        $this->trongate_tokens->destroy();
        redirect('/');
    }

    /* --- Callbacks --- */

    public function username_check(string $str): string|bool {
        block_url('members/username_check');
        $is_available = $this->model->is_username_available($str);
        if ($is_available === false) {
            return 'Dieser Benutzername ist bereits vergeben.';
        }
        return true;
    }

    public function email_check(string $str): string|bool {
        block_url('members/email_check');
        $is_available = $this->model->is_email_available($str);
        if ($is_available === false) {
            return 'Diese E-Mail Adresse ist bereits registriert.';
        }
        return true;
    }

    public function login_check(string $email): string|bool {
        block_url('members/login_check');
        $password = post('password');
        $credentials_valid = $this->model->validate_credentials($email, $password);

        if ($credentials_valid === false) {
            return 'Ungültige Anmeldedaten.';
        }
        return true;
    }

}
