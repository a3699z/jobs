@if (request('keyword') || request('category'))
    <div class="tw-text-center">
        @if (request('keyword'))
            <span class="tw-py-1 tw-pl-3 tw-pr-[28px] tw-bg-[#F1F2F4] tw-text-sm tw-text-[#474C54] tw-relative tw-rounded-[30px]">{{ __('keyword') }}:
            {{ request('keyword') }}
                <span class="tw-absolute tw-right-[5px] tw-top-[3px] cursor-pointer" onclick="keywordClose()">
                    <x-svg.tw-close-icon />
                </span>
            </span>
        @endif
        @if (request('category'))
            <span class="tw-py-1 tw-ml-3 tw-pr-[28px] tw-bg-[#F1F2F4] tw-text-sm tw-text-[#474C54] tw-relative tw-rounded-[30px]">{{ __('category') }}:
            {{ request('category') }}
                <span class="tw-absolute tw-right-[5px] tw-top-[3px] cursor-pointer" onclick="categoryClose()">
                    <x-svg.tw-close-icon />
                </span>
            </span>
        @endif
    </div>
@endif
