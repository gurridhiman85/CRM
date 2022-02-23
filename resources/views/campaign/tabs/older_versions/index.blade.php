<style>
    button.ds-c3 {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }
    button.ds-c3:hover {
        background-color: #3ea6d0;
        color: #fff;
    }

    button.ds-c4:hover {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }
    button.ds-c4 {
        background-color: #3ea6d0;
        color: #fff;
    }


    .ds-l{
        color: #9e9e9e;
    }

</style>
<div id="older_versions_tab" class="table-responsive m-t-5" >
    @include('campaign.tabs.older_versions.table')
    <script type="application/javascript">
        $(document).ready(function () {
            $('.c-btn').html('');
            $('.older-version').show();
        })
    </script>
</div>
