<?php
class Members extends Trongate {

    public function login(): void {
        $this->_init_csrf_token();
        $data['view_module'] = 'members';
        $data['view_file'] = 'login';
        $this->templates->public($data);
    }

    public function register(): void {
        $this->_init_csrf_token();
        $data['view_module'] = 'members';
        $data['view_file'] = 'register';
        $this->templates->public($data);
    }

    public function submit_registration(): void {
        $submit = post('submit');

        if ($submit !== 'Registrieren') {
            redirect('register');
        }

        // 1. Basic Validation
        $this->validation->set_rules('username', 'Benutzername', 'required|min_length[2]|max_length[65]');
        $this->validation->set_rules('email', 'E-Mail Adresse', 'required|valid_email');
        $this->validation->set_rules('password', 'Passwort', 'required|min_length[8]');

        $result = $this->validation->run();

        if ($result === true) {
            $username = post('username', true);
            $email = post('email', true);

            // 2. Manual check for Uniqueness (since callbacks fail with custom routing)
            $username_available = $this->model->is_username_available($username);
            $email_available = $this->model->is_email_available($email);

            if (!$username_available || !$email_available) {
                // Manual error reporting
                if (!$username_available) {
                    $_SESSION['form_submission_errors']['username'][] = 'Dieser Benutzername ist bereits vergeben.';
                }
                if (!$email_available) {
                    $_SESSION['form_submission_errors']['email'][] = 'Diese E-Mail Adresse ist bereits registriert.';
                }
                $this->register();
                return;
            }

            // 3. Success -> Insert
            try {
                $data = [
                    'username' => $username,
                    'email' => $email,
                    'password' => post('password')
                ];

                $this->model->create_new_member($data);
                set_flashdata('Konto erfolgreich erstellt. Bitte melde dich an.');
                redirect('login');
            } catch (Exception $e) {
                http_response_code(500);
                echo "<h1>Registrierungsfehler</h1>";
                echo "<p>Fehler: " . $e->getMessage() . "</p>";
                die();
            }
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

        $this->validation->set_rules('email', 'E-Mail Adresse', 'required|valid_email');
        $this->validation->set_rules('password', 'Passwort', 'required');

        $result = $this->validation->run();

        if ($result === true) {
            $email = post('email', true);
            $password = post('password');

            $credentials_valid = $this->model->validate_credentials($email, $password);

            if ($credentials_valid === false) {
                $_SESSION['form_submission_errors']['email'][] = 'Ungültige Anmeldedaten.';
                $this->login();
                return;
            }

            // Log user in
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

    public function db_test(): void {
        echo "<h1>Datenbank Diagnose</h1>";
        
        // 1. Check Connection
        try {
            $sql = "SELECT DATABASE() as db_name";
            $rows = $this->model->db->query($sql, 'object');
            echo "<p>Verbunden mit Datenbank: <strong>" . $rows[0]->db_name . "</strong></p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>Verbindungsfehler: " . $e->getMessage() . "</p>";
            die();
        }

        // 2. Check Tables
        echo "<h2>Vorhandene Tabellen:</h2>";
        $rows = $this->model->db->query("SHOW TABLES", 'array');
        echo "<ul>";
        foreach ($rows as $row) {
            echo "<li>" . array_values($row)[0] . "</li>";
        }
        echo "</ul>";

        // 3. Check 'members' table structure
        echo "<h2>Struktur von 'members':</h2>";
        try {
            $rows = $this->model->db->query("DESCRIBE members", 'object');
            echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
            foreach ($rows as $row) {
                echo "<tr><td>{$row->Field}</td><td>{$row->Type}</td></tr>";
            }
            echo "</table>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>Tabelle 'members' nicht gefunden oder Fehler: " . $e->getMessage() . "</p>";
        }

        // 4. Test Insert
        echo "<h2>Test Schreibvorgang:</h2>";
        try {
            $test_data = [
                'username' => 'testuser_' . time(),
                'email' => 'test@test.de',
                'password' => 'testpass',
                'trongate_user_id' => 0,
                'date_created' => time()
            ];
            $insert_id = $this->model->db->insert($test_data, 'members');
            echo "<p style='color:green;'>Erfolg! Test-Datensatz geschrieben. ID: <strong>$insert_id</strong></p>";
            
            // Clean up
            $this->model->db->query("DELETE FROM members WHERE id = $insert_id");
            echo "<p>Test-Datensatz wurde wieder gelöscht.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>Schreibfehler: " . $e->getMessage() . "</p>";
        }

        die("<hr>Diagnose beendet.");
    }

    /**
     * Ensures a CSRF token exists in the session.
     */
    private function _init_csrf_token(): void {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

}
