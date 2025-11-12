// This function inserts the tag at the current cursor in ONLYOFFICE editor
function insertTag(tag) {
    try {
        // Official API method for inserting text
        window.Asc.plugin.executeMethod('InsertText', [tag]);
        console.log(`✅ Inserted tag: ${tag}`);
    } catch (err) {
        console.error("❌ Failed to insert tag:", err);
        alert("Cannot insert tag. Make sure plugin API is available.");
    }
}