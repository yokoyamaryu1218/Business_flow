@props(['work_list'])

@auth
<x-navigation-auth-menu :work_list="$work_list" />
@endauth

@guest
<x-navigation-guest-menu :work_list="$work_list" />
@endguest