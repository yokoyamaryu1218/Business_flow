@can('manager-higher')
<x-approval.RoutineEdit.manager
    :title="$title" 
    :procedures="$procedures"
    :routines="$routines"
/>
@endcan

@can('user')
<x-approval.RoutineEdit.user
    :title="$title" 
    :procedures="$procedures"
    :routines="$routines"
    :getRoutines='$getRoutines'
/>
@endcan
