<!DOCTYPE html>
<html>
    <head>
        <!-- set page title to display in browser's tab -->
        <title>Python Scrapping</title>
        <!-- set page meta as utf-8 to show html characters as utf-8 encoded -->
        <meta charset="utf-8">
        <!-- set view port as max scale as 100% for responsiveness -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- include boostrtap CSS cdn for default styling -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- include jquery cdn to add interactivity -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- include popper cdn t support collapse elements of bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <!-- include bootstrap JS cdn for javascript elements -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- include cdn for datatables, library used to display data from database -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    </head>
    <body>
            
        <!-- set page heading -->
        <h1>Python Scrapping</h1>

        <!-- display datatable -->
        <table id="steampowered" class="display nowrap table table-hover table-striped table-bordered tbl" cellspacing="0" width="100%">
            <!-- set table headers -->
            <thead>
                <tr>
                    <th style="width:5%">Sr.</th>
                    <th style="width:21%">Title</th>
                    <th style="width:21%">Action</th>
                    <th style="width:21%">Developer</th>
                    <th style="width:21%">Publisher</th>
                    <th style="width:21%">Franchise</th>
                    <th style="width:21%">Release Date</th>
                    <th style="width:21%">Languages</th>
                    <th style="width:21%">Discount pct</th>
                    <th style="width:21%">discount Original Price</th>
                    <th style="width:21%">Discount Final Price</th>
                </tr>
            </thead>
        </table>
        
        <!-- javascript -->
        <script type="text/javascript">
            // path for ajax 
            var ajax_url = './jquery-ajax.php';
            // execute code when the document gets ready
            $(document).ready(function () {
                // initialize data tables
                $('#steampowered').DataTable({
                    // display buttons
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    // set server side processing to accept bulk data
                    processing: true,
                    destroy: true,
                    serverSide: true,
                    // define ajax call parameters
                    "ajax": {
                        "url": ajax_url,
                        "dataType": "json",
                        "type": "POST",
                        "data": {"action": "steampowered"

                        },
                        "dataSrc": "records"
                    },
                    // define data for the columns
                    "columns": [
                        {"data": "ID"},
                        {"data": "title"},
                        {"data": "action"},
                        {"data": "Developer"},
                        {"data": "Publisher"},
                        {"data": "Franchise"},
                        {"data": "Release_Date"},
                        {"data": "Languages"},
                        {"data": "discount_pct"},
                        {"data": "discount_original_price"},
                        {"data": "discount_final_price"}
                    ], 
                    // define default sorting of data
                    "order": [[0, "DESC"]],
                });
            });
        </script>

    </body>
</html>