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

/**
* Display validation errors beside form fields
*/
function handleValidationErrors(errors, form) {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback, .text-danger').forEach(el => el.remove());
  
    for (const [field, messages] of Object.entries(errors)) {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('invalid-feedback');
            errorDiv.textContent = messages[0];
            input.parentNode.appendChild(errorDiv);
        }
    }
}