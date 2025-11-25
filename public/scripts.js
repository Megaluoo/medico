(function () {
  document.querySelectorAll('.section-header').forEach(function (header) {
    header.addEventListener('click', function () {
      const container = header.parentElement;
      container.classList.toggle('collapsed');
    });
  });

  const specialtySelect = document.getElementById('specialty-select');
  if (specialtySelect) {
    specialtySelect.addEventListener('change', function (event) {
      const value = event.target.value;
      const params = new URLSearchParams(window.location.search);
      params.set('action', 'create');
      params.set('specialty', value);
      window.location.search = params.toString();
    });
  }

  const customContainer = document.getElementById('custom-fields-container');
  const addButton = document.getElementById('add-custom-field');
  if (addButton && customContainer) {
    addButton.addEventListener('click', function () {
      const row = document.createElement('div');
      row.className = 'custom-field-row';
      row.innerHTML = `
        <input type="text" name="custom_field_label[]" placeholder="Etiqueta" required />
        <input type="text" name="custom_field_value[]" placeholder="Valor" />
        <button type="button" class="remove-field">✕</button>
      `;
      row.querySelector('.remove-field').addEventListener('click', function () {
        row.remove();
      });
      customContainer.appendChild(row);
    });
  }
})();
