<?php

function checkDatabaseTables() {
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

function initialiseUserTable() {
    $User = new User();
    $res1 = $User->createTable();
    $data['fullname'] = "datatex.gr";
    $data['username'] = "nickpsal";
    $password = password_hash("NIKOS2908biblionet",PASSWORD_DEFAULT);
    $data['password'] = $password;
    $res2 = $User->insert($data);
}

function initialiseBiblionetScriptTable() {
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
        return $res[0]->lastDate;
    }
}

function showData($stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function BookData($monthNumber, $YearNumber, $PageNumber)
{
    $date = getCurrentDate();
    $date2 = getCurrentDate2();
    $authors_counter = 0;
    $publishers_counter = 0;
    $categorys_counter = 0;
    $books_counter = 0;
    $new_isset_id = get_current_isset_id() + 1;
    //Database tables 
    $Abauthor = new Abauthor();
    $Abbook = new Abbook();
    $Abcategories = new Abcategories();
    $Abeditor = new Abeditor();
    $abbookAuth = new Abbookauth();
    $lastDate = new biblionetScript();
    //grab data
    $bookData = grabJsonBookData($monthNumber, $YearNumber, $PageNumber);
    //imserting data
    if (is_array($bookData)) {
        foreach ($bookData as $book) {
            for ($i = 0; $i < 50; $i++) {
                if (isset($book[$i]->Title)) {
                    $Booktitle = $book[$i]->Title;
                    //remoce special characters from title
                    $Booktitle = str_replace(':', '', $Booktitle);
                    $Booktitle = str_replace('&', '', $Booktitle);
                    $bookalias = slug_gen2($Booktitle);
                }
                if (isset($book[$i]->Category)) {
                    $bookCategory = $book[$i]->Category;
                }
                if (isset($book[$i]->Price)) {
                    $BookPrice = $book[$i]->Price;
                    $BookPrice = number_format($BookPrice, 2);
                }
                if (isset($book[$i]->PageNo)) {
                    $BookPages = $book[$i]->PageNo;
                }
                if (isset($book[$i]->Summary)) {
                    $BookDesc = $book[$i]->Summary;
                }
                if (isset($book[$i]->CoverImage)) {
                    $img = $book[$i]->CoverImage;
                    $coverImageURL = 'https://www.biblionet.gr' . $img;
                    $filename = basename($coverImageURL);
                    //downlaod book cover image
                    download_Cover_Image($coverImageURL, $filename);
                    //local image path
                    $coverImage = "images/biblionet/" . $filename;
                }
                if (isset($book[$i]->ISBN)) {
                    $isbnNumber = $book[$i]->ISBN;
                }
                if (isset($book[$i]->Publisher)) {
                    $publisherName = $book[$i]->Publisher;
                }
                $publiserSlug = slug_gen2($publisherName);
                if (isset($book[$i]->WriterID)) {
                    $writerID = $book[$i]->WriterID;
                }
                $authorData = grabJsonAuthorData($writerID);
                if (isset($authorData[0][0]->Photo)) {
                    $img = $authorData[0][0]->Photo;
                    $authorPhotoURL = 'https://www.biblionet.gr' . $img;
                    $filename = basename($authorPhotoURL);
                    downlaod_Author_image($authorPhotoURL, $filename);
                    $authorPhoto = 'images/biblionet/Authors/' . $filename;
                }
                if (isset($authorData[0][0]->Name) && isset($authorData[0][0]->Surname)) {
                    $authorName = $authorData[0][0]->Name;
                    $authorSurname = $authorData[0][0]->Surname;
                    // Concatenate last name and first name
                    $alias = slug_gen($authorSurname, $authorName);
                }
                if (isset($authorData[0][0]->Biography)) {
                    $authorBio = $authorData[0][0]->Biography;
                }
                // starting saving data to database
                //---------------------------------
                //checking if author exists in Database 
                $authorsFromDB = $Abauthor->find_all();
                $authorfound = false;
                foreach ($authorsFromDB as $aut) {
                    if ($aut->lastname == $authorSurname && $aut->name == $authorName) {
                        $authorfound = true;
                    }
                }
                if (!$authorfound) {
                    //Creating sql query for author table in database
                    // Remove spaces and make it lowercase
                    $alias = strtolower(str_replace(' ', '', $alias));
                    //$query = "INSERT IGNORE INTO mcpyv_abauthor (lastname,name,alias,image,description,checked_out,checked_out_time,metakey,metadesc,state,language) VALUES ('$authorSurname','$authorName','$alias','$authorPhoto','" . str_replace(array('<<', '"', "'"), ' ', str_replace(array("\r", "\n"), ' ', $authorBio)) . "','0','0000-00-00 00:00:00','','','0','*');";
                    //End Creating sql query for author table in database 
                    //run each query to Database;
                    $data1['lastname'] = $authorSurname;
                    $data1['name'] = $authorName;
                    $data1['alias'] = $alias;
                    $data1['image'] = $authorPhoto;
                    $data1['description'] = str_replace(array('<<', '"', "'"), ' ', str_replace(array("\r", "\n"), ' ', $authorBio));
                    $data1['checked_out'] = '0';
                    $data1['checked_out_time'] = '0000-00-00 00:00:00';
                    $data1['metakey'] = '';
                    $data1['metadesc'] = '';
                    $data1['state'] = '0';
                    $data1['language'] = '*';
                    //$res = $Abauthor->query($query);
                    $res = $Abauthor->insert($data1);
                    $authors_counter++;
                }
                //checking if category exists in Database 
                $categoryFromDB = $Abcategories->find_all();
                $categoryFound = false;
                foreach ($categoryFromDB as $cat) {
                    if ($cat->title == $bookCategory) {
                        $categoryFound = true;
                    }
                }
                if (!$categoryFound) {
                    $datatoFindID['id'] = '4';
                    $resID = $Abcategories->get_first_from_db($datatoFindID);
                    //find lft from category and calculate rgt
                    $lft = $resID->rgt;
                    $rgt = $lft + 1;
                    //Creating sql query for category table in database
                    $category_alias = slug_gen2($bookCategory);
                    $category_path = 'alla-vivlia/' . $category_alias;
                    //$query = "INSERT IGNORE INTO mcpyv_abcategories (asset_id,parent_id,lft,rgt,level,path,extension,title,alias,note,description,published,checked_out,checked_out_time,access,params,metadesc,metakey,metadata,created_user_id,created_time,modified_user_id,modified_time,hits,language,version) VALUES ('$new_isset_id','4','$lft','$rgt','2','$category_path','com_abook','$bookCategory','$category_alias','','','1','0','$date2','1','{\"category_layout\":\"\",\"image\":\"\",\"alt_title\":\"\"}','', '', '{\"author\":\"\",\"robots\":\"\"}', '835', '$date2', '0', '0000-00-00 00:00:00', 0, '*', 1);";
                    //run each query to Database;
                    $data2['asset_id'] = $new_isset_id;
                    $data2['parent_id'] = '4';
                    $data2['lft'] = $lft;
                    $data2['rgt'] = $rgt;
                    $data2['level'] = '2';
                    $data2['path'] = $category_path;
                    $data2['extension'] = 'com_abook';
                    $data2['title'] = $bookCategory;
                    $data2['alias'] = $category_alias;
                    $data2['note'] = '';
                    $data2['description'] = '';
                    $data2['published'] = '1';
                    $data2['checked_out'] = '0';
                    $data2['checked_out_time'] = $date2;
                    $data2['access'] = '1';
                    $data2['params'] = '{"category_layout":"","image":"","alt_title":""}';
                    $data2['metadesc'] = $bookCategory;
                    $data2['metakey'] = $bookCategory;
                    $data2['metadata2'] = $bookCategory;
                    $data2['created_user_id'] = '835';
                    $data2['created_time'] = #date2;
                        $data2['modified_user_id'] = '';
                    $data2['modified_time'] = '0000-00-00 00:00:00';
                    $data2['hits'] = 0;
                    $data2['language'] = '*';
                    $data2['version'] = 1;
                    //$res2 = $Abcategories->query($query);
                    $res2 = $Abcategories->insert($data2);
                    $updatesData1['rgt'] = $rgt + 1;
                    $updatesData2['rgt'] = $rgt + 2;
                    $res3 = $Abcategories->update(4, $updatesData1);
                    $res4 = $Abcategories->update(1, $updatesData2);
                    //Ending Creating sql query for category table in database
                    $categorys_counter++;
                }
                //checking if publisher exists in Database 
                $EditorFromDB = $Abeditor->find_all();
                $EditorFound = false;
                foreach ($EditorFromDB as $editor) {
                    if ($editor->name == $publisherName) {
                        $EditorFound = true;
                    }
                }
                if (!$EditorFound) {
                    //Creating sql query for publiser table in Database
                    //$query = "INSERT IGNORE INTO mcpyv_abeditor (name,alias,description,checked_out,checked_out_time,metakey,metadesc,state,language) VALUES ('$publisherName','$publiserSlug','','0','0000-00-00 00:00:00','','','0','*');";
                    //run each query to Database
                    //$res = $Abeditor->query($query);
                    $data3['name'] = $publisherName;
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
                    $publishers_counter++;
                }
                //checking if book exists in Database 
                $BooksFromDb = $Abbook->find_all();
                $BookFound = false;
                foreach ($BooksFromDb as $b) {
                    if ($b->title == $Booktitle) {
                        $BookFound = true;
                    }
                }
                if (!$BookFound) {
                    $BookDesc = str_replace("'", "", $BookDesc);
                    $datatofind1['name'] = $publisherName;
                    $res = $Abeditor->get_first_from_db($datatofind1);
                    $editorID = $res->id;
                    $datatoFind2['title'] = $bookCategory;
                    $res = $Abcategories->get_first_from_db($datatoFind2);
                    $categoryID = $res->id;
                    //Creating sql query for books table in database
                    //$query = "INSERT IGNORE INTO `mcpyv_abbook` (`asset_id`, `title`, `subtitle`, `alias`, `ideditor`, `price`, `pag`, `pag_index`, `user_id`, `created_by_alias`, `description`, `other_info`, `image`, `docsfolder`, `file`, `year`, `idlocation`, `idlibrary`, `vote`, `numvote`, `hits`, `state`, `catid`, `qty`, `isbn`, `issn`, `doi`, `numpublication`, `approved`, `userid`, `url`, `url_label`, `url2`, `url2_label`, `url3`, `url3_label`, `dateinsert`, `catalogo`, `checked_out`, `checked_out_time`, `access`, `metakey`, `metadesc`, `metadata`, `language`, `ordering`, `params`, `note`, `editedby`) VALUES ('$new_isset_id', '$Booktitle', '', '$bookalias', $editorID, '$BookPrice', '$BookPages', 0, 835, '', '$BookDesc', '', '$coverImage', NULL, '', '$YearNumber', 0, 0, 0, 0, 0, 1, '$categoryID', 1, '$isbnNumber', '', '', 0, 0, 0, '', '', '', '', '', '', '$date2', '', '0', '0000-00-00 00:00:00', 1, '$Booktitle', '$Booktitle', '{\"robots\":\"index, follow\",\"author\":\"\",\"rights\":\"\"}', '*', 0, '{\"show_author\":\"\",\"author_order\":\"\",\"linkto\":\"\",\"linkimage\":\"\",\"view_date\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_hits\":\"\",\"breadcrumb\":\"\",\"search\":\"\",\"view_rate\":\"\",\"book_layout\":\"\",\"view_pag_index\":\"\"}', '', 0);";
                    $new_isset_id++;
                    //run each query to Database
                    //$res = $Abbook->query($query);
                    $data4['asset_id'] = $new_isset_id;
                    $data4['title'] = $Booktitle;
                    $data4['subtitle'] = '';
                    $data4['alias'] = $bookalias;
                    $data4['ideditor'] = $editorID;
                    $data4['price'] = $BookPrice;
                    $data4['pag'] = $BookPages;
                    $data4['pag_index'] = 0;
                    $data4['userid'] = 835;
                    $data4['created_by_alias'] = '';
                    $data4['description'] = $BookDesc;
                    $data4['other_info'] = '';
                    $data4['image'] = $coverImage;
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
                    $data4['metakey'] = $Booktitle;
                    $data4['metadesc'] = $Booktitle;
                    $data4['metadata4'] = '{"robots":"","author":"","rights":""}';
                    $data4['language'] = '*';
                    $data4['ordering`'] = 0;
                    $data4['params'] = '{"show_author":"","author_order":"","linkto":"","linkimage":"","view_date":"","show_icons":"","show_print_icon":"","show_hits":"","breadcrumb":"","search":"","view_rate":"","book_layout":"","view_pag_index":""}';
                    $data4['catid'] = $categoryID;
                    $data4['qty'] = 1;
                    $data4['isbn'] = $isbnNumber;
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
                    $books_counter++;
                }
                //assosiating Author with Book
                $datatoFind3['title'] = $Booktitle;
                $datatoFind4['lastname'] = $authorSurname;
                $datatoFind4['name'] = $authorName;
                $res7 = $Abbook->get_first_from_db($datatoFind3);
                $res8 = $Abauthor->get_first_from_db($datatoFind4);
                $BookID = $res7->id;
                $AuthorID = $res8->id;
                $data5['idbook'] = $BookID;
                $data5['idauth'] = $AuthorID;
                $res7 = $abbookAuth->insert($data5);
                // end saving data to database
                //---------------------------------
            }
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
    $res8 = $lastDate->insert($data6);
    return $returned_data;
}

function grabJsonBookData($monthNumber, $YearNumber, $PageNumber)
{
    $url = 'https://biblionet.gr/wp-json/biblionetwebservice/get_month_titles';
    // Initialize cURL session
    $curl = curl_init($url);
    // Set cURL options
    $postData = [
        'username' => biblionetUsername,
        'password' => biblionetPassword,
        'month' => $monthNumber,
        'year' => $YearNumber,
        'page' => $PageNumber,
        'titles_per_page' => 50,
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
    return $data;
}

function grabJsonAuthorData($personId)
{
    $url = 'https://biblionet.gr/wp-json/biblionetwebservice/get_person';
    // Initialize cURL session
    $curl = curl_init($url);
    // Set cURL options
    $postData = [
        'username' => biblionetUsername,
        'password' => biblionetPassword,
        'person' => $personId,
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
    return $data;
}

function get_current_isset_id()
{
    $cat = new Abcategories;
    $book = new Abbook;
    $asset_id1 = $book->getMax("asset_id");
    $asset_id2 = $cat->getMax("asset_id");
    if ($asset_id1[0]->max_value > $asset_id2[0]->max_value) {
        return $asset_id1[0]->max_value;
    } else {
        return $asset_id2[0]->max_value;
    }
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
    $slug = str_replace($greek_characters, $greeklish_characters, $category);
    return $slug;
}
