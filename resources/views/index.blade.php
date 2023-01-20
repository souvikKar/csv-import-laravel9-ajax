<!DOCTYPE html>
<html lang="pt-br">
   <head>
    <meta charset="utf-8">
     <title>CSV Column Mapping in Laravel</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel="icon" href="favicon.ico">
      <script src="http://code.jquery.com/jquery.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
      .table tbody tr th
      {
        min-width: 200px;
      }

      .table tbody tr td
      {
        min-width: 200px;
      }

      </style>
   </head>
   <body>
    <div class="container">
        <p align="center"><img src="https://laravel.com/img/logomark.min.svg" width="200"></p>
     <br />
     <br />
      <h1 align="center">CSV Column Mapping in Laravel</h1>
      <br />
        <div id="message"></div>
      <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Select CSV File</h3>
          </div>
          <div class="panel-body">
            <div class="row" id="upload_area">
              <form method="post" id="upload_form" enctype="multipart/form-data">
                <div class="col-md-6" align="right">Select File</div>
                <div class="col-md-6">
                    @csrf
                  <input type="file" name="file" id="csv_file" />
                </div>
                <br /><br /><br />
                <div class="col-md-12" align="center">
                  <input type="submit" name="upload_file" id="upload_file" class="btn btn-primary" value="Upload" />
                </div>
              </form>

            </div>
            <div class="table-responsive" id="process_area">

            </div>
          </div>
        </div>
     </div>

   </body>
</html>

<script>
$(document).ready(function(){

  $('#upload_form').on('submit', function(event){

    event.preventDefault();
    $.ajax({
      url:"{{ route('uploadFile') }}",
      method:"POST",
      data:new FormData(this),
      dataType:'json',
      contentType:false,
      cache:false,
      processData:false,
      success:function(data)
      {
        if(data.error != '')
        {
          $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
        }
        else
        {
          $('#process_area').html(data.output);
          $('#upload_area').css('display', 'none');
        }
      }
    });

  });

  var total_selection = 0;

  var first_name = 0;

  var last_name = 0;

  var email = 0;

  var column_data = [];

  $(document).on('change', '.set_column_data', function(){

    var column_name = $(this).val();

    var column_number = $(this).data('column_number');

    if(column_name in column_data)
    {
      alert('You have already define '+column_name+ ' column');

      $(this).val('');

      return false;
    }

    if(column_name != '')
    {
      column_data[column_name] = column_number;
    }
    else
    {
      const entries = Object.entries(column_data);

      for(const [key, value] of entries)
      {
        if(value == column_number)
        {
          delete column_data[key];
        }
      }
    }

    total_selection = Object.keys(column_data).length;

    if(total_selection == 3)
    {
      $('#import').attr('disabled', false);

      first_name = column_data.first_name;

      last_name = column_data.last_name;

      email = column_data.email;
    }
    else
    {
      $('#import').attr('disabled', 'disabled');
    }

  });

  $(document).on('click', '#import', function(event){

    event.preventDefault();

    $.ajax({
      url:"import",
      method:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
          first_name:first_name,
          last_name:last_name,
          email:email
        },
      beforeSend:function(){
        $('#import').attr('disabled', 'disabled');
        $('#import').text('Importing...');
      },
      success:function(data)
      {
        $('#import').attr('disabled', false);
        $('#import').text('Import');
        $('#process_area').css('display', 'none');
        $('#upload_area').css('display', 'block');
        $('#upload_form')[0].reset();
        $('#message').html("<div class='alert alert-success'>"+data+"</div>");
      }
    })

  });

});
</script>
