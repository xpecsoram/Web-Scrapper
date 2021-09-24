<?php

// set actions to avoid un-necessary calls
if ($_REQUEST['action'] == 'steampowered') {
    
    // database connection parameters
    $servername = "localhost"; // host name
    $username = "root"; // database user
    $password = ""; // database password (set empty for localhost)
    $dbname = "streampowered"; // database name

    // Create database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // if database connection failed, throw error
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // create request parameters
    $requestData = $_REQUEST;
    $start = $_REQUEST['start'];

    // create column orders to display data columns in table
    $columns_order = array(
        0 => 'ID',
        1 => 'title',
        2 => 'action',
        3 => 'Developer',
        4 => 'Publisher',
        5 => 'Franchise',
        6 => 'Release_Date',
        7 => 'Languages',
        8 => 'discount_pct',
        9 => 'discount_original_price',
        10 => 'discount_final_price'
    );

    // create SQL query to get data from database for 40 percent discounts
    $sql = " SELECT * FROM `streampowered` WHERE discount_pct >= 40 ";
    $result = $conn->query($sql);

    // get number of records
    $totalData = $result->num_rows;
    $totalFiltered = $totalData;

    // set search parameters for datatables
    if (!empty($requestData['search']['value'])) {
        $sql .= " AND ( ID LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  title LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  action LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  Developer LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  Publisher LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  Franchise LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  Release_Date LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  discount_pct LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  discount_original_price LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  discount_final_price LIKE '%" . $requestData['search']['value'] . "%'  ";
        $sql .= " OR  Languages LIKE '%" . $requestData['search']['value'] . "%')  ";
    }

    // manupulate SQL for search
    $result = $conn->query($sql);
    $totalData = $result->num_rows;

    // get number of records
    $totalFiltered = $totalData;

    // finalize SQL for sorting and set limit
    $sql .= " ORDER BY " . $columns_order[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

    // execute final SQL query
    $result = $conn->query($sql);
    
    // create a data array to store returned in it
    $data = array();

    // default counter to display for tables
    $counter = $start;
    $count = $start;

    // loop through each record returned from database
    while ($row = $result->fetch_assoc()) {
        // increment the counter to display serials for table
        $count++;
        // create an array for data to prepare it to display data into JSON
        $nestedData = array();
        $nestedData['ID'] = $count;
        $nestedData['title'] = $row["title"];
        $nestedData['action'] = $row["action"];
        $nestedData['Developer'] = $row["Developer"];
        $nestedData['Publisher'] = $row["Publisher"];
        $nestedData['Franchise'] = $row["Franchise"];
        $nestedData['Release_Date'] = $row["Release_Date"];
        $nestedData['Languages'] = $row["Languages"];
        $nestedData['discount_pct'] = $row["discount_pct"];
        $nestedData['discount_original_price'] = $row["discount_original_price"];
        $nestedData['discount_final_price'] = $row["discount_final_price"];

        $data[] = $nestedData;
    }

    // create JSON object with data and total records
    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "records" => $data
    );

    // encode JSON data and expose as an API
    echo json_encode($json_data);
}