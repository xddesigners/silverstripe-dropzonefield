import Dropzone from 'dropzone';
Dropzone.autoDiscover = false;

const dropzoneFields = document.querySelectorAll('.field.dropzone-field');
dropzoneFields.forEach((dropzoneField) => {
  const field = dropzoneField.querySelector('.dropzone');
  const input = dropzoneField.querySelector('input[type="file"]');
  const config = JSON.parse(input.attributes['dropzone-config'].value);
  const schema = JSON.parse(input.attributes['data-schema'].value);
  const dropzone = new Dropzone(field, config);

  console.log(schema);
  dropzone.on('success', (file, response) => {
    console.log(response);
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = `${schema.name}[Files][]`;
    input.value = response[0].id;
    dropzoneField.appendChild(input);
  });
});