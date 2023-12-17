
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
 <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>

<!-- Optional JavaScript; choose one of the two options below -->
<style>
    body{
        margin: 10px;
    }
</style>
</head>
<body>
    <form action="" method="post">
        <input type="file" name="uploadedFile">
        <input type="submit" id="submit">
    </form>

    <div class="d-flex flex-wrap mt-5">
        <table id="employeeTable" class="display" style="width:100%">
            
        </table>
    </div>
    <script>
        $(document).ready(function(){
           

            $('#submit').on('click', function(e){
                e.preventDefault();
                var formData = new FormData();
                var file = $('input[name=uploadedFile]')[0].files[0];
                formData.append('uploadedFile', file);
                $.ajax({
                    url: '/exec.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,

                }).done(data => {
                    console.log(data)
                    var uniqueDates = [];
                    var employees = {};

                    data.forEach(function(item) {
                        for (var date in item) {
                            if (date !== 'employee' && !uniqueDates.includes(date)) {
                                uniqueDates.push(date);
                            }
                        }

                        employees[item.employee] = true;
                    });

                    // Xây dựng cấu trúc dữ liệu cho DataTables
                    var columns = [
                        { data: 'employee', title: 'Nhân viên' }
                    ];

                    uniqueDates.forEach(function(date) {
                        columns.push({ data: date, title: date });
                    });

                    if(typeof dataTable !== 'undefined' || dataTable != null){
                        dataTable.clear().draw();
                        dataTable.columns().remove().draw();
                        dataTable.rows.add(data).draw();
                    }else{
                        // Khởi tạo DataTables
                        var dataTable = $('#employeeTable').DataTable({
                            data: data,
                            columns: columns,
                            dom: 'Bfrtip',
                            scrollX:  2200,
                            buttons: [
                                'copy', 'excel', 'pdf'
                            ]
                            // Cấu hình DataTables khác theo ý muốn của bạn
                        });
                    }
                    
                    
                })
            });
        });
    </script>
</body>
</html>