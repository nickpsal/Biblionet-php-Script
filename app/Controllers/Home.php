<?php
    class Home
    {
        use Controller;
        public function index($data = [])
        {
            if (empty($_SESSION['user_data'])) {
                message("Θα πρέπει να συνδεθείτε πρώτα!!!!");
                redirect('login');
            } else {
                $data['datetime'] = getLastgrabDate();
                $this->view('home', $data);
            }
        }

        public function getjsondata($data = [])
        {
            if (empty($_SESSION['user_data'])) {
                message("Θα πρέπει να συνδεθείτε πρώτα!!!!");
                redirect('login');
            } else {
                $request = new Request();
                if ($request->is_get()) {
                    $this->view('getjsondata', $data);
                } else if ($request->is_post()) {
                    $Month = $_POST['month'];
                    $Year = $_POST['year'];
                    $PageNumber = $_POST['pagenumber'];
                    $data = saveBookData($Month, $Year, $PageNumber);
                    $message = "Αποτελέσματα : Περάστηκαν με Επιτυχία </br>";
                    $message .= "Αριθμός Συγγραφέων : " . $data['author_counter'] . "</br>";
                    $message .= "Αριθμός Κατηγοριών : " . $data['category_counter'] . "</br>";
                    $message .= "Αριθμός Εκδοτών    : " . $data['publisher_counter'] . "</br>";
                    $message .= "Αριθμός Βιβλίων    : " . $data['books_counter'] . "</br>";
                    message($message);
                    $this->view('getjsondata', $data);
                }
            }
        }

        public function printjsondata($data = [])
        {
            if (empty($_SESSION['user_data'])) {
                message("Θα πρέπει να συνδεθείτε πρώτα!!!!");
                redirect('login');
            } else {
                $request = new Request();
                if ($request->is_get()) {
                    $this->view('printjsondata', $data);
                } else if ($request->is_post()) {
                    $Month = $_POST['month'];
                    $Year = $_POST['year'];
                    $data = printJsonBookData($Month, $Year);
                    $this->view('printjsondata', $data);
                }
            }
        }
    }
