<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OnlyOffice Editor</title>
    <script type="text/javascript" src="{{ env('ONLYOFFICE_DOC_SERVER') }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <h1>Editing Document</h1>
    <div id="onlyoffice-editor" style="width: 100%; height: 900px !important;"></div>
    
    <script type="text/javascript">
        const config = {!! json_encode($config) !!};
        console.log(config);
        const docEditor = new DocsAPI.DocEditor("onlyoffice-editor", config);
    </script> 
</body>
</html>