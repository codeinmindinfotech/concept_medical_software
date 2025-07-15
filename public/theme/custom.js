// permission assign checkbox
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const checked = this.checked;
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
            document.querySelectorAll('.select-module').forEach(cb => cb.checked = checked);
        });
    }

    document.querySelectorAll('.select-module').forEach(moduleCheckbox => {
        moduleCheckbox.addEventListener('change', function () {
            const module = this.dataset.module;
            const checked = this.checked;
            document.querySelectorAll(`.${module}-perm`).forEach(cb => cb.checked = checked);
        });
    });
});
  