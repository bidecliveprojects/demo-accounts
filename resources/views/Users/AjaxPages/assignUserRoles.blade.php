@php
    $assigned = $user->getRoleNames()->toArray();
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-muted">User: <strong>{{ e($user->name) }}</strong> — {{ e($user->email) }}</p>
        <p class="small">Sidebar links use Spatie permission checks with route names. Assign roles that already include those permissions (configure under Roles).</p>
        <form id="saveUserRolesForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ (int) $user->id }}">
            <div class="form-group">
                <label class="sf-label">Roles for this company</label>
                @forelse($roles as $role)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ e($role->name) }}"
                                {{ in_array($role->name, $assigned, true) ? 'checked' : '' }}>
                            {{ e($role->name) }}
                        </label>
                    </div>
                @empty
                    <p class="text-warning">No roles found for this company. Create roles first (HR → Roles).</p>
                @endforelse
            </div>
            <button type="submit" class="btn btn-success btn-sm">Save roles</button>
        </form>
    </div>
</div>
<script>
    (function ($) {
        $('#saveUserRolesForm').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: '<?php echo e(url('/udc/saveUserRoles')); ?>',
                type: 'POST',
                data: $form.serialize(),
                success: function (res) {
                    if (typeof UsersLoginTimePeriodAndRolePermissionList === 'function') {
                        UsersLoginTimePeriodAndRolePermissionList();
                    }
                    $('#showDetailModelOneParamerter').modal('hide');
                    window.alert(res.message || 'Saved');
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Request failed';
                    window.alert(msg);
                }
            });
        });
    })(jQuery);
</script>
