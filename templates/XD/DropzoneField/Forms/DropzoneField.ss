<% if $IncludeScripts %>
    <% require javascript("xddesigners/silverstripe-dropzonefield:client/dist/js/bundle.js") %>
    <% require css("xddesigners/silverstripe-dropzonefield:client/dist/styles/bundle.css") %>
<% end_if %>
<div class="dropzone">
    <div class="dz-message" data-dz-message>
        <span><%t XD\DropzoneField\Forms\DropzoneField.DZMessage 'Drop files here to upload' %></span>
    </div>
</div>
<input $AttributesHTML />
