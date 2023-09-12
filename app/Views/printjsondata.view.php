<div class="container">
    <div class="row">
        <div class=" col-md-6 mx-auto">
            <img src="<?=ROOT?>assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
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
                    if ($data != null) {
                        showData($data['json']);
                    }
                ?>
        </textarea>
        <div class="form-group">
            <label for="month">Εισαγωγή του Μήνα:</label>
            <input type="number" class="form-control" name="month" placeholder="Εισαγωγή του Μήνα" value="<?=(isset($_POST['month'])) ? $_POST['month'] : "";?>" required min="1" max="12">
        </div>
        <div class="form-group">
            <label for="year">Εισαγωγή Χρονολογίας:</label>
            <input type="number" class="form-control" name="year" placeholder="Εισαγωγή Χρονολογίας" value="<?=(isset($_POST['year'])) ? $_POST['year'] : "";?>" required min="2023" max="2033">
        </div>
        <div class="form-group">
            <label for="pageNumber">Αριθμός Σελίδας:</label>
            <input type="number" class="form-control" name="pagenumber" placeholder="Αριθμός Σελίδας" value="<?=(isset($_POST['pagenumber'])) ? $_POST['pagenumber'] : "";?>" required min="1" max="12">
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
        <a href="<?=ROOT?>" type="submit" class="btn btn-primary w-100 mb-3">Επιστροφή</a>
    </form>
</div>