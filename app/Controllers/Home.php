<?php
class Home
{
    use Controller;
    public function index($data = [])
    {
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
}
