<?php
class Logs
{
    use Controller;
    public function index($data = [])
    {
        if (empty($_SESSION['user_data'])) {
            message("Θα πρέπει να συνδεθείτε πρώτα!!!!");
            redirect('login');
        } else {
            $biblionetScript = new biblionetScript();
            $data['logs'] = $biblionetScript->find_all();
            $request = new Request();
            if ($request->is_get()) {
                $this->view('logs', $data);
            } else {
                exportPDF($data);
            }
        }
    }

    public function delete($data = []) {
        if (empty($_SESSION['user_data'])) {
            message("Θα πρέπει να συνδεθείτε πρώτα!!!!");
            redirect('login');
        } else {
            $request = new Request();
            if ($request->is_get()) {
                $biblionetScript = new biblionetScript();
                $biblionetScript->delete($data[2]);
                redirect('logs');
            }
        }
    }
}
