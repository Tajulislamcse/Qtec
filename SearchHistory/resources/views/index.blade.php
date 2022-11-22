
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel 5.8 - Individual Column Search in Datatables using Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> 
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css"/>
  <style>
   .error{ color:red; } 
</style>
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
                @foreach($keywords as $record)
                <input type="checkbox"  id="keywords" name="keywords" value="{{$record->keyword}}">
                <label class="custom-control-label" for="keywords">{{$record->keyword}}</label>
                <br>
                @endforeach
            </div>
            <!--/keyword search-->


        </div>
        <div class="col-md-4">
            <!--user search-->
            <div class="form-group">
                @foreach($users as $user)
                <input type="checkbox" id="users" name="users" value="{{$user->name}}">
                <label class="custom-control-label" for="users">{{$user->name}}</label>
                <br>
                @endforeach
            </div>
            <!--user search-->
        </div>
        @php
        $currentDate = \Carbon\Carbon::now();
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last saturday",$previous_week);
        $end_week = strtotime("next friday ",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);
        $yesterday = $currentDate->subDays(+1)->format('Y-m-d');
        $last_month = $currentDate->subMonth()->month;

        @endphp
        <div class="col-md-4">
            <div class="form-group">
                <label class="custom-control-label">From Date</label>
                <input type="text" id="from_date" name="from_date">
                <br>
                <br>
                <label class="custom-control-label">To Date</label>

                <input type="text" id="to_date" name="to_date">
                <br>
                <input type="checkbox" id="yesterday" name="yesterday" value="{{$yesterday}}">
                <label class="custom-control-label">Yesterday</label>
                <br>
                <input type="checkbox" id="last_week" name="last_week" value="{{$start_week.'~'.$end_week}}">
                <label class="custom-control-label">Last Week</label>
                <br>
                <input type="checkbox" id="last_month" name="last_month" value="{{$last_month}}">
                <label class="custom-control-label">Last Month</label>


            </div>

            <div class="form-group" align="center">
                <button type="button" name="filter" id="filter" class="btn btn-info">Search</button>

                <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
            </div>
        </div>
    </div>
    <br />
    <!--Modal starts from here-->
    <div class="modal fade" id="AddRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title model-own" id="myModalLabel">Edit Profile Picture</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form id="search_form" method="post" action="javascript:void(0)">

                        <div class="form-group">
                            <label for=keyword>Keyword</label>
                            <input type="text" name="keyword" class="form-control" id="keyword">

                        </div>
                        <div class="form-group">
                            <label for="result">Result</label>
                            <input type="text" name="result" class="form-control" id="result" >

                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" name="date" class="form-control" id="date">

                        </div>
                        <div class="form-group">
                            <label for="users">User</label>
                            <select name="user" class="form-select">
                                <option disabled="disabled" selected="selected">please select user</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">
                           <button type="submit" id="submit_form" class="btn btn-success">Submit</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
   <!--/Modal -->


   <div class="text-center">
      <a href="" class="btn btn-success btn-rounded mb-4" data-toggle="modal" data-target="#AddRecord">Add Record</a>
  </div>
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
      var  date = new DateTime($('#date'),
        {format: 'DD-MM-YYYY'}) ;
      var  min_date = new DateTime($('#from_date'),
        {format: 'DD-MM-YYYY'}) ;
      var max_date = new DateTime($('#to_date'),
        {format: 'DD-MM-YYYY'}) ;

      $('#submit_form').click(function(e){
         e.preventDefault();
   /*Ajax Request Header setup*/
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

         $('#submit_form').html('Sending..');

   /* Submit form data using ajax*/
         $.ajax({
            url: "{{ url('searches')}}",
            method: 'post',
            data: $('#search_form').serialize(),
            success: function(response){
         //------------------------
                $('#submit_form').html('Submit');
               var html = '<div class="alert alert-success">' + response.msg + '</div>';
                    $("#form_result").html(html);


                document.getElementById("search_form").reset(); 
                setTimeout(function(){
                    $('#form_result').hide();
                },10000);
         //--------------------------
            },
            error:function(error)
            {
                $('#submit_form').html('Submit');
                var html='';

                html = '<div class="alert alert-danger">';
                $.each(error.responseJSON.errors, function (i, error) {
                    html+='<div>'+error+'</div>';
                });
                html += '</div>';
                 $("#form_result").html(html);
                document.getElementById("search_form").reset(); 
                setTimeout(function(){
                    $('#form_result').hide();
                },10000);

            }
        });
     });

      fill_datatable();

      function fill_datatable(keywords = '',users = '',from_date = '', to_date = '', yesterday = '',last_week = '',lastmonth)
      {
        var dataTable = $('#customer_data').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "{{ route('searches.index') }}",
                data:{keywords:keywords,users:users,from_date:from_date, to_date:to_date,yesterday:yesterday,last_week:last_week,lastmonth:lastmonth}
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
        var yesterday = $('#yesterday').is(':checked') ? $('#yesterday').val() :'' ;
        var last_week = $('#last_week').is(':checked') ? $('#last_week').val():'';
        var lastmonth = $('#last_month').is(':checked') ? $('#last_month').val():'';
        var from_date = min_date.val()!=null  ? moment(min_date.val()).format('YYYY-MM-DD') : ''
        console.log(from_date)
        var to_date = max_date.val()!=null ? moment(max_date.val()).format('YYYY-MM-DD') : ''

               // console.log(from_date+' '+to_date)
              // console.log(from_date)

        $('#customer_data').DataTable().destroy();
        fill_datatable(keywords,users,from_date, to_date, yesterday,last_week,lastmonth);

    });


    $('#reset').click(function(){
        $('input:checkbox').prop('checked', false)
        $('#from_date').val('');
        $('#to_date').val('');
        $('#customer_data').DataTable().destroy();
        fill_datatable();
    });

});
</script>
</body>
</html>
