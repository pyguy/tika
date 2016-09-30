var eachTime = 8000;

function checkServices() {
    var module = 'services';

    $.get('check/' + module , function(data) {

        var $box = $('#' + module + ' tbody');
        $box.empty();

        for (var line in data) {

            var label_value = 'OFFLINE';

            if(data[line].status === 1)
                label_value = 'ONLINE';

            var label_status = 'label-danger';

            if(data[line].status === 1)
                label_status = 'label-success';

            var html = '<tr>';
            html += '<th>' + data[line].name + '</th>';
            html += '<td><span class="label ' + label_status + '">'+label_value+'</span></td>';
            html += '</th></tr>';

            $box.append(html);
        }

    }, 'json');
}

function checkNetworkUsage() {

    var module = 'network';

    $.get('check/' + module , function(data) {

        var $box = $('#' + module + ' tbody');
        $box.empty();

        var index = 1;

        for (var line in data) {

            var html = '';
            html += '<tr>';
            html += '<td scope="row">' + index + '</td>';
            html += '<td>'+data[line].interface+'</td>';
            html += '<td>'+data[line].ip+'</td>';
            html += '<td class="t-center">'+data[line].receive+'</td>';
            html += '<td class="t-center">'+data[line].transmit+'</td>';
            html += '</tr>';

            index++;

            $box.append(html);
        }

    }, 'json');
}

function getDatas() {
    // get network usage datas
    checkNetworkUsage();

    // get services-status datas
    checkServices();
}

$(document).ready(function () {
    getDatas()
});

// get datas each 8 per second
setInterval(function() {

    // get datas
    getDatas();

},eachTime);