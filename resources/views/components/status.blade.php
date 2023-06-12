@if (session('status'))
<div id="status-message" class="bg-blue-100 border border-blue-500 text-blue-700 px-4 py-3 rounded" role="alert">
    <div class="flex items-center">
        <p class="text-sm">{{ session('status') }}</p>
        <button type="button" class="ml-auto" onclick="hideMessage('status-message')">
            <span class="text-blue-700">&times;</span>
        </button>
    </div>
</div>
@endif

@if (session('alert'))
<div id="alert-message" class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded" role="alert">
    <div class="flex items-center">
        <p class="text-sm">{{ session('alert') }}</p>
        <button type="button" class="ml-auto" onclick="hideMessage('alert-message')">
            <span class="text-red-700">&times;</span>
        </button>
    </div>
</div>
@endif

<script>
    function hideMessage(elementId) {
        const message = document.getElementById(elementId);
        message.style.display = 'none';
    }
</script>
