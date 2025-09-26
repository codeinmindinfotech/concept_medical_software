<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ONLYOFFICE Editor</title>
    <script type="text/javascript" src="{{ $documentServer }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <div id="placeholder" style="width:100%; height:90vh;"></div>

    <script>
        const config = {!! $config !!};

        // Instantiate the editor
        const docEditor = new DocsAPI.DocEditor("placeholder", {
            width: "100%",
            height: "100%",
            documentType: "text",
            token: config.token ?? null,
            document: config.document,
            editorConfig: config.editorConfig
        });
    </script>
</body>
</html>
