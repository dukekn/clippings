<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<main>
    <section class="container_setter">
        <section class="file_uploader">
            <fieldset>
                <h3>Import invoices file</h3>
                <label for="file_uploader">should be in csv format</label>
                <input id="file_uploader" type="file" accept=".csv" required>
            </fieldset>
            <hr>
        </section>
        <section class="currencies">
            <fieldset>
                <h3>Main currency</h3>
                <label for="currency_main">eg.: EUR</label>
                <input id="currency_main" type="text" value="" required>
            </fieldset>
            <fieldset>
                <h3>Output currency</h3>
                <label for="currency_output">eg.: GBP</label>
                <input id="currency_output" type="text" value="" required>
            </fieldset>
            <!--            <button> next ></button>-->
            <section class="currency_pair">
                <table id="currency_table">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Rate</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input type="text" name="pair['code']" value="" onfocusout="currency_check(this)"
                                   placeholder="Currency code" required >
                        </td>
                        <td>
                            <input type="number" step="0.01" placeholder="Main currency : This currency"  name="pair['rate']"  required >
                        </td>
                        <td>
                            <button onclick="currency_delete(this)" class="red">X</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </section>
            <button id="currency_create" class="blue w100"> + add</button>
            <br>
            <br>
        </section>
        <button id="calculate_submit" class="green w100">Calculate</button>
    </section>

    <section class="container_getter">
        <fieldset class="filter">
            <input id="filter" type="text" onkeyup="filter(this)" placeholder="Start typing customer name" value="">
        </fieldset>
        <section class="container_result">

        </section>

    </section>

</main>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    function currency_delete(el) {
        if ($('#currency_table tbody tr').length > 1) {
            $(el).closest('tr').remove();
        } else {
            alert('Can\'t delete last row');
        }
    }

    function currency_check(el) {
        var current_val = $(el).val().replace(' ','').toUpperCase(),
            currency_main = $('input#currency_main').val().replace(' ','').toUpperCase(),
            current_parent = $(el).closest('tr'),
            same_val_el = $('input[name="pair[\'code\']"]').filter(function () {
                return this.value == current_val
            }).length;


        if (same_val_el > 1 && current_val != '' || current_val== currency_main) {
            if(current_val== currency_main)
            {
                $('<span class="error">Can\'t use main currency!</span>').insertAfter(current_parent);
            }else{
                $('<span class="error">Duplicate currency!</span>').insertAfter(current_parent);
            }
            $(this).addClass('invalid');
            current_parent.addClass('error');

        } else {
            if (current_parent.hasClass('error')) {
                current_parent.removeClass('error')
            }

        }
    }

    $('#currency_create').on('click', function () {
        var last_row = $('#currency_table tbody tr:last-of-type'),
            clone = last_row.clone();
        clone.insertAfter(last_row).find("input").val("").attr({'disabled': false, 'placeholder': ''});
    });

    function filter(el)
    {
        var needle = $(el).val(),
            needle = needle.replace(' ', ''),
            haystack = $('table.table_client[data-customer^="' + needle + '"');

        if(needle != '')
        {
            if (haystack.length >= 1) {
                haystack.siblings().addClass('hidden');
                haystack.each(function () {
                    $(this).removeClass('hidden');
                });
            } else {
                $('.container_result > *').addClass('hidden');
            }
        }else{
            $('.container_result > *').removeClass('hidden');
        }
    }

    $('*[required]:invalid').on('keyup , change' , function(){
        var invalids = $('*[required]:invalid').length;
        if( invalids>= 1)
        {
            $('.container_setter .error').removeClass('hidden');
            $(this).is(':valid')
            {
                if(invalids == 1)
                {
                    $('.container_setter .error').addClass('hidden');
                }
                $(this).removeClass('invalid');
            }
        }else{
            $('.container_setter .error').addClass('hidden');
        }

    });

    $('#calculate_submit').on('click', function () {
        var invalid = $('input[required]').filter(function () {
            return !this.value;
        });
        if (invalid.length > 0) {
            if(!$('section.container_setter .error').length)
            {
                $('<span class="error">There are invalid fields!</span>').prependTo('section.container_setter');
            }
            invalid.each(function(){
                $(this).addClass('invalid');
            });
        } else {
            var file = $('input#file_uploader')[0].files[0],
                currency_output = $('input#currency_output').val(),
                currency_main = $('input#currency_main').val(),
                data = new FormData();

            $('input[name="pair[\'code\']"]').each(function () {
                data.append('pair_code', $(this).val());
            });
            data.append('pair_code', JSON.stringify(data.getAll('pair_code')))

            $('input[name="pair[\'rate\']"]').each(function () {
                data.append('pair_rate', $(this).val());
            });
            data.append('pair_rate', JSON.stringify(data.getAll('pair_rate')))

            data.append('file', file)
            data.append('currency_output', currency_output)
            data.append('currency_main' , currency_main)

            fetch('getData.php', {
                method: 'POST',
                body: data // <-- Post parameters
            })
                .then((response) => response.text())
                .then((responseText) => {
                    console.log(responseText);
                    $('.container_result').html(responseText);

                    var docs_parnt = $('table.table_client td').attr('data-parent');
                    console.log(docs_parnt.length)
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
</script>
</html>