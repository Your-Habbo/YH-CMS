<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }}</title>
    <style>
        {!! $page->custom_css !!}
    </style>
</head>
<body>

            {!! $page->content !!}

        {!! $page->custom_js !!}
    </script>
</body>
</html>
