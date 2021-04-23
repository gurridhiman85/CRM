if ($('.header-typeahead').length) {
    $typeahead = $('.header-typeahead');
    $url = $typeahead.data('url');
    var projects = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/nba.json'
        remote: {
            url: $url + '?q=%QUERY',
            wildcard: '%QUERY',
            transform: function (response) {
                return response.projects;
            }
        }
    });

    var users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/nba.json'
        remote: {
            url: $url + '?q=%QUERY',
            wildcard: '%QUERY',
            transform: function (response) {
                return response.users;
            }
        }
    });

    var tasks = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/nba.json'
        remote: {
            url: $url + '?q=%QUERY',
            wildcard: '%QUERY',
            transform: function (response) {
                return response.tasks;
            }
        }
    });

    var tickets = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/nba.json'
        remote: {
            url: $url + '?q=%QUERY',
            wildcard: '%QUERY',
            transform: function (response) {
                return response.tickets;
            }
        }
    });

    var notes = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/nba.json'
        remote: {
            url: $url + '?q=%QUERY',
            wildcard: '%QUERY',
            transform: function (response) {
                return response.notes;
            }
        }
    });


    $('.header-typeahead').typeahead({
            highlight: true
        },
        {
            name: 'projects',
            display: 'name',
            source: projects,
            templates: {
                header: '<h5 class="search-header">Projects</h5>'
            }
        },
        {
            name: 'tickets',
            display: 'name',
            source: tickets,
            templates: {
                header: '<h5 class="search-header">Tickets</h5>'
            }
        },
        {
            name: 'users',
            display: 'name',
            source: users,
            templates: {
                header: '<h5 class="search-header">Users</h5>'
            }
        },
        {
            name: 'notes',
            display: 'name',
            source: notes,
            templates: {
                header: '<h5 class="search-header">Notes</h5>'
            }
        }
        // {
        //     name: 'nhl-teams',
        //     display: 'team',
        //     source: nhlTeams,
        //     templates: {
        //         header: '<h3 class="league-name">NHL Teams</h3>'
        //     }
        //
        // }
    );
}