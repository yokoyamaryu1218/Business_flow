@can('manager-higher')
<x-approval.index.manager
 :title='$title' 
 :documents='$documents'
 :routines='$routines'
 :procedures='$procedures'

 :procedurePage='$procedurePage'
 :routinePage='$routinePage'
 :documentPage='$documentPage'
/>
@endcan

@can('user')
<x-approval.index.user
 :title='$title' 
 :documents='$documents'
 :routines='$routines'
 :procedures='$procedures'

 :procedurePage='$procedurePage'
 :routinePage='$routinePage'
 :documentPage='$documentPage'
/>
@endcan