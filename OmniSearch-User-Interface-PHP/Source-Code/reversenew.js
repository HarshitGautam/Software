var gene = '';
var mirna='';
var mesh='';
var page = 1;
var pages = 1;
var total = 0;
var targets = [];
var rows = 100;
var sort_dir = 'ASC';
var sort_col = 'mirna_label';
var selected = [];
var validation_filter = 'all';
var database_filter = ['mirdb', 'targetscan', 'miranda', 'mirtarbase'];
var database_operator = 'any';
var pubmed_filter = 'all';
var jqxhr = null;

function reset() {
	gene='';
	mirna='';
	mesh='';
    page = 1;
    pages = 1;
    total = 0;
    targets = [];
    rows = 100;
    sort_dir = 'ASC';
    sort_col = 'mirna_label';
    selected = [];
    jqxhr = null;

    document.title = 'OmniSearch';

    $('#content').hide();
    $('#result_controls').hide();
    $('#rows_select').find('option[value=100]').prop('selected', true);
	$('#download_selected_lbl').text(selected.length);
    $('#download_partial_lbl').text(selected.length);
	
    $('#filter_panel').hide();
    $('#download_panel').hide();
    $('#analysis_panel').hide();

    $('#apply_btn').prop('disabled', true);
}
function disableAll() {
    $('input, button, select').prop('disabled', true);
    $('.sorting').addClass('disabled');
}
function enableAll() {
    $('input, button, select').prop('disabled', false);
    $('.sorting').removeClass('disabled');

    $('#first_btn').prop('disabled', page == 1);
    $('#prev_btn').prop('disabled', page == 1);
    $('#next_btn').prop('disabled', page == pages);
    $('#last_btn').prop('disabled', page == pages);

    $('[name=database_filter]').prop('checked', false);
    for (var i = 0; i < database_filter.length; ++i) {
        $('[name=database_filter][value=' + database_filter[i] + ']').prop('checked', true);
    }
    $('[name=database_operator][value=' + database_operator + ']').prop('checked', true);
    $('[name=validation_filter][value=' + validation_filter + ']').prop('checked', true);
    $('[name=pubmed_filter][value=' + pubmed_filter + ']').prop('checked', true);

    if (selected.length == 0) {
        $('[name=download_radio][value=selected]').prop('disabled', true);
        $('[name=download_radio][value=partial]').prop('disabled', true);
        $('[name=download_radio][value=all]').prop('checked', true);
        $('[name=format_radio]').prop('disabled', false);
    }
    else {
        $('[name=download_radio][value=selected]').prop('disabled', false);
        $('[name=download_radio][value=partial]').prop('disabled', false);
    }

    if ($('[name=download_radio][value=selected]').is(':checked')) {
        $('[name=format_radio]').prop('disabled', true);
    }
    else {
        $('[name=format_radio]').prop('disabled', false);
    }
}

$('body').tooltip({
    selector: '[data-toggle=tooltip]'
});

$(document).on('mousedown', function () {
    $('.searchbox > div').hide();
    if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}
});


$('.searchbox').on('mousedown', 'div', function (e) {
    e.stopPropagation();
});

$('.searchbox').on('blur', function() {
	if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}
});

$('.searchbox').on('click', 'div > ul > li', function () {
    $(this).parent().toggleClass('collapsed').find('ul').toggle();
});

