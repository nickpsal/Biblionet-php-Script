<?php

function checkDatabaseTables()
{
    $User = new User();
    $biblionet = new biblionetScript();
    $res = $User->checkifTableexists();
    $res2 = $biblionet->checkifTableexists();
    if ($res == false) {
        initialiseUserTable();
    }
    if ($res2 == false) {
        initialiseBiblionetScriptTable();
    }
}

function initialiseUserTable()
{
    $User = new User();
    $res1 = $User->createTable();
    $data['fullname'] = "datatex.gr";
    $data['username'] = "nickpsal";
    $password = password_hash("NIKOS2908biblionet", PASSWORD_DEFAULT);
    $data['password'] = $password;
    $res2 = $User->insert($data);
}

function initialiseBiblionetScriptTable()
{
    $biblionetScript = new biblionetScript();
    $res = $biblionetScript->createTable();
}

function getCurrentDate()
{
    // Set the timezone to GMT+3 (Eastern European Time)
    $timezone = new DateTimeZone('Europe/Athens');
    // Create a DateTime object with the specified timezone
    $date = new DateTime('now', $timezone);
    return $date->format('d-m-Y H:i');
}

function formatDate($date)
{
    $lastImportDate = DateTime::createFromFormat('Y-m-d H:i', $date);
    return $lastImportDate->format('d-m-Y H:i');
}

function getCurrentDate2()
{
    // Set the timezone to GMT+3 (Eastern European Time)
    $timezone = new DateTimeZone('Europe/Athens');
    // Create a DateTime object with the specified timezone
    $date = new DateTime('now', $timezone);
    return $date->format('Y-m-d H:i');
}

function  getLastgrabDate()
{
    $lastDate = new biblionetScript();
    $res = $lastDate->getLastDate();
    if ($res != false) {
        $lastImportDate = DateTime::createFromFormat('Y-m-d H:i', $res[0]->lastDate);
        return $lastImportDate->format('d-m-Y H:i');
    }
}

