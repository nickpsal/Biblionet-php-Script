<div class="container">
    <div class="row">
        <div class=" col-md-6 mx-auto">
            <img src="<?= ROOT ?>assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
        </div>
    </div>
</div>
<div class="container">
    <div>
        <h1 class="header">Σύστημα Διασύνδεσης με την βαση Δεδομενων της Biblionet</h1>
        <div class="custom-text">Δηλώστε τον Μήνα την Χρονολογία και τον Αριθμό της Σελίδας που θελετε να πάρετε τα Δεδομενα</div>
    </div>
</div>
<div class="container">
    <form action="" method="post">
        <textarea class="form-control centered-textarea" id="data" name="data" rows="10" readonly>
            <?php
                $counter = 0;
                if ($data != null) {
                    for ($i=0; $i<count($data['data']); $i++) {
                        echo "Σελίδα " . $i+1 . "\n";
                        foreach($data['data'][$i][0] as $book){
                            echo "Title => " . $book->Title . "\n";
                            echo "Cover Image => " . $book->CoverImage . "\n";
                            echo "ISBN => " . $book->ISBN . "\n";
                            echo "Writer => " . $book->Writer . "\n";
                            echo "Publisher => " . $book->Publisher . "\n";
                            echo "Price => " . $book->Price . "\n";
                            echo "Summary => " . $book->Summary . "\n";   
                            $counter++;
                        }  
                        echo "---------------------------------------------------------------------------------------------------------------------------------------------------------\n";   
                    }            
                }
            ?>
        </textarea>
        <div class="custom-text">Αποτελέσματα</div>
        <div class="custom-text">Συνολικός Αριθμός Bιβλίων : <?= $counter ?? '' ?></div>
        <div class="custom-text">Αριθμός Σελίδων : <?= $data['PageNumber'] ?? '' ?></div>
        <div class="form-group">
            <label for="month">Εισαγωγή του Μήνα:</label>
            <input type="number" class="form-control" name="month" placeholder="Εισαγωγή του Μήνα" value="<?= (isset($_POST['month'])) ? $_POST['month'] : ""; ?>" required min="1" max="12">
        </div>
        <div class="form-group">
            <label for="year">Εισαγωγή Χρονολογίας:</label>
            <input type="number" class="form-control" name="year" placeholder="Εισαγωγή Χρονολογίας" value="<?= (isset($_POST['year'])) ? $_POST['year'] : ""; ?>" required min="2023" max="2033">
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
        <a href="<?= ROOT ?>" type="submit" class="btn btn-primary w-100 mb-3">Επιστροφή</a>
    </form>
</div>