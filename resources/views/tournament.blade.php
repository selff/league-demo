@include('header')

@php
    $countMembers = $tournament->members_count ?? 0;
    if ($countMembers % 2) {
        $matchesPerWeek = 1;
        $matches = $launches = $countMembers;
    } else {
        $matchesPerWeek = $countMembers / 2;
        $matches = $countMembers * ($countMembers - 1);
        $launches = $matches / ($matchesPerWeek ? $matchesPerWeek : 1);
    }
@endphp

<div class="jumbotron">
    <div class="container">
        <div><h1 class="display-3">Okay!</h1></div>
        <p>A tournament {{ $tournament->title }}} has been created. Teams are generated. It's time to launch the game.<br>
            For {{ $countMembers }} teams to play with each other twice (home and away), you need {{ $launches }} launches of the games ({{ $matchesPerWeek }} per week).
        </p>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width:50%">League Table</th>
                    <th style="width:50%">Match Results</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <table class="table table-striped table-sm" id="weekTable">
                            <thead>
                            <tr>
                                <td scope="col">Teams</td>
                                <td scope="col">PTS</td>
                                <td scope="col">P</td>
                                <td scope="col">W</td>
                                <td scope="col">D</td>
                                <td scope="col">L</td>
                                <td scope="col">GD</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($members as $member)

                                <tr class="table-row row-{{ $loop->index }}">
                                    <td class="col-7">{{ $member->team_name }}</td>
                                    <td class="col-1"></td>
                                    <td class="col-1"></td>
                                    <td class="col-1"></td>
                                    <td class="col-1"></td>
                                    <td class="col-1"></td>
                                    <td class="col-1"></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table class="table table-striped table-sm" id="tableResult" style="visibility:hidden">
                            <thead>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <span class="setweek">{{ $week }}</span><sup>th</sup> Week Match Result
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="result-0">
                                <td class="col-5">Team1</td>
                                <td class="col-2 text-center"></td>
                                <td class="col-5 text-right">Team2</td>
                            </tr>
                            <tr class="result-1">
                                <td class="col-5">Team2</td>
                                <td class="col-2 text-center"></td>
                                <td class="col-5 text-right">Team1</td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form>
                            <button id="btn-all" class="btn btn-sm btn-outline-secondary">Play All</button>
                        </form>
                    </td>
                    <td class="text-right">
                        <form>
                            <button id="btn-new" class="btn btn-sm btn-outline-secondary">Next Week</button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <table class="table table-sm table-striped" id="tableProd">
                            <thead>
                            <tr>
                                <th colspan="2" class="text-center"><span class="setweek">0</span><sup>th</sup> Week productions of championship</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($members as $member)
                                <tr class="production">
                                    <td class="team-name">{{ $member->team_name }}</td>
                                    <td class="team-prod text-right">%0</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <table id="log" class="table">

        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        let disabled = false;
        let week = {{ $week }};
        setTimeout(function (){
            initLoad('last');
        }, 1);
        function adjust(color, amount) {
            return '#' + color.replace(/^#/, '').replace(/../g, color => ('0'+Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)).substr(-2));
        }
        function changer(id,text) {
            var result = prompt("Please enter new result", text);
            if (result != null) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "/game/edit",
                    data: {
                        tournamentId: window.location.pathname.split("/").pop(),
                        id: id,
                        text: result
                    },
                    success: function (data) {
                        initLoad('last');
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        }
        function initLoad(day){
            if ($.inArray(day, ['new', 'last', 'all']) == -1) return;
            $('#tableResult').css('visibility', 'visible');
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}});
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/game/"+day,
                data: {
                    tournamentId: window.location.pathname.split("/").pop(),
                    currentWeek: week,
                },
                success: function (data) {
                    week  = data.week;
                    $('.setweek').text(week+1);
                    let x = 0;
                    let y = 0;
                    let cols = ['team_name','points','played','won','drawn','lost','goals_diff'];
                    $('#weekTable').find('tr.table-row').each(function() {
                        y = 0;
                        $(this).find("td").each(function() {
                            $(this).text(data.week_table[x][cols[y]]);
                            y++;
                        });
                        x++;
                    });
                    cols = ['team_owner','goals','team_guest'];
                    for(x=0;x<2;x++) {
                        y = 0;
                        $('.table').find('tr.result-' + x).find("td").each(function () {
                            $(this).text(data.week_results[x][cols[y]]);
                            y++;
                        });
                    }
                    x = 0;
                    $('#tableProd').find('tr.production').each(function () {
                        if (data.week_productions[x]){
                            $(this).find('td.team-name').text(data.week_productions[x].team_name);
                            $(this).find('td.team-prod').text('%'+data.week_productions[x].production);
                            x++;
                        }
                    });
                    $('#log').empty();
                    let color = '#ffffff';
                    for(x=0;x<data.log.length;x++) {
                        var e = $('<tr style="background-color:'+color+'"><td class=\'text-right\'>'+data.log[x].owner_team +'</td><td class=\'text-center\'>'+data.log[x].owner_goals + '-' + data.log[x].guest_goals + '</td><td>'+data.log[x].guest_team +'</td></tr>');
                        $('#log').append(e);
                        e.attr('id', 'goals-'+data.log[x].id);
                        e.addClass('goals');
                        if (x%2) color = adjust(color, -20);
                    }
                    $(".goals").click(function (e) {
                        changer(this.id,$(this).find('td.text-center').first().text());
                    });
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
        $("#btn-new").click(function (e) {
            e.preventDefault();
            initLoad('new');
        });
        $("#btn-all").click(function (e) {
            e.preventDefault();
            initLoad('all');
        });
    });
</script>
@include('footer')
