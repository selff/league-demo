@include('header')

<div class="jumbotron">
    <div class="container">
        <div><h1 class="display-3">Let's, start!</h1></div>
        <p>Now there will be a generation of tournament members.<br>
            There will be {{ Config::get('constants.options.group_members_count') }} teams in our group, each will play with each one twice - at home and away.</p>
        <form method="POST" action="/tournament/start" class="form-inline">
            <div class="input-group">
                <button type="submit" class="btn btn-primary btn-lg">Go »</button>
                @csrf
            </div>
        </form>
    </div>
</div>

@include('footer')
