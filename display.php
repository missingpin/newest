<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
include 'connect.php';

if (isset($_POST['displaySend'])) {
    $table = '
    <table class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th scope="col" style="text-align: center;">ID <span class="sort-indicator" data-sort="id"></span></th>
            <th scope="col" style="text-align: center;">Product Image</th>
            <th scope="col" style="text-align: center;">Product Name <span class="sort-indicator" data-sort="name"></span></th>
            <th scope="col" style="text-align: center;">Product Type <span class="sort-indicator" data-sort="type"></span></th>
            <th scope="col" style="text-align: center;">Quantity <span class="sort-indicator" data-sort="quantity"></span></th>
            <th scope="col" style="text-align: center;">Expiration <span class="sort-indicator" data-sort="expiration"></span></th>
            <th scope="col" style="text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>';

    // Prepare the base query
// Prepare the base query
$sql = "SELECT * FROM product WHERE 1=1";

// Check if any sorting or filtering is applied
if (isset($_POST['typeSort']) && $_POST['typeSort'] !== 'All') {
    $typeSort = $_POST['typeSort'];
    $sql .= " AND type = '$typeSort'";
}

// Handle sorting for product name
if (isset($_POST['nameSort'])) {
    $nameSort = $_POST['nameSort'] == 'asc' ? 'ASC' : 'DESC';
    $sql .= " ORDER BY productname $nameSort";
}

// Handle sorting for expiration date, if specified
if (isset($_POST['expirationSort'])) {
    $expirationSort = $_POST['expirationSort'] == 'closest' ? 'ASC' : 'DESC';
    // If sorting by expiration, ensure it's combined correctly with name sorting
    if (strpos($sql, 'ORDER BY') !== false) {
        $sql .= ", exp $expirationSort"; // Append to existing ORDER BY
    } else {
        $sql .= " ORDER BY exp $expirationSort"; // Create a new ORDER BY
    }
}

    // Execute the query
    $result = mysqli_query($con, $sql);
    $number = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $image = $row['image'] ? 'uploads/' . $row['image'] : 'no-image.jpg';
        $productname = htmlspecialchars($row['productname']);
        $quantity = htmlspecialchars($row['quantity']);
        $exp = htmlspecialchars($row['exp']);
        $type = htmlspecialchars($row['type']);

        $table .= '<tr>
            <td scope="row">' . $number . '</td>
            <td><img src="' . $image . '" width="100" alt="' . $productname . '" data-toggle="modal" data-target="#imageModal" data-img="' . $image . '"></td>
            <td>' . $productname . '</td>
            <td>' . $type . '</td>
            <td>' . $quantity . '</td>
            <td>' . $exp . '</td>
            <td style="width: 150px;">
                <button class="btn btn-primary btn-sm" onclick="editline(' . $id . ')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteline(' . $id . ')">Delete</button>
            </td>
        </tr>';
        $number++;
    }

    $table .= '</tbody></table>';
    echo $table;
}
?>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 90vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
    function displayData() {
        var display = "true";
        $.ajax({
            url: "display.php",
            type: 'post',
            data: {
                displaySend: display
            },
            success: function(data, status) {
                $('#displaytable').html(data);
            }
        });
    }

    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('.table tbody');
        const rows = table.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }
            rows[i].style.display = match ? '' : 'none';
        }
    }

    $(document).on('click', 'img[data-toggle="modal"]', function() {
        var imgSrc = $(this).data('img');
        $('#modalImage').attr('src', imgSrc);
    });
</script>

</body>
</html>
