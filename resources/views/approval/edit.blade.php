@can('manager-higher')
<x-approval.DocumentEdit.manager
 :title='$title' 
 :document='$document'
 :fileContents='$fileContents'
/>
@endcan

@can('user')
<x-approval.DocumentEdit.user
 :title='$title' 
 :document='$document'
 :fileContents='$fileContents'
/>
@endcan