$('.searchbox > div')
    .on('keydown', function (e) {
        e.preventDefault();

        var p = $(this).find('p:focus');

        if (e.which == 38) {
            var prev = p.prev('p');
            if (prev.length) {
                prev.focus();
            }
        }
        else if (e.which == 40) {
            var next = p.next('p');
            if (next.length) {
                next.focus();
            }
        }
    })
    .on('keydown', 'p', function (e) {
        if (e.which == 9 || e.which == 13) {
            $(this).closest('div').hide();
            $(this).closest('div').prev().val($(this).text()).focus();
            if (e.which == 9)
                $(this).closest('div').parent().parent().next().find('input, button').focus();
        }
    })
    .on('mousemove', 'p', function () {
        $(this).focus();
    })
    .on('mousedown', 'p', function () {
        $(this).closest('div').prev().val($(this).text());
        $(this).closest('div').hide();
    });

	$('#gene')
    .on('input', function () {
        query('gene',false);
    })
    .on('keydown', function (e) {
        if (e.which == 40) {
            if ($(this).next().is(':visible')) {
                $(this).next().find('p:first').focus();
            }
            else {
                query('gene',true);
            }
            return false;
        }
    });
	
	$('#mirna')
    .on('input', function () {
        query('mirna', false);
    })
    .on('keydown', function (e) {
        if (e.which == 40) {
            if ($(this).next().is(':visible')) {
                $(this).next().find('p:first').focus();
            }
            else {
                query('mirna', true);
            }
            return false;
        }
    });
	
	$('#mesh')
    .on('input', function () {
        query('mesh', false);
    })
    .on('keydown', function (e) {
        if (e.which == 40) {
            if ($(this).next().is(':visible')) {
                $(this).next().find('p:first').focus();
            }
            else {
                query('mesh', true);
            }
            return false;
        }
    });
	
	function query(type,focus) {
    if (focus)
        $('#' + type).prop('disabled', true);

    $('#' + type).next().html('<h5>Searching...</h5>').show();

    if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}

    jqxhr = $.get('reversequery.php', {
            
			type: type,
            mirna: $('#mirna').val(),
            gene: $('#gene').val(),
			mesh: $('#mesh').val()
        })
        .done(function (json) {
            if(json.length == 0) {
                $('#error_panel').html('<h2>Server Timeout</h2>').show();
				return;
			}
            json = JSON.parse(json);
            if (json.success && jqxhr) {
				if(type=='gene') {
                $('#gene').next().html(json.html).show();
                $('#gene').next().find('p').on('click', function () {
                    $('#gene').val($(this).text());
                });
                if (focus) {
                    $('#gene').next().find('p:first').focus();
                }
				}
				if (type == 'mirna') {
                    $('#mirna').next().html(json.html).show();
                    $('#mirna').next().find('p').on('click', function () {
                        $('#mirna').val($(this).text());
                    });
                    if (focus) {
                        $('#mirna').next().find('p:first').focus();
                    }
                }
				if (type == 'mesh') {
                    $('#mesh').next().html(json.html).show();
                    $('#mesh').next().find('p').on('click', function () {
                        $('#mesh').val($(this).text());
                    });
                    if (focus) {
                        $('#mesh').next().find('p:first').focus();
                    }
                }
            }
            else {
				if (type == 'gene')
                $('#gene').next().html('<h5>' + json.error + '</h5>').show();
				else
					 $('#mirna').next().html('<h5>' + json.error + '</h5>').show();
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (errorThrown != 'abort')
                alert(textStatus + ':' + errorThrown);
        })
        .always(function () {
            $('#' + type).prop('disabled', false);
        });
    }
	
	$('#search_form').on('submit', function (e) {
    e.preventDefault();

    reset();
   
    gene = $('#gene').val();
	 mirna = $('#mirna').val();
	 mesh = $('#mesh').val();

    $('#error_panel').html('<h2>Searching...</h2>').show();

    applyFilters();
});

