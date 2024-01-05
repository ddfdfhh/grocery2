<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div style="margin:auto:width:300px;height:300px;margin-top:40%">
    <h2>Account Deletion Request Form For Registered Customers</h2>
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <form action="{{ url('deleteRequest') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="email">Registered Email address:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="phone">Registered Phone Number:</label>
            <input type="number" class="form-control" id="phone" name="phone">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body></html>