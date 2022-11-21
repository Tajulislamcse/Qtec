
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel 5.8 - Individual Column Search in Datatables using Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> 
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css"/>

</head>
<body>
  <div class="container">    
     <br />
     <br />
     <br />
     <div class="row">
        <div class="col-md-4">
                            <!--keyword search-->
              <div class="form-group">
                <input type="checkbox"  id="laravel" name="keywords" value="laravel">
                <label class="custom-control-label" for="laravel">laravel</label>
                <input type="checkbox" id="php" name="keywords" value="php">
                <label class="custom-control-label" for="laravel">php</label>
                <input type="checkbox" id="vue" name="keywords" value="vue">
                <label class="custom-control-label" for="vue">vue</label>
            </div>
                <!--/keyword search-->


        </div>
        <div class="col-md-4">
                            <!--user search-->
            <div class="form-group">
                <input type="checkbox" id="tajul" name="users" value="tajul">
                <label class="custom-control-label" for="tajul">tajul</label>
                <input type="checkbox" id="rakib" name="users" value="rakib">
                <label class="custom-control-label" for="rakib">rakib</label>
                <input type="checkbox" id="harun" name="users" value="harun">
                <label class="custom-control-label" for="harun">harun</label>
                <!--/user search-->
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="custom-control-label">From Date</label>
                <input type="text" id="from_date" name="from_date">
                <br>
                <br>
                <label class="custom-control-label">To Date</label>

                <input type="text" id="to_date" name="to_date">
            </div>

            <div class="form-group" align="center">
                <button type="button" name="filter" id="filter" class="btn btn-info">Search</button>

                <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
            </div>
        </div>
    </div>
    <br />
    <div class="table-responsive">
        <table id="customer_data" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Keyword</th>
                    <th>Result</th>
                    <th>Date</th>
                    <th>User</th>
                </tr>
            </thead>
        </table>
    </div>
    <br />
    <br />
</div>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>
<script>
    $(document).ready(function(){
          var  minDate = new DateTime($('#from_date'),
            {format: 'DD-MM-YYYY'}) ;
           var maxDate = new DateTime($('#to_date'),
            {format: 'DD-MM-YYYY'}) ;

        fill_datatable();

        function fill_datatable(keywords = '',users = '',from_date = '', to_date = '')
        {
            var dataTable = $('#customer_data').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('searches.index') }}",
                    data:{keywords:keywords,users:users,from_date:from_date, to_date:to_date}
                },
                columns: [
                {
                    data:'id',
                    name:'id'
                },
                {
                    data:'keyword',
                    name:'keyword'
                },
                {
                    data:'result',
                    name:'result'
                },
                {
                    data:'date',
                    render: function(data)
                    {
                      return moment(data).format('DD-MM-YYYY')
                    },
                    name:'date'
                },
                {
                    data:'user',
                    name:'user'
                },

                ]
            });
        }

        $('#filter').click(function(){
            var users = $('input:checkbox[name="users"]:checked').map(function () {
                    return this.value;
                }).get()
            var keywords = $('input:checkbox[name="keywords"]:checked').map(function () {
                    return this.value;
                }).get()

            var from_date = minDate.val()!=null  ? moment(minDate.val()).format('YYYY-MM-DD') : ''
            console.log(from_date)
            var to_date = maxDate.val()!=null ? moment(maxDate.val()).format('YYYY-MM-DD') : ''
            
               // console.log(from_date+' '+to_date)
               console.log(from_date)
               
                $('#customer_data').DataTable().destroy();
                fill_datatable(keywords,users,from_date, to_date);

        });


        $('#reset').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#customer_data').DataTable().destroy();
            fill_datatable();
        });

    });
</script>
</body>
</html>
