<div class="container">
    <div class="row>
            <div class=" col-md-6 mx-auto">
        <img src="assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
    </div>
</div>
<div class="container">
    <div>
        <h1 class="header">Σύστημα Διασύνδεσης με την βαση Δεδομενων της Biblionet</h1>
        <div class="custom-text">Αυτη η εφαρμογή δημιουργήθηκε απο την Datatex.gr γιά την εταιρία Juliet Αστικη μη Κερδοσκοπική</div>
        <div class="custom-text">Χρησιμοποιει το λινκ απο την Biblionet με το username και το password γιά να πάρει</div>
        <div class="custom-text">τις κυκλοφορίες του τελευταίου Μήυα απευθειας στην Βάση Δεδομένων για το Joomla και την Alexandria Book Library</div>
        <div class="custom-text">Σημερινή Ημερομηνία <?= getCurrentDate(); ?></div>
        <div class="custom-text">
            <?php
                $datetime = getLastgrabDate();
                if (isset($dateTime)) {
                    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                    $formattedDate = $dateTime->format('d-m-Y H:i:s');
                    echo "Τελευταία Ημερομηνία που έτρεξες την Εφαρμογή : " . $formattedDate;
                }
            ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center mt-5 mb-3">
        <a href="<?=ROOT?>home/getjsondata" type="submit" class="btn btn-primary mb-3">Περάσμα Δεδομένων απο την Biblionet στην Βάση Δεδομένων</a>
        <a href="<?=ROOT?>home/printjsondata" type="submit" class="btn btn-primary mb-3">Εκτύπωση Δεδομένων απο την Biblionet χωρις να περαστούν στην Βάση</a>
    </div>
</div>