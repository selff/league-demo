@include('../header')

<div class="jumbotron">
    <div class="container">
        <div><h1 class="display-3">Ups!</h1></div>
        <p>Please check all required data sources!<br>
            {{ $exception->getMessage() }}</p>
    </div>
</div>

@include('../footer')
