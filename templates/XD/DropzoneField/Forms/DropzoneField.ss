<% if $IncludeScripts %>
    <% require javascript("xddesigners/silverstripe-dropzonefield:client/dist/js/ssdropzonefield.js") %>
    <% require css("xddesigners/silverstripe-dropzonefield:client/dist/styles/ssdropzonefield.css") %>
<% end_if %>
<div class="dropzone">

    <% if $Items %>
        <div class="dropzone-items">
            <% loop $Items %>
                <input type="hidden" name="$Up.Name[Files][]" value="$ID.ATT" />
            <% end_loop %>
        </div>
    <% end_if %>


    <div class="dz-message" data-dz-message>
        <span><%t XD\DropzoneField\Forms\DropzoneField.DZMessage 'Drop files here to upload' %></span>
    </div>
</div>
<input $AttributesHTML />
