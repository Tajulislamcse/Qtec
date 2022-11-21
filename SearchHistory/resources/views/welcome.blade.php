<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css"/>
</head>
<body>
 <div class="container-fluid">
    <div class="col-sm-12">
        <div class="card">
            <table border="0" cellpadding="5">
                <tbody>
                    <tr>
                        <td>From Date:</td>
                        <td>
                            <input type="text" id="from_date" name="from_date">
                        </td>
                        <td>To Date:</td>
                        <td>
                            <input type="text" id="to_date" name="to_date">
                        </td>
                    </tr>
                </tbody>

                <!--keyword search-->
                <input type="checkbox"  id="laravel" name="keyword" value="laravel">
                <label class="custom-control-label" for="laravel">laravel</label>
                <input type="checkbox" id="php" name="keyword" value="php">
                <label class="custom-control-label" for="laravel">php</label>
                <input type="checkbox" id="vue" name="keyword" value="vue">
                <label class="custom-control-label" for="vue">vue</label>
                <!--/keyword search-->

                <!--user search-->
                <br>
                <br>
                <input type="checkbox" id="tajul" name="keyword" value="tajul">
                <label class="custom-control-label" for="tajul">tajul</label>
                <input type="checkbox" id="rakib" name="keyword" value="rakib">
                <label class="custom-control-label" for="rakib">rakib</label>
                <input type="checkbox" id="harun" name="keyword" value="harun">
                <label class="custom-control-label" for="harun">harun</label>
                <!--/user search-->

                <!--date search-->
                @php
                $yesterday = new DateTime('yesterday'); 
                $yesterday = $yesterday->format('d-m-Y');
              //  $date = date('Y-m-d'-(7));
               // $sql = DB::select("SELECT * FROM table WHERE date <='$date' ");
               // dd($sql)
                 @endphp
                <br>
                <br>

                <input type="checkbox"  name="keyword" value="{{$yesterday}}">
                <label class="custom-control-label" for="yesterday">Yesterday</label>
                <input type="checkbox"  name="keyword" value="">
                <label class="custom-control-label" for="rakib">Last Week</label>
                <input type="checkbox" id="harun" name="keyword" value="harun">
                <label class="custom-control-label" for="harun">harun</label>
                <!--/date search-->
                <h1 class="records_count"></h1>

            </table>

            <div class="table-responsive">
                <div>
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Keyword</th>
                                <th>Result</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($searches as $search)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$search->keyword ?? ''}}</td>
                                <td>{{$search->result ?? ''}}</td>
                                <td>{{isset($search->date) ? date('Y-m-d',strtotime($search->date)) : ''}}</td>
                                <td>{{$search->user ?? '' }}</td>
                                
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.10.4/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
     var minDate, maxDate;
    $.fn.dataTable.ext.search.push(
        function (settings,searchData, index, rowData, counter) {
        if(typeof(minDate ) !== "undefined")
            min = minDate.val();
         else
            min = ''
        if(typeof(maxDate ) !== "undefined")
             max = maxDate.val();
        else
            max = ''

        console.log('min '+min)
        console.log('max '+max)
        var date = new Date( searchData[3] );
        console.log('testff'+date)
        var offices = $('input:checkbox[name="keyword"]:checked').map(function () {
                return this.value;
            }).get();
        offices.push(min)
            

               console.log(offices);
               console.log('r&d')


              if (offices.length === 0) {
                return true;
                console.log('lenth0')
            }

            if (offices.indexOf(searchData[1])!== -1 || offices.indexOf(searchData[4])!== -1 || offices.indexOf(searchData[3])!== -1 ||) {
                console.log('lenth1')

                return true;
            }
        //     if (

        // ) {
        //         console.log('testreeeeeeeeeee')
        //     return true;
        // }

            console.log('shobar')


            return false;
        });

    var table = $('#example').DataTable({
        "ordering": false,
        "bPaginate": false,
        "bLengthChange": false,
        "bAutoWidth": false
    });

    minDate = new DateTime($('#from_date'), {
        format: 'DD-MM-YYYY'
    });
    maxDate = new DateTime($('#to_date'), {
        format: 'DD-MM-YYYY'
    });

    $('input:checkbox').on('change', function () {
        table.draw();

       console.log('draw');

    });
    $('#from_date, #to_date').on('change', function () {
        table.draw();
       console.log('drawDate');

    });
    $("input[type='checkbox']").on('change',function () {
      var result = table .$('tr', {"filter":"applied"}).length;
      $('.records_count').html('Number of Records :'+result)
  });
  //   $('#php').on('change',function () {
  //     var result = table .$('tr', {"filter":"applied"}).length;
  //     $('.records_count').html('Number of Records :'+result)
  // });
  //   $('#vue').on('change',function () {
  //     var result = table .$('tr', {"filter":"applied"}).length;
  //     $('.records_count').html('Number of Records :'+result)
  // });

   //      $('#received').on('change',function () {
   //     var result = table .$('tr', {"filter":"applied"}).length;
   //     $('.received').html('Number of Records :'+result)
   // });

});


</script>
</body>
</html>