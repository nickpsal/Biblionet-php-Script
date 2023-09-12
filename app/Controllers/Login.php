<?php
    class Login {
        use Controller;
        public function index($data = [])
        {
            if (empty($_SESSION['user_data'])) {
                $request = new Request();
                if ($request->is_get()) {
                    $this->view('login', $data);
                }else if ($request->is_post()) {
                    $data['username'] = $_POST['username'];
                    $user = new User;
                    $res = $user->get_first_from_db($data);
                    if (!empty($res)) {
                        if ($request->get_value_post('username') === $res->username && password_verify($request->get_value_post('password'), $res->password)) {
                            unset($res->password);
                            $_SESSION['user_data'] = $res;
                            message('Εχετε συνδεθεί με επιτυχία! ' . $_SESSION['user_data']->username);
                            redirect('home');
                        }else if ($request->get_value_post('username') === $res->username && !password_verify($request->get_value_post('password'), $res->password)){
                            message('Λανθασμένος Κωδικός Χρήστη Παρακαλω Ξαναδοκιμάστε');
                            redirect('login');
                        }
                    }else {
                        message('Δεν υπάρχει αυτό το όνομα Χρήστη');
                        redirect('login');
                    }
                }
            }else {
                message("Ειστε ήδη συνδεμένοι");
                redirect("home");
            }
        }
    }