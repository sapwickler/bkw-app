<?php
class Dashboard extends Trongate {

    public function index(): void {
        $this->trongate_security->make_sure_allowed();

        $data['view_module'] = 'dashboard';
        $data['view_file'] = 'home';
        $this->templates->public($data);
    }

}
