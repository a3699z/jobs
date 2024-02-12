@props(['educations'])
<div class="dashboard-right-header rt-mb-32 lg:tw-mt-0 justify-content-between">
    <h3 class="f-size-18 lh-1 m-0">{{ __('educations') }}</h3>
    <button id="addEducation" type="button" class="btn btn-primary ">
        {{ __('add_education') }}
    </button>
</div>
<div class="db-job-card-table">
    <table>
        <thead>
            <tr>
                <th>{{ __('education_level') }}</th>
                <th>{{ __('degree') }}</th>
                <th>{{ __('year') }}</th>
                <th>{{ __('action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($educations as $education)
                <tr>
                    <td>{{ $education->level }}</td>
                    <td>{{ $education->degree }}</td>
                    <td>{{ $education->year }}</td>
                    <td>
                        <div class=" d-flex justify-content-between">
                            <button type="button" class="btn btn-icon" id="dropdownMenuButton5"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 13.125C12.6213 13.125 13.125 12.6213 13.125 12C13.125 11.3787 12.6213 10.875 12 10.875C11.3787 10.875 10.875 11.3787 10.875 12C10.875 12.6213 11.3787 13.125 12 13.125Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                    <path
                                        d="M12 6.65039C12.6213 6.65039 13.125 6.14671 13.125 5.52539C13.125 4.90407 12.6213 4.40039 12 4.40039C11.3787 4.40039 10.875 4.90407 10.875 5.52539C10.875 6.14671 11.3787 6.65039 12 6.65039Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                    <path
                                        d="M12 19.6094C12.6213 19.6094 13.125 19.1057 13.125 18.4844C13.125 17.8631 12.6213 17.3594 12 17.3594C11.3787 17.3594 10.875 17.8631 10.875 18.4844C10.875 19.1057 11.3787 19.6094 12 19.6094Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end company-dashboard-dropdown"
                                aria-labelledby="dropdownMenuButton5">
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="educationDetail({{ json_encode($education) }})">
                                        <x-svg.edit-icon/>
                                        {{ __('edit') }}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('candidate.educations.destroy', $education->id) }}">
                                        @csrf
                                        @method('Delete')
                                        <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('are_you_sure_you_want_to_delete_this_item') }}');">
                                            <x-svg.trash-icon/>
                                            {{ __('delete') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <x-svg.not-found-icon />
                        <p class="mt-4">{{ __('no_data_found') }}</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add Education Modal --}}
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('candidate.educations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h5 class="modal-title rt-mb-18 f-size-18" id="cvModalLabel">{{ __('add_education') }}</h5>
                    <div class="from-group rt-mb-18">
                        <x-forms.label name="education_level" class="rt-mb-8" />
                        <input type="text" name="level" required class="@error('level') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('education_level') }}">
                        @error('level')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="degree" class="rt-mb-8" />
                            <input type="text" name="degree" required class="@error('degree') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('degree') }}">
                            @error('degree')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <x-forms.label name="year" class="rt-mb-8"/>
                             <input type="text" name="year" value="{{ old('year') }}" placeholder="year" class="year_picker form-control border-cutom @error('year') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-12">
                            <x-forms.label name="notes" class="rt-mb-8" :required="false"/>
                            <textarea class="form-control @error('notes') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('notes') }}" name="notes" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="bg-priamry-50 btn btn-primary-50" onclick="closeAddEducationModal()">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="button-content-wrapper ">
                                <span class="button-icon align-icon-right"><i class="ph-arrow-right"></i></span>
                                <span class="button-text">
                                    {{ __('add_education') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-close" onclick="closeAddEducationModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.75 5.25L5.25 18.75" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M18.75 18.75L5.25 5.25" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>


{{-- Edit Eduction Modal --}}
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('candidate.educations.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <h5 class="modal-title rt-mb-18 f-size-18" id="cvModalLabel">{{ __('edit_education') }}</h5>
                    <input type="hidden" name="education_id" id="education-modal-id">
                    <div class="from-group rt-mb-18">
                        <x-forms.label name="education_level" class="rt-mb-8" />
                        <input id="education-modal-level" type="text" name="level" required placeholder="{{ __('enter') }} {{ __('education_level') }}">
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="degree" class="rt-mb-8" />
                            <input id="education-modal-degree" type="text" name="degree" required placeholder="{{ __('enter') }} {{ __('degree') }}">
                            @error('degree')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <x-forms.label name="year" class="rt-mb-8"/>
                             <input id="education-modal-year" type="text" name="year" value="{{ old('year') }}" placeholder="d/m/y" class="year_picker form-control border-cutom @error('year') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-12">
                            <x-forms.label name="notes" class="rt-mb-8" :required="false"/>
                            <textarea id="education-notes" class="form-control @error('notes') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('notes') }}" name="notes" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="bg-priamry-50 btn btn-primary-50" onclick="closeEditEducationModal()">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="button-content-wrapper ">
                                <span class="button-icon align-icon-right"><i class="ph-arrow-right"></i></span>
                                <span class="button-text">
                                    {{ __('update_education') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-close" onclick="closeEditEducationModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.75 5.25L5.25 18.75" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M18.75 18.75L5.25 5.25" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('frontend_links')
<link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/bootstrap-datepicker.min.css">

@endpush

@push('frontend_scripts')
<script src="{{ asset('frontend/assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script>

        $('#addEducation').on('click', function(){
            $('#addEducationModal').modal('show');
        });

         $(".year_picker").attr("autocomplete", "off");

        //init datepicker
        $('.year_picker').off('focus').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        }).on('click',
            function() {
                $(this).datepicker('show');
            }
        );

        function closeAddEducationModal(){
            $('#addEducationModal').find('form')[0].reset();
            $('#addEducationModal').modal('hide')
        }

        function closeEditEducationModal(){
            $('#editEducationModal').find('form')[0].reset();
            $('#editEducationModal').modal('hide')
        }

        function educationDetail(education, start, end) {
            $('#education-modal-id').val(education.id);
            $('#education-modal-level').val(education.level);
            $('#education-modal-degree').val(education.degree);
            $('#education-modal-year').val(education.year);
            $('#education-notes').val(education.notes);

            $('#editEducationModal').modal('show');
        }
    </script>
@endpush
