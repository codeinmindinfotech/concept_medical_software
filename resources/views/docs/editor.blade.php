<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OnlyOffice Editor</title>
    <script type="text/javascript" src="{{ env('ONLYOFFICE_DOC_SERVER') }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
        <h1>Editing Document</h1>
        <div id="onlyoffice-editor" style="width: 100%; height: 100vh;"></div>
    
        <script type="text/javascript">
            var docEditor = new DocsAPI.DocEditor("onlyoffice-editor", {
                "document": {
                    "title": "{{ $config['document']['title'] }}",
                    "url": "{{ $config['document']['url'] }}",  // URL of the document
                    "fileType": "{{ $config['document']['fileType'] }}",  // File type (docx, etc.)
                    "key": "{{ $config['document']['key'] }}"  // Unique document key
                },
                "editorConfig": {
                    "mode": "{{ $config['editorConfig']['mode'] }}",  // edit or view mode
                    "lang": "en",  // Language for the editor
                    "callbackUrl": "{{ $config['editorConfig']['callbackUrl'] }}",  // Callback URL after editing
                    "user": {
                        "id": "{{ $config['editorConfig']['user']['id'] }}",
                        "name": "{{ $config['editorConfig']['user']['name'] }}"
                    },
                    "customization": {
                        "forcesave": true  // Ensure the document is saved on exit
                    }
                },
                "token": "{{ $config['token'] ?? '' }}"  // Add JWT if using
            });
        </script>
    {{-- </body>
    </html> --}}
    
{{-- <script>
    const config = {!! json_encode($config) !!};

    const docEditor = new DocsAPI.DocEditor("placeholder", config);
</script> --}}
</body>
</html>