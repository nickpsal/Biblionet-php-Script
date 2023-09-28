<div class="container">
    <div class="row">
        <div class=" col-md-6 mx-auto">
            <img src="<?= ROOT ?>assets/img/juliet.jpg" class="img-fluid rounded" alt="Image" width="300">
        </div>
    </div>
</div>
<div class="container">
    <form action="" method="post">
        <button type="submit" class="btn btn-primary w-100 mb-3">Αποθήκευση σε PDF</button>
        <a href="<?= ROOT ?>" type="submit" class="btn btn-primary w-100 mb-3">Επιστροφή</a>
    </form>
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
                    <th scope="col">InsertedAuthors</th>
                    <th scope="col">InsertedCategories</th>
                    <th scope="col">InsertedPublishers</th>
                    <th scope="col">InsertedBooks</th>
                </tr>
            </thead>
            <?php
            if (isset($data['logs'])) {
                if ($data['logs'] != false) {
                    for ($i = 0; $i < sizeof($data['logs']); $i++) { ?>
                        <tbody>
                            <th scope="col"><?= $data['logs'][$i]->id ?></th>
                            <td><?= formatDate($data['logs'][$i]->lastDate) ?></td>
                            <td><?= $data['logs'][$i]->InsertedMonth ?></td>
                            <td><?= $data['logs'][$i]->InsertedYear ?></td>
                            <td><?= $data['logs'][$i]->InsertedPage ?></td>
                            <td><?= $data['logs'][$i]->InsertedAuthors ?></td>
                            <td><?= $data['logs'][$i]->InsertedCategories ?></td>
                            <td><?= $data['logs'][$i]->InsertedPublishers ?></td>
                            <td><?= $data['logs'][$i]->InsertedBooks ?></td>
                            <td><a href="logs/delete/<?=$data['logs'][$i]->id?>">Delete</a></td>
                        </tbody>
            <?php }
                }
            }
            ?>
        </table>
    </div>
</div>