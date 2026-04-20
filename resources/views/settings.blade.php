@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title text-danger"><i class="ti ti-alert-triangle"></i> System Rescue Mode</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        If a snippet has caused your site or administration panel to crash (Fatal Errors) and you still have access to this page, you can execute a forced global override to disable all running snippets from executing.
                    </p>
                    <p class="text-muted mb-4">
                        <strong>Note:</strong> If the entire administration panel is completely inaccessible (White Screen of Death), you can still execute the rescue protocol externally via your server console using the following command:<br>
                        <code class="d-inline-block mt-2 p-2 bg-dark text-white rounded">php artisan snippets:rescue</code>
                    </p>

                    <form action="{{ route('snippets.rescue') }}" method="POST">
                        @csrf
                        <div class="alert alert-warning">
                            <i class="ti ti-info-circle"></i> Executing this will forcefully move <strong>all Published snippets</strong> back to <strong>Draft</strong> status immediately.
                        </div>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to disable ALL active snippets? This action cannot be undone automatically.');">
                            <i class="ti ti-lifebuoy"></i> Fire Rescue Protocol
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
