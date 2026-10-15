@if(session('status'))
<div role="status" class="mb-6 rounded-2xl border border-[#d6e3c7] bg-sage p-4 text-sm leading-6 text-[#435834]">{{ session('status') }}</div>
@endif
@if(session('mail_error'))
<div role="alert" class="mb-6 rounded-2xl bg-[#fff3c8] p-4 text-sm">{{ session('mail_error') }}</div>
@endif
@if($errors->any())
<div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
    <p class="font-semibold">Ada yang perlu dicek dulu:</p>
    <ul class="mt-2 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif
