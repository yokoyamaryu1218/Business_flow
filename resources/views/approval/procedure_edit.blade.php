@can('manager-higher')
<x-approval.ProcedureEdit.manager
    :title="$title" 
    :procedure="$procedure"
    :documents="$documents"
    :procedures="$procedures"
/>
@endcan

@can('user')
<x-approval.ProcedureEdit.user
    :title="$title" 
    :procedure="$procedure"
    :documents="$documents"
    :procedures="$procedures"
/>
@endcan