function search() {
    $('#gene').val(gene);
	$('#mirna').val(mirna);
	$('#mesh').val(mesh);
    disableAll();
	$.post('reversesearch.php', {
            
			mirna: mirna,
            gene: gene,
			mesh: mesh,
            page: page,
            rows: rows,
            sort_dir: sort_dir,
            sort_col: sort_col,
            selected: JSON.stringify(selected),
            validation_filter: validation_filter,
            database_filter: JSON.stringify(database_filter),
            database_operator: database_operator,
            pubmed_filter: pubmed_filter
        })
         .done(function (json) {
			if(json.length == 0) {
				$('#content').hide();
                $('#result_controls').hide();
                $('#error_panel').html('<h2>Server Timeout</h2>').show();
				enableAll();
				return;
			}
            json = JSON.parse(json);
            if (json.success) {
                page = parseInt(json.page, 10);
                pages = parseInt(json.pages, 10);
                total = parseInt(json.total, 10);
                targets = json.targets;

                document.title = 'OmniSearch: ' + gene;

                $('#page_span').text(page);
                $('#pages_span').text(pages);
                $('#total_span').text(total + ' Total microRNAs');
                $('#download_all_lbl').text(total);
                $('#table_div').html(json.html);
                $('#rna_central_link').attr('href', 'http://amigo.geneontology.org/amigo/search/annotation?fq=taxon_label:%22Homo%20sapiens%22&q=%22'+gene +'%22' );

                $('#error_panel').hide();
                $('#result_controls').show();
                $('#content').show();
            }
            else {
                $('#content').hide();
                $('#result_controls').hide();
                $('#error_panel').html('<h2>' + json.error + '</h2>').show();
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $('#content').hide();
			$('#result_controls').hide();
			$('#error_panel').html('<h2>Server Timeout</h2>').show();
        })
        .always(function () {
            enableAll();
        });
}

function applyFilters() {
    selected = [];
    $('#download_selected_lbl').text(selected.length);
    $('#download_partial_lbl').text(selected.length);

    database_filter = $('[name=database_filter]:checked').map(function () {
        return this.value;
    }).get();
    database_operator = $('[name=database_operator]:checked').val();
    validation_filter = $('[name=validation_filter]:checked').val();
    pubmed_filter = $('[name=pubmed_filter]:checked').val();

    search();
}

$('[name=validation_filter][value=predicted]').on('change', function() {
	if($('[name=database_filter][value=mirtarbase]').is(':checked')) {
		alert('There are no predicted results from the miRTarBase data source. This data source will be excluded.');
		$('[name=database_filter][value=mirtarbase]').prop('checked', false);
	}
});

$('[name=database_filter][value=mirtarbase]').on('change', function() {
	if($(this).is(':checked') && $('[name=validation_filter][value=predicted]').is(':checked')) {
		alert('There are no predicted results from the miRTarBase data source. Both validated and predicted results will be shown.');
		$('[name=validation_filter][value=all]').prop('checked', true);
	}
});

$('[name=download_radio][value=all]').on('change', function () {
    if (this.checked)
        $('[name=format_radio]').prop('disabled', false);
});

$('[name=download_radio][value=partial]').on('change', function() {
   if (this.checked)
        $('[name=format_radio]').prop('disabled', false);
});

$('[name=download_radio][value=selected]').on('change', function () {
    if (this.checked)
        $('[name=format_radio]').prop('disabled', true);
});

$('#download_btn1').on('click', function () {
    var format = $('[name=format_radio]:checked').val();

    if ($('[name=download_radio][value=selected]').is(':checked')) {
        format = 'txt';

        $.ajax({
            type: 'POST',
            url: 'reversedownload.php',
            data: {
                format: 'selected',
                selected: JSON.stringify(selected)
            },
            async: false,
            success: function (json) {
                json = JSON.parse(json);
                if (json.success) {
                    window.open('reversedownload.php?format=' + format + '&gene=' + gene + '&sort_dir=' + sort_dir + '&sort_col=' + sort_col, '_blank');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ':' + errorThrown);
            }
        });
    }
    else if ($('[name=download_radio][value=partial]').is(':checked')) {
        $.ajax({
            type: 'POST',
            url: 'reversedownload.php',
            data: {
                format: 'selected',
                selected: JSON.stringify(selected)
            },
            async: false,
            success: function (json) {
                json = JSON.parse(json);
                if (json.success) {
                    window.open('reversedownload.php?format=' + format + '&partial=true&gene=' + gene +
                        '&sort_dir=' + sort_dir + '&sort_col=' + sort_col +
                        '&validation_filter=' + validation_filter +
                        '&database_filter=' + JSON.stringify(database_filter) +
                        '&database_operator=' + database_operator +
                        '&pubmed_filter=' + pubmed_filter, '_blank');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ':' + errorThrown);
            }
        });
    }
    else {
        window.open('reversedownload.php?format=' + format + '&gene=' + gene+
            '&sort_dir=' + sort_dir + '&sort_col=' + sort_col +
            '&validation_filter=' + validation_filter +
            '&database_filter=' + JSON.stringify(database_filter) +
            '&database_operator=' + database_operator +
            '&pubmed_filter=' + pubmed_filter, '_blank');
    }
});

$('#goto_page_form').on('submit', function (e) {
    e.preventDefault();

    var input = $(this).find('input[type=text]');
    page = parseInt(input.val(), 10);
    input.val('');
    search();
});

$('#goto_page_form').find('input[type=text]').on('keydown', function (e) {
    var key = (96 <= e.keyCode && e.keyCode <= 105) ? e.keyCode - 48 : e.keyCode;
    if (key != 8 && key != 13 && (key < 48 || key > 57))
        return false;

    if (key != 8 && key != 13) {
        var n = parseInt($(this).val() + String.fromCharCode(key));
        if (n < 1 || n > pages)
            return false;
    }
});

$('#rows_select').on('change', function () {
    rows = $(this).val();
    search();
});

function first() {
    page = 1;
    search();
}

function prev() {
    page = Math.max(1, page - 1);
    search();
}

function next() {
    page = Math.min(pages, page + 1);
    search();
}

function last() {
    page = pages;
    search();
}

function selectAll(e) {
    if ($(e).is(':checked')) {
        selected = targets;
        $('[name=download_radio][value=partial]').prop('disabled', false).prop('checked', true);
        $('[name=download_radio][value=selected]').prop('disabled', false);
        $('[name=format_radio]').prop('disabled', false);
        $('#results_body').find('input[type=checkbox]').prop('checked', true);
    }
    else {
        selected = [];
        $('[name=download_radio][value=selected]').prop('disabled', true);
        $('[name=download_radio][value=partial]').prop('disabled', true);
        $('[name=download_radio][value=all]').prop('checked', true);
        $('#results_body').find('input[type=checkbox]').prop('checked', false);
    }
    $('#download_selected_lbl').text(selected.length);
    $('#download_partial_lbl').text(selected.length);
}

function onSelect(e) {
    if ($(e).is(':checked')) {
        selected.push(e.value);
        if (selected.length == total) {
            $('#select_all').prop('checked', true);
        }
        if (selected.length > 0) {
            $('[name=download_radio][value=partial]').prop('disabled', false).prop('checked', true);
            $('[name=download_radio][value=selected]').prop('disabled', false);
            $('[name=format_radio]').prop('disabled', false);
        }
    }
    else {
        selected.splice(selected.indexOf(e.value), 1);
        if (selected.length == 0) {
            $('[name=download_radio][value=selected]').prop('disabled', true);
            $('[name=download_radio][value=partial]').prop('disabled', true);
            $('[name=download_radio][value=all]').prop('checked', true);
        }
        $('#select_all').prop('checked', false);
    }
    $('#download_selected_lbl').text(selected.length);
    $('#download_partial_lbl').text(selected.length);
}

function sort(col) {
    if(sort_col != col) {
        if(col == 'mirna_label' )
            sort_dir = 'ASC';
        else
            sort_dir = 'DESC';
    }
    else {
        sort_dir = sort_dir == 'ASC' ? 'DESC' : 'ASC';
    }
    sort_col = col;
    search();
}