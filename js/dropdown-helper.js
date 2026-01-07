// Reusable dropdown setup function
function setupDropdown(btnId, dropdownId, pillsId, radioClass, hiddenInputId, placeholderText) {
    const btn = document.getElementById(btnId);
    const dropdown = document.getElementById(dropdownId);
    const pills = document.getElementById(pillsId);
    const radios = document.querySelectorAll('.' + radioClass);
    const hiddenInput = document.getElementById(hiddenInputId);

    if (!btn || !dropdown || !pills || !hiddenInput) return;

    const toggle = (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    };

    btn.addEventListener('click', toggle);
    pills.addEventListener('click', toggle);

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !btn.contains(e.target) && !pills.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                pills.innerHTML = `<div class="user-pill">${radio.dataset.name} <i class="fa fa-times" onclick="removeOption('${pillsId}', '${radioClass}', '${hiddenInputId}', '${placeholderText}', event)"></i></div>`;
                hiddenInput.value = radio.value;
                dropdown.classList.remove('show');
            }
        });
    });
}

window.removeOption = function (pillsId, radioClass, hiddenInputId, placeholderText, e) {
    e.stopPropagation();
    document.getElementById(pillsId).innerHTML = `<span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">${placeholderText}</span>`;
    document.getElementById(hiddenInputId).value = '';
    document.querySelectorAll('.' + radioClass).forEach(r => r.checked = false);
};
