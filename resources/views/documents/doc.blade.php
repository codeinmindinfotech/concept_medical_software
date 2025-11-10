<!DOCTYPE html>
<html>
<head>
    <title>OnlyOffice Demo</title>
    <script type="text/javascript" src="{{ rtrim(config('onlyoffice.server_url'), '/') }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body style="margin:0">
    <div id="placeholder" style="height:100vh;"></div>

    <script type="text/javascript">
        var docEditor = new DocsAPI.DocEditor("placeholder", {!! json_encode($config) !!});
    </script>
</body>
</html>
