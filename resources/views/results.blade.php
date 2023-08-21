<!DOCTYPE html>
<html>
<head>
    <title>Customer report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body class="mx-5 my-5">
<h3>Valid Customers</h3>
<div class="text-muted">Valid customers from DB and File that passed validation successfully.</div>
@foreach($validData as $validData)
    <hr>
    <strong>Name</strong>: {{ $validData['name'] }}
    <strong>Email</strong>: {{ $validData['mail'] }}
    <strong>Tel</strong>: {{ $validData['tel'] }}
    <br>
    <strong>Masked Result</strong>: {{ maskEmail($validData['mail']) }} / {{ maskTelephone($validData['tel']) }}
@endforeach

<h3 class="mt-5">Invalid Customers</h3>
<div class="text-muted">Customers that failed validation to any of their fields.</div>
@foreach($invalidData as $invalidData)
<hr>
<strong>Name</strong>: {{ $invalidData['customer']['name'] }}
<strong>Email</strong>: {{ $invalidData['customer']['mail'] }}
<strong>Tel</strong>: {{ $invalidData['customer']['tel'] }}
<br>
<strong>Fail reason</strong>: {{ json_encode($invalidData['errors']) }}
@endforeach

<h3 class="mt-5">Invalid lines</h3>
<div class="text-muted">Lines from file that could not be parsed as they were missing at least 1 element.</div>
@foreach($invalidLines as $invalidLine)
    <hr>
    {{ $invalidLine }}
@endforeach

</body>
</html>

