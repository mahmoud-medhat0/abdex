<div class="col-12">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif(session('success1'))
        <div class="alert alert-danger">
            {{ session('success1') }}
        </div>
    @endif
</div>
