<div class="container">
    <div class="row">
        <div class=" col-md-6 mx-auto">
            <img src="<?= ROOT ?>assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
        </div>
    </div>
</div>
<div class="container">
    <h1 class="header">Σύστημα Διασύνδεσης με την βαση Δεδομενων της Biblionet</h1>
    <div class="custom-text">Αυτη η εφαρμογή δημιουργήθηκε απο την Datatex.gr γιά την εταιρία Juliet Αστικη μη Κερδοσκοπική</div>
    <div class="custom-text">Δώστε το όνομα χρήστη της εφαρμογής και τον κωιδκό για να συνδεθείτε</div>
</div>
<div class="container">
    <div class="custom-text">
        <?php
        if (message()) {
            echo message('', true);
        }
        ?>
    </div>
</div>
<div class="container">
    <form action="" method="post">
        <div class="form-outline mb-4">
            <input type="text" id="form2Example1" name="username" class="form-control" />
            <label class="form-label" for="form2Example1">Username</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" id="form2Example2" name="password" class="form-control" />
            <label class="form-label" for="form2Example2">Password</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block mb-4 w-100">Sign in</button>
    </form>
</div>