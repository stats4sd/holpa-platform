@if(isset($data['name']) && $data['name'] === $key)

    <div class="px-6 py-4 col-span-2 text-sm text-gray-900">
        {{ $data['type'] }}
    </div>
    <div class="px-6 py-4 col-span-2 text-sm text-gray-900">
        {{ $data['name'] }}
    </div>
    <div class="px-6 py-4 col-span-4 text-sm text-gray-900 break-words">
        {{ $data['label'] }}
    </div>
    <div class="px-6 py-4 col-span-4 text-sm text-gray-900">
        {{ $data['value'] }}
    </div>
@else
    @if($sectionLabel = $surveyRows->where('name', $key)->first()?->defaultLabel?->text)
        <div class="col-span-12 px-12 py-4 bg-blue text-black text-lg font-bold">
            {{ $sectionLabel }}
        </div>
    @endif
    @foreach($data as $key => $value)
        <x-submission-row :key="$key" :data="$value" :surveyRows="$surveyRows"/>
    @endforeach
@endif
