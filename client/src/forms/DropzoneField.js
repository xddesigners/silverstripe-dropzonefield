import Dropzone from 'dropzone';
Dropzone.autoDiscover = false;

const dropzoneFields = document.querySelectorAll('.field.dropzone-field');
dropzoneFields.forEach((dropzoneField) => {
  const field = dropzoneField.querySelector('.dropzone');
  const input = dropzoneField.querySelector('input[type="file"]');
  const config = JSON.parse(input.attributes['dropzone-config'].value);
  const schema = JSON.parse(input.attributes['data-schema'].value);
  const state = JSON.parse(input.attributes['data-state'].value);
  const dropzone = new Dropzone(field, config);

  if (state.data.files.length) {
    for (const i in state.data.files) {
      const file = state.data.files[i];
      const mockFile = { name: file.title, size: file.size };

      // Call the default addedfile event handler
      dropzone.emit("addedfile", mockFile);

      // And optionally show the thumbnail of the file:
      dropzone.emit("thumbnail", mockFile, file.thumbnail);

      // Make sure that there is no progress bar, etc...
      dropzone.emit("complete", mockFile);

      dropzone.options.maxFiles = dropzone.options.maxFiles - 1;
    }
  }
  
  dropzone.on('success', (file, response) => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = `${schema.name}[Files][]`;
    input.value = response[0].id;
    dropzoneField.appendChild(input);
  });
});