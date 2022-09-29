// import Dropzone from 'dropzone/dist/min/dropzone.min';
import { Dropzone } from "dropzone";
Dropzone.autoDiscover = false;

const IE = navigator.appName == 'Microsoft Internet Explorer' ||  !!(navigator.userAgent.match(/Trident/) || navigator.userAgent.match(/rv:11/)) || (typeof $.browser !== "undefined" && $.browser.msie == 1);
const dropzoneFields = document.querySelectorAll('.field.dropzone-field');


for (let dropzoneField of dropzoneFields) {

  if (!IE ) {
    dropzoneField.classList.add('dropzone-field--supported');
    const field = dropzoneField.querySelector('.dropzone');
    const input = dropzoneField.querySelector('input[type="file"]');
    const config = JSON.parse(input.attributes['dropzone-config'].value);
    const schema = JSON.parse(input.attributes['data-schema'].value);
    const state = JSON.parse(input.attributes['data-state'].value);

    console.log('config', config);

    const dropzone = new Dropzone(field, config);
    dropzone.on("removedfile", file => {
      
      console.log('removedfile', file);

      if (config.hasOwnProperty('removeUrl')) {
        const req = new XMLHttpRequest();
        req.open('POST', config.removeUrl);
        for (const [key, value] of Object.entries(config.headers)) {
          req.setRequestHeader(key, value);
        }
        req.send(JSON.stringify({fileId: file.serverId}));
      }
    });

    if (typeof state.data.files !== 'undefined' && state.data.files.length > 0) {
      let file = state.data.files[0];
      dropzone.displayExistingFile(file, file.thumbnail);
    }

    dropzone.on('success', (file, response) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = `${schema.name}[Files][]`;
      input.value = response[0].id;
      file.serverId = response[0].id;
      dropzoneField.appendChild(input);
    });
  }
}
