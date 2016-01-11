@if(Session::has('message'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-{{ Session::has('success') ? 'success' : 'danger' }} fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-{{ Session::has('success') ? 'check' : 'times' }}-circle fa-fw fa-lg"></i>
                {{ Session::get('message') }}
            </div>
        </div>
    </div>
@endif