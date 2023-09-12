<?php
class Home
{
    use Controller;
    public function index($data = [])
    {
        $data['datetime'] = getLastgrabDate();
        $this->view('home', $data);
    }

    public function getjsondata($data = [])
    {
        if (ismethodGet()) {
            $this->view('getjsondata', $data);
        }else if (ismethodPost()) {
            $Month = $_POST['month'];
            $Year = $_POST['year'];
            $PageNumber = $_POST['pagenumber'];
            $data = BookData($Month, $Year, $PageNumber);
            $this->view('getjsondata', $data);
        }
    }

    public function printjsondata($data = [])
    {
        if (ismethodGet()) {
            $this->view('printjsondata', $data);
        }else if (ismethodPost()) {
            $Month = $_POST['month'];
            $Year = $_POST['year'];
            $PageNumber = $_POST['pagenumber'];
            $data['json'] = grabJsonBookData($Month, $Year, $PageNumber);
            $this->view('printjsondata', $data);
        }
    }

    public function logs($data = []) {
        $biblionetScript = new biblionetScript();
        $data['logs'] = $biblionetScript->find_all();
        $this->view('logs', $data);
    }
}