function showData($stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function saveBookData($monthNumber, $YearNumber, $PageNumber)
{
    $date = getCurrentDate();
    $date2 = getCurrentDate2();
    $authors_counter = 0;
    $publishers_counter = 0;
    $categorys_counter = 0;
    $books_counter = 0;
    //Database tables 
    $Abauthor = new Abauthor();
    $Abbook = new Abbook();
    $Abcategories = new Abcategories();
    $Abeditor = new Abeditor();
    $abbookAuth = new Abbookauth();
    $lastDate = new biblionetScript();
    $menu = new Menu();
    $new_isset_id = get_current_isset_id($Abcategories, $Abbook) + 1;
    //initialize cURL`
    $curl = curl_init();
    $returnedResult = grabJsonBookData($monthNumber, $YearNumber, $PageNumber, $curl);
    if (is_array($returnedResult)) {
        foreach ($returnedResult[0] as $book) {
            $returnResults = fixData($book, $curl);
            if (isset($returnResults['data1'])) {
                $data1 = $returnResults['data1'];
            }
            $data2 = $returnResults['data2'];
            $data3 = $returnResults['data3'];
            $data4 = $returnResults['data4'];
            // starting saving data to database
            //---------------------------------
            //checking if author exists in Database 
            if (isset( $data1['lastname']) && isset($data1['lastname'])) {
                $datatofind1['lastname'] = $data1['lastname'];
                $datatofind1['name'] = $data1['name'];
            }
            $authorsFromDB = $Abauthor->get_first_from_db($datatofind1);
            if (empty($authorsFromDB)) {
                insertAuthorData($Abauthor, $data1);
                $authors_counter++;
            }
            //checking if category exists in Database 
            $datatoFind2['title'] = $data2['title'];
            $categoryFromDB = $Abcategories->get_first_from_db($datatoFind2);
            if (empty($categoryFromDB)) {
                insertCategoryData($Abcategories, $data2, $new_isset_id, $date2);
                $categorys_counter++;
            }
            //checking if publisher exists in Database 
            $datatoFind3['name'] = $data3['name'];
            $EditorFromDB = $Abeditor->get_first_from_db($datatoFind3);
            if (empty($EditorFromDB)) {
                insertPublisherData($Abeditor, $data3);
                $publishers_counter++;
            }
            //checking if book exists in Database 
            $datatoFind4['title'] = $data4['title'];
            $BooksFromDb = $Abbook->get_first_from_db($datatoFind4);
            if (empty($BooksFromDb)) {
                $data4['description'] = str_replace("'", "", $data4['description']);
                $datatofind5['name'] = $data3['name'];
                $res = $Abeditor->get_first_from_db($datatofind5);
                $editorID = $res->id;
                $datatoFind6['title'] = $data2['title'];
                $res = $Abcategories->get_first_from_db($datatoFind6);
                $categoryID = $res->id;
                //Creating sql query for books table in database
                $new_isset_id++;
                insertBookData($Abbook, $Abauthor, $abbookAuth, $new_isset_id, $editorID, $YearNumber, $data1, $date2, $data4, $categoryID, $menu);
                $books_counter++;
            }
            // end saving data to database
            //---------------------------------
        }
    }
    $returned_data['author_counter'] = $authors_counter;
    $returned_data['category_counter'] = $categorys_counter;
    $returned_data['publisher_counter'] = $publishers_counter;
    $returned_data['books_counter'] = $books_counter;
    $data6['lastDate'] = $date2;
    $data6['InsertedMonth'] = $monthNumber;
    $data6['InsertedYear'] = $YearNumber;
    $data6['InsertedPage'] = $PageNumber;
    $data6['InsertedAuthors'] = $authors_counter;
    $data6['InsertedCategories'] = $categorys_counter;
    $data6['InsertedPublishers'] = $publishers_counter;
    $data6['InsertedBooks'] = $books_counter;
    $res11 = $lastDate->insert($data6);
    return $returned_data;
}

function fixData($book, $curl){
    if (isset($book->WriterID)) {
        $authorData = grabJsonAuthorData($book->WriterID, $curl);
    }
    if (isset($book->Title)) {
        $data4['title'] = $book->Title;
        //remoce special characters from title
        $data4['title'] = str_replace(':', '', $data4['title']);
        $data4['title'] = str_replace('&', '', $data4['title']);
        $data4['alias'] = slug_gen2($data4['title']);
    }
    if (isset($book->Category)) {
        $data2['title'] = $book->Category;
    }
    if (isset($book->Price)) {
        $data4['price'] = $book->Price;
        $data4['price'] = number_format($data4['price'], 2);
    }
    if (isset($book->PageNo)) {
        $data4['pag'] = $book->PageNo;
    }
    if (isset($book->Summary)) {
        $data4['description'] = $book->Summary;
    }
    if (isset($book->CoverImage)) {
        $img = $book->CoverImage;
        $coverImageURL = 'https://www.biblionet.gr' . $img;
        $filename = basename($coverImageURL);
        //downlaod book cover image
        download_Cover_Image($coverImageURL, $filename);
        //local image path
        $data4['image'] = "images/biblionet/" . $filename;
    }
    if (isset($book->ISBN)) {
        $data4['isbn'] = $book->ISBN;
    }
    if (isset($book->Publisher)) {
        if ($book->Publisher == "Κουίντα") {
            $data3['name'] = "Εκδοσεις ΚΟΥΙΝΤΑ";
        } else {
            $data3['name'] = $book->Publisher;
        }
    }
    if (isset($authorData[0][0]->Photo)) {
        $img = $authorData[0][0]->Photo;
        $authorPhotoURL = 'https://www.biblionet.gr' . $img;
        $filename = basename($authorPhotoURL);
        downlaod_Author_image($authorPhotoURL, $filename);
        $data1['image'] = 'images/biblionet/Authors/' . $filename;
    }
    if (isset($authorData[0][0]->Name) && isset($authorData[0][0]->Surname)) {
        $data1['name'] = $authorData[0][0]->Name;
        $data1['lastname'] = $authorData[0][0]->Surname;
        // Concatenate last name and first name
        $data1['alias'] = slug_gen($data1['lastname'], $data1['name']);
        if (isset($authorData[0][0]->Biography)) {
            $data1['description'] = str_replace(array('<<', '"', "'"), ' ', str_replace(array("\r", "\n"), ' ', $authorData[0][0]->Biography));
        }
        $returnedResult = [
            "data1" => $data1,
            "data2" => $data2,
            "data3" => $data3,
            "data4" => $data4
        ];
    }else {
        $returnedResult = [
            "data2" => $data2,
            "data3" => $data3,
            "data4" => $data4
        ];
    }
    return $returnedResult;
}

function insertBookData($Abbook, $Abauthor, $abbookAuth, $new_isset_id, $editorID, $YearNumber, $data1, $date2, $data4, $categoryID, $menu)
{
    //run each query to Database
    $data4['asset_id'] = $new_isset_id;
    $data4['subtitle'] = '';
    $data4['ideditor'] = $editorID;
    $data4['pag_index'] = 0;
    $data4['userid'] = 835;
    $data4['created_by_alias'] = '';
    $data4['other_info'] = '';
    $data4['docsfolder'] = '';
    $data4['file'] = '';
    $data4['year'] = $YearNumber;
    $data4['idlocation'] = 0;
    $data4['idlibrary'] = 0;
    $data4['vote'] = 0;
    $data4['numvote'] = 0;
    $data4['hits'] = 0;
    $data4['state'] = 1;
    $data4['dateinsert'] = $date2;
    $data4['catalogo'] = '';
    $data4['checked_out'] = '0';
    $data4['checked_out_time'] = '0000-00-00 00:00:00';
    $data4['access'] = '1';
    $data4['metakey'] = $data4['title'];
    $data4['metadesc'] = $data4['title'];
    $data4['metadata4'] = '{"robots":"","author":"juliet","rights":""}';
    $data4['language'] = '*';
    $data4['ordering`'] = 0;
    $data4['params'] = '{"show_author":"","author_order":"","linkto":"","linkimage":"","view_date":"","show_icons":"","show_print_icon":"","show_hits":"","breadcrumb":"","search":"","view_rate":"","book_layout":"","view_pag_index":""}';
    $data4['catid'] = $categoryID;
    $data4['qty'] = 1;
    $data4['issn'] = '';
    $data4['doi'] = '';
    $data4['numpublication'] = 0;
    $data4['approved'] = 0;
    $data4['userid'] = 835;
    $data4['url'] = '';
    $data4['url_label'] = '';
    $data4['url2'] = '';
    $data4['url2_label'] = '';
    $data4['url3'] = '';
    $data4['url3_label'] = '';
    $data4['note'] = '';
    $data4['editedby'] = 0;
    $res6 = $Abbook->insert($data4);
    //End Creating sql query for books table in database
    //assosiating Author with Book
    $datatoFind7['title'] = $data4['title'];
    $datatoFind8['lastname'] = $data1['lastname'];
    $datatoFind8['name'] = $data1['name'];
    $res7 = $Abbook->get_first_from_db($datatoFind7);
    $res8 = $Abauthor->get_first_from_db($datatoFind8);
    $data5['idbook'] = $res7->id;
    $data5['idauth'] = $res8->id;
    $res10 = $abbookAuth->insert($data5);
    // setting data to menu database
    $categorylink = "index.php?option=com_abook&view=book&id=" . $data5['idbook'];
    $datatoFindID['id'] = '1';
    $resID = $menu->get_first_from_db($datatoFindID);
    //find lft from category and calculate rgt
    $lft = $resID->rgt;
    $rgt = $lft + 1;
    $data6['menutype'] = 'hidden';
    $data6['title'] = $data4['title'];
    $data6['alias'] =   $data4['alias'];
    $data6['note'] = '';
    //$data6['path'] = $data4['alias'];
    $data6['link'] = $categorylink;
    $data6['type'] = 'component';
    $data6['published'] = 1;
    $data6['parent_id'] = 1;
    $data6['level'] = 1;
    $data6['component_id'] = '10386';
    $data6['checked_out'] = null;
    $data6['checked_out_time'] = null;
    $data6['browserNav'] = 0;
    $data6['access'] = 1;
    $data6['img'] = ' ';
    $data6['template_style_id'] = 0;
    $data6['params'] = '{"breadcrumb":"","search":"0","show_description":"","show_author":"","show_author_bio":"","linkimage":"","view_date":"","show_icons":"","show_print_icon":"","show_isbn":"","show_issn":"","show_numpublication":"","show_library":"","show_location":"","show_catalog":"","show_category":"","show_editor":"","show_pag":"","show_hits":"","show_tags":"","show_note":"","view_rate":"","book_layout":"","show_bookcover":"","show_bookcover_link":"","bookcover_position":"","show_writtenby":"","show_description_title":"","name_display":"","author_order":"","menu-anchor_title":"","menu-anchor_css":"","menu_icon_css":"","menu_image":"","menu_image_css":"","menu_text":1,"menu_show":1,"page_title":"","show_page_heading":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","robots":"","helixultimatemenulayout":"","helixultimate_enable_page_title":"0","helixultimate_page_title_alt":"","helixultimate_page_subtitle":"","helixultimate_page_title_heading":"h2","helixultimate_page_title_bg_color":"","helixultimate_page_title_bg_image":""}';
    $data6['lft'] = $lft;
    $data6['rgt'] = $rgt;
    $data6['home'] = 0;
    $data6['language'] = '*';
    $data6['client_id'] = 0;
    $data6['publish_up'] = null;
    $data6['publish_down'] = null;
    $res12 = $menu->insert($data6);
    $updatesData3['rgt'] = $rgt + 1;
    $res4 = $menu->update(1, $updatesData3);
}

function insertAuthorData($Abauthor, $data1) {
    //Creating sql query for author table in database
    // Remove spaces and make it lowercase
    //run each query to Database;
    $data1['checked_out'] = '0';
    $data1['checked_out_time'] = '0000-00-00 00:00:00';
    $data1['metakey'] = '';
    $data1['metadesc'] = '';
    $data1['state'] = '0';
    $data1['language'] = '*';
    $res = $Abauthor->insert($data1);
}

function insertCategoryData($Abcategories, $data2, $new_isset_id, $date2){
    $datatoFindID['id'] = '4';
    $resID = $Abcategories->get_first_from_db($datatoFindID);
    //find lft from category and calculate rgt
    $lft = $resID->rgt;
    $rgt = $lft + 1;
    //Creating sql query for category table in database
    $category_alias = slug_gen2($data2['title']);
    $category_path = 'alla-vivlia/' . $category_alias;
    //run each query to Database;
    $data2['asset_id'] = $new_isset_id;
    $data2['parent_id'] = '4';
    $data2['lft'] = $lft;
    $data2['rgt'] = $rgt;
    $data2['level'] = '2';
    $data2['path'] = $category_path;
    $data2['extension'] = 'com_abook';
    $data2['alias'] = $category_alias;
    $data2['note'] = '';
    $data2['description'] = '';
    $data2['published'] = '1';
    $data2['checked_out'] = '0';
    $data2['checked_out_time'] = $date2;
    $data2['access'] = '1';
    $data2['params'] = '{"category_layout":"","image":"","alt_title":""}';
    $data2['metadesc'] = $data2['title'];
    $data2['metakey'] = $data2['title'];
    $data2['metadata2'] = $data2['title'];
    $data2['created_user_id'] = '835';
    $data2['created_time'] = #date2;
    $data2['modified_user_id'] = '';
    $data2['modified_time'] = '0000-00-00 00:00:00';
    $data2['hits'] = 0;
    $data2['language'] = '*';
    $data2['version'] = 1;
    $res2 = $Abcategories->insert($data2);
    $updatesData1['rgt'] = $rgt + 1;
    $updatesData2['rgt'] = $rgt + 2;
    $res3 = $Abcategories->update(4, $updatesData1);
    $res4 = $Abcategories->update(1, $updatesData2);
    //Ending Creating sql query for category table in database
}

function insertPublisherData($Abeditor, $data3) {
    $publiserSlug = slug_gen2($data3['name']);
    //Creating sql query for publiser table in Database
    $data3['alias'] = $publiserSlug;
    $data3['description'] = '';
    $data3['checked_out'] = '';
    $data3['checked_out_time'] = '0000-00-00 00:00:00';
    $data3['metakey'] = '';
    $data3['metadesc'] = '';
    $data3['state'] = '0';
    $data3['language'] = '*';
    $res5 = $Abeditor->insert($data3);
     //Ending Creating sql query for publiser table in Database
}

function grabJsonBookData($monthNumber, $YearNumber, $PageNumber, $curl)
{
    $url = 'https://biblionet.gr/wp-json/biblionetwebservice/get_month_titles';
    // Set cURL options
    $postData = [
        'username' => biblionetUsername,
        'password' => biblionetPassword,
        'month' => $monthNumber,
        'year' => $YearNumber,
        'page' => $PageNumber,
        'titles_per_page' => 25,
    ];
    curl_setopt($curl, CURLOPT_URL, $url);
    // Set the request method to POST
    curl_setopt($curl, CURLOPT_POST, true);
    // Set the POST data
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
    // Set other cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session and store the response in $response
    $response = curl_exec($curl);
    // Check for cURL errors
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    // Close cURL session
    curl_close($curl);
    // Now, $response contains the JSON response from the API
    // You can decode it to work with the data as an array or object
    $data = json_decode($response);
    return $data;
}

function printJsonBookData($monthNumber, $YearNumber)
{
    $url = 'https://biblionet.gr/wp-json/biblionetwebservice/get_month_titles';
    // Initialize an array to store the data from all pages
    $allData = [];
    $PageNumber = 1;
    // Continue making requests until you receive an empty response
    while (true) {
        // Initialize cURL session
        $curl = curl_init($url);
        // Set cURL options
        $postData = [
            'username' => biblionetUsername,
            'password' => biblionetPassword,
            'month' => $monthNumber,
            'year' => $YearNumber,
            'page' => $PageNumber,
            'titles_per_page' => 25,
        ];
        // Set the request method to POST
        curl_setopt($curl, CURLOPT_POST, true);
        // Set the POST data
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        // Set other cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute the cURL session and store the response in $response
        $response = curl_exec($curl);
        // Check for cURL errors
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        // Close cURL session
        curl_close($curl);
        // Now, $response contains the JSON response from the API
        // You can decode it to work with the data as an array or object
        $data = json_decode($response);
        // If the response is empty, break out of the loop
        if ($data == 'Δεν υπάρχουν αποτελέσματα') {
            break;
        }
        // Append the data from this page to the $allData array
        $allData[] = $data;
        // Increment the page number for the next request
        $PageNumber++;
    }
    $PageNumber--;
    $returnedResult = [
        "data" => $allData,
        "PageNumber" => $PageNumber
    ];
    return $returnedResult;
}

function grabJsonAuthorData($personId, $curl)
{
    $url = 'https://biblionet.gr/wp-json/biblionetwebservice/get_person';
    // Set cURL options
    $postData = [
        'username' => biblionetUsername,
        'password' => biblionetPassword,
        'person' => $personId,
    ];
    curl_setopt($curl, CURLOPT_URL, $url);
    // Set the request method to POST
    curl_setopt($curl, CURLOPT_POST, true);
    // Set the POST data
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
    // Set other cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session and store the response in $response
    $response = curl_exec($curl);
    // Check for cURL errors
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    // Close cURL session
    curl_close($curl);
    // Now, $response contains the JSON response from the API
    // You can decode it to work with the data as an array or object
    $data = json_decode($response);
    return $data;
}

function get_current_isset_id($Abcategories, $Abbook)
{
    $asset_id1 = $Abbook->getMax("asset_id");
    $asset_id2 = $Abcategories->getMax("asset_id");
    return max($asset_id1[0]->max_value, $asset_id2[0]->max_value);
}

function redirect($page)
{
    header("Location: " . URL .  $page);
    die();
}

//show message only once
function message($msg = '', $erase = false)
{
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else if (!empty($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        if ($erase) {
            unset($_SESSION['message']);
        }
        return $msg;
    }
    return false;
}

function download_Cover_Image($coverImageURL, $filename)
{
    //
    $localPath = CoverImagesPath . $filename;
    // Download the image and save it locally
    $content = @file_get_contents($coverImageURL);
    if ($content !== false) {
        file_put_contents($localPath, file_get_contents($coverImageURL));
    }
}

function downlaod_Author_image($AuthorImageURL, $filename)
{
    $localPath = AuthorsImagePath . $filename;
    // Download the image and save it locally
    $content = @file_get_contents($AuthorImageURL);
    if ($content  !== false) {
        file_put_contents($localPath, file_get_contents($AuthorImageURL));
    }
}

function exportPDF($data) {
    // Create an instance of TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    //set font 
    $pdf->SetFont('dejavusans', '', 10);
    //Add a Page
    $pdf->AddPage();
    // Set HTML content
    $html = '<h1 class="header">Αρχείο Καταγραφής Εφαρμογής</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Page</th>
                    <th>Authors</th>
                    <th>Categories</th>
                    <th>Publishers</th>
                    <th>Books</th>
                </tr>
            </thead>';
    if (isset($data['logs'])) {
        if ($data['logs'] != false) {
            $html .= '<tbody>'; // Start tbody here
            for ($i = 0; $i < sizeof($data['logs']); $i++) {
                $html .= '<tr>
                                    <td>' . formatDate($data['logs'][$i]->lastDate) . '</td>
                                    <td>' . $data['logs'][$i]->InsertedMonth . '</td>
                                    <td>' . $data['logs'][$i]->InsertedYear . '</td>
                                    <td>' . $data['logs'][$i]->InsertedPage . '</td>
                                    <td>' . $data['logs'][$i]->InsertedAuthors . '</td>
                                    <td>' . $data['logs'][$i]->InsertedCategories . '</td>
                                    <td>' . $data['logs'][$i]->InsertedPublishers . '</td>
                                    <td>' . $data['logs'][$i]->InsertedBooks . '</td>
                                </tr>';
            }
            $html .= '</tbody>'; // End tbody here
        }
    }
    $html .= '</table>';
    // Set font size to auto-scale content
    $font_size = 12; // Initial font size
    $max_height = 280; // Maximum height for the content (adjust as needed)
    // Convert HTML to PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    // Output PDF
    $pdf->Output('example.pdf', 'D'); // 'I' to open in the browser, 'D' to download, 'F' to save to a file.
}

function slug_gen($lastname, $firstname)
{
    $greek_characters = array(
        'α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'θ', 'Θ',
        'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ',
        'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' '
    );
    $greeklish_characters = array(
        'a', 'a', 'A', 'A', 'b', 'B', 'g', 'G', 'd', 'D', 'e', 'e', 'E', 'E', 'z', 'Z', 'i', 'i', 'I', 'th', 'Th',
        'i', 'i', 'i', 'i', 'I', 'I', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'x', 'X', 'o', 'o', 'O', 'O', 'p', 'P', 'r', 'R', 's',
        's', 'S', 't', 'T', 'u', 'U', 'y', 'Y', 'f', 'F', 'x', 'X', 'ps', 'Ps', 'o', 'o', 'O', 'O', '-'
    );
    $lastname = str_replace($greek_characters, $greeklish_characters, $lastname);
    $firstname = str_replace($greek_characters, $greeklish_characters, $firstname);
    $slug = $lastname . "-" . $firstname;
    return $slug;
}

function slug_gen2($category)
{
    $greek_characters = array(
        'α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'θ', 'Θ',
        'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ',
        'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' '
    );
    $greeklish_characters = array(
        'a', 'a', 'A', 'A', 'b', 'B', 'g', 'G', 'd', 'D', 'e', 'e', 'E', 'E', 'z', 'Z', 'i', 'i', 'I', 'th', 'Th',
        'i', 'i', 'i', 'i', 'I', 'I', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'x', 'X', 'o', 'o', 'O', 'O', 'p', 'P', 'r', 'R', 's',
        's', 'S', 't', 'T', 'u', 'U', 'y', 'Y', 'f', 'F', 'x', 'X', 'ps', 'Ps', 'o', 'o', 'O', 'O', '-'
    );
    $slug = str_replace('-', ' ', $category);
    $slug = str_replace($greek_characters, $greeklish_characters, $category);
    return $slug;
}
