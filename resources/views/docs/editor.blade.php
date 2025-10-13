<!DOCTYPE html>
<html>
<head>
    <title>OnlyOffice Document Editor</title>
    <script type="text/javascript" src="http://137.184.194.64/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <h2>Document Editor</h2>
    <div id="placeholder" style="width: 100%; height: 800px;"></div>

    <script type="text/javascript">
        var docEditor = new DocsAPI.DocEditor("placeholder", {
            document: {
                fileType: "docx",
                key: "doc-key-{{ \Illuminate\Support\Str::uuid() }}",
                title: "My Document",
                url: "https://conceptmedicalpm.ie/storage/document_templates/KkqZ2ghGmwwaXBS1D1XmrOSVfZtopDuayNOqLpih.docx"
            },
            documentType: "word",
            editorConfig: {
                mode: "edit", // or 'view'
                callbackUrl: "{{ route('onlyoffice.callback') }}",
                user: {
                    id: "1",
                    name: "John Doe"
                }
            }
        });
    </script>
</body>
</html>
