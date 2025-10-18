<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OnlyOffice Editor</title>
    <script type="text/javascript" src="{{ env('ONLYOFFICE_DOC_SERVER') }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
<div id="placeholder" style="height:900px;"></div>

<script>
    const config = {!! json_encode($config) !!};

    const docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
</body>
</html>