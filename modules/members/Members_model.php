<?php
class Members_model extends Model {

    private string $table_name = 'members';

    /**
     * Find a single record based on a column value.
     */
    private function find_one(string $column, $value): object|false {
        $sql = "SELECT * FROM {$this->table_name} WHERE {$column} = :{$column} LIMIT 1";
        $params = [$column => $value];
        $rows = $this->db->query_bind($sql, $params, 'object');
        return !empty($rows) ? $rows[0] : false;
    }

    /**
     * Get a user record by email.
     */
    public function get_user_by_email(string $email): object|false {
        return $this->find_one('email', $email);
    }

    /**
     * Validate credentials by email and password.
     */
    public function validate_credentials(string $email, string $password): bool {
        $user = $this->get_user_by_email($email);
        if ($user === false) {
            return false;
        }
        return password_verify($password, $user->password);
    }

    /**
     * Check if a username is available.
     */
    public function is_username_available(string $username): bool {
        return ($this->find_one('username', $username) === false);
    }

    /**
     * Check if an email is available.
     */
    public function is_email_available(string $email): bool {
        return ($this->find_one('email', $email) === false);
    }

    /**
     * Handle user registration.
     */
    public function create_new_member(array $data): int {
        // 1. Create trongate_users record
        $trongate_user_data = [
            'code' => make_rand_str(32),
            'user_level_id' => 2 // 'member' level
        ];
        $trongate_user_id = $this->db->insert($trongate_user_data, 'trongate_users');

        // 2. Prepare member data
        $member_data = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 11]),
            'trongate_user_id' => $trongate_user_id,
            'date_created' => time()
        ];

        // 3. Insert into members table
        return $this->db->insert($member_data, $this->table_name);
    }

    /**
     * Log a user in by email.
     */
    public function log_user_in(string $email, int $remember = 0): string|bool {
        $this->module('trongate_tokens');
        $user = $this->get_user_by_email($email);

        if ($user === false) {
            return false;
        }

        $token_data = [
            'user_id' => (int) $user->trongate_user_id
        ];

        if ($remember === 1) {
            $token_data['expiry_date'] = time() + (86400 * 30);
            $token = $this->trongate_tokens->generate_token($token_data);
            setcookie('trongatetoken', $token, $token_data['expiry_date'], '/');
            return $token;
        }

        $token = $this->trongate_tokens->generate_token($token_data);
        $_SESSION['trongatetoken'] = $token;
        return $token;
    }
}
