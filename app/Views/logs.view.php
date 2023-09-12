<div class="container">
    <div class="row>
            <div class=" col-md-6 mx-auto">
        <img src="assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
    </div>
</div>
<div class="container">
    <div>
        <h1 class="header">Αρχείο Καταγραφής Εφαρμογής</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">lastDate</th>
                    <th scope="col">InsertedMonth</th>
                    <th scope="col">InsertedYear</th>
                    <th scope="col">InsertedPage</th>
                </tr>
            </thead>
            <?php
            if (isset($data['logs'])) {
                for ($i = 0; $i < sizeof($data['logs']); $i++) { ?>
                        <tbody>
                            <th scope="col"><?= $data['logs'][$i]->id?></th>
                            <td><?= $data['logs'][$i]->lastDate?></td>
                            <td><?= $data['logs'][$i]->InsertedMonth?></td>
                            <td><?= $data['logs'][$i]->InsertedYear?></td>
                            <td><?= $data['logs'][$i]->InsertedPage?></td>
                        </tbody>
                <?php }
            }
            ?>
        </table>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center mt-5 mb-3">
        <a href="<?= ROOT ?>" type="submit" class="btn btn-primary mb-3">Επιστροφή</a>
    </div>
</div>