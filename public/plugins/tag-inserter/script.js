// ✅ Called automatically by OnlyOffice
window.Asc.plugin.init = function() {
    console.log("✅ Tag Inserter plugin initialized");

    // Enable all buttons
    const buttons = document.querySelectorAll('.tag-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tag = this.dataset.tag;
            try {
                window.Asc.plugin.executeMethod('InsertText', [tag]);
                console.log(`✅ Inserted tag: ${tag}`);
            } catch (err) {
                console.error("❌ Failed to insert tag:", err);
                alert("Cannot insert tag. Make sure plugin API is available.");
            }
        });
    });
};
