<div id="side-popup" class="bk-overlay {{isset($size) ? $size : ''}}" style="width: 100%;">
    <div class="overlay-content">
        <div class="panel panel-default">
            <div class="modal-header">
                <h4 class="modal-title pull-left"> {{$title}}</h4>
                <button type="button" class="close side-close">×</button>
                <h4 class="modal-title release-note-heading pull-right"> </h4>
            </div>
            <div class="panel-body">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>