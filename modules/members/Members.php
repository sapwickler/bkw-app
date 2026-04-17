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

}
